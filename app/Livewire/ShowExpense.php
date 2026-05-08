<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ShowExpense extends Component
{
    #[Reactive]
    public $activeBudget;

    public function mount($activeBudget = null)
    {
        $this->activeBudget = $activeBudget;
    }

    public function render()
    {
        return view('livewire.show-expense', [
            'spends' => $this->activeBudget->spends
        ]);
    }
}
