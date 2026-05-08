<?php

namespace App\Livewire;

use App\Models\Budget as ModelsBudget;
use Livewire\Component;

class Budget extends Component
{
    public $activeBudget;
    public $budgets;

    public function mount()
    {
        $this->activeBudget = ModelsBudget::first();
        $this->budgets = ModelsBudget::get(['id', 'name']);
    }

    public function render()
    {
        return view('livewire.budget');
    }
}
