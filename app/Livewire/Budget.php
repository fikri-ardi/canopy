<?php

namespace App\Livewire;

use App\Models\Budget as ModelsBudget;
use App\Models\InvestmentMovement;
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

    public $incomeAmount;

    public $budgetRenderKey = 0;

    public $selectedInvestmentName;

    public $selectedAllocationPlatformId;

    public $onboardingStep;

    public function mount()
    {
        $this->refreshBudgets();
        $this->onboardingStep = session('canopy_onboarding_step');

        if ($this->budgets->isEmpty() && ! $this->onboardingStep) {
            $this->onboardingStep = 'budget';
            session(['canopy_onboarding_step' => 'budget']);
        }

        $this->setActiveBudget($this->userBudgetsQuery()->first());
    }

    #[On('budget-created')]
    public function budgetCreated($budgetId = null)
    {
        $this->refreshBudgets();
        $this->onboardingStep = session('canopy_onboarding_step');
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
            'renameBudgetName' => ['required', 'string', 'max:255'],
        ]);

        $this->activeBudget->update([
            'name' => $validated['renameBudgetName'],
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

    public function selectInvestment(string $investmentName): void
    {
        $option = $this->investmentOptions()->firstWhere('key', $investmentName);

        if (! $option) {
            return;
        }

        $this->selectedInvestmentName = $option['key'];
    }

    public function selectAllocationPlatform(int $platformId): void
    {
        $option = $this->allocationOptions()->firstWhere('id', $platformId);

        if (! $option) {
            return;
        }

        $this->selectedAllocationPlatformId = $option['id'];
    }

    private function refreshBudgets()
    {
        $this->budgets = $this->userBudgetsQuery()->get(['id', 'name']);
    }

    private function userBudgetsQuery()
    {
        return ModelsBudget::query()
            ->where('user_id', auth()->id())
            ->latest('created_at')
            ->latest('id');
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

    private function summaryCards(?array $investment = null, ?array $allocation = null): array
    {
        if (! $this->activeBudget) {
            return [
                ['label' => 'TOTAL INCOME', 'amount' => 0, 'key' => 'income'],
                ['label' => 'ALLOCATION', 'amount' => 0, 'key' => 'allocation', 'detail' => 'No platform allocation'],
                ['label' => 'REMAINING', 'amount' => 0, 'key' => 'remaining'],
                ['label' => 'MAIN BANK', 'amount' => 0, 'key' => 'main_bank'],
                ['label' => 'INVESTMENT', 'amount' => 0, 'key' => 'investment', 'detail' => 'No investment spend'],
            ];
        }

        return [
            ['label' => 'TOTAL INCOME', 'amount' => (int) $this->activeBudget->income, 'key' => 'income'],
            [
                'label' => 'ALLOCATION',
                'amount' => (int) ($allocation['amount'] ?? 0),
                'key' => 'allocation',
                'detail' => $allocation['name'] ?? 'No platform allocation',
            ],
            ['label' => 'REMAINING', 'amount' => $this->remainingBalance(), 'key' => 'remaining'],
            ['label' => 'MAIN BANK', 'amount' => $this->mainBankBalance(), 'key' => 'main_bank'],
            [
                'label' => 'INVESTMENT',
                'amount' => (int) ($investment['amount'] ?? 0),
                'key' => 'investment',
                'detail' => $investment['name'] ?? 'No investment spend',
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

    private function transactionCount(): int
    {
        if (! $this->activeBudget) {
            return 0;
        }

        return (int) Spend::where('budget_id', $this->activeBudget->id)->count();
    }

    private function averageExpense(): int
    {
        $transactionCount = $this->transactionCount();

        if ($transactionCount === 0) {
            return 0;
        }

        return (int) round($this->totalExpense() / $transactionCount);
    }

    private function largestExpense(): int
    {
        if (! $this->activeBudget) {
            return 0;
        }

        return (int) Spend::where('budget_id', $this->activeBudget->id)->max('amount');
    }

    private function unallocatedTotal(): int
    {
        if (! $this->activeBudget) {
            return 0;
        }

        return (int) Spend::query()
            ->join('statuses', 'spends.status_id', '=', 'statuses.id')
            ->where('spends.budget_id', $this->activeBudget->id)
            ->whereIn(DB::raw('lower(statuses.body)'), ['unallocated', 'unalocated'])
            ->sum('spends.amount');
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

    private function topExpenses()
    {
        if (! $this->activeBudget) {
            return collect();
        }

        $relations = ['platform', 'status'];

        if (Schema::hasColumn('spends', 'label_id')) {
            $relations[] = 'label';
        }

        return Spend::with($relations)
            ->where('budget_id', $this->activeBudget->id)
            ->orderByDesc('amount')
            ->take(4)
            ->get();
    }

    private function spendProgress(): int
    {
        if (! $this->activeBudget || (int) $this->activeBudget->income === 0) {
            return 0;
        }

        return min(100, round(($this->totalExpense() / (int) $this->activeBudget->income) * 100));
    }

    private function selectedInvestmentOption($options): ?array
    {
        if ($options->isEmpty()) {
            return null;
        }

        $selected = $this->selectedInvestmentName
            ? $options->firstWhere('key', $this->selectedInvestmentName)
            : null;

        $selected ??= $options->first();

        return $selected;
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
            ->whereIn(DB::raw('lower(trim(statuses.body))'), ['allocated', 'allcoated'])
            ->selectRaw('platforms.id as id, platforms.name as name, sum(spends.amount) as total, count(*) as transactions')
            ->groupBy('platforms.id', 'platforms.name')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($platform) => [
                'id' => (int) $platform->id,
                'name' => $platform->name,
                'amount' => (int) $platform->total,
                'transactions' => (int) $platform->transactions,
            ]);
    }

    private function investmentOptions()
    {
        if (! $this->labelsSchemaReady()) {
            return collect();
        }

        $principals = Spend::query()
            ->join('budgets', 'spends.budget_id', '=', 'budgets.id')
            ->join('labels', 'spends.label_id', '=', 'labels.id')
            ->where('budgets.user_id', auth()->id())
            ->whereIn(DB::raw('lower(trim(labels.name))'), ['investment', 'investasi'])
            ->selectRaw('lower(trim(spends.name)) as investment_key, min(spends.name) as name, sum(spends.amount) as principal, count(*) as transactions, count(distinct spends.budget_id) as budgets_count')
            ->groupByRaw('lower(trim(spends.name))')
            ->get()
            ->keyBy('investment_key');

        $movementTotals = $this->investmentMovementsSchemaReady()
            ? InvestmentMovement::query()
                ->where('user_id', auth()->id())
                ->selectRaw("investment_key, sum(case when type = 'withdrawal' then amount else 0 end) as withdrawn, sum(case when type = 'deposit' then amount else 0 end) as deposit, count(*) as movements_count")
                ->groupBy('investment_key')
                ->get()
                ->keyBy('investment_key')
            : collect();

        return $principals
            ->map(function ($spend, $key) use ($movementTotals) {
                $movement = $movementTotals->get($key);
                $principal = (int) $spend->principal;
                $withdrawn = (int) ($movement->withdrawn ?? 0);
                $deposit = (int) ($movement->deposit ?? 0);

                return [
                    'key' => $spend->investment_key,
                    'name' => $spend->name,
                    'amount' => $principal + $deposit - $withdrawn,
                    'principal' => $principal,
                    'withdrawn' => $withdrawn,
                    'deposit' => $deposit,
                    'movements' => (int) ($movement->movements_count ?? 0),
                    'transactions' => (int) $spend->transactions,
                    'budgets' => (int) $spend->budgets_count,
                ];
            })
            ->sortByDesc('amount')
            ->values();
    }

    private function labelsSchemaReady(): bool
    {
        return Schema::hasTable('labels')
            && Schema::hasColumn('labels', 'user_id')
            && Schema::hasColumn('spends', 'label_id');
    }

    private function investmentMovementsSchemaReady(): bool
    {
        return Schema::hasTable('investment_movements');
    }

    public function rupiah($amount): string
    {
        return 'Rp'.number_format((int) $amount, 0, ',', '.');
    }

    private function rawAmount(string $amount): int
    {
        return (int) str_replace('.', '', $amount);
    }

    public function render()
    {
        $allocationOptions = $this->allocationOptions();
        $selectedAllocation = $this->selectedAllocationOption($allocationOptions);
        $investmentOptions = $this->investmentOptions();
        $selectedInvestment = $this->selectedInvestmentOption($investmentOptions);

        return view('livewire.budget', [
            'summaryCards' => $this->summaryCards($selectedInvestment, $selectedAllocation),
            'insightCards' => [
                ['label' => 'TRANSACTIONS', 'amount' => $this->transactionCount(), 'format' => 'number'],
                ['label' => 'AVG EXPENSE', 'amount' => $this->averageExpense(), 'format' => 'money'],
                ['label' => 'LARGEST EXPENSE', 'amount' => $this->largestExpense(), 'format' => 'money'],
                ['label' => 'UNALLOCATED', 'amount' => $this->unallocatedTotal(), 'format' => 'money'],
            ],
            'platformAnalytics' => $this->platformAnalytics(),
            'statusAnalytics' => $this->statusAnalytics(),
            'topExpenses' => $this->topExpenses(),
            'allocationOptions' => $allocationOptions,
            'selectedAllocationPlatformId' => $selectedAllocation['id'] ?? null,
            'investmentOptions' => $investmentOptions,
            'selectedInvestmentKey' => $selectedInvestment['key'] ?? null,
            'spendProgress' => $this->spendProgress(),
            'remainingBalance' => $this->remainingBalance(),
        ]);
    }
}
