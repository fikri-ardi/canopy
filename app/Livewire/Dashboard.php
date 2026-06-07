<?php

namespace App\Livewire;

use App\Models\Budget;
use App\Models\Spend;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Throwable;
use Livewire\Component;

class Dashboard extends Component
{
    public $search = '';
    public $startDate = '';
    public $endDate = '';
    public $labelActivityYear;
    public $showAllLabelActivity = false;

    public function mount(): void
    {
        $this->labelActivityYear ??= now()->year;
    }

    public function completeOnboarding(): void
    {
        if (! auth()->user()->needsOnboarding()) {
            return;
        }

        auth()->user()->forceFill([
            'onboarding_completed_at' => now(),
        ])->save();
    }

    public function toggleLabelActivityRows(): void
    {
        $this->showAllLabelActivity = ! $this->showAllLabelActivity;
    }

    public function updatedLabelActivityYear(): void
    {
        $this->showAllLabelActivity = false;
    }

    private function shouldShowOnboardingWelcome(): bool
    {
        return auth()->user()->needsOnboarding()
            && Budget::query()
                ->where('user_id', auth()->id())
                ->whereHas('spends')
                ->exists();
    }

    private function userSpendQuery(bool $withRelations = false): Builder
    {
        $relations = ['budget', 'platform', 'status'];

        if ($this->labelsSchemaReady()) {
            $relations[] = 'label';
        }

        return Spend::query()
            ->when($withRelations, fn ($query) => $query->with($relations))
            ->whereHas('budget', fn ($query) => $query->where('user_id', auth()->id()))
            ->tap(fn (Builder $query) => $this->applyDateRange($query));
    }

    private function userBudgetQuery(): Builder
    {
        return Budget::query()
            ->where('user_id', auth()->id());
    }

    private function labelBreakdown()
    {
        if (! $this->labelsSchemaReady()) {
            return collect();
        }

        $labels = Spend::query()
            ->leftJoin('labels', 'spends.label_id', '=', 'labels.id')
            ->whereHas('budget', fn ($query) => $query->where('user_id', auth()->id()))
            ->tap(fn (Builder $query) => $this->applyDateRange($query))
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('labels.name', 'like', '%'.$this->search.'%')
                        ->orWhere('spends.name', 'like', '%'.$this->search.'%');
                });
            })
            ->selectRaw("coalesce(labels.name, 'Unlabeled') as label_name, sum(spends.amount) as total, count(*) as transactions")
            ->groupByRaw("coalesce(labels.name, 'Unlabeled')")
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        return $labels->map(function ($label) {
            $items = Spend::query()
                ->leftJoin('labels', 'spends.label_id', '=', 'labels.id')
                ->whereHas('budget', fn ($query) => $query->where('user_id', auth()->id()))
                ->whereRaw("coalesce(labels.name, 'Unlabeled') = ?", [$label->label_name])
                ->tap(fn (Builder $query) => $this->applyDateRange($query))
                ->when($this->search, function ($query) {
                    $query->where(function ($query) {
                        $query->where('labels.name', 'like', '%'.$this->search.'%')
                            ->orWhere('spends.name', 'like', '%'.$this->search.'%');
                    });
                })
                ->selectRaw('spends.name as name, sum(spends.amount) as total, count(*) as transactions')
                ->groupBy('spends.name')
                ->orderByDesc('total')
                ->limit(4)
                ->get();

            $maxTotal = max((int) $items->max('total'), 1);

            return [
                'name' => $label->label_name,
                'total' => (int) $label->total,
                'transactions' => (int) $label->transactions,
                'items' => $items->map(fn ($item) => [
                    'name' => $item->name,
                    'total' => (int) $item->total,
                    'transactions' => (int) $item->transactions,
                    'percentage' => round(((int) $item->total / $maxTotal) * 100),
                ]),
            ];
        });
    }

    private function totalExpense(): int
    {
        return (int) $this->userSpendQuery()->sum('amount');
    }

    private function labelActivityHeatmap(): array
    {
        if (! $this->labelsSchemaReady()) {
            return [
                'ready' => false,
                'rows' => collect(),
                'weeks' => collect(),
                'totalRows' => 0,
                'hiddenRows' => 0,
                'canExpand' => false,
                'isExpanded' => false,
                'totalTransactions' => 0,
                'periodLabel' => '',
            ];
        }

        [$periodStart, $periodEnd, $gridStart, $gridEnd] = $this->labelActivityRange();
        $weeks = collect();
        $cursor = $gridStart->copy();

        while ($cursor->lte($gridEnd)) {
            $weekStart = $cursor->copy();

            $weeks->push([
                'key' => $weekStart->toDateString(),
                'label' => $weekStart->isSameMonth($weekStart->copy()->subWeek()) ? '' : $weekStart->format('M'),
                'short' => $weekStart->format('d M'),
                'days' => collect(range(0, 6))->map(function (int $offset) use ($weekStart) {
                    $day = $weekStart->copy()->addDays($offset);

                    return [
                        'key' => $day->toDateString(),
                        'label' => $day->format('D'),
                        'short' => $day->format('d M'),
                    ];
                }),
            ]);

            $cursor->addWeek();
        }

        $spends = Spend::query()
            ->leftJoin('labels', 'spends.label_id', '=', 'labels.id')
            ->whereHas('budget', fn ($query) => $query->where('user_id', auth()->id()))
            ->whereBetween('spends.created_at', [$periodStart, $periodEnd])
            ->selectRaw("coalesce(labels.name, 'Unlabeled') as label_name, spends.amount as raw_amount, spends.created_at")
            ->get();

        $cellTotals = $spends
            ->groupBy(fn ($spend) => $spend->label_name.'|'.$spend->created_at->copy()->toDateString())
            ->map(fn ($items) => (int) $items->sum(fn ($spend) => (int) $spend->raw_amount));

        $maxCell = max((int) $cellTotals->max(), 1);

        $labelRows = $spends
            ->groupBy('label_name')
            ->map(fn ($items, string $label) => [
                'label' => $label,
                'total' => (int) $items->sum(fn ($spend) => (int) $spend->raw_amount),
                'transactions' => $items->count(),
                'items' => $items,
            ])
            ->sortByDesc('total')
            ->values();

        $totalRows = $labelRows->count();
        $visibleRows = $this->showAllLabelActivity ? $labelRows : $labelRows->take(3);

        $rows = $visibleRows->map(function (array $row) use ($weeks, $cellTotals, $maxCell) {
            return [
                'label' => $row['label'],
                'total' => $row['total'],
                'transactions' => $row['transactions'],
                'weeks' => $weeks->map(function (array $week) use ($row, $cellTotals, $maxCell) {
                    return [
                        'key' => $week['key'],
                        'days' => $week['days']->map(function (array $day) use ($row, $cellTotals, $maxCell) {
                            $amount = (int) ($cellTotals->get($row['label'].'|'.$day['key']) ?? 0);
                            $level = $amount === 0 ? 0 : max(1, min(4, (int) ceil(($amount / $maxCell) * 4)));

                            return [
                                'amount' => $amount,
                                'formatted' => $this->rupiah($amount),
                                'level' => $level,
                                'day' => $day['label'],
                                'date' => $day['short'],
                            ];
                        }),
                    ];
                }),
            ];
        });

        return [
            'ready' => true,
            'rows' => $rows,
            'totalRows' => $totalRows,
            'hiddenRows' => max(0, $totalRows - $rows->count()),
            'canExpand' => $totalRows > 3,
            'isExpanded' => (bool) $this->showAllLabelActivity,
            'weeks' => $weeks,
            'totalTransactions' => $spends->count(),
            'periodLabel' => $periodStart->format('M').' - '.$periodEnd->format('M Y'),
        ];
    }

    private function totalIncome(): int
    {
        return (int) $this->userBudgetQuery()->sum('income');
    }

    private function transactionCount(): int
    {
        return (int) $this->userSpendQuery()->count();
    }

    private function averageTransaction(): int
    {
        $transactionCount = $this->transactionCount();

        if ($transactionCount === 0) {
            return 0;
        }

        return (int) round($this->totalExpense() / $transactionCount);
    }

    private function largestExpense()
    {
        return $this->userSpendQuery(true)
            ->orderByDesc('amount')
            ->first();
    }

    private function recentExpenses()
    {
        return $this->userSpendQuery(true)
            ->latest()
            ->take(5)
            ->get();
    }

    private function budgetHealth()
    {
        return $this->userBudgetQuery()
            ->withSum(['spends as total_spent' => function ($query) {
                $this->applyDateRange($query, 'created_at');
            }], 'amount')
            ->latest('created_at')
            ->latest('id')
            ->take(5)
            ->get()
            ->map(function (Budget $budget) {
                $income = (int) $budget->income;
                $spent = (int) ($budget->total_spent ?? 0);
                $remaining = $income - $spent;
                $percentage = $income > 0 ? min(100, round(($spent / $income) * 100)) : 0;

                return [
                    'id' => $budget->id,
                    'name' => $budget->name,
                    'income' => $income,
                    'spent' => $spent,
                    'remaining' => $remaining,
                    'percentage' => $percentage,
                    'tone' => $remaining < 0 ? 'danger' : ($percentage >= 80 ? 'warning' : 'healthy'),
                ];
            });
    }

    private function platformBreakdown()
    {
        $totalExpense = max($this->totalExpense(), 1);

        return $this->userSpendQuery()
            ->join('platforms', 'spends.platform_id', '=', 'platforms.id')
            ->selectRaw('platforms.name as name, sum(spends.amount) as total, count(*) as transactions')
            ->groupBy('platforms.id', 'platforms.name')
            ->orderByDesc('total')
            ->limit(6)
            ->get()
            ->map(fn ($item) => [
                'name' => $item->name,
                'total' => (int) $item->total,
                'transactions' => (int) $item->transactions,
                'percentage' => round(((int) $item->total / $totalExpense) * 100),
            ]);
    }

    private function statusBreakdown()
    {
        return $this->userSpendQuery()
            ->join('statuses', 'spends.status_id', '=', 'statuses.id')
            ->selectRaw('statuses.body as name, sum(spends.amount) as total, count(*) as transactions')
            ->groupBy('statuses.id', 'statuses.body')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($item) => [
                'name' => $item->name,
                'total' => (int) $item->total,
                'transactions' => (int) $item->transactions,
            ]);
    }

    private function topExpenses()
    {
        return $this->userSpendQuery(true)
            ->orderByDesc('amount')
            ->take(5)
            ->get();
    }

    private function categoryBudgetChart(): array
    {
        if (! $this->labelsSchemaReady()) {
            return [
                'ready' => false,
                'series' => collect(),
                'budgets' => collect(),
                'yTicks' => collect(),
                'width' => 700,
                'height' => 300,
            ];
        }

        $rows = Spend::query()
            ->join('budgets', 'spends.budget_id', '=', 'budgets.id')
            ->leftJoin('labels', 'spends.label_id', '=', 'labels.id')
            ->where('budgets.user_id', auth()->id())
            ->tap(fn (Builder $query) => $this->applyDateRange($query))
            ->selectRaw("spends.budget_id as budget_id, coalesce(labels.name, 'Unlabeled') as category, sum(spends.amount) as total")
            ->groupBy('spends.budget_id')
            ->groupByRaw("coalesce(labels.name, 'Unlabeled')")
            ->get();

        if ($rows->isEmpty()) {
            return [
                'ready' => true,
                'series' => collect(),
                'budgets' => collect(),
                'yTicks' => collect(),
                'width' => 700,
                'height' => 300,
            ];
        }

        $budgetIds = $rows->pluck('budget_id')->unique()->values();
        $budgets = Budget::query()
            ->where('user_id', auth()->id())
            ->whereIn('id', $budgetIds)
            ->orderBy('created_at')
            ->orderBy('id')
            ->get(['id', 'name'])
            ->values();

        $width = 700;
        $height = 300;
        $padding = ['left' => 48, 'right' => 20, 'top' => 18, 'bottom' => 46];
        $plotWidth = $width - $padding['left'] - $padding['right'];
        $plotHeight = $height - $padding['top'] - $padding['bottom'];
        $maxValue = max((int) $rows->max('total'), 1);
        $chartMax = $maxValue;
        $colors = $this->chartColors();

        $chartBudgets = $budgets
            ->map(fn (Budget $budget) => [
                'id' => $budget->id,
                'name' => $budget->name,
                'shortName' => str($budget->name)->limit(14)->toString(),
                'baseline' => false,
            ])
            ->values();

        if ($chartBudgets->count() === 1) {
            $chartBudgets->prepend([
                'id' => '__baseline',
                'name' => 'Start',
                'shortName' => 'Start',
                'baseline' => true,
            ]);
        }

        $xStep = $chartBudgets->count() > 1 ? $plotWidth / ($chartBudgets->count() - 1) : 0;

        $budgetPoints = $chartBudgets->map(function (array $budget, int $index) use ($padding, $xStep, $plotWidth) {
            return [
                'id' => $budget['id'],
                'name' => $budget['name'],
                'shortName' => $budget['shortName'],
                'baseline' => $budget['baseline'],
                'x' => $padding['left'] + ($xStep ? $index * $xStep : $plotWidth / 2),
            ];
        });

        $valuesByCategory = $rows
            ->groupBy('category')
            ->map(fn ($items) => [
                'total' => (int) $items->sum('total'),
                'values' => $items->keyBy('budget_id')->map(fn ($item) => (int) $item->total),
            ])
            ->sortByDesc('total');

        $series = $valuesByCategory->values()->map(function (array $category, int $index) use ($budgetPoints, $chartMax, $colors, $padding, $plotHeight) {
            $points = $budgetPoints->map(function (array $budget) use ($category, $chartMax, $padding, $plotHeight) {
                $amount = (int) ($category['values']->get($budget['id']) ?? 0);
                $y = $padding['top'] + $plotHeight - (($amount / $chartMax) * $plotHeight);

                return [
                    'budget' => $budget['name'],
                    'amount' => $amount,
                    'formatted' => $this->rupiah($amount),
                    'x' => round($budget['x'], 2),
                    'y' => round($y, 2),
                ];
            })->values();

            $latestPoint = $points->filter(fn ($point) => $point['amount'] > 0)->last() ?? $points->last();

            return [
                'name' => $category['values']->keys()->isNotEmpty() ? $category['values']->keys()->first() : null,
                'label' => null,
                'total' => $category['total'],
                'formattedTotal' => $this->rupiah($category['total']),
                'color' => $colors[$index % count($colors)],
                'points' => $points,
                'path' => $this->smoothPath($points->all()),
                'areaPath' => $this->areaPath($points->all(), $padding['top'] + $plotHeight),
                'latestPoint' => $latestPoint,
            ];
        });

        $series = $valuesByCategory->keys()->values()->map(function (string $name, int $index) use ($series) {
            $item = $series[$index];
            $item['name'] = $name;
            $item['label'] = str($name)->limit(18)->toString();

            return $item;
        });

        $yTicks = collect([0, 0.5, 1])->map(function (float $tick) use ($chartMax, $padding, $plotHeight) {
            $value = (int) round($chartMax * $tick);
            $y = $padding['top'] + $plotHeight - (($value / $chartMax) * $plotHeight);

            return [
                'value' => $value,
                'label' => $this->compactAxisAmount($value),
                'y' => round($y, 2),
            ];
        })->reverse()->values();

        return [
            'ready' => true,
            'series' => $series,
            'budgets' => $budgetPoints,
            'yTicks' => $yTicks,
            'width' => $width,
            'height' => $height,
            'plot' => [
                'left' => $padding['left'],
                'right' => $width - $padding['right'],
                'top' => $padding['top'],
                'bottom' => $height - $padding['bottom'],
                'height' => $plotHeight,
            ],
        ];
    }

    private function chartColors(): array
    {
        return [
            '#22c55e',
            '#0ea5e9',
            '#8b5cf6',
            '#f59e0b',
            '#ef4444',
            '#14b8a6',
            '#6366f1',
            '#84cc16',
            '#ec4899',
            '#f97316',
            '#06b6d4',
            '#64748b',
        ];
    }

    private function smoothPath(array $points): string
    {
        if (count($points) === 0) {
            return '';
        }

        if (count($points) === 1) {
            return 'M '.$points[0]['x'].' '.$points[0]['y'];
        }

        $path = 'M '.$points[0]['x'].' '.$points[0]['y'];

        for ($index = 0; $index < count($points) - 1; $index++) {
            $current = $points[$index];
            $next = $points[$index + 1];
            $previous = $points[$index - 1] ?? $current;
            $afterNext = $points[$index + 2] ?? $next;

            $controlOneX = $current['x'] + ($next['x'] - $previous['x']) / 6;
            $controlOneY = $current['y'] + ($next['y'] - $previous['y']) / 6;
            $controlTwoX = $next['x'] - ($afterNext['x'] - $current['x']) / 6;
            $controlTwoY = $next['y'] - ($afterNext['y'] - $current['y']) / 6;

            $path .= ' C '.round($controlOneX, 2).' '.round($controlOneY, 2).', '.round($controlTwoX, 2).' '.round($controlTwoY, 2).', '.$next['x'].' '.$next['y'];
        }

        return $path;
    }

    private function areaPath(array $points, float $baseline): string
    {
        if (count($points) === 0) {
            return '';
        }

        $linePath = $this->smoothPath($points);
        $last = $points[count($points) - 1];
        $first = $points[0];

        return $linePath.' L '.$last['x'].' '.round($baseline, 2).' L '.$first['x'].' '.round($baseline, 2).' Z';
    }

    private function compactAxisAmount(int $amount): string
    {
        if ($amount >= 1000000) {
            $value = $amount / 1000000;

            return number_format($value, $amount % 1000000 === 0 ? 0 : 1, ',', '.').'jt';
        }

        if ($amount >= 1000) {
            return number_format($amount / 1000, 0, ',', '.').'rb';
        }

        return number_format($amount, 0, ',', '.');
    }

    private function applyDateRange(Builder $query, string $column = 'spends.created_at'): Builder
    {
        return $query
            ->when($this->dateBoundary($this->startDate), fn (Builder $query, Carbon $start) => $query->where($column, '>=', $start))
            ->when($this->dateBoundary($this->endDate, true), fn (Builder $query, Carbon $end) => $query->where($column, '<=', $end));
    }

    private function labelActivityRange(): array
    {
        $year = $this->selectedLabelActivityYear();
        $periodStart = Carbon::create($year, 1, 1)->startOfDay();
        $periodEnd = Carbon::create($year, 12, 31)->endOfDay();
        $gridStart = $periodStart->copy()->startOfWeek();
        $gridEnd = $periodEnd->copy()->endOfWeek();

        return [$periodStart, $periodEnd, $gridStart, $gridEnd];
    }

    private function selectedLabelActivityYear(): int
    {
        $year = filter_var($this->labelActivityYear, FILTER_VALIDATE_INT);

        if (! $year || $year < 1970 || $year > 2100) {
            return now()->year;
        }

        return $year;
    }

    private function labelActivityYears()
    {
        $years = Spend::query()
            ->whereHas('budget', fn ($query) => $query->where('user_id', auth()->id()))
            ->pluck('created_at')
            ->filter()
            ->map(fn ($date) => Carbon::parse($date)->year)
            ->push(now()->year)
            ->push($this->selectedLabelActivityYear())
            ->unique()
            ->sortDesc()
            ->values();

        return $years;
    }

    private function dateBoundary(?string $date, bool $endOfDay = false): ?Carbon
    {
        if (! is_string($date) || trim($date) === '') {
            return null;
        }

        try {
            $boundary = Carbon::createFromFormat('Y-m-d', trim($date));
        } catch (Throwable) {
            return null;
        }

        return $endOfDay ? $boundary->endOfDay() : $boundary->startOfDay();
    }

    public function rupiah($amount): string
    {
        return 'Rp'.number_format((int) $amount, 0, ',', '.');
    }

    public function render()
    {
        $labelBreakdown = $this->labelBreakdown();
        $totalIncome = $this->totalIncome();
        $totalExpense = $this->totalExpense();
        $transactionCount = $this->transactionCount();

        return view('livewire.dashboard', [
            'labelBreakdown' => $labelBreakdown,
            'labelActivityHeatmap' => $this->labelActivityHeatmap(),
            'labelActivityYears' => $this->labelActivityYears(),
            'platformBreakdown' => $this->platformBreakdown(),
            'statusBreakdown' => $this->statusBreakdown(),
            'topExpenses' => $this->topExpenses(),
            'categoryBudgetChart' => $this->categoryBudgetChart(),
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'remainingBalance' => $totalIncome - $totalExpense,
            'budgetCount' => $this->userBudgetQuery()->count(),
            'transactionCount' => $transactionCount,
            'averageTransaction' => $this->averageTransaction(),
            'largestExpense' => $this->largestExpense(),
            'recentExpenses' => $this->recentExpenses(),
            'budgetHealth' => $this->budgetHealth(),
            'labelCount' => $labelBreakdown->count(),
            'labelsReady' => $this->labelsSchemaReady(),
            'topLabel' => $labelBreakdown->first(),
            'showOnboardingWelcome' => $this->shouldShowOnboardingWelcome(),
        ]);
    }

    private function labelsSchemaReady(): bool
    {
        return Schema::hasTable('labels')
            && Schema::hasColumn('labels', 'user_id')
            && Schema::hasColumn('spends', 'label_id');
    }
}
