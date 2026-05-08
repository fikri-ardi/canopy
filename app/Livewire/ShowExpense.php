<?php

namespace App\Livewire;

use App\Models\Spend;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ShowExpense extends Component
{
    #[Reactive]
    public $activeBudgetId;

    #[On('saved')]
    #[On('expense-deleted')]
    public function refreshExpenses()
    {
    }

    public function render()
    {
        $relations = ['platform', 'status'];

        if (Schema::hasTable('labels') && Schema::hasColumn('spends', 'label_id')) {
            $relations[] = 'label';
        }

        return view('livewire.show-expense', [
            'spends' => Spend::with($relations)
                ->where('budget_id', $this->activeBudgetId)
                ->whereHas('budget', fn ($query) => $query->where('user_id', auth()->id()))
                ->get(),
        ]);
    }
}
