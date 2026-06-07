<?php

namespace App\Exports;

use App\Models\Budget;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BudgetSpendsExport implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithColumnWidths, WithCustomCsvSettings, WithEvents, WithHeadings, WithProperties, WithStyles, WithTitle
{
    /**
     * @param  array<int>  $budgetIds
     */
    public function __construct(
        private readonly User $user,
        private readonly array $budgetIds = [],
    ) {}

    public function title(): string
    {
        return 'Expenses';
    }

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
                $this->currencyValue((int) $spend->getRawOriginal('amount')),
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
            'Plan ID',
            'Plan Name',
            'Plan Income',
            'Plan Expense Count',
            'Plan Expense Total',
            'Plan Remaining',
            'Plan Created At',
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
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'J' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'B' => 24,
            'I' => 28,
            'K' => 18,
            'L' => 18,
            'M' => 18,
            'N' => 20,
            'O' => 20,
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
            'creator' => 'Alokasi',
            'title' => 'Alokasi Plan Export',
            'description' => 'Plans and expenses exported from Alokasi.',
            'subject' => 'Plans and expenses',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->freezePane('A2');

        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0F766E'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                if ($highestRow < 1) {
                    return;
                }

                $sheet->setAutoFilter("A1:{$highestColumn}{$highestRow}");
                $sheet->getRowDimension(1)->setRowHeight(28);
                $sheet->getStyle("A1:{$highestColumn}{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'CBD5E1'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($row % 2 === 0) {
                        $sheet->getStyle("A{$row}:{$highestColumn}{$row}")->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setRGB('F8FAFC');
                    }
                }

                foreach (['A', 'D', 'H'] as $column) {
                    $sheet->getStyle("{$column}2:{$column}{$highestRow}")->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                $this->paintMagnitudeColumn($sheet, 'C', $highestRow);
                $this->paintMagnitudeColumn($sheet, 'E', $highestRow);
                $this->paintRemainingColumn($sheet, $highestRow);
                $this->paintMagnitudeColumn($sheet, 'J', $highestRow);
            },
        ];
    }

    private function budgets(): Collection
    {
        $relations = [
            'spends' => function ($query) {
                $query
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
            $this->currencyValue($income),
            $expenseCount,
            $this->currencyValue($expenseTotal),
            $this->currencyValue($income - $expenseTotal),
            $budget->created_at?->format('Y-m-d H:i:s'),
        ];
    }

    private function labelsReady(): bool
    {
        return Schema::hasTable('labels') && Schema::hasColumn('spends', 'label_id');
    }

    private function currencyValue(int $amount): string
    {
        return $this->rupiah($amount);
    }

    private function rupiah(int $amount): string
    {
        return 'Rp'.number_format($amount, 0, ',', '.');
    }

    private function paintMagnitudeColumn(Worksheet $sheet, string $column, int $highestRow): void
    {
        $values = $this->numericColumnValues($sheet, $column, $highestRow);
        $max = max($values ?: [0]);

        if ($max <= 0) {
            return;
        }

        foreach ($values as $row => $value) {
            $this->paintCell($sheet, "{$column}{$row}", match (true) {
                $value >= $max * 0.67 => 'high',
                $value >= $max * 0.34 => 'medium',
                default => 'low',
            });
        }
    }

    private function paintRemainingColumn(Worksheet $sheet, int $highestRow): void
    {
        for ($row = 2; $row <= $highestRow; $row++) {
            $remaining = $this->integer($sheet->getCell("F{$row}")->getValue());
            $income = max(1, $this->integer($sheet->getCell("C{$row}")->getValue()));

            $this->paintCell($sheet, "F{$row}", match (true) {
                $remaining < 0 => 'high',
                $remaining <= $income * 0.2 => 'medium',
                default => 'low',
            });
        }
    }

    /**
     * @return array<int, int>
     */
    private function numericColumnValues(Worksheet $sheet, string $column, int $highestRow): array
    {
        $values = [];

        for ($row = 2; $row <= $highestRow; $row++) {
            $value = $sheet->getCell("{$column}{$row}")->getValue();

            if (filled($value)) {
                $values[$row] = abs($this->integer($value));
            }
        }

        return $values;
    }

    private function integer(mixed $value): int
    {
        if (is_numeric($value)) {
            return (int) round((float) $value);
        }

        return (int) preg_replace('/[^\d-]/', '', (string) $value);
    }

    private function paintCell(Worksheet $sheet, string $cell, string $tone): void
    {
        $palette = [
            'low' => ['fill' => 'DCFCE7', 'text' => '166534'],
            'medium' => ['fill' => 'FEF3C7', 'text' => '92400E'],
            'high' => ['fill' => 'FEE2E2', 'text' => '991B1B'],
        ];

        $sheet->getStyle($cell)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => $palette[$tone]['text']],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $palette[$tone]['fill']],
            ],
        ]);
    }
}
