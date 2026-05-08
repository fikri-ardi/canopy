<?php

namespace App\Livewire;

use App\Models\Platform;
use App\Models\Status;
use Livewire\Component;

class CreateExpense extends Component
{
    public $name;
    public $amount;
    public $platforms;
    public $selectedPlatform;
    public $statuses;
    public $selectedStatus;
    public $activeBudget;

    public function mount($activeBudget = null)
    {
        $this->platforms = Platform::get(['id', 'name']);
        $this->selectedPlatform = Platform::first();
        $this->statuses = Status::get(['id', 'body']);
        $this->selectedStatus = Status::first();
        $this->activeBudget = $activeBudget;
    }

    public function selectPlatform(Platform $platform)
    {
        $this->selectedPlatform = $platform;
    }

    public function selectStatus(Status $status)
    {
        $this->selectedStatus = $status;
    }

    public function store()
    {
        $this->activeBudget->spends()->create([
            'platform_id' => $this->selectedPlatform->id,
            'status_id' => $this->selectedStatus->id,
            'name' => $this->name,
            'amount' => $this->amount
        ]);

        $this->dispatch('saved');
    }

    public function render()
    {
        return view('livewire.create-expense');
    }
}
