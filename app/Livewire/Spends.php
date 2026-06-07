<?php

namespace App\Livewire;

use App\Models\Spend;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\WithPagination;

class Spends extends Component
{
    use WithPagination;

    public $search = '';

    public function updated($name): void
    {
        if ($name === 'search') {
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
            'spends' => $filteredQuery->latest('spends.created_at')->paginate(12),
            'totalAmount' => $totalAmount,
            'transactionCount' => $transactionCount,
            'averageAmount' => $transactionCount > 0 ? (int) round($totalAmount / $transactionCount) : 0,
            'largestAmount' => (int) (clone $filteredQuery)->max('amount'),
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

    private function labelsSchemaReady(): bool
    {
        return Schema::hasTable('labels')
            && Schema::hasColumn('labels', 'user_id')
            && Schema::hasColumn('spends', 'label_id');
    }

}
