<?php

namespace App\Http\Controllers;

use App\Exports\AlokasiDataExport;
use App\Exports\BudgetSpendsExport;
use App\Models\Budget;
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
        ]);

        $budgetIds = collect($validated['budgets'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        if ($budgetIds === [] && ! Budget::where('user_id', $request->user()->id)->exists()) {
            return back()->with('error', 'Belum ada plan yang bisa diexport.');
        }

        $format = $validated['format'];
        $writerType = match ($format) {
            'csv' => ExcelWriter::CSV,
            'ods' => ExcelWriter::ODS,
            default => ExcelWriter::XLSX,
        };

        $fileName = 'alokasi-plan-export-'.now()->format('Ymd-His').'.'.$format;
        $headers = $format === 'csv' ? ['Content-Type' => 'text/csv'] : [];

        return Excel::download(
            $format === 'csv'
                ? new BudgetSpendsExport($request->user(), $budgetIds)
                : new AlokasiDataExport($request->user(), $budgetIds),
            $fileName,
            $writerType,
            $headers,
        );
    }
}
