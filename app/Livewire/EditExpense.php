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
    public $maxAmount = 0;

    public function mount($spend = null, $iteration = null, $maxAmount = 0)
    {
        abort_unless($spend && $spend->budget()->where('user_id', auth()->id())->exists(), 404);

        $this->iteration = $iteration;
        $this->maxAmount = (int) $maxAmount;
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
        $maxAmount = max((int) $this->maxAmount, $amount, 1);
        $tier = max(1, min(10, (int) ceil(($amount / $maxAmount) * 10)));

        return 'expense-amount-tier-'.$tier;
    }

    public function toneStyle(string $tone): string
    {
        [$background, $text, $border, $accent] = $this->tonePalette()[$tone] ?? $this->tonePalette()['expense-tone-slate'];

        return "--chip-bg: {$background}; --chip-text: {$text}; --chip-border: {$border}; --chip-accent: {$accent};";
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
            str_contains($status, 'unallocated'), str_contains($status, 'unalocated') => 'expense-tone-rose',
            str_contains($status, 'allocated'), str_contains($status, 'allcoated') => 'expense-tone-sky',
            str_contains($status, 'withdraw') => 'expense-tone-blue',
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

    private function tonePalette(): array
    {
        return [
            'expense-tone-emerald' => ['rgba(16, 185, 129, 0.14)', '#34d399', 'rgba(16, 185, 129, 0.28)', '#10b981'],
            'expense-tone-rose' => ['rgba(244, 63, 94, 0.14)', '#fb7185', 'rgba(244, 63, 94, 0.28)', '#f43f5e'],
            'expense-tone-blue' => ['rgba(37, 99, 235, 0.15)', '#93c5fd', 'rgba(37, 99, 235, 0.30)', '#2563eb'],
            'expense-tone-amber' => ['rgba(245, 158, 11, 0.14)', '#fbbf24', 'rgba(245, 158, 11, 0.28)', '#f59e0b'],
            'expense-tone-sky' => ['rgba(14, 165, 233, 0.14)', '#38bdf8', 'rgba(14, 165, 233, 0.28)', '#0ea5e9'],
            'expense-tone-violet' => ['rgba(139, 92, 246, 0.14)', '#c4b5fd', 'rgba(139, 92, 246, 0.28)', '#8b5cf6'],
            'expense-tone-indigo' => ['rgba(99, 102, 241, 0.14)', '#a5b4fc', 'rgba(99, 102, 241, 0.28)', '#6366f1'],
            'expense-tone-teal' => ['rgba(20, 184, 166, 0.14)', '#5eead4', 'rgba(20, 184, 166, 0.28)', '#14b8a6'],
            'expense-tone-slate' => ['rgba(148, 163, 184, 0.12)', '#cbd5e1', 'rgba(148, 163, 184, 0.22)', '#94a3b8'],
            'expense-amount-tier-1' => ['rgba(16, 185, 129, 0.14)', '#34d399', 'rgba(16, 185, 129, 0.28)', '#10b981'],
            'expense-amount-tier-2' => ['rgba(34, 197, 94, 0.14)', '#4ade80', 'rgba(34, 197, 94, 0.28)', '#22c55e'],
            'expense-amount-tier-3' => ['rgba(132, 204, 22, 0.14)', '#a3e635', 'rgba(132, 204, 22, 0.28)', '#84cc16'],
            'expense-amount-tier-4' => ['rgba(234, 179, 8, 0.14)', '#facc15', 'rgba(234, 179, 8, 0.28)', '#eab308'],
            'expense-amount-tier-5' => ['rgba(245, 158, 11, 0.14)', '#fbbf24', 'rgba(245, 158, 11, 0.28)', '#f59e0b'],
            'expense-amount-tier-6' => ['rgba(249, 115, 22, 0.14)', '#fb923c', 'rgba(249, 115, 22, 0.28)', '#f97316'],
            'expense-amount-tier-7' => ['rgba(239, 68, 68, 0.14)', '#f87171', 'rgba(239, 68, 68, 0.28)', '#ef4444'],
            'expense-amount-tier-8' => ['rgba(244, 63, 94, 0.14)', '#fb7185', 'rgba(244, 63, 94, 0.28)', '#f43f5e'],
            'expense-amount-tier-9' => ['rgba(225, 29, 72, 0.16)', '#fda4af', 'rgba(225, 29, 72, 0.30)', '#e11d48'],
            'expense-amount-tier-10' => ['rgba(220, 38, 38, 0.18)', '#fca5a5', 'rgba(220, 38, 38, 0.34)', '#dc2626'],
        ];
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
