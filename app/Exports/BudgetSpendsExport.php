<?php

namespace App\Exports;

use App\Models\Budget;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BudgetSpendsExport implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithCustomCsvSettings, WithHeadings, WithProperties, WithStyles
{
    /**
     * @param  array<int>  $budgetIds
     */
    public function __construct(
        private readonly User $user,
        private readonly array $budgetIds = [],
        private readonly ?CarbonImmutable $dateFrom = null,
        private readonly ?CarbonImmutable $dateTo = null,
    ) {}

    public function collection(): Collection
    {
        return $this->budgets()->flatMap(function (Budget $budget) {
            $spends = $budget->spends;
            $expenseCount = $spends->count();
            $expenseTotal = $spends->sum(fn ($spend) => (int) $spend->getRawOriginal('amount'));
            $income = (int) $budget->getRawOriginal('income');

            if ($spends->isEmpty()) {
                return [array_merge($this->budgetColumns($budget, $expenseCount, $expenseTotal, $income), [
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                ])];
            }

            return $spends->map(fn ($spend) => array_merge($this->budgetColumns($budget, $expenseCount, $expenseTotal, $income), [
                $spend->id,
                $spend->name,
                (int) $spend->getRawOriginal('amount'),
                $this->labelsReady() ? ($spend->label?->name ?? 'Unlabeled') : null,
                $spend->platform?->name,
                $spend->status?->body,
                $spend->created_at?->format('Y-m-d H:i:s'),
                $spend->updated_at?->format('Y-m-d H:i:s'),
            ]));
        })->values();
    }

    public function headings(): array
    {
        return [
            'Budget ID',
            'Budget Name',
            'Budget Income',
            'Budget Expense Count',
            'Budget Expense Total',
            'Budget Remaining',
            'Budget Created At',
            'Expense ID',
            'Expense Name',
            'Expense Amount',
            'Expense Category',
            'Expense Platform',
            'Expense Status',
            'Expense Created At',
            'Expense Updated At',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
            'J' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',',
            'enclosure' => '"',
            'line_ending' => PHP_EOL,
            'use_bom' => true,
        ];
    }

    public function properties(): array
    {
        return [
            'creator' => 'Canopy',
            'title' => 'Canopy Budget Export',
            'description' => 'Budgets and expenses exported from Canopy.',
            'subject' => 'Budgets and expenses',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->freezePane('A2');

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    private function budgets(): Collection
    {
        $relations = [
            'spends' => function ($query) {
                $query
                    ->when($this->dateFrom, fn ($query) => $query->where('created_at', '>=', $this->dateFrom->startOfDay()))
                    ->when($this->dateTo, fn ($query) => $query->where('created_at', '<=', $this->dateTo->endOfDay()))
                    ->orderBy('created_at')
                    ->orderBy('id');
            },
            'spends.platform',
            'spends.status',
        ];

        if ($this->labelsReady()) {
            $relations[] = 'spends.label';
        }

        return Budget::query()
            ->where('user_id', $this->user->id)
            ->when($this->budgetIds !== [], fn ($query) => $query->whereIn('id', $this->budgetIds))
            ->with($relations)
            ->orderBy('name')
            ->get();
    }

    private function budgetColumns(Budget $budget, int $expenseCount, int $expenseTotal, int $income): array
    {
        return [
            $budget->id,
            $budget->name,
            $income,
            $expenseCount,
            $expenseTotal,
            $income - $expenseTotal,
            $budget->created_at?->format('Y-m-d H:i:s'),
        ];
    }

    private function labelsReady(): bool
    {
        return Schema::hasTable('labels') && Schema::hasColumn('spends', 'label_id');
    }
}
