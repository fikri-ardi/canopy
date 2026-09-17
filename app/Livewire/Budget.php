<?php

namespace App\Livewire;

use App\Models\Budget as ModelsBudget;
use App\Models\Spend;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class Budget extends Component
{
    public $activeBudget;

    public $activeBudgetId;

    public $budgets;

    public $renameBudget;

    public $incomeAmount;

    public $budgetRenderKey = 0;

    public $activeFinancialGoal;

    public $selectedAllocationPlatformId;

    public $onboardingStep;

    public function mount()
    {
        $this->refreshBudgets();
        $this->setActiveBudget($this->userBudgetsQuery()->first());
        $this->setActiveFinancialGoal($this->financialGoals()->first()?->id);
        $this->onboardingStep = $this->initialOnboardingStep();
    }

    #[On('budget-created')]
    public function budgetCreated($budgetId = null)
    {
        $this->refreshBudgets();
        $this->setActiveBudget($this->userBudgetsQuery()->find($budgetId) ?? $this->userBudgetsQuery()->first());
        $this->onboardingStep = auth()->user()->needsOnboarding() ? 'expense-button' : null;

        if ($this->onboardingStep === 'expense-button') {
            $this->dispatch('onboarding-expense-ready');
        }
    }

    #[On('saved')]
    #[On('expense-updated')]
    #[On('expense-deleted')]
    public function refreshSummary()
    {
        $this->setActiveBudget($this->activeBudgetId ? $this->userBudgetsQuery()->find($this->activeBudgetId) : null, false);
        $this->syncOnboardingStep();
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
        $this->renameBudget = $this->activeBudget?->name;
    }

    public function startEditingIncome()
    {
        $income = (int) ($this->activeBudget?->income ?? 0);
        $this->incomeAmount = $income > 0 ? number_format($income, 0, ',', '.') : '';
    }

    public function renameActiveBudget()
    {
        if (! $this->activeBudget) {
            return;
        }

        $validated = $this->validate([
            'renameBudget' => ['required', 'string', 'max:255'],
        ]);

        $this->activeBudget->update([
            'name' => $validated['renameBudget'],
        ]);

        $this->setActiveBudget($this->activeBudget->fresh(), false);
        $this->refreshBudgets();
        $this->dispatch('budget-renamed');
    }

    public function updateActiveBudgetIncome()
    {
        if (! $this->activeBudget) {
            return;
        }

        $validated = $this->validate([
            'incomeAmount' => ['required', 'regex:/^[0-9][0-9.]*$/'],
        ]);

        $this->activeBudget->update([
            'income' => $this->rawAmount($validated['incomeAmount']),
        ]);

        $this->setActiveBudget($this->activeBudget->fresh(), false);
        $this->dispatch('budget-income-updated');
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

    #[Computed]
    public function financialGoals()
    {
        return auth()->user()
            ->financialGoals()
            ->get();
    }

    public function setActiveFinancialGoal($id): void
    {
        $this->activeFinancialGoal = $this->financialGoals()->firstWhere('id', $id);

        if (! $this->activeFinancialGoal) {
            return;
        }
    }

    public function selectAllocationPlatform(int $platformId): void
    {
        $option = $this->allocationOptions()->firstWhere('id', $platformId);

        if (! $option) {
            return;
        }

        $this->selectedAllocationPlatformId = $option['id'];
    }

    public function completeOnboarding(): void
    {
        $this->markOnboardingComplete();
        $this->onboardingStep = null;
        $this->dispatch('onboarding-completed');
    }

    private function initialOnboardingStep(): ?string
    {
        if (! auth()->user()->needsOnboarding()) {
            return null;
        }

        if ($this->budgets->isEmpty()) {
            return 'budgets-menu';
        }

        if (! $this->userHasAnyExpense()) {
            return 'expense-button';
        }

        return 'dashboard-menu';
    }

    private function markOnboardingComplete(): void
    {
        if (! auth()->user()->needsOnboarding()) {
            return;
        }

        auth()->user()->forceFill([
            'onboarding_completed_at' => now(),
        ])->save();
    }

    private function syncOnboardingStep(): void
    {
        if (! auth()->user()->needsOnboarding()) {
            $this->onboardingStep = null;

            return;
        }

        $this->onboardingStep = $this->initialOnboardingStep();

        if ($this->onboardingStep === 'expense-button') {
            $this->dispatch('onboarding-expense-ready');
        }

        if ($this->onboardingStep === 'dashboard-menu') {
            $this->dispatch('onboarding-dashboard-ready');
        }
    }

    private function userHasAnyExpense(): bool
    {
        return Spend::query()
            ->join('budgets', 'spends.budget_id', '=', 'budgets.id')
            ->where('budgets.user_id', auth()->id())
            ->exists();
    }

    private function refreshBudgets()
    {
        $this->budgets = ModelsBudget::query()
            ->where('user_id', auth()->id())
            ->latest('created_at')
            ->latest('id')
            ->get(['id', 'name']);
    }

    /**
     * Get the query builder for the authenticated user's budgets.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function userBudgetsQuery()
    {
        return ModelsBudget::query()
            ->where('user_id', auth()->id())
            ->latest('created_at')
            ->latest('id');
    }

    /**
     * Set the active budget and optionally refresh child components.
     *
     * @param  \App\Models\Budget|null  $budget
     * @param  bool  $refreshChildren
     * @return void
     */
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
        $baseName = $name . ' Salinan';
        $copyName = $baseName;
        $copyNumber = 2;

        while ($this->userBudgetsQuery()->where('name', $copyName)->exists()) {
            $copyName = $baseName . ' ' . $copyNumber;
            $copyNumber++;
        }

        return $copyName;
    }

    private function summaryCards(?array $allocation = null): array
    {
        if (! $this->activeBudget) {
            return [
                [
                    'label' => 'TOTAL PEMASUKAN',
                    'amount' => 0,
                    'key' => 'income'
                ],
                [
                    'label' => 'ALOKASI',
                    'amount' => 0,
                    'key' => 'allocation',
                    'detail' => 'Belum ada alokasi platform'
                ],
                [
                    'label' => 'SISA',
                    'amount' => 0,
                    'key' => 'remaining'
                ],
                [
                    'label' => 'BANK UTAMA',
                    'amount' => 0,
                    'key' => 'main_bank'
                ],
                [
                    'label' => 'TUJUAN KEUANGAN',
                    'amount' => 0,
                    'key' => 'financial_goals',
                    'detail' => 'Belum ada tujuan keuangan'
                ],
            ];
        }

        return [
            [
                'label' => 'TOTAL PEMASUKAN',
                'amount' => (int) $this->activeBudget->income,
                'key' => 'income'
            ],
            [
                'label' => 'ALOKASI',
                'amount' => (int) ($allocation['amount'] ?? 0),
                'key' => 'allocation',
                'detail' => $allocation['name'] ?? 'Belum ada alokasi platform',
            ],
            [
                'label' => 'SISA',
                'amount' => $this->remainingBalance,
                'key' => 'remaining'
            ],
            [
                'label' => 'BANK UTAMA',
                'amount' => $this->mainBankBalance(),
                'key' => 'main_bank'
            ],
            [
                'label' => $this->activeFinancialGoal->name,
                'amount' => $this->activeFinancialGoal->movements->sum('amount') ?? 0,
                'key' => 'financial_goals',
                'detail' => $this->activeFinancialGoal->progress . ' tercapai',
            ],
        ];
    }

    private function totalExpense(): int
    {
        if (! $this->activeBudget) {
            return 0;
        }

        return (int) Spend::where('budget_id', $this->activeBudget->id)->sum('amount');
    }

    #[Computed]
    public function remainingBalance(): int
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
            ->whereNotIn(DB::raw('lower(statuses.body)'), ['unallocated', 'unalocated', 'belum dialokasi'])
            ->sum('spends.amount');

        return (int) $this->activeBudget->income - (int) $managedExpense;
    }

    private function selectedAllocationOption($options): ?array
    {
        if ($options->isEmpty()) {
            return null;
        }

        $selected = $this->selectedAllocationPlatformId
            ? $options->firstWhere('id', (int) $this->selectedAllocationPlatformId)
            : null;

        $selected ??= $options->first();

        return $selected;
    }

    private function allocationOptions()
    {
        if (! $this->activeBudget) {
            return collect();
        }

        return Spend::query()
            ->join('platforms', 'spends.platform_id', '=', 'platforms.id')
            ->join('statuses', 'spends.status_id', '=', 'statuses.id')
            ->where('spends.budget_id', $this->activeBudget->id)
            ->whereIn(DB::raw('lower(trim(statuses.body))'), ['allocated', 'allcoated', 'dialokasi'])
            ->selectRaw('platforms.id as id, platforms.name as name, sum(spends.amount) as total, count(*) as transactions')
            ->groupBy('platforms.id', 'platforms.name')
            ->orderByDesc('total')
            ->get()
            ->map(fn($platform) => [
                'id' => (int) $platform->id,
                'name' => $platform->name,
                'amount' => (int) $platform->total,
                'transactions' => (int) $platform->transactions,
            ]);
    }

    private function labelsSchemaReady(): bool
    {
        return Schema::hasTable('labels')
            && Schema::hasColumn('labels', 'user_id')
            && Schema::hasColumn('spends', 'label_id');
    }

    private function rawAmount(string $amount): int
    {
        return (int) str_replace('.', '', $amount);
    }

    public function render()
    {
        $allocationOptions = $this->allocationOptions();
        $selectedAllocation = $this->selectedAllocationOption($allocationOptions);

        return view('livewire.budget', [
            'summaryCards' => $this->summaryCards($selectedAllocation),
            'allocationOptions' => $allocationOptions,
            'selectedAllocationPlatformId' => $selectedAllocation['id'] ?? null,
        ]);
    }
}
