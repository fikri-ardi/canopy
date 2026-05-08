<?php

namespace App\Livewire;

use App\Models\Spend;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class Dashboard extends Component
{
    public $search = '';

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
            return (int) Spend::whereHas('budget', fn ($query) => $query->where('user_id', auth()->id()))->sum('amount');
        }

        return (int) Spend::whereHas('budget', fn ($query) => $query->where('user_id', auth()->id()))
            ->when($this->search, function ($query) {
            $query->leftJoin('labels', 'spends.label_id', '=', 'labels.id')
                ->where(function ($query) {
                    $query->where('labels.name', 'like', '%'.$this->search.'%')
                        ->orWhere('spends.name', 'like', '%'.$this->search.'%');
                });
        })->sum('spends.amount');
    }

    public function rupiah($amount): string
    {
        return 'Rp'.number_format((int) $amount, 0, ',', '.');
    }

    public function render()
    {
        $labelBreakdown = $this->labelBreakdown();

        return view('livewire.dashboard', [
            'labelBreakdown' => $labelBreakdown,
            'totalExpense' => $this->totalExpense(),
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
