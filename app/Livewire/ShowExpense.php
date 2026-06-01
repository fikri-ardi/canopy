<?php

namespace App\Livewire;

use App\Models\Spend;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ShowExpense extends Component
{
    #[Reactive]
    public $activeBudgetId;

    public $sortBy = 'created_at';
    public $sortDirection = 'asc';

    #[On('saved')]
    #[On('expense-updated')]
    #[On('expense-deleted')]
    public function refreshExpenses()
    {
    }

    public function sort(string $column): void
    {
        if (! in_array($column, ['number', 'name', 'amount', 'label', 'platform', 'status'], true)) {
            return;
        }

        $column = $column === 'number' ? 'created_at' : $column;

        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
            return;
        }

        $this->sortBy = $column;
        $this->sortDirection = $column === 'amount' ? 'desc' : 'asc';
    }

    public function sortIcon(string $column): string
    {
        $column = $column === 'number' ? 'created_at' : $column;

        if ($this->sortBy !== $column) {
            return 'idle';
        }

        return $this->sortDirection;
    }

    public function render()
    {
        $relations = ['platform', 'status'];
        $labelsReady = Schema::hasTable('labels') && Schema::hasColumn('spends', 'label_id');

        if ($labelsReady) {
            $relations[] = 'label';
        }

        $spends = $this->sortedSpendQuery(
            Spend::query()
                ->select('spends.*')
                ->with($relations)
                ->where('budget_id', $this->activeBudgetId)
                ->whereHas('budget', fn ($query) => $query->where('user_id', auth()->id())),
            $labelsReady
        )->get();

        return view('livewire.show-expense', [
            'spends' => $spends,
            'maxAmount' => (int) $spends->max(fn ($spend) => (int) $spend->getRawOriginal('amount')),
        ]);
    }

    private function sortedSpendQuery(Builder $query, bool $labelsReady): Builder
    {
        $direction = $this->sortDirection === 'asc' ? 'asc' : 'desc';

        return match ($this->sortBy) {
            'name' => $query->orderBy('spends.name', $direction)->orderBy('spends.id'),
            'amount' => $query->orderBy('spends.amount', $direction)->orderBy('spends.name'),
            'label' => $labelsReady
                ? $query->leftJoin('labels', 'spends.label_id', '=', 'labels.id')
                    ->orderByRaw("case when labels.name is null then 1 else 0 end")
                    ->orderBy('labels.name', $direction)
                    ->orderBy('spends.name')
                : $query->orderBy('spends.name', $direction),
            'platform' => $query->join('platforms', 'spends.platform_id', '=', 'platforms.id')
                ->orderBy('platforms.name', $direction)
                ->orderBy('spends.name'),
            'status' => $query->join('statuses', 'spends.status_id', '=', 'statuses.id')
                ->orderBy('statuses.body', $direction)
                ->orderBy('spends.name'),
            default => $query->orderBy('spends.created_at', $direction)->orderBy('spends.id', $direction),
        };
    }
}
