<?php

namespace App\Livewire;

use App\Models\Budget;
use App\Models\Spend;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class Dashboard extends Component
{
    public $search = '';
    public $categoryChartPeriod;
    public $labelActivityYear;
    public $showAllLabelActivity = false;

    public function mount(): void
    {
        $this->labelActivityYear ??= now()->year;
        $this->categoryChartPeriod ??= $this->defaultCategoryChartYear();
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

    public function setCategoryChartPeriod(string $period): void
    {
        if (! array_key_exists($period, $this->categoryChartPeriodOptions())) {
            return;
        }

        $this->categoryChartPeriod = $period;
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
            ->whereHas('budget', fn ($query) => $query->where('user_id', auth()->id()));
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
            ->selectRaw("coalesce(labels.name, 'Unlabeled') as label_name, spends.name as spend_name, spends.amount as raw_amount, spends.created_at")
            ->get();

        $cellTotals = $spends
            ->groupBy(fn ($spend) => $spend->label_name.'|'.$spend->created_at->copy()->toDateString())
            ->map(fn ($items) => (int) $items->sum(fn ($spend) => (int) $spend->raw_amount));

        $cellNames = $spends
            ->groupBy(fn ($spend) => $spend->label_name.'|'.$spend->created_at->copy()->toDateString())
            ->map(function ($items) {
                $names = $items
                    ->pluck('spend_name')
                    ->filter()
                    ->unique()
                    ->values();

                if ($names->isEmpty()) {
                    return 'No spend';
                }

                if ($names->count() === 1) {
                    return $names->first();
                }

                return $names->first().' +'.($names->count() - 1).' more';
            });

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

        $rows = $visibleRows->map(function (array $row) use ($weeks, $cellTotals, $cellNames, $maxCell) {
            return [
                'label' => $row['label'],
                'total' => $row['total'],
                'transactions' => $row['transactions'],
                'weeks' => $weeks->map(function (array $week) use ($row, $cellTotals, $cellNames, $maxCell) {
                    return [
                        'key' => $week['key'],
                        'days' => $week['days']->map(function (array $day) use ($row, $cellTotals, $cellNames, $maxCell) {
                            $cellKey = $row['label'].'|'.$day['key'];
                            $amount = (int) ($cellTotals->get($cellKey) ?? 0);
                            $level = $amount === 0 ? 0 : max(1, min(4, (int) ceil(($amount / $maxCell) * 4)));

                            return [
                                'amount' => $amount,
                                'formatted' => $this->rupiah($amount),
                                'level' => $level,
                                'spendName' => $cellNames->get($cellKey, 'No spend'),
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
            ->withSum('spends as total_spent', 'amount')
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
                    'tone' => $remaining < 0 ? 'danger' : 'healthy',
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
        $width = 760;
        $height = 330;
        $padding = ['left' => 42, 'right' => 24, 'top' => 30, 'bottom' => 56];
        $periodOptions = $this->categoryChartPeriodOptions();
        $selectedPeriod = array_key_exists((string) $this->categoryChartPeriod, $periodOptions)
            ? (string) $this->categoryChartPeriod
            : $this->defaultCategoryChartYear();

        if (! $this->labelsSchemaReady()) {
            return [
                'ready' => false,
                'series' => collect(),
                'buckets' => collect(),
                'yTicks' => collect(),
                'width' => $width,
                'height' => $height,
                'periodOptions' => $periodOptions,
                'selectedPeriod' => $selectedPeriod,
            ];
        }

        [$periodStart, $periodEnd, $buckets, $bucketFormat, $periodLabel] = $this->categoryChartRange((int) $selectedPeriod);

        $spends = Spend::query()
            ->join('budgets', 'spends.budget_id', '=', 'budgets.id')
            ->leftJoin('labels', 'spends.label_id', '=', 'labels.id')
            ->where('budgets.user_id', auth()->id())
            ->whereBetween('spends.created_at', [$periodStart, $periodEnd])
            ->selectRaw("coalesce(labels.name, 'Unlabeled') as category, spends.amount as raw_amount, spends.created_at")
            ->get();

        if ($spends->isEmpty()) {
            return [
                'ready' => true,
                'series' => collect(),
                'buckets' => $buckets,
                'yTicks' => collect(),
                'width' => $width,
                'height' => $height,
                'periodLabel' => $periodLabel,
                'periodOptions' => $periodOptions,
                'selectedPeriod' => $selectedPeriod,
            ];
        }

        $plotWidth = $width - $padding['left'] - $padding['right'];
        $plotHeight = $height - $padding['top'] - $padding['bottom'];
        $colors = $this->chartColors();
        $xStep = $buckets->count() > 1 ? $plotWidth / ($buckets->count() - 1) : 0;

        $valuesByCategory = $spends
            ->groupBy('category')
            ->map(fn ($items) => [
                'total' => (int) $items->sum(fn ($item) => (int) $item->raw_amount),
                'transactions' => $items->count(),
                'values' => $items
                    ->groupBy(fn ($item) => $this->categoryChartBucketKey(Carbon::parse($item->created_at), $bucketFormat))
                    ->map(fn ($bucketItems) => [
                        'amount' => (int) $bucketItems->sum(fn ($item) => (int) $item->raw_amount),
                        'datesLabel' => $this->spendDatesLabel($bucketItems, $bucketFormat),
                    ]),
            ])
            ->sortByDesc('total');

        $chartMax = max((int) $valuesByCategory->take(5)->max(
            fn (array $category) => $category['values']->max(fn (array $bucket) => (int) ($bucket['amount'] ?? 0)) ?? 0
        ), 1);

        $bucketCount = max($buckets->count(), 1);
        $labelStep = max(1, (int) ceil($bucketCount / 4));
        $chartBuckets = $buckets->values()->map(fn (array $bucket, int $index) => [
            ...$bucket,
            'x' => round($padding['left'] + ($xStep * $index), 2),
            'showLabel' => $bucketCount <= 12 || $index === 0 || $index === $bucketCount - 1 || $index % $labelStep === 0,
        ]);

        $seriesIndex = 0;
        $series = $valuesByCategory
            ->take(5)
            ->map(function (array $category, string $name) use ($chartBuckets, $chartMax, $colors, $padding, $plotHeight, $periodLabel, &$seriesIndex) {
                $points = $chartBuckets->map(function (array $bucket) use ($category, $chartMax, $padding, $plotHeight) {
                    $bucketData = $category['values']->get($bucket['key']);
                    $amount = (int) ($bucketData['amount'] ?? 0);
                    $y = $padding['top'] + $plotHeight - (($amount / $chartMax) * $plotHeight);

                    return [
                        'label' => $bucket['label'],
                        'fullLabel' => $bucket['fullLabel'],
                        'spendDateLabel' => $bucketData['datesLabel'] ?? $bucket['fullLabel'],
                        'amount' => $amount,
                        'formatted' => $this->rupiah($amount),
                        'x' => $bucket['x'],
                        'y' => round($y, 2),
                    ];
                })->values();

                $color = $colors[$seriesIndex % count($colors)];
                $seriesIndex++;
                $firstAmount = (int) (($points->firstWhere('amount', '>', 0)['amount'] ?? null) ?: $points->first()['amount']);
                $points = $points
                    ->map(function (array $point) use ($firstAmount) {
                        $changePercentage = $this->percentageChange($firstAmount, (int) $point['amount']);

                        return [
                            ...$point,
                            'changePercentageLabel' => $this->formatPercentage($changePercentage),
                            'changeTone' => $changePercentage > 0 ? 'up' : ($changePercentage < 0 ? 'down' : 'flat'),
                        ];
                    })
                    ->values();
                $defaultPoint = $points->filter(fn (array $point) => $point['amount'] > 0)->last() ?? $points->last();

                return [
                    'name' => $name,
                    'label' => str($name)->limit(18)->toString(),
                    'total' => $category['total'],
                    'formattedTotal' => $this->rupiah($category['total']),
                    'transactions' => $category['transactions'],
                        'color' => $color,
                        'points' => $points,
                    'path' => $this->linePath($points->all()),
                        'latestPoint' => $points->filter(fn (array $point) => $point['amount'] > 0)->last() ?? $points->last(),
                        'summary' => [
                            'name' => $name,
                            'label' => str($name)->limit(22)->toString(),
                            'dateLabel' => $periodLabel,
                            'formattedTotal' => $this->rupiah($category['total']),
                            'changePercentageLabel' => $defaultPoint['changePercentageLabel'],
                            'changeTone' => $defaultPoint['changeTone'],
                        ],
                    ];
                })
            ->values();

        $yTicks = collect([0, 0.5, 1])->map(function (float $tick) use ($chartMax, $padding, $plotHeight) {
            $value = (int) round($chartMax * $tick);
            $y = $padding['top'] + $plotHeight - (($value / $chartMax) * $plotHeight);

            return [
                'value' => $value,
                'label' => $this->compactAxisAmount($value),
                'y' => round($y, 2),
            ];
        })->reverse()->values();

        $topCategory = $series->first()['summary'] ?? null;

        return [
            'ready' => true,
            'series' => $series,
            'buckets' => $chartBuckets,
            'yTicks' => $yTicks,
            'topCategory' => $topCategory,
            'width' => $width,
            'height' => $height,
            'periodLabel' => $periodLabel,
            'periodOptions' => $periodOptions,
            'selectedPeriod' => $selectedPeriod,
            'plot' => [
                'left' => $padding['left'],
                'right' => $width - $padding['right'],
                'top' => $padding['top'],
                'bottom' => $height - $padding['bottom'],
                'height' => $plotHeight,
            ],
        ];
    }

    private function categoryChartPeriodOptions(): array
    {
        return $this->categoryChartYears()
            ->mapWithKeys(fn (int $year) => [(string) $year => (string) $year])
            ->all();
    }

    private function categoryChartRange(int $year): array
    {
        $start = Carbon::create($year, 1, 1)->startOfDay();
        $end = Carbon::create($year, 12, 31)->endOfDay();

        return [$start, $end, $this->monthlyBuckets($year), 'Y-m', (string) $year];
    }

    private function monthlyBuckets(int $year)
    {
        return collect(range(1, 12))->map(function (int $month) use ($year) {
            $date = Carbon::create($year, $month, 1)->startOfMonth();

            return [
                'key' => $date->format('Y-m'),
                'label' => $date->format('M'),
                'fullLabel' => $date->format('F Y'),
            ];
        });
    }

    private function categoryChartBucketKey(Carbon $date, string $bucketFormat): string
    {
        if ($bucketFormat === 'quarter') {
            return $date->format('Y-').'Q'.$date->quarter;
        }

        return $date->format($bucketFormat);
    }

    private function spendDatesLabel($spends, string $bucketFormat): string
    {
        $format = 'd M Y';
        $dates = $spends
            ->pluck('created_at')
            ->filter()
            ->map(fn ($date) => Carbon::parse($date)->format($format))
            ->unique()
            ->values();

        if ($dates->isEmpty()) {
            return 'No spend date';
        }

        if ($dates->count() <= 2) {
            return $dates->join(', ');
        }

        return $dates->take(2)->join(', ').' +'.($dates->count() - 2).' dates';
    }

    private function chartColors(): array
    {
        return [
            '#10b981',
            '#0ea5e9',
            '#8b5cf6',
            '#f59e0b',
            '#ef4444',
        ];
    }

    private function linePath(array $points): string
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
            $midX = round(($current['x'] + $next['x']) / 2, 2);

            $path .= ' C '.$midX.' '.$current['y'].', '.$midX.' '.$next['y'].', '.$next['x'].' '.$next['y'];
        }

        return $path;
    }

    private function areaPath(array $points, float $baseline): string
    {
        if (count($points) === 0) {
            return '';
        }

        $linePath = $this->linePath($points);
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

    private function percentageChange(int $start, int $end): float
    {
        if ($start === 0) {
            return $end > 0 ? 100.0 : 0.0;
        }

        return (($end - $start) / $start) * 100;
    }

    private function formatPercentage(float $percentage): string
    {
        $rounded = round($percentage, 1);
        $formatted = number_format($rounded, abs($rounded) === floor(abs($rounded)) ? 0 : 1, ',', '.');

        return ($rounded > 0 ? '+' : '').$formatted.'%';
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

    private function categoryChartYears()
    {
        return Spend::query()
            ->whereHas('budget', fn ($query) => $query->where('user_id', auth()->id()))
            ->pluck('created_at')
            ->filter()
            ->map(fn ($date) => Carbon::parse($date)->year)
            ->unique()
            ->sortDesc()
            ->values();
    }

    private function defaultCategoryChartYear(): string
    {
        return (string) ($this->categoryChartYears()->first() ?? now()->year);
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
