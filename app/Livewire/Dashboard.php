<?php

namespace App\Livewire;

use App\Models\Budget;
use App\Models\Spend;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class Dashboard extends Component
{
    public $search = '';
    public $range = 'all';
    public $budgetId = 'all';

    private function userSpendQuery(bool $withRelations = false): Builder
    {
        $relations = ['budget', 'platform', 'status'];

        if ($this->labelsSchemaReady()) {
            $relations[] = 'label';
        }

        return Spend::query()
            ->when($withRelations, fn ($query) => $query->with($relations))
            ->whereHas('budget', fn ($query) => $query->where('user_id', auth()->id()))
            ->when($this->budgetId !== 'all', fn ($query) => $query->where('budget_id', $this->budgetId))
            ->when($this->rangeStart(), fn ($query, Carbon $start) => $query->where('spends.created_at', '>=', $start));
    }

    private function userBudgetQuery(): Builder
    {
        return Budget::query()
            ->where('user_id', auth()->id())
            ->when($this->budgetId !== 'all', fn ($query) => $query->where('id', $this->budgetId));
    }

    private function allBudgets()
    {
        return Budget::query()
            ->where('user_id', auth()->id())
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    private function labelBreakdown()
    {
        if (! $this->labelsSchemaReady()) {
            return collect();
        }

        $rangeStart = $this->rangeStart();

        $labels = Spend::query()
            ->leftJoin('labels', 'spends.label_id', '=', 'labels.id')
            ->whereHas('budget', fn ($query) => $query->where('user_id', auth()->id()))
            ->when($this->budgetId !== 'all', fn ($query) => $query->where('spends.budget_id', $this->budgetId))
            ->when($rangeStart, fn ($query, Carbon $start) => $query->where('spends.created_at', '>=', $start))
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('labels.name', 'like', '%'.$this->search.'%')
                        ->orWhere('spends.name', 'like', '%'.$this->search.'%');
                });
            })
            ->selectRaw("coalesce(labels.name, 'Unlabeled') as label_name, sum(spends.amount) as total, count(*) as transactions")
            ->groupByRaw("coalesce(labels.name, 'Unlabeled')")
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        return $labels->map(function ($label) use ($rangeStart) {
            $items = Spend::query()
                ->leftJoin('labels', 'spends.label_id', '=', 'labels.id')
                ->whereHas('budget', fn ($query) => $query->where('user_id', auth()->id()))
                ->whereRaw("coalesce(labels.name, 'Unlabeled') = ?", [$label->label_name])
                ->when($this->budgetId !== 'all', fn ($query) => $query->where('spends.budget_id', $this->budgetId))
                ->when($rangeStart, fn ($query, Carbon $start) => $query->where('spends.created_at', '>=', $start))
                ->when($this->search, function ($query) {
                    $query->where(function ($query) {
                        $query->where('labels.name', 'like', '%'.$this->search.'%')
                            ->orWhere('spends.name', 'like', '%'.$this->search.'%');
                    });
                })
                ->selectRaw('spends.name as name, sum(spends.amount) as total, count(*) as transactions')
                ->groupBy('spends.name')
                ->orderByDesc('total')
                ->limit(4)
                ->get();

            $maxTotal = max((int) $items->max('total'), 1);

            return [
                'name' => $label->label_name,
                'total' => (int) $label->total,
                'transactions' => (int) $label->transactions,
                'items' => $items->map(fn ($item) => [
                    'name' => $item->name,
                    'total' => (int) $item->total,
                    'transactions' => (int) $item->transactions,
                    'percentage' => round(((int) $item->total / $maxTotal) * 100),
                ]),
            ];
        });
    }

    private function totalExpense(): int
    {
        return (int) $this->userSpendQuery()->sum('amount');
    }

    private function totalIncome(): int
    {
        return (int) $this->userBudgetQuery()->sum('income');
    }

    private function transactionCount(): int
    {
        return (int) $this->userSpendQuery()->count();
    }

    private function averageTransaction(): int
    {
        $transactionCount = $this->transactionCount();

        if ($transactionCount === 0) {
            return 0;
        }

        return (int) round($this->totalExpense() / $transactionCount);
    }

    private function largestExpense()
    {
        return $this->userSpendQuery(true)
            ->orderByDesc('amount')
            ->first();
    }

    private function recentExpenses()
    {
        return $this->userSpendQuery(true)
            ->latest()
            ->take(5)
            ->get();
    }

    private function budgetHealth()
    {
        $rangeStart = $this->rangeStart();

        return $this->userBudgetQuery()
            ->withSum(['spends as total_spent' => function ($query) use ($rangeStart) {
                if ($rangeStart) {
                    $query->where('created_at', '>=', $rangeStart);
                }
            }], 'amount')
            ->orderBy('name')
            ->get()
            ->map(function (Budget $budget) {
                $income = (int) $budget->income;
                $spent = (int) ($budget->total_spent ?? 0);
                $remaining = $income - $spent;
                $percentage = $income > 0 ? min(100, round(($spent / $income) * 100)) : 0;

                return [
                    'id' => $budget->id,
                    'name' => $budget->name,
                    'income' => $income,
                    'spent' => $spent,
                    'remaining' => $remaining,
                    'percentage' => $percentage,
                    'tone' => $remaining < 0 ? 'danger' : ($percentage >= 80 ? 'warning' : 'healthy'),
                ];
            });
    }

    private function platformBreakdown()
    {
        $totalExpense = max($this->totalExpense(), 1);

        return $this->userSpendQuery()
            ->join('platforms', 'spends.platform_id', '=', 'platforms.id')
            ->selectRaw('platforms.name as name, sum(spends.amount) as total, count(*) as transactions')
            ->groupBy('platforms.id', 'platforms.name')
            ->orderByDesc('total')
            ->limit(6)
            ->get()
            ->map(fn ($item) => [
                'name' => $item->name,
                'total' => (int) $item->total,
                'transactions' => (int) $item->transactions,
                'percentage' => round(((int) $item->total / $totalExpense) * 100),
            ]);
    }

    private function statusBreakdown()
    {
        return $this->userSpendQuery()
            ->join('statuses', 'spends.status_id', '=', 'statuses.id')
            ->selectRaw('statuses.body as name, sum(spends.amount) as total, count(*) as transactions')
            ->groupBy('statuses.id', 'statuses.body')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($item) => [
                'name' => $item->name,
                'total' => (int) $item->total,
                'transactions' => (int) $item->transactions,
            ]);
    }

    private function topExpenses()
    {
        return $this->userSpendQuery(true)
            ->orderByDesc('amount')
            ->take(5)
            ->get();
    }

    private function rangeStart(): ?Carbon
    {
        return match ($this->range) {
            '30' => now()->subDays(30),
            '90' => now()->subDays(90),
            '365' => now()->subDays(365),
            default => null,
        };
    }

    public function rupiah($amount): string
    {
        return 'Rp'.number_format((int) $amount, 0, ',', '.');
    }

    public function render()
    {
        $labelBreakdown = $this->labelBreakdown();
        $totalIncome = $this->totalIncome();
        $totalExpense = $this->totalExpense();
        $transactionCount = $this->transactionCount();

        return view('livewire.dashboard', [
            'budgets' => $this->allBudgets(),
            'labelBreakdown' => $labelBreakdown,
            'platformBreakdown' => $this->platformBreakdown(),
            'statusBreakdown' => $this->statusBreakdown(),
            'topExpenses' => $this->topExpenses(),
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'remainingBalance' => $totalIncome - $totalExpense,
            'budgetCount' => $this->userBudgetQuery()->count(),
            'transactionCount' => $transactionCount,
            'averageTransaction' => $this->averageTransaction(),
            'largestExpense' => $this->largestExpense(),
            'recentExpenses' => $this->recentExpenses(),
            'budgetHealth' => $this->budgetHealth(),
            'labelCount' => $labelBreakdown->count(),
            'labelsReady' => $this->labelsSchemaReady(),
            'topLabel' => $labelBreakdown->first(),
        ]);
    }

    private function labelsSchemaReady(): bool
    {
        return Schema::hasTable('labels')
            && Schema::hasColumn('labels', 'user_id')
            && Schema::hasColumn('spends', 'label_id');
    }
}
