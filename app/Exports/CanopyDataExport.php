<?php

namespace App\Exports;

use App\Models\Budget;
use App\Models\InvestmentMovement;
use App\Models\InvestmentTarget;
use App\Models\Label;
use App\Models\Platform;
use App\Models\Status;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CanopyDataExport implements WithMultipleSheets
{
    /**
     * @param  array<int>  $budgetIds
     */
    public function __construct(
        private readonly User $user,
        private readonly array $budgetIds = [],
    ) {}

    public function sheets(): array
    {
        return [
            new BudgetSpendsExport($this->user, $this->budgetIds),
            new CanopyDataSheet('Budgets', $this->budgetHeadings(), $this->budgetRows(), ['C']),
            new CanopyDataSheet('Labels', $this->labelHeadings(), $this->labelRows()),
            new CanopyDataSheet('Platforms', $this->platformHeadings(), $this->platformRows()),
            new CanopyDataSheet('Statuses', $this->statusHeadings(), $this->statusRows()),
            new CanopyDataSheet('Investment Movements', $this->movementHeadings(), $this->movementRows(), ['E']),
            new CanopyDataSheet('Investment Targets', $this->targetHeadings(), $this->targetRows(), ['D']),
        ];
    }

    private function budgetRows(): Collection
    {
        return $this->budgets()
            ->map(fn (Budget $budget) => [
                $budget->id,
                $budget->name,
                $this->rupiah((int) $budget->getRawOriginal('income')),
                $budget->created_at?->format('Y-m-d H:i:s'),
                $budget->updated_at?->format('Y-m-d H:i:s'),
            ]);
    }

    private function labelRows(): Collection
    {
        if (! Schema::hasTable('labels')) {
            return collect();
        }

        return Label::query()
            ->when(Schema::hasColumn('labels', 'user_id'), fn ($query) => $query->where('user_id', $this->user->id))
            ->orderBy('name')
            ->get()
            ->map(fn (Label $label) => [
                $label->id,
                $label->name,
                $label->created_at?->format('Y-m-d H:i:s'),
                $label->updated_at?->format('Y-m-d H:i:s'),
            ]);
    }

    private function platformRows(): Collection
    {
        return Platform::query()
            ->when(Schema::hasColumn('platforms', 'user_id'), fn ($query) => $query->where('user_id', $this->user->id))
            ->orderBy('name')
            ->get()
            ->map(fn (Platform $platform) => [
                $platform->id,
                $platform->name,
                $platform->created_at?->format('Y-m-d H:i:s'),
                $platform->updated_at?->format('Y-m-d H:i:s'),
            ]);
    }

    private function statusRows(): Collection
    {
        return Status::query()
            ->when(Schema::hasColumn('statuses', 'user_id'), fn ($query) => $query->where('user_id', $this->user->id))
            ->orderBy('body')
            ->get()
            ->map(fn (Status $status) => [
                $status->id,
                $status->body,
                $status->created_at?->format('Y-m-d H:i:s'),
                $status->updated_at?->format('Y-m-d H:i:s'),
            ]);
    }

    private function movementRows(): Collection
    {
        if (! Schema::hasTable('investment_movements')) {
            return collect();
        }

        return InvestmentMovement::where('user_id', $this->user->id)
            ->orderBy('investment_name')
            ->orderBy('occurred_on')
            ->get()
            ->map(fn (InvestmentMovement $movement) => [
                $movement->id,
                $movement->investment_key,
                $movement->investment_name,
                $movement->type,
                $this->rupiah((int) $movement->amount),
                $movement->occurred_on?->format('Y-m-d'),
                $movement->note,
                $movement->created_at?->format('Y-m-d H:i:s'),
                $movement->updated_at?->format('Y-m-d H:i:s'),
            ]);
    }

    private function targetRows(): Collection
    {
        if (! Schema::hasTable('investment_targets')) {
            return collect();
        }

        return InvestmentTarget::where('user_id', $this->user->id)
            ->orderBy('investment_name')
            ->get()
            ->map(fn (InvestmentTarget $target) => [
                $target->id,
                $target->investment_key,
                $target->investment_name,
                $this->rupiah((int) $target->target_amount),
                $target->created_at?->format('Y-m-d H:i:s'),
                $target->updated_at?->format('Y-m-d H:i:s'),
            ]);
    }

    private function budgets(): Collection
    {
        return Budget::query()
            ->where('user_id', $this->user->id)
            ->when($this->budgetIds !== [], fn ($query) => $query->whereIn('id', $this->budgetIds))
            ->orderBy('name')
            ->get();
    }

    private function budgetHeadings(): array
    {
        return ['Budget ID', 'Budget Name', 'Budget Income', 'Budget Created At', 'Budget Updated At'];
    }

    private function labelHeadings(): array
    {
        return ['Label ID', 'Label Name', 'Label Created At', 'Label Updated At'];
    }

    private function platformHeadings(): array
    {
        return ['Platform ID', 'Platform Name', 'Platform Created At', 'Platform Updated At'];
    }

    private function statusHeadings(): array
    {
        return ['Status ID', 'Status Name', 'Status Created At', 'Status Updated At'];
    }

    private function movementHeadings(): array
    {
        return ['Movement ID', 'Investment Key', 'Investment Name', 'Movement Type', 'Movement Amount', 'Occurred On', 'Note', 'Movement Created At', 'Movement Updated At'];
    }

    private function targetHeadings(): array
    {
        return ['Target ID', 'Investment Key', 'Investment Name', 'Target Amount', 'Target Created At', 'Target Updated At'];
    }

    private function rupiah(int $amount): string
    {
        return 'Rp'.number_format($amount, 0, ',', '.');
    }
}
