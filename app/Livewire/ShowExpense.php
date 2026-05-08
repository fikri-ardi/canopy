<?php

namespace App\Livewire;

use App\Models\Spend;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ShowExpense extends Component
{
    #[Reactive]
    public $activeBudget;

    #[On('saved')]
    #[On('expense-deleted')]
    public function refreshExpenses()
    {
    }

    public function render()
    {
        return view('livewire.show-expense', [
            'spends' => Spend::with(['platform', 'status'])
                ->where('budget_id', $this->activeBudget?->getKey())
                ->get(),
        ]);
    }
}
