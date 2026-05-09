<?php

namespace App\Livewire;

use App\Models\Budget as BudgetModel;
use App\Models\Label;
use App\Models\Platform;
use App\Models\Spend;
use App\Models\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\WithPagination;

class Spends extends Component
{
    use WithPagination;

    public $search = '';
    public $budgetId = 'all';
    public $labelId = 'all';
    public $platformId = 'all';
    public $statusId = 'all';
    public $sort = 'latest';

    public function updated($name): void
    {
        if (in_array($name, ['search', 'budgetId', 'labelId', 'platformId', 'statusId', 'sort'], true)) {
            $this->resetPage();
        }
    }

    public function rupiah($amount): string
    {
        return 'Rp'.number_format((int) $amount, 0, ',', '.');
    }

    public function render()
    {
        $filteredQuery = $this->filteredSpendQuery();
        $totalAmount = (int) (clone $filteredQuery)->sum('amount');
        $transactionCount = (int) (clone $filteredQuery)->count();

        return view('livewire.spends', [
            'spends' => $this->sortedSpendQuery($filteredQuery)->paginate(12),
            'totalAmount' => $totalAmount,
            'transactionCount' => $transactionCount,
            'averageAmount' => $transactionCount > 0 ? (int) round($totalAmount / $transactionCount) : 0,
            'largestAmount' => (int) (clone $filteredQuery)->max('amount'),
            'budgets' => BudgetModel::where('user_id', auth()->id())->orderBy('name')->get(['id', 'name']),
            'labels' => $this->labelsSchemaReady()
                ? Label::where('user_id', auth()->id())->orderBy('name')->get(['id', 'name'])
                : collect(),
            'platforms' => Platform::orderBy('name')->get(['id', 'name']),
            'statuses' => Status::orderBy('body')->get(['id', 'body']),
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
            ->when($this->platformId !== 'all', fn ($query) => $query->where('platform_id', $this->platformId))
            ->when($this->statusId !== 'all', fn ($query) => $query->where('status_id', $this->statusId))
            ->when($this->labelsSchemaReady() && $this->labelId !== 'all', function ($query) {
                if ($this->labelId === 'unlabeled') {
                    $query->whereNull('label_id');
                    return;
                }

                $query->where('label_id', $this->labelId);
            })
            ->when($this->search !== '', function ($query) {
                $search = '%'.$this->search.'%';

                $query->where(function ($query) use ($search) {
                    $query->where('spends.name', 'like', $search)
                        ->orWhereHas('budget', fn ($query) => $query->where('name', 'like', $search))
                        ->orWhereHas('platform', fn ($query) => $query->where('name', 'like', $search))
                        ->orWhereHas('status', fn ($query) => $query->where('body', 'like', $search));

                    if ($this->labelsSchemaReady()) {
                        $query->orWhereHas('label', fn ($query) => $query->where('name', 'like', $search));
                    }
                });
            });
    }

    private function sortedSpendQuery(Builder $query): Builder
    {
        return match ($this->sort) {
            'amount_desc' => $query->orderByDesc('amount'),
            'amount_asc' => $query->orderBy('amount'),
            'name' => $query->orderBy('name'),
            default => $query->latest('spends.created_at'),
        };
    }

    private function labelsSchemaReady(): bool
    {
        return Schema::hasTable('labels')
            && Schema::hasColumn('labels', 'user_id')
            && Schema::hasColumn('spends', 'label_id');
    }
}
