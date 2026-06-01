<?php

namespace App\Livewire;

use App\Models\InvestmentMovement;
use App\Models\InvestmentTarget;
use App\Models\Spend;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Investment extends Component
{
    public $selectedInvestmentKey;
    public $movementType = 'withdrawal';
    public $movementAmount = '';
    public $movementDate;
    public $movementNote = '';
    public $targetAmount = '';
    public $deleteMovementId;

    public function mount(): void
    {
        $this->movementDate = now()->toDateString();
        $selected = $this->selectedInvestment();

        if ($selected) {
            $this->selectedInvestmentKey = $selected['key'];
            $this->targetAmount = $this->editableAmount($selected['target']);
        }
    }

    public function selectInvestment(string $investmentKey): void
    {
        if (! $this->investmentGroups()->firstWhere('key', $investmentKey)) {
            return;
        }

        $this->selectedInvestmentKey = $investmentKey;
        $this->targetAmount = $this->editableAmount((int) ($this->investmentGroups()->firstWhere('key', $investmentKey)['target'] ?? 0));
    }

    public function saveTarget(): void
    {
        if (! $this->targetsSchemaReady()) {
            return;
        }

        $selected = $this->selectedInvestment();

        if (! $selected) {
            return;
        }

        $validated = $this->validate([
            'targetAmount' => ['nullable', 'regex:/^[0-9.]*$/'],
        ]);

        $target = $this->rawAmount($validated['targetAmount'] ?: '0');

        InvestmentTarget::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'investment_key' => $selected['key'],
            ],
            [
                'investment_name' => $selected['name'],
                'target_amount' => $target,
            ]
        );

        $this->targetAmount = $this->editableAmount($target);
    }

    public function storeMovement(): void
    {
        $selected = $this->selectedInvestment();

        if (! $selected) {
            return;
        }

        $validated = $this->validate([
            'movementType' => ['required', Rule::in(['withdrawal', 'deposit'])],
            'movementAmount' => ['required', 'regex:/^[0-9.]+$/'],
            'movementDate' => ['required', 'date'],
            'movementNote' => ['nullable', 'string', 'max:255'],
        ]);

        InvestmentMovement::create([
            'user_id' => auth()->id(),
            'investment_key' => $selected['key'],
            'investment_name' => $selected['name'],
            'type' => $validated['movementType'],
            'amount' => $this->rawAmount($validated['movementAmount']),
            'occurred_on' => $validated['movementDate'],
            'note' => $validated['movementNote'] ?: null,
        ]);

        $this->reset(['movementAmount', 'movementNote']);
        $this->movementType = 'withdrawal';
        $this->movementDate = now()->toDateString();
    }

    public function confirmDeleteMovement(int $movementId): void
    {
        if (! $this->movementsSchemaReady()) {
            return;
        }

        $movement = InvestmentMovement::where('user_id', auth()->id())->find($movementId);

        if (! $movement) {
            return;
        }

        $this->deleteMovementId = $movement->id;
    }

    public function deleteMovement(): void
    {
        if (! $this->movementsSchemaReady() || ! $this->deleteMovementId) {
            return;
        }

        InvestmentMovement::where('user_id', auth()->id())->findOrFail($this->deleteMovementId)->delete();
        $this->reset('deleteMovementId');
    }

    public function rupiah($amount): string
    {
        return 'Rp'.number_format((int) $amount, 0, ',', '.');
    }

    public function render()
    {
        $groups = $this->investmentGroups();
        $selected = $this->selectedInvestment($groups);
        $movements = $selected ? $this->movementsFor($selected['key']) : collect();

        return view('livewire.investment', [
            'schemaReady' => $this->labelsSchemaReady() && $this->movementsSchemaReady(),
            'targetsReady' => $this->targetsSchemaReady(),
            'groups' => $groups,
            'selected' => $selected,
            'movements' => $movements,
            'deleteMovement' => $this->deleteMovementId && $this->movementsSchemaReady()
                ? InvestmentMovement::where('user_id', auth()->id())->find($this->deleteMovementId)
                : null,
            'summary' => [
                'principal' => (int) $groups->sum('principal'),
                'withdrawn' => (int) $groups->sum('withdrawn'),
                'deposit' => (int) $groups->sum('deposit'),
                'balance' => (int) $groups->sum('balance'),
                'target' => (int) $groups->sum('target'),
                'progress' => $this->progressPercent((int) $groups->sum('balance'), (int) $groups->sum('target')),
                'progressWidth' => $this->progressWidth((int) $groups->sum('balance'), (int) $groups->sum('target')),
            ],
        ]);
    }

    private function selectedInvestment(?Collection $groups = null): ?array
    {
        $groups ??= $this->investmentGroups();

        if ($groups->isEmpty()) {
            return null;
        }

        return ($this->selectedInvestmentKey
            ? $groups->firstWhere('key', $this->selectedInvestmentKey)
            : null) ?? $groups->first();
    }

    private function investmentGroups(): Collection
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
            ->orderByDesc('principal')
            ->get()
            ->keyBy('investment_key');

        $movementTotals = $this->movementsSchemaReady()
            ? InvestmentMovement::query()
                ->where('user_id', auth()->id())
                ->selectRaw("investment_key, sum(case when type = 'withdrawal' then amount else 0 end) as withdrawn, sum(case when type = 'deposit' then amount else 0 end) as deposit, count(*) as movements_count")
                ->groupBy('investment_key')
                ->get()
                ->keyBy('investment_key')
            : collect();

        $targets = $this->targetsSchemaReady()
            ? InvestmentTarget::query()
                ->where('user_id', auth()->id())
                ->get(['investment_key', 'target_amount'])
                ->keyBy('investment_key')
            : collect();

        return $principals
            ->map(function ($principal, $key) use ($movementTotals, $targets) {
                $movement = $movementTotals->get($key);
                $target = (int) ($targets->get($key)->target_amount ?? 0);
                $withdrawn = (int) ($movement->withdrawn ?? 0);
                $deposit = (int) ($movement->deposit ?? 0);
                $principalAmount = (int) $principal->principal;
                $balance = $principalAmount + $deposit - $withdrawn;

                return [
                    'key' => $key,
                    'name' => $principal->name,
                    'principal' => $principalAmount,
                    'withdrawn' => $withdrawn,
                    'deposit' => $deposit,
                    'balance' => $balance,
                    'target' => $target,
                    'targetProgress' => $this->progressPercent($balance, $target),
                    'targetProgressWidth' => $this->progressWidth($balance, $target),
                    'remainingToTarget' => max(0, $target - $balance),
                    'transactions' => (int) $principal->transactions,
                    'budgets' => (int) $principal->budgets_count,
                    'movements' => (int) ($movement->movements_count ?? 0),
                ];
            })
            ->sortByDesc('balance')
            ->values();
    }

    private function movementsFor(string $investmentKey): Collection
    {
        if (! $this->movementsSchemaReady()) {
            return collect();
        }

        return InvestmentMovement::query()
            ->where('user_id', auth()->id())
            ->where('investment_key', $investmentKey)
            ->latest('occurred_on')
            ->latest('id')
            ->get();
    }

    private function rawAmount(string $amount): int
    {
        return (int) str_replace('.', '', $amount);
    }

    private function editableAmount(int $amount): string
    {
        return $amount > 0 ? number_format($amount, 0, ',', '.') : '';
    }

    private function progressPercent(int $balance, int $target): ?int
    {
        if ($target <= 0) {
            return null;
        }

        return max(0, (int) round(($balance / $target) * 100));
    }

    private function progressWidth(int $balance, int $target): int
    {
        return min(100, $this->progressPercent($balance, $target) ?? 0);
    }

    private function labelsSchemaReady(): bool
    {
        return Schema::hasTable('labels')
            && Schema::hasColumn('labels', 'user_id')
            && Schema::hasColumn('spends', 'label_id');
    }

    private function movementsSchemaReady(): bool
    {
        return Schema::hasTable('investment_movements');
    }

    private function targetsSchemaReady(): bool
    {
        return Schema::hasTable('investment_targets');
    }
}
