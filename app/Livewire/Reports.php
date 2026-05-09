<?php

namespace App\Livewire;

use App\Models\Budget as BudgetModel;
use App\Models\Spend;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class Reports extends Component
{
    public $range = 'all';
    public $budgetId = 'all';

    public function rupiah($amount): string
    {
        return 'Rp'.number_format((int) $amount, 0, ',', '.');
    }

    public function render()
    {
        $totalIncome = $this->totalIncome();
        $totalExpense = $this->totalExpense();
        $transactionCount = $this->transactionCount();

        return view('livewire.reports', [
            'budgets' => BudgetModel::where('user_id', auth()->id())->orderBy('name')->get(['id', 'name']),
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'remainingBalance' => $totalIncome - $totalExpense,
            'transactionCount' => $transactionCount,
            'averageExpense' => $transactionCount > 0 ? (int) round($totalExpense / $transactionCount) : 0,
            'budgetProgress' => $this->budgetProgress(),
            'labelBreakdown' => $this->labelBreakdown(),
            'platformBreakdown' => $this->platformBreakdown(),
            'statusBreakdown' => $this->statusBreakdown(),
            'topExpenses' => $this->topExpenses(),
            'labelsReady' => $this->labelsSchemaReady(),
        ]);
    }

    private function filteredSpendQuery(): Builder
    {
        $relations = ['budget', 'platform', 'status'];

        if ($this->labelsSchemaReady()) {
            $relations[] = 'label';
        }

        return Spend::query()
            ->with($relations)
            ->whereHas('budget', fn ($query) => $query->where('user_id', auth()->id()))
            ->when($this->budgetId !== 'all', fn ($query) => $query->where('budget_id', $this->budgetId))
            ->when($this->rangeStart(), fn ($query, Carbon $start) => $query->where('spends.created_at', '>=', $start));
    }

    private function budgetQuery(): Builder
    {
        return BudgetModel::query()
            ->where('user_id', auth()->id())
            ->when($this->budgetId !== 'all', fn ($query) => $query->where('id', $this->budgetId));
    }

    private function totalIncome(): int
    {
        return (int) $this->budgetQuery()->sum('income');
    }

    private function totalExpense(): int
    {
        return (int) $this->filteredSpendQuery()->sum('amount');
    }

    private function transactionCount(): int
    {
        return (int) $this->filteredSpendQuery()->count();
    }

    private function budgetProgress()
    {
        $rangeStart = $this->rangeStart();

        return $this->budgetQuery()
            ->withSum(['spends as total_spent' => function ($query) use ($rangeStart) {
                if ($rangeStart) {
                    $query->where('created_at', '>=', $rangeStart);
                }
            }], 'amount')
            ->orderBy('name')
            ->get()
            ->map(function (BudgetModel $budget) {
                $income = (int) $budget->income;
                $spent = (int) ($budget->total_spent ?? 0);
                $remaining = $income - $spent;

                return [
                    'name' => $budget->name,
                    'income' => $income,
                    'spent' => $spent,
                    'remaining' => $remaining,
                    'percentage' => $income > 0 ? min(100, round(($spent / $income) * 100)) : 0,
                ];
            });
    }

    private function labelBreakdown()
    {
        if (! $this->labelsSchemaReady()) {
            return collect();
        }

        $totalExpense = max($this->totalExpense(), 1);

        return $this->filteredSpendQuery()
            ->leftJoin('labels', 'spends.label_id', '=', 'labels.id')
            ->selectRaw("coalesce(labels.name, 'Unlabeled') as name, sum(spends.amount) as total, count(*) as transactions")
            ->groupByRaw("coalesce(labels.name, 'Unlabeled')")
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

    private function platformBreakdown()
    {
        $totalExpense = max($this->totalExpense(), 1);

        return $this->filteredSpendQuery()
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
        return $this->filteredSpendQuery()
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
        return $this->filteredSpendQuery()
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

    private function labelsSchemaReady(): bool
    {
        return Schema::hasTable('labels')
            && Schema::hasColumn('labels', 'user_id')
            && Schema::hasColumn('spends', 'label_id');
    }
}
