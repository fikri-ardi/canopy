<div class="min-w-0" x-data="alokasiDashboardPage(@js($showOnboardingWelcome))">
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
                    <h1 class="page-title">Dashboard</h1>
                </div>
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
            <p class="onboarding-tour-copy">Di sini kamu bisa membaca ringkasan income, expense, grafik kategori, plan health, dan transaksi terbaru dalam satu tempat.</p>
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
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Total Pemasukan</div>
                        <div class="metric-value money-value">{{ $this->rupiah($totalIncome) }}</div>
                    </div>
                    <span class="icon-box">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182-.586-.439-1.354-.659-2.121-.659-.768 0-1.536-.22-2.121-.659-1.172-.879-1.172-2.303 0-3.182 1.171-.879 3.07-.879 4.242 0l.879.659" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="metric-card metric-card-savings group cursor-pointer" wire:click="openSavingsDetail" id="savings-rate-card">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Saving Rate</div>
                        <div class="metric-value">{{ $savingsRate }}%</div>
                    </div>
                    <span class="icon-box-savings">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                        </svg>
                    </span>
                </div>
                <div class="mt-1.5 flex items-center gap-1.5 text-[11px] font-medium text-gray-400 dark:text-slate-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3 transition group-hover:translate-x-0.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                    <span>Lihat detail</span>
                </div>
            </div>
            <div class="metric-card">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Total Pengeluaran</div>
                        <div class="metric-value money-value">{{ $this->rupiah($totalExpense) }}</div>
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
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Sisa</div>
                        <div class="{{ $remainingBalance < 0 ? 'metric-value-danger' : 'metric-value' }} money-value">{{ $this->rupiah($remainingBalance) }}</div>
                    </div>
                    <span class="{{ $remainingBalance < 0 ? 'inline-flex size-10 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-600 ring-1 ring-red-100 dark:bg-red-500/10 dark:text-red-300 dark:ring-red-500/20' : 'icon-box-muted' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </span>
                </div>
            </div>
        </section>

        {{-- Savings Rate Detail Panel --}}
        @if ($showSavingsDetail)
            <template x-teleport="body">
                <div
                    x-data="{
                        open: false,
                        close() {
                            this.open = false;
                            setTimeout(() => {
                                $wire.closeSavingsDetail();
                            }, 250);
                        }
                    }"
                    x-init="$nextTick(() => open = true)"
                    x-show="open"
                    x-on:keydown.escape.window="close()"
                    x-cloak
                    class="savings-detail-overlay"
                    id="savings-detail-panel"
                >
                    {{-- Backdrop --}}
                    <div
                        class="savings-detail-backdrop"
                        x-show="open"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        x-on:click="close()"
                    ></div>

                    {{-- Panel --}}
                    <div
                        class="savings-detail-panel"
                        x-show="open"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="savings-detail-panel-hidden"
                        x-transition:enter-end="savings-detail-panel-visible"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="savings-detail-panel-visible"
                        x-transition:leave-end="savings-detail-panel-hidden"
                        x-on:click.outside="close()"
                    >
                        {{-- Header --}}
                        <div class="flex items-center justify-between gap-3 border-b border-slate-200/70 px-5 py-4 dark:border-slate-800/70">
                            <div class="min-w-0">
                                <div class="eyebrow">Detail</div>
                                <h2 class="mt-0.5 text-base font-bold text-gray-950 dark:text-slate-50">Saving Rate</h2>
                            </div>
                            <button
                                type="button"
                                class="btn-icon"
                                x-on:click="close()"
                                aria-label="Tutup panel"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 overflow-y-auto px-5 py-5">
                            @if ($savingsRateDetail)
                                @if (! $savingsRateDetail['hasIncome'])
                                    <div class="rounded-lg border border-dashed border-gray-200 px-4 py-8 text-center text-sm text-gray-500 dark:border-slate-700 dark:text-slate-400">
                                        Belum ada pemasukan yang tercatat untuk periode ini.
                                    </div>
                                @elseif (! $savingsRateDetail['hasSavings'])
                                    <div class="rounded-lg border border-dashed border-gray-200 px-4 py-8 text-center text-sm text-gray-500 dark:border-slate-700 dark:text-slate-400">
                                        Belum ada tabungan atau investasi yang tercatat untuk periode ini.
                                    </div>
                                @else
                                    {{-- Rate display --}}
                                    <div class="text-center">
                                        <div class="savings-rate-display">
                                            <span class="savings-rate-number">{{ $savingsRateDetail['rate'] }}</span>
                                            <span class="savings-rate-symbol">%</span>
                                        </div>
                                        <div class="mt-1 text-xs font-medium text-gray-500 dark:text-slate-400">Savings Rate</div>
                                    </div>

                                    {{-- Breakdown --}}
                                    <div class="mt-6 space-y-3">
                                        <div class="rounded-lg bg-gray-50 px-4 py-3 ring-1 ring-gray-100 dark:bg-slate-800/70 dark:ring-slate-700">
                                            <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Total Income</div>
                                            <div class="money-value mt-1 text-base font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($savingsRateDetail['totalIncome']) }}</div>
                                        </div>
                                        <div class="rounded-lg bg-green-50/70 px-4 py-3 ring-1 ring-green-100 dark:bg-green-500/[0.08] dark:ring-green-500/20">
                                            <div class="text-xs font-semibold uppercase text-green-700/70 dark:text-green-300/70">Total Saved</div>
                                            <div class="money-value mt-1 text-base font-bold text-green-700 dark:text-green-300">{{ $this->rupiah($savingsRateDetail['totalSavings']) }}</div>
                                        </div>
                                    </div>

                                    {{-- Formula --}}
                                    <div class="mt-5 rounded-lg border border-slate-200/70 px-4 py-3 dark:border-slate-800/70">
                                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Formula</div>
                                        <div class="mt-2 text-center font-mono text-xs text-gray-600 dark:text-slate-300">
                                            <span class="money-value text-green-600 dark:text-green-300">{{ $this->rupiah($savingsRateDetail['totalSavings']) }}</span>
                                            <span class="text-gray-400 dark:text-slate-500"> / </span>
                                            <span class="money-value">{{ $this->rupiah($savingsRateDetail['totalIncome']) }}</span>
                                            <span class="text-gray-400 dark:text-slate-500"> × 100 = </span>
                                            <span class="font-bold text-gray-950 dark:text-slate-50">{{ $savingsRateDetail['rate'] }}%</span>
                                        </div>
                                    </div>

                                    {{-- Trend --}}
                                    <div class="mt-5">
                                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Trend</div>
                                        <div class="mt-3 flex items-center gap-3">
                                            <div class="flex-1 rounded-lg bg-gray-50 px-3 py-2.5 text-center ring-1 ring-gray-100 dark:bg-slate-800/70 dark:ring-slate-700">
                                                <div class="text-[11px] font-medium text-gray-400 dark:text-slate-500">{{ $savingsRateDetail['trend']['previous']['label'] }}</div>
                                                <div class="mt-0.5 text-lg font-bold tabular-nums text-gray-950 dark:text-slate-50">{{ $savingsRateDetail['trend']['previous']['rate'] }}%</div>
                                            </div>
                                            <div class="flex flex-col items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 text-gray-300 dark:text-slate-600">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 rounded-lg bg-gray-50 px-3 py-2.5 text-center ring-1 ring-gray-100 dark:bg-slate-800/70 dark:ring-slate-700">
                                                <div class="text-[11px] font-medium text-gray-400 dark:text-slate-500">{{ $savingsRateDetail['trend']['current']['label'] }}</div>
                                                <div class="mt-0.5 text-lg font-bold tabular-nums text-gray-950 dark:text-slate-50">{{ $savingsRateDetail['trend']['current']['rate'] }}%</div>
                                            </div>
                                        </div>
                                        <div class="mt-2 flex justify-center">
                                            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold
                                                {{ $savingsRateDetail['trend']['tone'] === 'up' ? 'bg-green-50 text-green-600 ring-1 ring-green-100 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20' : ($savingsRateDetail['trend']['tone'] === 'down' ? 'bg-red-50 text-red-500 ring-1 ring-red-100 dark:bg-red-500/10 dark:text-red-300 dark:ring-red-500/20' : 'bg-gray-50 text-gray-500 ring-1 ring-gray-100 dark:bg-slate-800 dark:text-slate-400 dark:ring-slate-700') }}
                                            ">
                                                @if ($savingsRateDetail['trend']['tone'] === 'up')
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                                                    </svg>
                                                @elseif ($savingsRateDetail['trend']['tone'] === 'down')
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 4.5l15 15m0 0V8.25m0 11.25H8.25" />
                                                    </svg>
                                                @endif
                                                {{ $savingsRateDetail['trend']['diffFormatted'] }}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </template>
        @endif

        <section
            wire:key="dashboard-category-chart-{{ $categoryBudgetChart['selectedPeriod'] ?? 'unready' }}"
            class="panel overflow-hidden px-4 py-4 sm:px-5 sm:py-5"
            x-data="{
                selectedLine: @js($categoryBudgetChart['topCategory']['name'] ?? null),
                summary: @js($categoryBudgetChart['topCategory'] ?? null),
                seriesData: @js($categoryBudgetChart['series'] ?? collect()),
                crosshair: { show: false, x: 0, y: 0, label: '' },
                selectedSeries() {
                    return this.seriesData.find((series) => series.name === this.selectedLine) ?? this.seriesData[0] ?? null;
                },
                selectSeries(series) {
                    this.selectedLine = series.name;
                    this.summary = series.summary;
                    this.crosshair.show = false;
                },
                showCrosshair(event) {
                    const series = this.selectedSeries();

                    if (! series || ! series.points.length) {
                        return;
                    }

                    const svg = event.currentTarget.ownerSVGElement;
                    const rect = svg.getBoundingClientRect();
                    const viewBox = svg.viewBox.baseVal;
                    const pointerX = (event.clientX - rect.left) * (viewBox.width / rect.width);
                    const point = series.points.reduce((closest, item) => Math.abs(item.x - pointerX) < Math.abs(closest.x - pointerX) ? item : closest, series.points[0]);

                    this.summary = {
                        ...series.summary,
                        dateLabel: point.fullLabel,
                        formattedTotal: point.formatted,
                        changePercentageLabel: point.changePercentageLabel,
                        changeTone: point.changeTone,
                    };
                    this.crosshair = { show: true, x: point.x, y: point.y, label: point.fullLabel };
                },
                closeTooltip() {
                    const series = this.selectedSeries();

                    this.crosshair.show = false;
                    this.summary = series?.summary ?? this.summary;
                }
            }"
            x-on:click.self="closeTooltip()"
        >
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div class="min-w-0">
                    <div class="eyebrow">Chart</div>
                    <h2 class="mt-1 text-base font-semibold text-gray-950 dark:text-slate-50 sm:text-lg">Tren pengeluaran</h2>
                </div>
                <span class="inline-flex items-center rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700 ring-1 ring-green-100 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20">
                    {{ $categoryBudgetChart['periodLabel'] ?? now()->year }}
                </span>
            </div>

            @if (! $categoryBudgetChart['ready'])
                <div class="mt-4 rounded-lg border border-dashed border-gray-200 px-4 py-10 text-center text-sm text-gray-500 dark:border-slate-700 dark:text-slate-400">
                    Tambahkan label pada pengeluaran untuk mengaktifkan chart kategori.
                </div>
            @elseif ($categoryBudgetChart['series']->isEmpty())
                <div class="mt-4 rounded-lg border border-dashed border-gray-200 px-4 py-10 text-center text-sm text-gray-500 dark:border-slate-700 dark:text-slate-400">
                    Belum ada pengeluaran kategori di tahun ini.
                </div>
            @else
                <div class="mt-4 -mx-4 bg-green-50/45 px-4 py-4 dark:bg-green-500/[0.06] sm:-mx-5 sm:px-5 sm:py-5">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0">
                            <div class="text-xs font-semibold uppercase text-green-700/70 dark:text-green-300/70">Kategori</div>
                            <h3 class="mt-1 truncate text-xl font-semibold text-gray-950 dark:text-slate-50" x-text="summary?.label ?? @js($categoryBudgetChart['topCategory']['label'])">{{ $categoryBudgetChart['topCategory']['label'] }}</h3>
                            <div class="mt-1 text-xs font-medium text-gray-500 dark:text-slate-400" x-text="summary?.dateLabel ?? @js($categoryBudgetChart['topCategory']['dateLabel'])">{{ $categoryBudgetChart['topCategory']['dateLabel'] }}</div>
                            <div class="mt-2 flex flex-wrap items-center gap-2 text-sm">
                                <span class="money-value font-semibold text-green-700 dark:text-green-300" x-text="summary?.formattedTotal ?? @js($categoryBudgetChart['topCategory']['formattedTotal'])">{{ $categoryBudgetChart['topCategory']['formattedTotal'] }}</span>
                                <span class="text-gray-400 dark:text-slate-500">/</span>
                                <span
                                    class="rounded-full px-2 py-0.5 text-xs font-medium"
                                    x-bind:class="summary?.changeTone === 'up' ? 'bg-green-50 text-green-600 ring-1 ring-green-100 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20' : (summary?.changeTone === 'down' ? 'bg-red-50 text-red-500 ring-1 ring-red-100 dark:bg-red-500/10 dark:text-red-300 dark:ring-red-500/20' : 'bg-white/70 text-gray-500 ring-1 ring-gray-100 dark:bg-slate-950/30 dark:text-slate-400 dark:ring-slate-700')"
                                    x-text="summary?.changePercentageLabel ?? @js($categoryBudgetChart['topCategory']['changePercentageLabel'])"
                                >{{ $categoryBudgetChart['topCategory']['changePercentageLabel'] }}</span>
                            </div>
                        </div>

                        <div class="grid w-full grid-cols-2 gap-1.5 sm:flex sm:max-w-full sm:flex-wrap sm:justify-end">
                            @foreach ($categoryBudgetChart['series'] as $seriesItem)
                                <button
                                    type="button"
                                    wire:key="dashboard-chart-legend-{{ str($seriesItem['name'])->slug() }}"
                                    class="inline-flex min-w-0 items-center justify-center gap-2 rounded-full bg-white/65 px-2.5 py-1 text-[11px] font-medium text-gray-600 ring-1 ring-green-100/70 transition dark:bg-slate-950/30 dark:text-slate-300 dark:ring-green-500/15 sm:shrink-0"
                                    x-bind:class="selectedLine === @js($seriesItem['name']) ? 'text-gray-950 ring-green-200 dark:text-slate-50 dark:ring-green-500/30' : ''"
                                    x-on:click="selectSeries(@js($seriesItem))"
                                >
                                    <span class="size-2 rounded-full" style="background-color: {{ $seriesItem['color'] }}"></span>
                                    <span class="min-w-0 truncate sm:max-w-28">{{ $seriesItem['label'] }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="relative mt-5 min-h-[13rem] w-full max-w-full sm:min-h-[18rem] lg:min-h-0" style="aspect-ratio: {{ $categoryBudgetChart['width'] }} / {{ $categoryBudgetChart['height'] }};">
                        <svg
                            class="absolute inset-0 block h-full w-full overflow-visible"
                            viewBox="0 0 {{ $categoryBudgetChart['width'] }} {{ $categoryBudgetChart['height'] }}"
                            preserveAspectRatio="xMidYMid meet"
                            role="img"
                            aria-label="Multi-line chart of spending by category"
                        >
                            <defs>
                                @foreach ($categoryBudgetChart['series'] as $seriesItem)
                                    <linearGradient id="dashboard-chart-area-gradient-{{ $loop->index }}" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="{{ $seriesItem['color'] }}" stop-opacity="0.28" />
                                        <stop offset="58%" stop-color="{{ $seriesItem['color'] }}" stop-opacity="0.10" />
                                        <stop offset="100%" stop-color="{{ $seriesItem['color'] }}" stop-opacity="0" />
                                    </linearGradient>
                                @endforeach
                            </defs>

                            <rect x="0" y="0" width="{{ $categoryBudgetChart['width'] }}" height="{{ $categoryBudgetChart['height'] }}" fill="transparent" x-on:click="closeTooltip()"></rect>

                            @foreach ($categoryBudgetChart['yTicks'] as $tick)
                                <line x1="{{ $categoryBudgetChart['plot']['left'] }}" y1="{{ $tick['y'] }}" x2="{{ $categoryBudgetChart['plot']['right'] }}" y2="{{ $tick['y'] }}" class="dashboard-chart-grid" />
                                <text x="8" y="{{ $tick['y'] + 4 }}" class="dashboard-chart-axis-text">{{ $tick['label'] }}</text>
                            @endforeach

                            @foreach ($categoryBudgetChart['buckets'] as $bucket)
                                @if ($bucket['showLabel'])
                                    <text x="{{ $bucket['x'] }}" y="{{ $categoryBudgetChart['height'] - 24 }}" text-anchor="middle" class="dashboard-chart-axis-text">{{ $bucket['label'] }}</text>
                                @endif
                            @endforeach

                            @foreach ($categoryBudgetChart['series'] as $seriesItem)
                                <path
                                    d="{{ $seriesItem['areaPath'] }}"
                                    class="dashboard-chart-area"
                                    x-bind:class="selectedLine && selectedLine !== @js($seriesItem['name']) ? 'dashboard-chart-area-muted' : (selectedLine === @js($seriesItem['name']) ? 'dashboard-chart-area-active' : '')"
                                    fill="url(#dashboard-chart-area-gradient-{{ $loop->index }})"
                                ></path>
                            @endforeach

                            @foreach ($categoryBudgetChart['series'] as $seriesItem)
                                <path
                                    d="{{ $seriesItem['path'] }}"
                                    class="dashboard-chart-line"
                                    x-bind:class="selectedLine && selectedLine !== @js($seriesItem['name']) ? 'dashboard-chart-line-muted' : (selectedLine === @js($seriesItem['name']) ? 'dashboard-chart-line-active' : '')"
                                    style="stroke: {{ $seriesItem['color'] }}"
                                ></path>
                            @endforeach

                            @foreach ($categoryBudgetChart['series'] as $seriesItem)
                                @if ($seriesItem['latestPoint'])
                                    <circle
                                        cx="{{ $seriesItem['latestPoint']['x'] }}"
                                        cy="{{ $seriesItem['latestPoint']['y'] }}"
                                        r="4"
                                        class="dashboard-chart-marker"
                                        x-bind:class="selectedLine && selectedLine !== @js($seriesItem['name']) ? 'dashboard-chart-marker-muted' : ''"
                                        style="fill: {{ $seriesItem['color'] }}"
                                    ></circle>
                                @endif
                            @endforeach

                            <g x-show="crosshair.show" x-cloak>
                                <line
                                    x-bind:x1="crosshair.x"
                                    y1="{{ $categoryBudgetChart['plot']['top'] }}"
                                    x-bind:x2="crosshair.x"
                                    y2="{{ $categoryBudgetChart['plot']['bottom'] }}"
                                    class="stroke-green-500 dark:stroke-green-400"
                                    stroke-width="1.35"
                                    vector-effect="non-scaling-stroke"
                                />
                                <circle
                                    x-bind:cx="crosshair.x"
                                    x-bind:cy="crosshair.y"
                                    r="5.5"
                                    class="fill-green-500 dark:fill-green-400"
                                />
                                <text
                                    x-bind:x="crosshair.x"
                                    y="{{ $categoryBudgetChart['plot']['top'] - 10 }}"
                                    text-anchor="middle"
                                    class="dashboard-chart-crosshair-text"
                                    x-text="crosshair.label"
                                ></text>
                            </g>

                            <rect
                                x="{{ $categoryBudgetChart['plot']['left'] }}"
                                y="{{ $categoryBudgetChart['plot']['top'] }}"
                                width="{{ $categoryBudgetChart['plot']['right'] - $categoryBudgetChart['plot']['left'] }}"
                                height="{{ $categoryBudgetChart['plot']['bottom'] - $categoryBudgetChart['plot']['top'] }}"
                                fill="transparent"
                                class="cursor-crosshair"
                                x-on:mouseenter="showCrosshair($event)"
                                x-on:mousemove="showCrosshair($event)"
                                x-on:mouseleave="closeTooltip()"
                                x-on:click="showCrosshair($event)"
                            ></rect>
                        </svg>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="mx-auto flex w-fit max-w-full flex-wrap justify-center gap-1 rounded-2xl border border-slate-200/70 bg-white/70 p-1 shadow-sm shadow-slate-900/5 dark:border-slate-800/70 dark:bg-slate-950/35 sm:min-w-max sm:rounded-full">
                        @foreach ($categoryBudgetChart['periodOptions'] as $periodKey => $periodLabel)
                            <button
                                type="button"
                                wire:click="setCategoryChartPeriod('{{ $periodKey }}')"
                                wire:key="dashboard-category-period-{{ $periodKey }}"
                                class="{{ $categoryBudgetChart['selectedPeriod'] === $periodKey ? 'bg-green-50 text-green-700 shadow-sm ring-1 ring-green-100 dark:bg-green-500/12 dark:text-green-300 dark:ring-green-500/20' : 'text-gray-500 hover:bg-slate-50 hover:text-gray-900 dark:text-slate-400 dark:hover:bg-slate-800/70 dark:hover:text-slate-100' }} rounded-full px-3 py-1.5 text-center text-xs font-medium transition sm:px-4"
                            >
                                {{ $periodLabel }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>

        <section class="panel overflow-hidden px-4 py-4">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <div class="eyebrow">Activity</div>
                    <h2 class="mt-1 text-lg font-semibold text-gray-950 dark:text-slate-50">Aktivitas</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">{{ $labelActivityHeatmap['totalTransactions'] }} transaksi / {{ $labelActivityHeatmap['periodLabel'] }}</p>
                </div>
                <label class="relative w-full sm:w-40">
                    <span class="sr-only">Filter tahun</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M5.25 5.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25A2.25 2.25 0 0 1 18.75 21H5.25A2.25 2.25 0 0 1 3 18.75V7.5A2.25 2.25 0 0 1 5.25 5.25Z" />
                    </svg>
                    <select wire:model.live="labelActivityYear" class="input-field appearance-none pl-9 pr-9">
                        @foreach ($labelActivityYears as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="pointer-events-none absolute right-3 top-1/2 size-4 -translate-y-1/2 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </label>
            </div>

            @if (! $labelActivityHeatmap['ready'])
                <div class="mt-4 rounded-lg border border-dashed border-gray-200 px-4 py-10 text-center text-sm text-gray-500 dark:border-slate-700 dark:text-slate-400">
                    Tambahkan label pada pengeluaran untuk mengaktifkan aktivitas label.
                </div>
            @elseif ($labelActivityHeatmap['rows']->isEmpty())
                <div class="mt-4 rounded-lg border border-dashed border-gray-200 px-4 py-10 text-center text-sm text-gray-500 dark:border-slate-700 dark:text-slate-400">
                    Belum ada pengeluaran berlabel di tampilan ini.
                </div>
            @else
                <div class="mt-5 overflow-x-auto pb-1">
                    <div class="label-activity-heatmap min-w-[30rem] sm:min-w-[48rem]">
                        <div class="label-activity-week-grid ml-[4.35rem] mr-[4.25rem] sm:ml-32 sm:mr-24" style="grid-template-columns: repeat({{ $labelActivityHeatmap['weeks']->count() }}, var(--label-activity-cell-size));">
                            @foreach ($labelActivityHeatmap['weeks'] as $week)
                                <div class="h-4 text-center text-[10px] font-medium text-gray-400 dark:text-slate-500">{{ $week['label'] }}</div>
                            @endforeach
                        </div>

                        <div class="label-activity-rows mt-1">
                            @foreach ($labelActivityHeatmap['rows'] as $row)
                                <div class="label-activity-row grid grid-cols-[3.95rem_minmax(0,1fr)_3.95rem] items-center gap-1 sm:grid-cols-[7.5rem_minmax(0,1fr)_6rem] sm:gap-2">
                                    <div class="min-w-0">
                                        <div class="truncate text-xs font-semibold text-gray-700 dark:text-slate-200">{{ $row['label'] }}</div>
                                    </div>
                                    <div class="label-activity-week-grid" style="grid-template-columns: repeat({{ $labelActivityHeatmap['weeks']->count() }}, var(--label-activity-cell-size));">
                                        @foreach ($row['weeks'] as $week)
                                            <span
                                                tabindex="0"
                                                class="label-activity-cell label-activity-level-{{ $week['level'] }} focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-slate-950"
                                                data-tooltip="{{ $week['spendName'] }} / {{ $week['date'] }} / {{ $week['formatted'] }}"
                                                aria-label="{{ $week['spendName'] }} {{ $week['date'] }} {{ $week['formatted'] }}"
                                            ></span>
                                        @endforeach
                                    </div>
                                    <div class="money-value truncate text-right text-[10px] font-semibold text-gray-500 dark:text-slate-400 sm:text-xs">{{ $this->rupiah($row['total']) }}</div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 flex items-center justify-end gap-1 text-xs text-gray-400 dark:text-slate-500 sm:gap-1.5">
                            <span>Ringan</span>
                            @for ($level = 0; $level <= 4; $level++)
                                <span class="label-activity-cell label-activity-level-{{ $level }}"></span>
                            @endfor
                            <span>Padat</span>
                        </div>
                    </div>
                </div>

            @endif
        </section>

        <section class="grid gap-4 xl:grid-cols-[minmax(0,1.1fr)_minmax(340px,0.9fr)]">
            <div class="panel px-4 py-4">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <div class="eyebrow">Rencana</div>
                        <h2 class="mt-1 text-xl font-bold text-gray-950 dark:text-slate-50">Kondisi plan</h2>
                    </div>
                    <div class="rounded-lg bg-gray-50 px-3 py-2 text-right ring-1 ring-gray-100 dark:bg-slate-800/70 dark:ring-slate-700">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Rata-rata</div>
                        <div class="money-value font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($averageTransaction) }}</div>
                    </div>
                </div>

                <div class="mt-5 space-y-4">
                    @forelse ($budgetHealth as $budget)
                        <div wire:key="dashboard-budget-health-{{ $budget['id'] ?? str($budget['name'])->slug() }}">
                            <div class="mb-2 flex items-center justify-between gap-3 text-sm">
                                <div class="min-w-0">
                                    <div class="truncate font-semibold text-gray-950 dark:text-slate-50">{{ $budget['name'] }}</div>
                                    <div class="money-value text-xs text-gray-500 dark:text-slate-400">{{ $this->rupiah($budget['spent']) }} of {{ $this->rupiah($budget['income']) }}</div>
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
                            Belum ada plan.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="panel px-4 py-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <div class="eyebrow">Recent</div>
                        <h2 class="mt-1 text-xl font-bold text-gray-950 dark:text-slate-50">Pengeluaran terbaru</h2>
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
                                    <span>{{ $expense->label?->name ?? 'Tanpa label' }}</span>
                                    <span>{{ $expense->created_at?->format('d M Y') }}</span>
                                </div>
                            </div>
                            <div class="money-value shrink-0 text-sm font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($expense->getRawOriginal('amount')) }}</div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-sm text-gray-500 dark:text-slate-400">Belum ada pengeluaran terbaru.</div>
                    @endforelse
                </div>

                @if ($largestExpense)
                    <div class="mt-4 rounded-lg bg-gray-50 px-3 py-3 ring-1 ring-gray-100 dark:bg-slate-800/70 dark:ring-slate-700">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Pengeluaran Terbesar</div>
                        <div class="mt-2 flex items-center justify-between gap-3">
                            <div class="min-w-0 truncate font-semibold text-gray-950 dark:text-slate-50">{{ $largestExpense->name }}</div>
                            <div class="money-value shrink-0 font-bold text-red-500">{{ $this->rupiah($largestExpense->getRawOriginal('amount')) }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        <section class="grid gap-4 xl:grid-cols-3">
            <div class="panel px-4 py-4">
                <div class="eyebrow">Top</div>
                <h2 class="mt-1 text-base font-bold text-gray-950 dark:text-slate-50">Pengeluaran terbesar</h2>

                <div class="mt-4 divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse ($topExpenses as $expense)
                        <div wire:key="dashboard-top-expense-{{ $expense->id }}" class="flex items-center justify-between gap-3 py-3">
                            <div class="min-w-0">
                                <div class="truncate font-semibold text-gray-950 dark:text-slate-50">{{ $expense->name }}</div>
                                <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-slate-400">
                                    <span>{{ $expense->budget?->name }}</span>
                                    <span>{{ $expense->label?->name ?? 'Tanpa label' }}</span>
                                </div>
                            </div>
                            <div class="money-value shrink-0 text-sm font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($expense->getRawOriginal('amount')) }}</div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-sm text-gray-500 dark:text-slate-400">Belum ada pengeluaran di tampilan ini.</div>
                    @endforelse
                </div>
            </div>

            <div class="panel px-4 py-4">
                <div class="eyebrow">Platforms</div>
                <h2 class="mt-1 text-base font-bold text-gray-950 dark:text-slate-50">Platform</h2>

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
                        <div class="py-8 text-center text-sm text-gray-500 dark:text-slate-400">Belum ada data platform.</div>
                    @endforelse
                </div>
            </div>

            <div class="panel px-4 py-4">
                <div class="eyebrow">Status</div>
                <h2 class="mt-1 text-base font-bold text-gray-950 dark:text-slate-50">Status alokasi</h2>

                <div class="mt-4 grid gap-2">
                    @forelse ($statusBreakdown as $status)
                        <div wire:key="dashboard-status-breakdown-{{ str($status['name'])->slug() }}" class="rounded-lg bg-gray-50 px-3 py-2 ring-1 ring-gray-100 dark:bg-slate-800/70 dark:ring-slate-700">
                            <div class="flex items-center justify-between gap-2 text-sm">
                                <span class="truncate font-semibold text-gray-700 dark:text-slate-200">{{ ucfirst($status['name']) }}</span>
                                <span class="text-gray-500 dark:text-slate-400">{{ $status['transactions'] }}x</span>
                            </div>
                            <div class="money-value mt-2 text-sm font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($status['total']) }}</div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-sm text-gray-500 dark:text-slate-400">Belum ada data status.</div>
                    @endforelse
                </div>
            </div>
        </section>

    </main>
</div>
