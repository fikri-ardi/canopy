<?php

namespace App\Livewire;

use App\Models\Label;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Labels extends Component
{
    public $name = '';
    public $editingLabelId;
    public $editingName = '';
    public $deleteLabelId;

    public function store()
    {
        if (! $this->labelsSchemaReady()) {
            return;
        }

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255', 'unique:labels,name'],
        ]);

        Label::create($validated);
        $this->reset('name');
    }

    public function startEditing(Label $label)
    {
        if (! $this->labelsSchemaReady()) {
            return;
        }

        $this->editingLabelId = $label->id;
        $this->editingName = $label->name;
    }

    public function update()
    {
        if (! $this->labelsSchemaReady()) {
            return;
        }

        $label = Label::findOrFail($this->editingLabelId);

        $validated = $this->validate([
            'editingName' => ['required', 'string', 'max:255', Rule::unique('labels', 'name')->ignore($label->id)],
        ]);

        $label->update(['name' => $validated['editingName']]);
        $this->reset(['editingLabelId', 'editingName']);
    }

    public function confirmDelete(Label $label)
    {
        if (! $this->labelsSchemaReady()) {
            return;
        }

        $this->deleteLabelId = $label->id;
    }

    public function delete()
    {
        if (! $this->labelsSchemaReady() || ! $this->deleteLabelId) {
            return;
        }

        Label::findOrFail($this->deleteLabelId)->delete();
        $this->reset('deleteLabelId');
    }

    public function render()
    {
        return view('livewire.labels', [
            'schemaReady' => $this->labelsSchemaReady(),
            'labels' => $this->labelsSchemaReady() ? Label::withCount('spends')->orderBy('name')->get() : collect(),
            'labelToDelete' => $this->labelsSchemaReady() && $this->deleteLabelId ? Label::find($this->deleteLabelId) : null,
        ]);
    }

    private function labelsSchemaReady(): bool
    {
        return Schema::hasTable('labels') && Schema::hasColumn('spends', 'label_id');
    }
}
