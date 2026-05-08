<?php

namespace App\Livewire;

use App\Models\Budget;
use Livewire\Component;

class CreateBudget extends Component
{
    public $name;
    public $income;

    public function store()
    {
        $budget = Budget::create([
            'name' => $this->name,
            'income' => $this->income
        ]);

        dd($budget);
    }

    public function render()
    {
        return view('livewire.create-budget');
    }
}
