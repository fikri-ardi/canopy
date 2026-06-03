<?php

namespace App\Http\Controllers;

use App\Exports\BudgetSpendsExport;
use App\Models\Budget;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Excel as ExcelWriter;
use Maatwebsite\Excel\Facades\Excel;

class DataExportController extends Controller
{
    public function budgets(Request $request)
    {
        $validated = $request->validate([
            'format' => ['required', Rule::in(['csv', 'xlsx', 'ods'])],
            'budgets' => ['nullable', 'array'],
            'budgets.*' => [
                'integer',
                Rule::exists('budgets', 'id')->where('user_id', $request->user()->id),
            ],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        $budgetIds = collect($validated['budgets'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        if ($budgetIds === [] && ! Budget::where('user_id', $request->user()->id)->exists()) {
            return back()->with('error', 'Belum ada budget yang bisa diexport.');
        }

        $format = $validated['format'];
        $writerType = match ($format) {
            'csv' => ExcelWriter::CSV,
            'ods' => ExcelWriter::ODS,
            default => ExcelWriter::XLSX,
        };

        $fileName = 'canopy-budget-export-'.now()->format('Ymd-His').'.'.$format;
        $headers = $format === 'csv' ? ['Content-Type' => 'text/csv'] : [];

        return Excel::download(
            new BudgetSpendsExport(
                user: $request->user(),
                budgetIds: $budgetIds,
                dateFrom: filled($validated['date_from'] ?? null) ? CarbonImmutable::parse($validated['date_from']) : null,
                dateTo: filled($validated['date_to'] ?? null) ? CarbonImmutable::parse($validated['date_to']) : null,
            ),
            $fileName,
            $writerType,
            $headers,
        );
    }
}
