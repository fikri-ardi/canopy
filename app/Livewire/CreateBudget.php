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
        $hadBudget = Budget::where('user_id', auth()->id())->exists();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'income' => ['required', 'regex:/^[0-9][0-9.]*$/'],
        ]);

        $budget = Budget::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'income' => $this->rawAmount($validated['income']),
        ]);

        if (! $hadBudget || session('canopy_onboarding_step') === 'budget') {
            session(['canopy_onboarding_step' => 'expense']);
        }

        $this->reset(['name', 'income']);
        $this->dispatch('budget-created', budgetId: $budget->id);
    }

    private function rawAmount(string $amount): int
    {
        return (int) str_replace('.', '', $amount);
    }

    public function render()
    {
        return view('livewire.create-budget');
    }
}
