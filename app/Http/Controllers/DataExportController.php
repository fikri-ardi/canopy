<?php

namespace App\Http\Controllers;

use App\Services\DataExportService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class DataExportController extends Controller
{
    public function budgets(Request $request, DataExportService $dataExportService)
    {
        $validated = $request->validate([
            'format' => ['required', Rule::in(['csv', 'xlsx', 'ods'])],
            'budgets' => ['nullable', 'array'],
            'budgets.*' => [
                'integer',
                Rule::exists('budgets', 'id')->where('user_id', $request->user()->id),
            ],
        ]);

        $budgetIds = $dataExportService->selectedBudgetIds($validated['budgets'] ?? []);

        if ($budgetIds === [] && ! $dataExportService->userHasBudgets($request->user())) {
            return back()->with('error', 'Belum ada plan yang bisa diexport.');
        }

        $format = $validated['format'];

        return Excel::download(
            $dataExportService->export($request->user(), $budgetIds, $format),
            $dataExportService->fileName($format),
            $dataExportService->writerType($format),
            $dataExportService->headers($format),
        );
    }
}
