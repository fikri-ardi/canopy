<?php

namespace App\Livewire;

use App\Models\Budget as ModelsBudget;
use App\Models\Spend;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\On;
use Livewire\Component;

class Budget extends Component
{
    public $activeBudget;
    public $activeBudgetId;
    public $budgets;
    public $renameBudgetName;
    public $budgetRenderKey = 0;

    public function mount()
    {
        $this->refreshBudgets();
        $this->setActiveBudget($this->userBudgetsQuery()->first());
    }

    #[On('budget-created')]
    public function budgetCreated($budgetId = null)
    {
        $this->refreshBudgets();
        $this->setActiveBudget($this->userBudgetsQuery()->find($budgetId) ?? $this->userBudgetsQuery()->first());
    }

    #[On('saved')]
    #[On('expense-updated')]
    #[On('expense-deleted')]
    public function refreshSummary()
    {
        $this->setActiveBudget($this->activeBudgetId ? $this->userBudgetsQuery()->find($this->activeBudgetId) : null, false);
    }

    public function selectBudget($budgetId)
    {
        $budget = $this->userBudgetsQuery()->find($budgetId);

        if (! $budget) {
            return;
        }

        $this->setActiveBudget($budget);
    }

    public function startRenamingBudget()
    {
        $this->renameBudgetName = $this->activeBudget?->name;
    }

    public function renameActiveBudget()
    {
        if (! $this->activeBudget) {
            return;
        }

        $validated = $this->validate([
            'renameBudgetName' => ['required', 'string', 'max:255'],
        ]);

        $this->activeBudget->update([
            'name' => $validated['renameBudgetName'],
        ]);

        $this->setActiveBudget($this->activeBudget->fresh(), false);
        $this->refreshBudgets();
        $this->dispatch('budget-renamed');
    }

    public function deleteActiveBudget()
    {
        $budget = $this->activeBudgetId ? $this->userBudgetsQuery()->find($this->activeBudgetId) : null;

        if (! $budget) {
            $this->setActiveBudget($this->userBudgetsQuery()->first());
            return;
        }

        DB::transaction(function () use ($budget) {
            $budget->spends()->delete();
            $budget->delete();
        });

        $this->refreshBudgets();
        $this->setActiveBudget($this->userBudgetsQuery()->first());
        $this->dispatch('budget-deleted');
    }

    public function duplicateActiveBudget()
    {
        if (! $this->activeBudget) {
            return;
        }

        $sourceBudget = $this->activeBudget;

        $newBudget = DB::transaction(function () use ($sourceBudget) {
            $newBudget = ModelsBudget::create([
                'user_id' => auth()->id(),
                'name' => $this->duplicateBudgetName($sourceBudget->name),
                'income' => $sourceBudget->income,
            ]);

            Spend::where('budget_id', $sourceBudget->id)->get()->each(function ($spend) use ($newBudget) {
                $payload = [
                    'platform_id' => $spend->platform_id,
                    'status_id' => $spend->status_id,
                    'name' => $spend->name,
                    'amount' => $spend->getRawOriginal('amount'),
                ];

                if (Schema::hasColumn('spends', 'label_id')) {
                    $payload['label_id'] = $spend->label_id;
                }

                $newBudget->spends()->create($payload);
            });

            return $newBudget;
        });

        $this->refreshBudgets();
        $this->setActiveBudget($newBudget);
    }

    private function refreshBudgets()
    {
        $this->budgets = $this->userBudgetsQuery()->get(['id', 'name']);
    }

    private function userBudgetsQuery()
    {
        return ModelsBudget::query()->where('user_id', auth()->id());
    }

    private function setActiveBudget(?ModelsBudget $budget, bool $refreshChildren = true): void
    {
        $this->activeBudget = $budget;
        $this->activeBudgetId = $budget?->getKey();

        if ($refreshChildren) {
            $this->budgetRenderKey++;
        }
    }

    private function duplicateBudgetName(string $name): string
    {
        $baseName = $name.' Copy';
        $copyName = $baseName;
        $copyNumber = 2;

        while ($this->userBudgetsQuery()->where('name', $copyName)->exists()) {
            $copyName = $baseName.' '.$copyNumber;
            $copyNumber++;
        }

        return $copyName;
    }

    private function summaryCards(): array
    {
        if (! $this->activeBudget) {
            return [
                ['label' => 'TOTAL INCOME', 'amount' => 0],
                ['label' => 'TOTAL EXPENSE', 'amount' => 0],
                ['label' => 'REMAINING', 'amount' => 0],
                ['label' => 'MAIN BANK', 'amount' => 0],
                ['label' => 'CASH', 'amount' => 0],
            ];
        }

        $totalExpense = $this->totalExpense();
        $platformTotals = $this->platformTotals();

        return [
            ['label' => 'TOTAL INCOME', 'amount' => (int) $this->activeBudget->income],
            ['label' => 'TOTAL EXPENSE', 'amount' => (int) $totalExpense],
            ['label' => 'REMAINING', 'amount' => $this->remainingBalance()],
            ['label' => 'MAIN BANK', 'amount' => $this->mainBankBalance()],
            ['label' => 'CASH', 'amount' => (int) ($platformTotals['cash'] ?? 0)],
        ];
    }

    private function totalExpense(): int
    {
        if (! $this->activeBudget) {
            return 0;
        }

        return (int) Spend::where('budget_id', $this->activeBudget->id)->sum('amount');
    }

    private function remainingBalance(): int
    {
        if (! $this->activeBudget) {
            return 0;
        }

        return (int) $this->activeBudget->income - $this->totalExpense();
    }

    private function mainBankBalance(): int
    {
        if (! $this->activeBudget) {
            return 0;
        }

        $managedExpense = Spend::query()
            ->join('statuses', 'spends.status_id', '=', 'statuses.id')
            ->where('spends.budget_id', $this->activeBudget->id)
            ->whereNotIn(DB::raw('lower(statuses.body)'), ['unallocated', 'unalocated'])
            ->sum('spends.amount');

        return (int) $this->activeBudget->income - (int) $managedExpense;
    }

    private function platformTotals()
    {
        if (! $this->activeBudget) {
            return collect();
        }

        return Spend::query()
            ->join('platforms', 'spends.platform_id', '=', 'platforms.id')
            ->where('spends.budget_id', $this->activeBudget->id)
            ->selectRaw('lower(platforms.name) as platform_name, sum(spends.amount) as total')
            ->groupByRaw('lower(platforms.name)')
            ->pluck('total', 'platform_name');
    }

    private function platformAnalytics()
    {
        if (! $this->activeBudget) {
            return collect();
        }

        $totalExpense = max($this->totalExpense(), 1);

        return Spend::query()
            ->join('platforms', 'spends.platform_id', '=', 'platforms.id')
            ->where('spends.budget_id', $this->activeBudget->id)
            ->selectRaw('platforms.name as name, sum(spends.amount) as total, count(*) as transactions')
            ->groupBy('platforms.id', 'platforms.name')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($platform) => [
                'name' => $platform->name,
                'total' => (int) $platform->total,
                'transactions' => (int) $platform->transactions,
                'percentage' => round(((int) $platform->total / $totalExpense) * 100),
            ]);
    }

    private function statusAnalytics()
    {
        if (! $this->activeBudget) {
            return collect();
        }

        return Spend::query()
            ->join('statuses', 'spends.status_id', '=', 'statuses.id')
            ->where('spends.budget_id', $this->activeBudget->id)
            ->selectRaw('statuses.body as name, sum(spends.amount) as total, count(*) as transactions')
            ->groupBy('statuses.id', 'statuses.body')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($status) => [
                'name' => $status->name,
                'total' => (int) $status->total,
                'transactions' => (int) $status->transactions,
            ]);
    }

    private function spendProgress(): int
    {
        if (! $this->activeBudget || (int) $this->activeBudget->income === 0) {
            return 0;
        }

        return min(100, round(($this->totalExpense() / (int) $this->activeBudget->income) * 100));
    }

    public function rupiah($amount): string
    {
        return 'Rp'.number_format((int) $amount, 0, ',', '.');
    }

    public function render()
    {
        return view('livewire.budget', [
            'summaryCards' => $this->summaryCards(),
            'platformAnalytics' => $this->platformAnalytics(),
            'statusAnalytics' => $this->statusAnalytics(),
            'spendProgress' => $this->spendProgress(),
            'remainingBalance' => $this->remainingBalance(),
        ]);
    }
}
