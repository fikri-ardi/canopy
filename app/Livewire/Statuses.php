<?php

namespace App\Livewire;

use App\Models\Status;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Statuses extends Component
{
    public $body = '';
    public $editingStatusId;
    public $editingBody = '';
    public $deleteStatusId;

    public function store()
    {
        if (! $this->schemaReady()) {
            return;
        }

        $validated = $this->validate([
            'body' => [
                'required',
                'string',
                'max:255',
                Rule::unique('statuses', 'body')->where('user_id', auth()->id()),
            ],
        ]);

        Status::create([
            'user_id' => auth()->id(),
            'body' => $validated['body'],
        ]);

        $this->reset('body');
    }

    public function startEditing(Status $status)
    {
        if (! $this->ownedByCurrentUser($status)) {
            return;
        }

        $this->editingStatusId = $status->id;
        $this->editingBody = $status->body;
    }

    public function update()
    {
        if (! $this->schemaReady()) {
            return;
        }

        $status = Status::where('user_id', auth()->id())->findOrFail($this->editingStatusId);

        $validated = $this->validate([
            'editingBody' => [
                'required',
                'string',
                'max:255',
                Rule::unique('statuses', 'body')->where('user_id', auth()->id())->ignore($status->id),
            ],
        ]);

        $status->update(['body' => $validated['editingBody']]);
        $this->reset(['editingStatusId', 'editingBody']);
    }

    public function confirmDelete(Status $status)
    {
        if (! $this->ownedByCurrentUser($status)) {
            return;
        }

        $this->deleteStatusId = $status->id;
    }

    public function delete()
    {
        if (! $this->schemaReady() || ! $this->deleteStatusId) {
            return;
        }

        $status = Status::where('user_id', auth()->id())->withCount('spends')->findOrFail($this->deleteStatusId);

        if ($status->spends_count > 0) {
            $this->addError('deleteStatus', 'Status yang sudah dipakai transaksi tidak bisa dihapus.');
            return;
        }

        $status->delete();
        $this->reset('deleteStatusId');
    }

    public function render()
    {
        return view('livewire.statuses', [
            'schemaReady' => $this->schemaReady(),
            'statuses' => $this->schemaReady()
                ? Status::where('user_id', auth()->id())->withCount('spends')->orderBy('body')->get()
                : collect(),
            'statusToDelete' => $this->schemaReady() && $this->deleteStatusId
                ? Status::where('user_id', auth()->id())->withCount('spends')->find($this->deleteStatusId)
                : null,
        ]);
    }

    private function ownedByCurrentUser(Status $status): bool
    {
        return $this->schemaReady() && (int) $status->user_id === auth()->id();
    }

    private function schemaReady(): bool
    {
        return Schema::hasColumn('statuses', 'user_id');
    }
}
