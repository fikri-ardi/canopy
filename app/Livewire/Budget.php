<?php

namespace App\Livewire;

use App\Models\Budget as ModelsBudget;
use App\Models\Spend;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class Budget extends Component
{
    public $activeBudget;
    public $budgets;
    public $renameBudgetName;

    public function mount()
    {
        $this->refreshBudgets();
        $this->activeBudget = ModelsBudget::first();
    }

    #[On('budget-created')]
    public function budgetCreated($budgetId = null)
    {
        $this->refreshBudgets();
        $this->activeBudget = ModelsBudget::find($budgetId) ?? ModelsBudget::first();
    }

    #[On('saved')]
    #[On('expense-updated')]
    #[On('expense-deleted')]
    public function refreshSummary()
    {
    }

    public function selectBudget(ModelsBudget $budget)
    {
        $this->activeBudget = $budget;
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

        $this->activeBudget = $this->activeBudget->fresh();
        $this->refreshBudgets();
        $this->dispatch('budget-renamed');
    }

    public function deleteActiveBudget()
    {
        if (! $this->activeBudget) {
            return;
        }

        $this->activeBudget->spends()->delete();
        $this->activeBudget->delete();

        $this->refreshBudgets();
        $this->activeBudget = ModelsBudget::first();
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
                'name' => $this->duplicateBudgetName($sourceBudget->name),
                'income' => $sourceBudget->income,
            ]);

            Spend::where('budget_id', $sourceBudget->id)->get()->each(function ($spend) use ($newBudget) {
                $newBudget->spends()->create([
                    'platform_id' => $spend->platform_id,
                    'status_id' => $spend->status_id,
                    'name' => $spend->name,
                    'amount' => $spend->getRawOriginal('amount'),
                ]);
            });

            return $newBudget;
        });

        $this->refreshBudgets();
        $this->activeBudget = $newBudget;
    }

    private function refreshBudgets()
    {
        $this->budgets = ModelsBudget::get(['id', 'name']);
    }

    private function duplicateBudgetName(string $name): string
    {
        $baseName = $name.' Copy';
        $copyName = $baseName;
        $copyNumber = 2;

        while (ModelsBudget::where('name', $copyName)->exists()) {
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
                ['label' => 'UNMANAGED', 'amount' => 0],
                ['label' => 'MAIN BANK', 'amount' => 0],
                ['label' => 'CASH', 'amount' => 0],
                ['label' => 'GOPAY', 'amount' => 0],
                ['label' => 'SHOPEEPAY', 'amount' => 0],
            ];
        }

        $totalExpense = Spend::where('budget_id', $this->activeBudget->id)->sum('amount');
        $platformTotals = Spend::query()
            ->join('platforms', 'spends.platform_id', '=', 'platforms.id')
            ->where('spends.budget_id', $this->activeBudget->id)
            ->selectRaw('lower(platforms.name) as platform_name, sum(spends.amount) as total')
            ->groupByRaw('lower(platforms.name)')
            ->pluck('total', 'platform_name');

        $mainBank = collect(['seabank', 'bri', 'jago', 'bni'])
            ->sum(fn ($platform) => (int) ($platformTotals[$platform] ?? 0));

        return [
            ['label' => 'TOTAL INCOME', 'amount' => (int) $this->activeBudget->income],
            ['label' => 'TOTAL EXPENSE', 'amount' => (int) $totalExpense],
            ['label' => 'UNMANAGED', 'amount' => (int) $this->activeBudget->income - (int) $totalExpense],
            ['label' => 'MAIN BANK', 'amount' => $mainBank],
            ['label' => 'CASH', 'amount' => (int) ($platformTotals['cash'] ?? 0)],
            ['label' => 'GOPAY', 'amount' => (int) ($platformTotals['gopay'] ?? 0)],
            ['label' => 'SHOPEEPAY', 'amount' => (int) ($platformTotals['shopeepay'] ?? 0)],
        ];
    }

    public function render()
    {
        return view('livewire.budget', [
            'summaryCards' => $this->summaryCards(),
        ]);
    }
}
