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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('labels', 'name')->where('user_id', auth()->id()),
            ],
        ]);

        Label::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
        ]);
        $this->reset('name');
    }

    public function startEditing(Label $label)
    {
        if (! $this->labelsSchemaReady() || (int) $label->user_id !== auth()->id()) {
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

        $label = Label::where('user_id', auth()->id())->findOrFail($this->editingLabelId);

        $validated = $this->validate([
            'editingName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('labels', 'name')->where('user_id', auth()->id())->ignore($label->id),
            ],
        ]);

        $label->update(['name' => $validated['editingName']]);
        $this->reset(['editingLabelId', 'editingName']);
    }

    public function confirmDelete(Label $label)
    {
        if (! $this->labelsSchemaReady() || (int) $label->user_id !== auth()->id()) {
            return;
        }

        $this->deleteLabelId = $label->id;
    }

    public function delete()
    {
        if (! $this->labelsSchemaReady() || ! $this->deleteLabelId) {
            return;
        }

        Label::where('user_id', auth()->id())->findOrFail($this->deleteLabelId)->delete();
        $this->reset('deleteLabelId');
    }

    public function render()
    {
        return view('livewire.labels', [
            'schemaReady' => $this->labelsSchemaReady(),
            'labels' => $this->labelsSchemaReady()
                ? Label::where('user_id', auth()->id())
                    ->withCount(['spends' => fn ($query) => $query->whereHas('budget', fn ($query) => $query->where('user_id', auth()->id()))])
                    ->orderBy('name')
                    ->get()
                : collect(),
            'labelToDelete' => $this->labelsSchemaReady() && $this->deleteLabelId
                ? Label::where('user_id', auth()->id())->find($this->deleteLabelId)
                : null,
        ]);
    }

    private function labelsSchemaReady(): bool
    {
        return Schema::hasTable('labels')
            && Schema::hasColumn('labels', 'user_id')
            && Schema::hasColumn('spends', 'label_id');
    }
}
