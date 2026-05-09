<?php

namespace App\Livewire;

use App\Models\Budget;
use App\Models\Spend;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class Dashboard extends Component
{
    public $search = '';

    private function userSpendQuery()
    {
        return Spend::query()
            ->whereHas('budget', fn ($query) => $query->where('user_id', auth()->id()));
    }

    private function userBudgetQuery()
    {
        return Budget::query()->where('user_id', auth()->id());
    }

    private function labelBreakdown()
    {
        if (! $this->labelsSchemaReady()) {
            return collect();
        }

        $labels = Spend::query()
            ->leftJoin('labels', 'spends.label_id', '=', 'labels.id')
            ->whereHas('budget', fn ($query) => $query->where('user_id', auth()->id()))
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('labels.name', 'like', '%'.$this->search.'%')
                        ->orWhere('spends.name', 'like', '%'.$this->search.'%');
                });
            })
            ->selectRaw("coalesce(labels.name, 'Unlabeled') as label_name, sum(spends.amount) as total, count(*) as transactions")
            ->groupByRaw("coalesce(labels.name, 'Unlabeled')")
            ->orderByDesc('total')
            ->get();

        return $labels->map(function ($label) {
            $items = Spend::query()
                ->leftJoin('labels', 'spends.label_id', '=', 'labels.id')
                ->whereHas('budget', fn ($query) => $query->where('user_id', auth()->id()))
                ->whereRaw("coalesce(labels.name, 'Unlabeled') = ?", [$label->label_name])
                ->when($this->search, function ($query) {
                    $query->where(function ($query) {
                        $query->where('labels.name', 'like', '%'.$this->search.'%')
                            ->orWhere('spends.name', 'like', '%'.$this->search.'%');
                    });
                })
                ->selectRaw('spends.name as name, sum(spends.amount) as total, count(*) as transactions')
                ->groupBy('spends.name')
                ->orderByDesc('total')
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
        if (! $this->labelsSchemaReady()) {
            return (int) $this->userSpendQuery()->sum('amount');
        }

        return (int) $this->userSpendQuery()
            ->when($this->search, function ($query) {
                $query->leftJoin('labels', 'spends.label_id', '=', 'labels.id')
                    ->where(function ($query) {
                        $query->where('labels.name', 'like', '%'.$this->search.'%')
                            ->orWhere('spends.name', 'like', '%'.$this->search.'%');
                    });
            })->sum('spends.amount');
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
        $relations = ['budget', 'platform', 'status'];

        if ($this->labelsSchemaReady()) {
            $relations[] = 'label';
        }

        return $this->userSpendQuery()
            ->with($relations)
            ->orderByDesc('amount')
            ->first();
    }

    private function recentExpenses()
    {
        $relations = ['budget', 'platform', 'status'];

        if ($this->labelsSchemaReady()) {
            $relations[] = 'label';
        }

        return $this->userSpendQuery()
            ->with($relations)
            ->latest()
            ->take(5)
            ->get();
    }

    private function budgetHealth()
    {
        return $this->userBudgetQuery()
            ->withSum('spends as total_spent', 'amount')
            ->latest()
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
            'labelBreakdown' => $labelBreakdown,
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
