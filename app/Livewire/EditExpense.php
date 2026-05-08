<?php

namespace App\Livewire;

use App\Models\Label;
use App\Models\Platform;
use App\Models\Status;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class EditExpense extends Component
{
    public $spend;
    public $name;
    public $amount;
    public $platform_id;
    public $status_id;
    public $label_id;
    public $iteration;
    public $platforms;
    public $statuses;
    public $labels;
    public $labelsReady = false;

    public function mount($spend = null, $iteration = null)
    {
        abort_unless($spend && $spend->budget()->where('user_id', auth()->id())->exists(), 404);

        $this->iteration = $iteration;
        $this->spend = $spend;
        $this->name = $spend->name;
        $this->amount = $spend->amount;
        $this->platform_id = $spend->platform_id;
        $this->status_id = $spend->status_id;
        $this->labelsReady = $this->labelsSchemaReady();
        $this->label_id = $this->labelsReady ? $spend->label_id : null;
        $this->platforms = Platform::get(['id', 'name']);
        $this->statuses = Status::get(['id', 'body']);
        $this->labels = $this->labelsReady ? Label::where('user_id', auth()->id())->get(['id', 'name']) : collect();
    }

    public function updated($name, $value)
    {
        if (! $this->spendBelongsToCurrentUser()) {
            return;
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'regex:/^[0-9.]+$/'],
            'platform_id' => ['required', 'exists:platforms,id'],
            'status_id' => ['required', 'exists:statuses,id'],
        ];

        if ($this->labelsReady) {
            $rules['label_id'] = ['nullable', 'exists:labels,id,user_id,'.auth()->id()];
        }

        $this->validateOnly($name, $rules);

        $this->spend->update([
            $name => $value
        ]);

        $this->spend = $this->spend->fresh($this->labelsReady ? ['platform', 'status', 'label'] : ['platform', 'status']);
        $this->dispatch('expense-updated');
    }

    public function delete()
    {
        if (! $this->spendBelongsToCurrentUser()) {
            return;
        }

        $this->spend->delete();

        $this->dispatch('expense-deleted');
        $this->skipRender();
    }

    public function render()
    {
        return view('livewire.edit-expense');
    }

    private function labelsSchemaReady(): bool
    {
        return Schema::hasTable('labels')
            && Schema::hasColumn('labels', 'user_id')
            && Schema::hasColumn('spends', 'label_id');
    }

    private function spendBelongsToCurrentUser(): bool
    {
        return $this->spend?->budget()->where('user_id', auth()->id())->exists() ?? false;
    }
}
