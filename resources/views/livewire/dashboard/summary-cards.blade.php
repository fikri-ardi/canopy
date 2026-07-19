<section class="summary-grid">
    {{-- Total Income --}}
    <div class="metric-card">
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0" wire:loading.remove wire:target="setCategoryChartPeriod,selectInvestment,labelActivityYear">
                <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Total Pemasukan</div>
                <div class="metric-value money-value">{{ rupiah($totalIncome) }}</div>
            </div>
            <div class="min-w-0 space-y-2" wire:loading wire:target="setCategoryChartPeriod,selectInvestment,labelActivityYear">
                <div class="h-3 w-16 skeleton rounded"></div>
                <div class="h-5.5 w-28 skeleton rounded"></div>
            </div>
            <span class="icon-box" wire:loading.remove wire:target="setCategoryChartPeriod,selectInvestment,labelActivityYear">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182-.586-.439-1.354-.659-2.121-.659-.768 0-1.536-.22-2.121-.659-1.172-.879-1.172-2.303 0-3.182 1.171-.879 3.07-.879 4.242 0l.879.659" />
                </svg>
            </span>
            <span class="size-9 rounded-full skeleton" wire:loading wire:target="setCategoryChartPeriod,selectInvestment,labelActivityYear"></span>
        </div>
    </div>

    {{-- Saving Rate --}}
    <div 
        class="metric-card metric-card-savings group cursor-pointer" 
        wire:click="openSavingsDetail" id="savings-rate-card">
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0" wire:loading.remove wire:target="setCategoryChartPeriod,selectInvestment,labelActivityYear">
                <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Saving Rate</div>
                <div class="metric-value">{{ $this->savingsRate }}%</div>
            </div>
            <div class="min-w-0 space-y-2" wire:loading wire:target="setCategoryChartPeriod,selectInvestment,labelActivityYear">
                <div class="h-3 w-16 skeleton rounded"></div>
                <div class="h-5.5 w-16 skeleton rounded"></div>
            </div>
            <span class="icon-box-savings" wire:loading.remove wire:target="setCategoryChartPeriod,selectInvestment,labelActivityYear">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                </svg>
            </span>
            <span class="size-9 rounded-full skeleton" wire:loading wire:target="setCategoryChartPeriod,selectInvestment,labelActivityYear"></span>
        </div>
        <div class="mt-1.5 flex items-center gap-1.5 text-[11px] font-medium text-gray-400 dark:text-slate-500" wire:loading.remove
            wire:target="setCategoryChartPeriod,selectInvestment,labelActivityYear">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="size-3 transition group-hover:translate-x-0.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
            <span>Lihat detail</span>
        </div>
        <div class="mt-2 h-3.5 w-20 skeleton rounded" wire:loading wire:target="setCategoryChartPeriod,selectInvestment,labelActivityYear"></div>
    </div>

    {{-- Savings Rate Detail Panel --}}
    @if ($this->showSavingsDetail)
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
                class="savings-detail-overlay" id="savings-detail-panel">

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
                    x-on:click="close()">
                </div>
        
                {{-- Panel --}}
                <div class="savings-detail-panel" x-show="open" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="savings-detail-panel-hidden" x-transition:enter-end="savings-detail-panel-visible"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="savings-detail-panel-visible"
                    x-transition:leave-end="savings-detail-panel-hidden" x-on:click.outside="close()">

                    {{-- Header --}}
                    <div class="flex items-center justify-between gap-3 border-b border-slate-200/70 px-5 py-4 dark:border-slate-800/70">
                        <div class="min-w-0">
                            <div class="eyebrow">Detail</div>
                            <h2 class="mt-0.5 text-base font-bold text-gray-950 dark:text-slate-50">Saving Rate</h2>
                        </div>
                        <button type="button" class="btn-icon" x-on:click="close()" aria-label="Tutup panel">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
        
                    {{-- Content --}}
                    <div class="flex-1 overflow-y-auto px-5 py-5">
                        @if ($this->savingsRateDetail)
                        @if (! $this->savingsRateDetail['hasIncome'])
                        <div
                            class="rounded-lg border border-dashed border-gray-200 px-4 py-8 text-center text-sm text-gray-500 dark:border-slate-700 dark:text-slate-400">
                            Belum ada pemasukan yang tercatat untuk periode ini.
                        </div>
                        @elseif (! $this->savingsRateDetail['hasSavings'])
                        <div
                            class="rounded-lg border border-dashed border-gray-200 px-4 py-8 text-center text-sm text-gray-500 dark:border-slate-700 dark:text-slate-400">
                            Belum ada tabungan atau investasi yang tercatat untuk periode ini.
                        </div>
                        @else
                        {{-- Rate display --}}
                        <div class="text-center">
                            <div class="savings-rate-display">
                                <span class="savings-rate-number">{{ $this->savingsRateDetail['rate'] }}</span>
                                <span class="savings-rate-symbol">%</span>
                            </div>
                            <div class="mt-1 text-xs font-medium text-gray-500 dark:text-slate-400">Savings Rate</div>
                        </div>
        
                        {{-- Breakdown --}}
                        <div class="mt-6 space-y-3">
                            <div class="rounded-lg bg-gray-50 px-4 py-3 ring-1 ring-gray-100 dark:bg-slate-800/70 dark:ring-slate-700">
                                <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Total Income</div>
                                <div class="money-value mt-1 text-base font-bold text-gray-950 dark:text-slate-50">{{
                                    rupiah($this->savingsRateDetail['totalIncome']) }}</div>
                            </div>
                            <div class="rounded-lg bg-green-50/70 px-4 py-3 ring-1 ring-green-100 dark:bg-green-500/[0.08] dark:ring-green-500/20">
                                <div class="text-xs font-semibold uppercase text-green-700/70 dark:text-green-300/70">Total Saved</div>
                                <div class="money-value mt-1 text-base font-bold text-green-700 dark:text-green-300">{{
                                    rupiah($this->savingsRateDetail['totalSavings']) }}</div>
                            </div>
                        </div>
        
                        {{-- Formula --}}
                        <div class="mt-5 rounded-lg border border-slate-200/70 px-4 py-3 dark:border-slate-800/70">
                            <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Formula</div>
                            <div class="mt-2 text-center font-mono text-xs text-gray-600 dark:text-slate-300">
                                <span class="money-value text-green-600 dark:text-green-300">{{ rupiah($this->savingsRateDetail['totalSavings']) }}</span>
                                <span class="text-gray-400 dark:text-slate-500"> / </span>
                                <span class="money-value">{{ rupiah($this->savingsRateDetail['totalIncome']) }}</span>
                                <span class="text-gray-400 dark:text-slate-500"> × 100 = </span>
                                <span class="font-bold text-gray-950 dark:text-slate-50">{{ $this->savingsRateDetail['rate'] }}%</span>
                            </div>
                        </div>
        
                        {{-- Trend --}}
                        <div class="mt-5">
                            <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Trend</div>
                            <div class="mt-3 flex items-center gap-3">
                                <div
                                    class="flex-1 rounded-lg bg-gray-50 px-3 py-2.5 text-center ring-1 ring-gray-100 dark:bg-slate-800/70 dark:ring-slate-700">
                                    <div class="text-[11px] font-medium text-gray-400 dark:text-slate-500">{{ $this->savingsRateDetail['trend']['previous']['label']
                                        }}</div>
                                    <div class="mt-0.5 text-lg font-bold tabular-nums text-gray-950 dark:text-slate-50">{{
                                        $this->savingsRateDetail['trend']['previous']['rate'] }}%</div>
                                </div>
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        class="size-4 text-gray-300 dark:text-slate-600">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                    </svg>
                                </div>
                                <div
                                    class="flex-1 rounded-lg bg-gray-50 px-3 py-2.5 text-center ring-1 ring-gray-100 dark:bg-slate-800/70 dark:ring-slate-700">
                                    <div class="text-[11px] font-medium text-gray-400 dark:text-slate-500">{{ $this->savingsRateDetail['trend']['current']['label']
                                        }}</div>
                                    <div class="mt-0.5 text-lg font-bold tabular-nums text-gray-950 dark:text-slate-50">{{
                                        $this->savingsRateDetail['trend']['current']['rate'] }}%</div>
                                </div>
                            </div>
                            <div class="mt-2 flex justify-center">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold
                                                        {{ $this->savingsRateDetail['trend']['tone'] === 'up' ? 'bg-green-50 text-green-600 ring-1 ring-green-100 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20' : ($this->savingsRateDetail['trend']['tone'] === 'down' ? 'bg-red-50 text-red-500 ring-1 ring-red-100 dark:bg-red-500/10 dark:text-red-300 dark:ring-red-500/20' : 'bg-gray-50 text-gray-500 ring-1 ring-gray-100 dark:bg-slate-800 dark:text-slate-400 dark:ring-slate-700') }}
                                                    ">
                                    @if ($this->savingsRateDetail['trend']['tone'] === 'up')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                        class="size-3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                                    </svg>
                                    @elseif ($this->savingsRateDetail['trend']['tone'] === 'down')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                        class="size-3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 4.5l15 15m0 0V8.25m0 11.25H8.25" />
                                    </svg>
                                    @endif
                                    {{ $this->savingsRateDetail['trend']['diffFormatted'] }}
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

    {{-- Total Pengeluaran --}}
    <div class="metric-card">
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0" wire:loading.remove wire:target="setCategoryChartPeriod,selectInvestment,labelActivityYear">
                <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Total Pengeluaran</div>
                <div class="metric-value money-value">{{ rupiah($totalExpense) }}</div>
            </div>
            <div class="min-w-0 space-y-2" wire:loading wire:target="setCategoryChartPeriod,selectInvestment,labelActivityYear">
                <div class="h-3 w-16 skeleton rounded"></div>
                <div class="h-5.5 w-28 skeleton rounded"></div>
            </div>
            <span class="icon-box" wire:loading.remove wire:target="setCategoryChartPeriod,selectInvestment,labelActivityYear">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m19.5 0h-.75a.75.75 0 0 1-.75-.75V4.5m0 0H3.75m16.5 0c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125H3.75A1.125 1.125 0 0 1 2.625 15.375v-9.75C2.625 5.004 3.129 4.5 3.75 4.5" />
                </svg>
            </span>
            <span class="size-9 rounded-full skeleton" wire:loading wire:target="setCategoryChartPeriod,selectInvestment,labelActivityYear"></span>
        </div>
    </div>

    {{-- Sisa --}}
    <div class="metric-card">
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0" wire:loading.remove wire:target="setCategoryChartPeriod,selectInvestment,labelActivityYear">
                <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Sisa</div>
                <div class="{{ $remainingBalance < 0 ? 'metric-value-danger' : 'metric-value' }} money-value">{{ rupiah($remainingBalance) }}
                </div>
            </div>
            <div class="min-w-0 space-y-2" wire:loading wire:target="setCategoryChartPeriod,selectInvestment,labelActivityYear">
                <div class="h-3 w-16 skeleton rounded"></div>
                <div class="h-5.5 w-28 skeleton rounded"></div>
            </div>
            <span
                class="{{ $remainingBalance < 0 ? 'inline-flex size-10 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-600 ring-1 ring-red-100 dark:bg-red-500/10 dark:text-red-300 dark:ring-red-500/20' : 'icon-box-muted' }}"
                wire:loading.remove wire:target="setCategoryChartPeriod,selectInvestment,labelActivityYear">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </span>
            <span class="size-9 rounded-full skeleton" wire:loading wire:target="setCategoryChartPeriod,selectInvestment,labelActivityYear"></span>
        </div>
    </div>

    {{-- Investment Detail --}}
    <div class="metric-card">
        {{-- Investment card --}}
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0" wire:loading.remove wire:target="setCategoryChartPeriod,selectInvestment,labelActivityYear">
                {{-- Selected investment name --}}
                <div class="flex items-center gap-2">
                    <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">{{ $this->selectedInvestmentName }}</div>
                    @if ($this->investmentOptions->isNotEmpty())
                    <button x-ref="dashboardInvestmentTrigger" type="button"
                        x-on:click.stop="investmentMenu.toggle($refs.dashboardInvestmentTrigger, $refs.dashboardInvestmentMenu)"
                        class="summary-menu-button" aria-label="Pilih pengeluaran investasi" data-tooltip="Pilih pengeluaran investasi">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            class="size-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    @endif
                </div>

                {{-- Invested amount --}}
                <div class="metric-value money-value">{{ rupiah($this->totalInvestment) }}</div>

                {{-- Investment percentage --}}
                <div class="text-xs font-medium text-gray-400 dark:text-slate-500">
                    {{ $this->totalInvestment }}
                </div>
            </div>

            {{-- Skeleton loaders --}}
            <div class="min-w-0 space-y-2" wire:loading wire:target="setCategoryChartPeriod,selectInvestment,labelActivityYear">
                <div class="h-3 w-16 skeleton rounded"></div>
                <div class="h-5.5 w-28 skeleton rounded"></div>
                <div class="h-3 w-20 skeleton rounded"></div>
            </div>
            <span class="icon-box" wire:loading.remove wire:target="setCategoryChartPeriod,selectInvestment,labelActivityYear">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                </svg>
            </span>
            <span class="size-9 rounded-full skeleton" wire:loading wire:target="setCategoryChartPeriod,selectInvestment,labelActivityYear"></span>
        </div>

        {{-- Investment popover --}}
        @if ($this->investmentOptions->isNotEmpty())
        <template x-teleport="body">
            <div 
                x-ref="dashboardInvestmentMenu" 
                x-show="investmentMenu.open" 
                x-cloak 
                x-transition 
                x-bind:style="investmentMenu.style"
                x-on:click.outside="investmentMenu.close()" 
                x-on:resize.window="investmentMenu.close()" 
                wire:key="dashboard-investment-menu"
                wire:ignore.self class="floating-select-menu investment-select-menu">
                @foreach ($this->investmentOptions as $option)
                <button type="button" x-on:click="investmentMenu.close()" wire:click="selectInvestment(@js($option['key']))"
                    wire:key="dashboard-investment-option-{{ str($option['key'])->slug() }}"
                    class="investment-option {{ $selectedInvestmentKey === $option['key'] ? 'investment-option-active' : '' }}">
                    <span class="min-w-0">
                        <span class="block truncate font-semibold text-gray-800 dark:text-slate-100">{{ $option['name'] }}</span>
                        <span class="mt-0.5 block text-xs text-gray-400 dark:text-slate-500">{{ $option['transactions'] }} transaksi / {{
                            $option['movements'] }} mutasi</span>
                    </span>
                    <span class="money-value shrink-0 text-sm font-bold text-gray-950 dark:text-slate-50">{{ rupiah($option['amount'])
                        }}</span>
                </button>
                @endforeach
            </div>
        </template>
        @endif
    </div>
</section>