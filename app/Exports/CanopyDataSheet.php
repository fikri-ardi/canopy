<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CanopyDataSheet implements FromCollection, ShouldAutoSize, WithEvents, WithHeadings, WithStyles, WithTitle
{
    /**
     * @param  array<int, string>  $headings
     * @param  array<int, string>  $currencyColumns
     */
    public function __construct(
        private readonly string $title,
        private readonly array $headings,
        private readonly Collection $rows,
        private readonly array $currencyColumns = [],
    ) {}

    public function title(): string
    {
        return $this->title;
    }

    public function collection(): Collection
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->freezePane('A2');

        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F766E']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
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

                foreach ($this->currencyColumns as $column) {
                    $this->paintMagnitudeColumn($sheet, $column, $highestRow);
                }
            },
        ];
    }

    private function paintMagnitudeColumn(Worksheet $sheet, string $column, int $highestRow): void
    {
        $values = [];

        for ($row = 2; $row <= $highestRow; $row++) {
            $value = $this->integer($sheet->getCell("{$column}{$row}")->getValue());

            if ($value > 0) {
                $values[$row] = $value;
            }
        }

        $max = max($values ?: [0]);

        if ($max <= 0) {
            return;
        }

        foreach ($values as $row => $value) {
            $tone = match (true) {
                $value >= $max * 0.67 => 'high',
                $value >= $max * 0.34 => 'medium',
                default => 'low',
            };

            $palette = [
                'low' => ['fill' => 'DCFCE7', 'text' => '166534'],
                'medium' => ['fill' => 'FEF3C7', 'text' => '92400E'],
                'high' => ['fill' => 'FEE2E2', 'text' => '991B1B'],
            ];

            $sheet->getStyle("{$column}{$row}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => $palette[$tone]['text']]],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $palette[$tone]['fill']]],
            ]);
        }
    }

    private function integer(mixed $value): int
    {
        if (is_numeric($value)) {
            return (int) round((float) $value);
        }

        return (int) preg_replace('/[^\d-]/', '', (string) $value);
    }
}
