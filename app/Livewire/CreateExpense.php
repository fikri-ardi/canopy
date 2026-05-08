<?php

namespace App\Livewire;

use App\Models\Budget;
use App\Models\Label;
use App\Models\Platform;
use App\Models\Status;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class CreateExpense extends Component
{
    public $name;
    public $amount;
    public $platforms;
    public $selectedPlatform;
    public $statuses;
    public $selectedStatus;
    public $labels;
    public $selectedLabel;
    public $labelsReady = false;
    public $activeBudgetId;

    public function mount($activeBudgetId = null)
    {
        $this->platforms = Platform::get(['id', 'name']);
        $this->selectedPlatform = Platform::first();
        $this->statuses = Status::get(['id', 'body']);
        $this->selectedStatus = Status::first();
        $this->labelsReady = $this->labelsSchemaReady();
        $this->labels = collect();

        if ($this->labelsReady) {
            $this->selectedLabel = Label::where('user_id', auth()->id())->first()
                ?? Label::create(['user_id' => auth()->id(), 'name' => 'General']);
            $this->labels = Label::where('user_id', auth()->id())->get(['id', 'name']);
        }

        $this->activeBudgetId = $activeBudgetId;
    }

    public function selectPlatform(Platform $platform)
    {
        $this->selectedPlatform = $platform;
    }

    public function selectStatus(Status $status)
    {
        $this->selectedStatus = $status;
    }

    public function selectLabel($labelId)
    {
        $this->selectedLabel = Label::where('user_id', auth()->id())->find($labelId);
    }

    public function store()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
        ]);

        $activeBudget = $this->activeBudgetId
            ? Budget::where('user_id', auth()->id())->find($this->activeBudgetId)
            : null;

        if (! $activeBudget || ! $this->selectedPlatform || ! $this->selectedStatus) {
            return;
        }

        $payload = [
            'platform_id' => $this->selectedPlatform->id,
            'status_id' => $this->selectedStatus->id,
            'name' => $validated['name'],
            'amount' => $validated['amount']
        ];

        if ($this->labelsReady && $this->selectedLabel) {
            $payload['label_id'] = $this->selectedLabel->id;
        }

        $activeBudget->spends()->create($payload);

        $this->reset(['name', 'amount']);
        $this->dispatch('saved');
    }

    private function labelsSchemaReady(): bool
    {
        return Schema::hasTable('labels')
            && Schema::hasColumn('labels', 'user_id')
            && Schema::hasColumn('spends', 'label_id');
    }

    public function render()
    {
        return view('livewire.create-expense');
    }
}
