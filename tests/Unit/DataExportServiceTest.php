<?php

use App\Exports\AlokasiDataExport;
use App\Exports\BudgetSpendsExport;
use App\Models\User;
use App\Services\DataExportService;
use Carbon\CarbonImmutable;
use Maatwebsite\Excel\Excel as ExcelWriter;

it('normalizes selected budget ids', function () {
    $service = new DataExportService();

    expect($service->selectedBudgetIds(['2', 1, '2', 3]))
        ->toBe([2, 1, 3]);
});

it('resolves writer types and headers by format', function () {
    $service = new DataExportService();

    expect($service->writerType('csv'))->toBe(ExcelWriter::CSV)
        ->and($service->writerType('ods'))->toBe(ExcelWriter::ODS)
        ->and($service->writerType('xlsx'))->toBe(ExcelWriter::XLSX)
        ->and($service->headers('csv'))->toBe(['Content-Type' => 'text/csv'])
        ->and($service->headers('xlsx'))->toBe([]);
});

it('builds deterministic export filenames', function () {
    $service = new DataExportService();
    $now = CarbonImmutable::create(2026, 6, 13, 19, 45, 10);

    expect($service->fileName('xlsx', $now))
        ->toBe('alokasi-plan-export-20260613-194510.xlsx');
});

it('selects the export class for the requested format', function () {
    $service = new DataExportService();
    $user = new User(['id' => 10]);

    expect($service->export($user, [1, 2], 'csv'))->toBeInstanceOf(BudgetSpendsExport::class)
        ->and($service->export($user, [1, 2], 'xlsx'))->toBeInstanceOf(AlokasiDataExport::class)
        ->and($service->export($user, [1, 2], 'ods'))->toBeInstanceOf(AlokasiDataExport::class);
});
