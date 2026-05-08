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
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'income' => ['required', 'numeric', 'min:0'],
        ]);

        $budget = Budget::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'income' => $validated['income'],
        ]);

        $this->reset(['name', 'income']);
        $this->dispatch('budget-created', budgetId: $budget->id);
    }

    public function render()
    {
        return view('livewire.create-budget');
    }
}
