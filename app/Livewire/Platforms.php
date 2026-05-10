<?php

namespace App\Livewire;

use App\Models\Platform;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Platforms extends Component
{
    public $name = '';
    public $editingPlatformId;
    public $editingName = '';
    public $deletePlatformId;

    public function store()
    {
        if (! $this->schemaReady()) {
            return;
        }

        $validated = $this->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('platforms', 'name')->where('user_id', auth()->id()),
            ],
        ]);

        Platform::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
        ]);

        $this->reset('name');
    }

    public function startEditing(Platform $platform)
    {
        if (! $this->ownedByCurrentUser($platform)) {
            return;
        }

        $this->editingPlatformId = $platform->id;
        $this->editingName = $platform->name;
    }

    public function update()
    {
        if (! $this->schemaReady()) {
            return;
        }

        $platform = Platform::where('user_id', auth()->id())->findOrFail($this->editingPlatformId);

        $validated = $this->validate([
            'editingName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('platforms', 'name')->where('user_id', auth()->id())->ignore($platform->id),
            ],
        ]);

        $platform->update(['name' => $validated['editingName']]);
        $this->reset(['editingPlatformId', 'editingName']);
    }

    public function confirmDelete(Platform $platform)
    {
        if (! $this->ownedByCurrentUser($platform)) {
            return;
        }

        $this->deletePlatformId = $platform->id;
    }

    public function delete()
    {
        if (! $this->schemaReady() || ! $this->deletePlatformId) {
            return;
        }

        $platform = Platform::where('user_id', auth()->id())->withCount('spends')->findOrFail($this->deletePlatformId);

        if ($platform->spends_count > 0) {
            $this->addError('deletePlatform', 'Platform yang sudah dipakai transaksi tidak bisa dihapus.');
            return;
        }

        $platform->delete();
        $this->reset('deletePlatformId');
    }

    public function render()
    {
        return view('livewire.platforms', [
            'schemaReady' => $this->schemaReady(),
            'platforms' => $this->schemaReady()
                ? Platform::where('user_id', auth()->id())->withCount('spends')->orderBy('name')->get()
                : collect(),
            'platformToDelete' => $this->schemaReady() && $this->deletePlatformId
                ? Platform::where('user_id', auth()->id())->withCount('spends')->find($this->deletePlatformId)
                : null,
        ]);
    }

    private function ownedByCurrentUser(Platform $platform): bool
    {
        return $this->schemaReady() && (int) $platform->user_id === auth()->id();
    }

    private function schemaReady(): bool
    {
        return Schema::hasColumn('platforms', 'user_id');
    }
}
