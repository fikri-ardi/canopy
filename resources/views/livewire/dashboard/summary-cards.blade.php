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
    <div class="metric-card metric-card-savings group cursor-pointer" wire:click="openSavingsDetail" id="savings-rate-card">
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
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0" wire:loading.remove wire:target="setCategoryChartPeriod,selectInvestment,labelActivityYear">
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

                <div class="metric-value money-value">{{ rupiah($this->totalInvestment) }}</div>
                @if ($this->selectedInvestmentName)
                <div class="mt-1 truncate text-xs font-medium text-gray-500 dark:text-slate-400">{{ $this->selectedInvestmentName }}</div>
                @endif
            </div>
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