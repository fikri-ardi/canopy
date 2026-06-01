<?php

namespace App\Livewire;

use App\Models\Label;
use App\Models\Platform;
use App\Models\Status;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
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
        $this->platforms = $this->userPlatformsQuery()->get(['id', 'name']);
        $this->statuses = $this->userStatusesQuery()->get(['id', 'body']);
        $this->labels = $this->labelsReady ? Label::where('user_id', auth()->id())->get(['id', 'name']) : collect();
    }

    public function updated($name, $value)
    {
        if (! $this->spendBelongsToCurrentUser()) {
            return;
        }

        if ($name === 'label_id' && $value === '') {
            $this->label_id = null;
            $value = null;
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'regex:/^[0-9.]+$/'],
            'platform_id' => [
                'required',
                Rule::exists('platforms', 'id')->when($this->platformsSchemaReady(), fn ($rule) => $rule->where('user_id', auth()->id())),
            ],
            'status_id' => [
                'required',
                Rule::exists('statuses', 'id')->when($this->statusesSchemaReady(), fn ($rule) => $rule->where('user_id', auth()->id())),
            ],
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

    public function amountToneClass(): string
    {
        $amount = (int) str_replace('.', '', (string) $this->amount);

        if ($amount >= 1000000) {
            return 'expense-tone-rose';
        }

        if ($amount >= 500000) {
            return 'expense-tone-amber';
        }

        if ($amount >= 100000) {
            return 'expense-tone-indigo';
        }

        return 'expense-tone-emerald';
    }

    public function labelToneClass(): string
    {
        return $this->toneFromText($this->spend->label?->name, 'expense-tone-slate');
    }

    public function platformToneClass(): string
    {
        $platform = strtolower($this->spend->platform?->name ?? '');

        if (str_contains($platform, 'cash')) {
            return 'expense-tone-emerald';
        }

        if (str_contains($platform, 'bank') || str_contains($platform, 'bri') || str_contains($platform, 'bni') || str_contains($platform, 'jago') || str_contains($platform, 'seabank')) {
            return 'expense-tone-sky';
        }

        if (str_contains($platform, 'pay') || str_contains($platform, 'wallet') || str_contains($platform, 'gopay') || str_contains($platform, 'shopee')) {
            return 'expense-tone-violet';
        }

        return $this->toneFromText($platform, 'expense-tone-slate');
    }

    public function statusToneClass(): string
    {
        $status = strtolower($this->spend->status?->body ?? '');

        return match (true) {
            str_contains($status, 'done'), str_contains($status, 'paid'), str_contains($status, 'complete') => 'expense-tone-emerald',
            str_contains($status, 'allocated') && ! str_contains($status, 'unallocated') => 'expense-tone-sky',
            str_contains($status, 'withdraw') => 'expense-tone-amber',
            str_contains($status, 'unallocated'), str_contains($status, 'unalocated') => 'expense-tone-rose',
            default => $this->toneFromText($status, 'expense-tone-slate'),
        };
    }

    private function toneFromText(?string $text, string $fallback): string
    {
        if (! $text) {
            return $fallback;
        }

        $tones = [
            'expense-tone-teal',
            'expense-tone-violet',
            'expense-tone-sky',
            'expense-tone-amber',
            'expense-tone-indigo',
            'expense-tone-emerald',
        ];

        return $tones[abs(crc32(strtolower($text))) % count($tones)];
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

    private function userPlatformsQuery()
    {
        return Platform::query()
            ->when($this->platformsSchemaReady(), fn ($query) => $query->where('user_id', auth()->id()))
            ->orderBy('name');
    }

    private function userStatusesQuery()
    {
        return Status::query()
            ->when($this->statusesSchemaReady(), fn ($query) => $query->where('user_id', auth()->id()))
            ->orderBy('body');
    }

    private function platformsSchemaReady(): bool
    {
        return Schema::hasColumn('platforms', 'user_id');
    }

    private function statusesSchemaReady(): bool
    {
        return Schema::hasColumn('statuses', 'user_id');
    }
}
