<?php

namespace App\Services;

use App\Exports\AlokasiDataExport;
use App\Exports\BudgetSpendsExport;
use App\Models\Budget;
use App\Models\User;
use Carbon\CarbonInterface;
use Maatwebsite\Excel\Excel as ExcelWriter;

class DataExportService
{
    public function selectedBudgetIds(array $budgetIds): array
    {
        return collect($budgetIds)
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }

    public function userHasBudgets(User $user): bool
    {
        return Budget::where('user_id', $user->id)->exists();
    }

    public function writerType(string $format): string
    {
        return match ($format) {
            'csv' => ExcelWriter::CSV,
            'ods' => ExcelWriter::ODS,
            default => ExcelWriter::XLSX,
        };
    }

    public function fileName(string $format, ?CarbonInterface $now = null): string
    {
        $now ??= now();

        return 'alokasi-plan-export-'.$now->format('Ymd-His').'.'.$format;
    }

    public function headers(string $format): array
    {
        return $format === 'csv' ? ['Content-Type' => 'text/csv'] : [];
    }

    public function export(User $user, array $budgetIds, string $format): AlokasiDataExport|BudgetSpendsExport
    {
        return $format === 'csv'
            ? new BudgetSpendsExport($user, $budgetIds)
            : new AlokasiDataExport($user, $budgetIds);
    }
}
