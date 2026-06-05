<div class="min-w-0" x-data="canopyDashboardPage(@js($showOnboardingWelcome))">
    <header class="app-header" data-onboarding-target="dashboard-welcome">
        <div class="page-header-layout">
            <div class="page-header-copy">
                <span class="page-hero-icon page-hero-icon-emerald">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5 10.5 6.75l3.75 3.75L20.25 4.5" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5h15M6 16.5v3m4.5-6v6m4.5-4.5v4.5m4.5-9v9" />
                    </svg>
                </span>

                <div class="min-w-0">
                    <div class="eyebrow">Dashboard</div>
                    <h1 class="page-title">Financial Command Center</h1>
                    <p class="page-subtitle max-w-2xl">A quick read on income, spending pressure, budgets, and recent movement.</p>
                </div>
            </div>

            <div class="page-header-actions">
                <div class="min-w-0 flex-1 sm:w-44 sm:flex-none">
                    <button x-ref="budgetTrigger" type="button" x-on:click.stop="budgetMenu.toggle($refs.budgetTrigger, $refs.budgetMenu)" class="btn-secondary w-full justify-between">
                        <span class="truncate">{{ $budgetId === 'all' ? 'All budgets' : $budgets->firstWhere('id', (int) $budgetId)?->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <template x-teleport="body">
                        <div x-ref="budgetMenu" x-show="budgetMenu.open" x-cloak x-transition x-bind:style="budgetMenu.style" x-on:click.outside="budgetMenu.close()" x-on:resize.window="budgetMenu.close()" wire:key="dashboard-budget-menu" wire:ignore.self class="floating-select-menu">
                            <button type="button" x-on:click="budgetMenu.close()" wire:click="$set('budgetId', 'all')" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">All budgets</button>
                            @foreach ($budgets as $budget)
                                <button type="button" x-on:click="budgetMenu.close()" wire:click="$set('budgetId', '{{ $budget->id }}')" wire:key="dashboard-budget-option-{{ $budget->id }}" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">{{ $budget->name }}</button>
                            @endforeach
                        </div>
                    </template>
                </div>

                <div class="min-w-0 flex-1 sm:w-40 sm:flex-none">
                    @php($rangeLabels = ['all' => 'All time', '30' => 'Last 30 days', '90' => 'Last 90 days', '365' => 'Last year'])
                    <button x-ref="rangeTrigger" type="button" x-on:click.stop="rangeMenu.toggle($refs.rangeTrigger, $refs.rangeMenu)" class="btn-secondary w-full justify-between">
                        <span class="truncate">{{ $rangeLabels[$range] ?? 'All time' }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <template x-teleport="body">
                        <div x-ref="rangeMenu" x-show="rangeMenu.open" x-cloak x-transition x-bind:style="rangeMenu.style" x-on:click.outside="rangeMenu.close()" x-on:resize.window="rangeMenu.close()" wire:key="dashboard-range-menu" wire:ignore.self class="floating-select-menu">
                            @foreach ($rangeLabels as $value => $label)
                                <button type="button" x-on:click="rangeMenu.close()" wire:click="$set('range', '{{ $value }}')" wire:key="dashboard-range-option-{{ $value }}" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">{{ $label }}</button>
                            @endforeach
                        </div>
                    </template>
                </div>

                <a href="{{ route('budgets') }}" wire:navigate class="btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Manage Budget</span>
                </a>
                <button type="button" x-on:click="theme = theme === 'dark' ? 'light' : 'dark'" class="btn-icon" aria-label="Toggle appearance" data-tooltip="Toggle appearance">
                    <svg x-show="theme === 'dark'" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>
                    <svg x-show="theme !== 'dark'" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75 9.75 9.75 0 0 1 8.25 6c0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25 9.75 9.75 0 0 0 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <template x-teleport="body">
        <template x-if="welcomeTour.visible">
            <div aria-hidden="true">
                <div class="onboarding-spotlight-blur" x-bind:style="welcomeTour.blurStyle.top"></div>
                <div class="onboarding-spotlight-blur" x-bind:style="welcomeTour.blurStyle.right"></div>
                <div class="onboarding-spotlight-blur" x-bind:style="welcomeTour.blurStyle.bottom"></div>
                <div class="onboarding-spotlight-blur" x-bind:style="welcomeTour.blurStyle.left"></div>
            </div>
        </template>
    </template>

    <template x-teleport="body">
        <svg
            x-show="welcomeTour.visible"
            x-cloak
            x-transition.opacity
            class="onboarding-spotlight-overlay"
            aria-hidden="true"
        >
            <defs>
                <radialGradient
                    id="dashboard-welcome-gradient"
                    gradientUnits="userSpaceOnUse"
                    x-bind:cx="welcomeTour.spotlight.cx"
                    x-bind:cy="welcomeTour.spotlight.cy"
                    x-bind:r="welcomeTour.spotlight.r"
                >
                    <stop offset="0%" stop-color="#020617" stop-opacity="0.24" />
                    <stop offset="58%" stop-color="#020617" stop-opacity="0.54" />
                    <stop offset="100%" stop-color="#020617" stop-opacity="0.72" />
                </radialGradient>
                <mask id="dashboard-welcome-mask">
                    <rect width="100%" height="100%" fill="white" />
                    <rect
                        x-bind:x="welcomeTour.spotlight.x"
                        x-bind:y="welcomeTour.spotlight.y"
                        x-bind:width="welcomeTour.spotlight.width"
                        x-bind:height="welcomeTour.spotlight.height"
                        x-bind:rx="welcomeTour.spotlight.radius"
                        fill="black"
                    />
                </mask>
            </defs>
            <rect width="100%" height="100%" fill="url(#dashboard-welcome-gradient)" mask="url(#dashboard-welcome-mask)" />
        </svg>
    </template>

    <template x-teleport="body">
        <div
            x-show="welcomeTour.visible"
            x-cloak
            x-transition.opacity
            class="onboarding-tour-tooltip dashboard-welcome-tooltip"
            x-bind:style="welcomeTour.style"
        >
            <div class="onboarding-tour-kicker">Dashboard</div>
            <div class="onboarding-tour-title">Selamat datang di Dashboard</div>
            <p class="onboarding-tour-copy">Di sini kamu bisa membaca ringkasan income, expense, grafik kategori, budget health, dan transaksi terbaru dalam satu tempat.</p>
            <div class="mt-3 flex justify-end">
                <button
                    type="button"
                    x-on:click="Promise.resolve($wire.completeOnboarding()).then(() => closeWelcomeTour())"
                    class="btn-primary px-2.5 py-1.5 text-xs"
                >
                    Mengerti
                </button>
            </div>
        </div>
    </template>

    <main class="space-y-6 px-4 py-5 sm:px-6 sm:py-6 lg:px-8">
        <section class="summary-grid">
            <div class="metric-card">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Total Income</div>
                        <div class="metric-value">{{ $this->rupiah($totalIncome) }}</div>
                    </div>
                    <span class="icon-box">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182-.586-.439-1.354-.659-2.121-.659-.768 0-1.536-.22-2.121-.659-1.172-.879-1.172-2.303 0-3.182 1.171-.879 3.07-.879 4.242 0l.879.659" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="metric-card">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Total Expense</div>
                        <div class="metric-value">{{ $this->rupiah($totalExpense) }}</div>
                    </div>
                    <span class="icon-box">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m19.5 0h-.75a.75.75 0 0 1-.75-.75V4.5m0 0H3.75m16.5 0c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125H3.75A1.125 1.125 0 0 1 2.625 15.375v-9.75C2.625 5.004 3.129 4.5 3.75 4.5" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="metric-card">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Remaining</div>
                        <div class="{{ $remainingBalance < 0 ? 'metric-value-danger' : 'metric-value' }}">{{ $this->rupiah($remainingBalance) }}</div>
                    </div>
                    <span class="{{ $remainingBalance < 0 ? 'inline-flex size-10 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-600 ring-1 ring-red-100 dark:bg-red-500/10 dark:text-red-300 dark:ring-red-500/20' : 'icon-box-muted' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="metric-card">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Transactions</div>
                        <div class="metric-value">{{ $transactionCount }}</div>
                    </div>
                    <span class="icon-box-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="metric-card">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Active Labels</div>
                        <div class="metric-value">{{ $labelCount }}</div>
                    </div>
                    <span class="icon-box-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                        </svg>
                    </span>
                </div>
            </div>
        </section>

        <section
            class="panel overflow-hidden px-3 py-3 sm:px-4 sm:py-4"
            x-data="{
                activeLine: null,
                tooltip: { show: false, x: 0, y: 0, category: '', period: '', total: '' },
                closeTooltip() {
                    this.activeLine = null;
                    this.tooltip.show = false;
                },
                placeTooltip(event) {
                    const offsetX = 8;
                    const offsetY = 8;

                    return {
                        x: Math.round(event.clientX + offsetX),
                        y: Math.round(event.clientY + offsetY),
                    };
                },
                showPointTooltip(event, category, point) {
                    event.stopPropagation();
                    const position = this.placeTooltip(event);

                    this.activeLine = category;
                    this.tooltip = {
                        show: true,
                        x: position.x,
                        y: position.y,
                        category,
                        period: point.budget,
                        total: point.formatted,
                    };
                },
                showLineTooltip(event, category, points) {
                    event.stopPropagation();
                    const svg = event.target.ownerSVGElement;
                    const rect = svg.getBoundingClientRect();
                    const viewBox = svg.viewBox.baseVal;
                    const pointerX = (event.clientX - rect.left) * (viewBox.width / rect.width);
                    const nearest = points.reduce((closest, point) => Math.abs(point.x - pointerX) < Math.abs(closest.x - pointerX) ? point : closest, points[0]);
                    const position = this.placeTooltip(event);

                    this.activeLine = category;
                    this.tooltip = {
                        show: true,
                        x: position.x,
                        y: position.y,
                        category,
                        period: nearest.budget,
                        total: nearest.formatted,
                    };
                }
            }"
            x-on:click.self="closeTooltip()"
        >
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <div class="eyebrow">Category Flow</div>
                    <h2 class="mt-1 text-base font-bold text-gray-950 dark:text-slate-50 sm:text-lg">Spending by category across budgets</h2>
                    <p class="mt-1 text-xs text-gray-500 dark:text-slate-400 sm:text-sm">Each line tracks one category total in the selected view.</p>
                </div>
                <div class="rounded-lg bg-gray-50 px-2.5 py-2 text-right ring-1 ring-gray-100 dark:bg-slate-800/70 dark:ring-slate-700">
                    <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Categories</div>
                    <div class="font-bold text-gray-950 dark:text-slate-50">{{ $categoryBudgetChart['series']->count() }}</div>
                </div>
            </div>

            @if (! $categoryBudgetChart['ready'])
                <div class="mt-4 rounded-lg border border-dashed border-gray-200 px-4 py-10 text-center text-sm text-gray-500 dark:border-slate-700 dark:text-slate-400">
                    Add labels to expenses to activate the category chart.
                </div>
            @elseif ($categoryBudgetChart['series']->isEmpty())
                <div class="mt-4 rounded-lg border border-dashed border-gray-200 px-4 py-10 text-center text-sm text-gray-500 dark:border-slate-700 dark:text-slate-400">
                    No category spending found for this budget view.
                </div>
            @else
                <div class="mt-3 flex gap-1.5 overflow-x-auto pb-1 sm:flex-wrap sm:overflow-visible">
                    @foreach ($categoryBudgetChart['series'] as $seriesItem)
                        <div
                            wire:key="dashboard-chart-legend-{{ str($seriesItem['name'])->slug() }}"
                            class="inline-flex shrink-0 items-center gap-2 rounded-md bg-gray-50 px-2 py-1 text-[11px] font-semibold text-gray-600 ring-1 ring-gray-100 transition dark:bg-slate-800/70 dark:text-slate-300 dark:ring-slate-700"
                            x-bind:class="activeLine === @js($seriesItem['name']) ? 'ring-green-200 dark:ring-green-500/30' : ''"
                            x-on:mouseenter="activeLine = @js($seriesItem['name'])"
                            x-on:mouseleave="activeLine = null"
                        >
                            <span class="size-2 rounded-full" style="background-color: {{ $seriesItem['color'] }}"></span>
                            <span class="max-w-32 truncate">{{ $seriesItem['label'] }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="mt-2 overflow-hidden pb-1">
                    <div class="-mx-3 w-[calc(100%+1.5rem)] sm:mx-auto sm:w-[95%]">
                        <svg
                            class="block h-auto w-full"
                            viewBox="0 0 {{ $categoryBudgetChart['width'] }} {{ $categoryBudgetChart['height'] }}"
                            role="img"
                            aria-label="Multi-line chart of spending by category across budgets"
                            x-on:click.self="closeTooltip()"
                        >
                            <defs>
                                @foreach ($categoryBudgetChart['series'] as $seriesIndex => $seriesItem)
                                    <linearGradient id="dashboard-chart-gradient-{{ $seriesIndex }}" x1="0" x2="0" y1="0" y2="1">
                                        <stop offset="0%" stop-color="{{ $seriesItem['color'] }}" stop-opacity="0.22" />
                                        <stop offset="70%" stop-color="{{ $seriesItem['color'] }}" stop-opacity="0.06" />
                                        <stop offset="100%" stop-color="{{ $seriesItem['color'] }}" stop-opacity="0" />
                                    </linearGradient>
                                @endforeach
                            </defs>

                            <rect x="0" y="0" width="{{ $categoryBudgetChart['width'] }}" height="{{ $categoryBudgetChart['height'] }}" fill="transparent" x-on:click="closeTooltip()"></rect>

                            @foreach ($categoryBudgetChart['yTicks'] as $tick)
                                <line x1="{{ $categoryBudgetChart['plot']['left'] }}" y1="{{ $tick['y'] }}" x2="{{ $categoryBudgetChart['plot']['right'] }}" y2="{{ $tick['y'] }}" class="dashboard-chart-grid" />
                                <text x="12" y="{{ $tick['y'] + 4 }}" class="dashboard-chart-axis-text">{{ $tick['label'] }}</text>
                            @endforeach

                            @foreach ($categoryBudgetChart['budgets'] as $budget)
                                <line x1="{{ $budget['x'] }}" y1="{{ $categoryBudgetChart['plot']['top'] }}" x2="{{ $budget['x'] }}" y2="{{ $categoryBudgetChart['plot']['bottom'] }}" class="dashboard-chart-guide" />
                                <text x="{{ $budget['x'] }}" y="{{ $categoryBudgetChart['height'] - 28 }}" text-anchor="middle" class="dashboard-chart-axis-text">{{ $budget['shortName'] }}</text>
                            @endforeach

                            @foreach ($categoryBudgetChart['series'] as $seriesIndex => $seriesItem)
                                <path
                                    d="{{ $seriesItem['areaPath'] }}"
                                    class="dashboard-chart-area"
                                    x-bind:class="activeLine && activeLine !== @js($seriesItem['name']) ? 'dashboard-chart-area-muted' : (activeLine === @js($seriesItem['name']) ? 'dashboard-chart-area-active' : '')"
                                    fill="url(#dashboard-chart-gradient-{{ $seriesIndex }})"
                                ></path>
                            @endforeach

                            @foreach ($categoryBudgetChart['series'] as $seriesItem)
                                <path
                                    d="{{ $seriesItem['path'] }}"
                                    class="dashboard-chart-line"
                                    x-bind:class="activeLine && activeLine !== @js($seriesItem['name']) ? 'dashboard-chart-line-muted' : (activeLine === @js($seriesItem['name']) ? 'dashboard-chart-line-active' : '')"
                                    style="stroke: {{ $seriesItem['color'] }}"
                                ></path>
                            @endforeach

                            @foreach ($categoryBudgetChart['series'] as $seriesItem)
                                <path
                                    d="{{ $seriesItem['path'] }}"
                                    class="dashboard-chart-hitbox"
                                    x-on:mouseenter="showLineTooltip($event, @js($seriesItem['name']), @js($seriesItem['points']))"
                                    x-on:mousemove="showLineTooltip($event, @js($seriesItem['name']), @js($seriesItem['points']))"
                                    x-on:mouseleave="if (!window.matchMedia('(pointer: coarse)').matches) closeTooltip()"
                                    x-on:click="showLineTooltip($event, @js($seriesItem['name']), @js($seriesItem['points']))"
                                ></path>
                            @endforeach

                            @foreach ($categoryBudgetChart['series'] as $seriesItem)
                                @if ($seriesItem['latestPoint'])
                                    <circle
                                        cx="{{ $seriesItem['latestPoint']['x'] }}"
                                        cy="{{ $seriesItem['latestPoint']['y'] }}"
                                        r="4"
                                        class="dashboard-chart-marker"
                                        x-bind:class="activeLine && activeLine !== @js($seriesItem['name']) ? 'dashboard-chart-marker-muted' : ''"
                                        style="fill: {{ $seriesItem['color'] }}"
                                    ></circle>
                                @endif
                            @endforeach

                            @foreach ($categoryBudgetChart['series'] as $seriesItem)
                                @foreach ($seriesItem['points'] as $point)
                                    <circle
                                        cx="{{ $point['x'] }}"
                                        cy="{{ $point['y'] }}"
                                        r="13"
                                        fill="transparent"
                                        class="cursor-pointer"
                                        x-on:mouseenter="showPointTooltip($event, @js($seriesItem['name']), @js($point))"
                                        x-on:mousemove="showPointTooltip($event, @js($seriesItem['name']), @js($point))"
                                        x-on:mouseleave="if (!window.matchMedia('(pointer: coarse)').matches) closeTooltip()"
                                        x-on:click.stop="showPointTooltip($event, @js($seriesItem['name']), @js($point))"
                                    ></circle>
                                @endforeach
                            @endforeach
                        </svg>
                    </div>
                </div>

                <template x-teleport="body">
                    <div
                        x-show="tooltip.show"
                        x-cloak
                        x-transition.opacity
                        class="pointer-events-none fixed z-[90] rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs shadow-xl shadow-slate-900/10 dark:border-slate-700 dark:bg-slate-900 dark:shadow-black/30"
                        x-bind:style="`left:${tooltip.x}px;top:${tooltip.y}px;`"
                    >
                        <div class="text-[10px] font-semibold uppercase text-gray-400 dark:text-slate-500">Kategori</div>
                        <div class="font-bold text-gray-950 dark:text-slate-50" x-text="tooltip.category"></div>
                        <div class="mt-2 text-[10px] font-semibold uppercase text-gray-400 dark:text-slate-500">Periode</div>
                        <div class="text-gray-600 dark:text-slate-300" x-text="tooltip.period"></div>
                        <div class="mt-2 text-[10px] font-semibold uppercase text-gray-400 dark:text-slate-500">Total pengeluaran</div>
                        <div class="font-semibold text-green-600 dark:text-green-400" x-text="tooltip.total"></div>
                    </div>
                </template>
            @endif
        </section>

        <section class="grid gap-4 xl:grid-cols-[minmax(0,1.1fr)_minmax(340px,0.9fr)]">
            <div class="panel px-4 py-4">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <div class="eyebrow">Budget Health</div>
                        <h2 class="mt-1 text-xl font-bold text-gray-950 dark:text-slate-50">Spending pressure by budget</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">{{ $budgetCount }} active budgets tracked.</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 px-3 py-2 text-right ring-1 ring-gray-100 dark:bg-slate-800/70 dark:ring-slate-700">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Avg Spend</div>
                        <div class="font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($averageTransaction) }}</div>
                    </div>
                </div>

                <div class="mt-5 space-y-4">
                    @forelse ($budgetHealth as $budget)
                        <div wire:key="dashboard-budget-health-{{ $budget['id'] ?? str($budget['name'])->slug() }}">
                            <div class="mb-2 flex items-center justify-between gap-3 text-sm">
                                <div class="min-w-0">
                                    <div class="truncate font-semibold text-gray-950 dark:text-slate-50">{{ $budget['name'] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-slate-400">{{ $this->rupiah($budget['spent']) }} of {{ $this->rupiah($budget['income']) }}</div>
                                </div>
                                <div class="{{ $budget['tone'] === 'danger' ? 'text-red-500' : ($budget['tone'] === 'warning' ? 'text-amber-500' : 'text-green-500') }} shrink-0 font-semibold">
                                    {{ $budget['percentage'] }}%
                                </div>
                            </div>
                            <div class="progress-track h-2">
                                <div class="progress-fill" style="--progress: {{ $budget['percentage'] }}%; --progress-color: {{ $budget['tone'] === 'danger' ? '#ef4444' : ($budget['tone'] === 'warning' ? '#f59e0b' : '#22c55e') }}"></div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-lg border border-dashed border-gray-200 px-4 py-8 text-center text-sm text-gray-500 dark:border-slate-700 dark:text-slate-400">
                            No budgets yet.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="panel px-4 py-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <div class="eyebrow">Recent Movement</div>
                        <h2 class="mt-1 text-xl font-bold text-gray-950 dark:text-slate-50">Latest expenses</h2>
                    </div>
                    <span class="icon-box-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m5-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </span>
                </div>

                <div class="mt-4 divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse ($recentExpenses as $expense)
                        <div wire:key="dashboard-recent-expense-{{ $expense->id }}" class="flex items-center justify-between gap-3 py-3">
                            <div class="min-w-0">
                                <div class="truncate font-semibold text-gray-950 dark:text-slate-50">{{ $expense->name }}</div>
                                <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-slate-400">
                                    <span>{{ $expense->budget?->name }}</span>
                                    <span>{{ $expense->label?->name ?? 'Unlabeled' }}</span>
                                    <span>{{ $expense->created_at?->format('d M Y') }}</span>
                                </div>
                            </div>
                            <div class="shrink-0 text-sm font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($expense->getRawOriginal('amount')) }}</div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-sm text-gray-500 dark:text-slate-400">No recent expenses.</div>
                    @endforelse
                </div>

                @if ($largestExpense)
                    <div class="mt-4 rounded-lg bg-gray-50 px-3 py-3 ring-1 ring-gray-100 dark:bg-slate-800/70 dark:ring-slate-700">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Largest Expense</div>
                        <div class="mt-2 flex items-center justify-between gap-3">
                            <div class="min-w-0 truncate font-semibold text-gray-950 dark:text-slate-50">{{ $largestExpense->name }}</div>
                            <div class="shrink-0 font-bold text-red-500">{{ $this->rupiah($largestExpense->getRawOriginal('amount')) }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        <section class="grid gap-4 xl:grid-cols-3">
            <div class="panel px-4 py-4">
                <div class="eyebrow">Top Expenses</div>
                <h2 class="mt-1 text-base font-bold text-gray-950 dark:text-slate-50">Highest-impact transactions</h2>

                <div class="mt-4 divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse ($topExpenses as $expense)
                        <div wire:key="dashboard-top-expense-{{ $expense->id }}" class="flex items-center justify-between gap-3 py-3">
                            <div class="min-w-0">
                                <div class="truncate font-semibold text-gray-950 dark:text-slate-50">{{ $expense->name }}</div>
                                <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-slate-400">
                                    <span>{{ $expense->budget?->name }}</span>
                                    <span>{{ $expense->label?->name ?? 'Unlabeled' }}</span>
                                </div>
                            </div>
                            <div class="shrink-0 text-sm font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($expense->getRawOriginal('amount')) }}</div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-sm text-gray-500 dark:text-slate-400">No expenses in this view.</div>
                    @endforelse
                </div>
            </div>

            <div class="panel px-4 py-4">
                <div class="eyebrow">Platforms</div>
                <h2 class="mt-1 text-base font-bold text-gray-950 dark:text-slate-50">Payment mix</h2>

                <div class="mt-4 space-y-3">
                    @forelse ($platformBreakdown as $platform)
                        <div wire:key="dashboard-platform-breakdown-{{ str($platform['name'])->slug() }}">
                            <div class="mb-1 flex items-center justify-between gap-3 text-sm">
                                <span class="truncate font-semibold text-gray-700 dark:text-slate-200">{{ $platform['name'] }}</span>
                                <span class="shrink-0 text-gray-500 dark:text-slate-400">{{ $platform['percentage'] }}%</span>
                            </div>
                            <div class="progress-track h-2">
                                <div class="progress-fill" style="--progress: {{ $platform['percentage'] }}%; --progress-color: #22c55e"></div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-sm text-gray-500 dark:text-slate-400">No platform data yet.</div>
                    @endforelse
                </div>
            </div>

            <div class="panel px-4 py-4">
                <div class="eyebrow">Status</div>
                <h2 class="mt-1 text-base font-bold text-gray-950 dark:text-slate-50">Allocation state</h2>

                <div class="mt-4 grid gap-2">
                    @forelse ($statusBreakdown as $status)
                        <div wire:key="dashboard-status-breakdown-{{ str($status['name'])->slug() }}" class="rounded-lg bg-gray-50 px-3 py-2 ring-1 ring-gray-100 dark:bg-slate-800/70 dark:ring-slate-700">
                            <div class="flex items-center justify-between gap-2 text-sm">
                                <span class="truncate font-semibold text-gray-700 dark:text-slate-200">{{ ucfirst($status['name']) }}</span>
                                <span class="text-gray-500 dark:text-slate-400">{{ $status['transactions'] }}x</span>
                            </div>
                            <div class="mt-2 text-sm font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($status['total']) }}</div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-sm text-gray-500 dark:text-slate-400">No status data yet.</div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-gray-950 dark:text-slate-50">Breakdown by Label</h2>
                <p class="text-sm text-gray-500 dark:text-slate-400">{{ $labelCount }} active labels, top category: {{ $topLabel['name'] ?? '-' }}.</p>
            </div>
            <div class="relative w-full sm:w-80">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197M15.803 15.803A7.5 7.5 0 1 0 5.197 5.197a7.5 7.5 0 0 0 10.606 10.606Z" />
                </svg>
                <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search label or expense" class="input-field pl-9">
            </div>
        </section>

        <section class="space-y-4">
            @forelse ($labelBreakdown as $label)
                <article wire:key="dashboard-label-breakdown-{{ str($label['name'])->slug() }}" class="panel px-4 py-4">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="icon-box-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-lg font-bold text-gray-950 dark:text-slate-50">{{ $label['name'] }}</h3>
                                <p class="text-xs text-gray-500 dark:text-slate-400">{{ $label['transactions'] }} transactions</p>
                            </div>
                        </div>
                        <div class="text-right text-lg font-bold text-green-500">{{ $this->rupiah($label['total']) }}</div>
                    </div>

                    <div class="mt-4 space-y-3">
                        @foreach ($label['items'] as $item)
                            <div wire:key="dashboard-label-{{ str($label['name'])->slug() }}-item-{{ str($item['name'])->slug() }}">
                                <div class="mb-1 flex items-center justify-between gap-3 text-sm">
                                    <div class="min-w-0">
                                        <div class="truncate font-semibold text-gray-700 dark:text-slate-200">{{ $item['name'] }}</div>
                                        <div class="text-xs text-gray-400 dark:text-slate-500">{{ $item['transactions'] }}x</div>
                                    </div>
                                    <div class="shrink-0 font-semibold text-gray-950 dark:text-slate-50">{{ $this->rupiah($item['total']) }}</div>
                                </div>
                                <div class="progress-track h-2">
                                    <div class="progress-fill" style="--progress: {{ $item['percentage'] }}%; --progress-color: #22c55e"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>
            @empty
                <div class="panel border-dashed px-6 py-12 text-center">
                    <span class="icon-box mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                        </svg>
                    </span>
                    <div class="mt-4 text-lg font-semibold text-gray-950 dark:text-slate-50">No label spending found</div>
                    <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Create expenses with labels to fill this dashboard.</p>
                </div>
            @endforelse
        </section>
    </main>
</div>
