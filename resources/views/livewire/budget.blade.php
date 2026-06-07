<div
    x-data="alokasiBudgetPage(@js($onboardingStep))"
    data-server-onboarding-step="{{ $onboardingStep ?? '' }}"
    x-on:saved="afterExpenseSaved()"
    x-on:saved.window="afterExpenseSaved()"
    x-on:onboarding-completed="skipOnboarding()"
    x-on:onboarding-completed.window="skipOnboarding()"
    x-on:budget-created="afterBudgetCreated()"
    x-on:budget-created.window="afterBudgetCreated()"
    x-on:onboarding-budget-created="reloadFullPageForOnboarding()"
    x-on:onboarding-budget-created.window="reloadFullPageForOnboarding()"
    x-on:onboarding-expense-created="reloadFullPageForOnboarding()"
    x-on:onboarding-expense-created.window="reloadFullPageForOnboarding()"
    x-on:onboarding-expense-ready="startExpenseOnboarding()"
    x-on:onboarding-expense-ready.window="startExpenseOnboarding()"
    x-on:onboarding-dashboard-ready="startDashboardOnboarding()"
    x-on:onboarding-dashboard-ready.window="startDashboardOnboarding()"
    x-on:budget-renamed="renameBudget = false"
    x-on:budget-renamed.window="renameBudget = false"
    x-on:budget-income-updated="editIncome = false"
    x-on:budget-income-updated.window="editIncome = false"
    x-on:budget-deleted="deleteBudget = false"
    x-on:budget-deleted.window="deleteBudget = false"
    class="min-w-0"
>
    <header class="app-header">
        <div class="page-header-layout">
            <div class="page-header-copy">
                <span class="page-hero-icon page-hero-icon-amber">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m19.5 0h-.75a.75.75 0 0 1-.75-.75V4.5m0 0H3.75m16.5 0c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125H3.75A1.125 1.125 0 0 1 2.625 15.375v-9.75C2.625 5.004 3.129 4.5 3.75 4.5" />
                    </svg>
                </span>

                <div class="min-w-0">
                    <div class="eyebrow">Active Budget</div>
                    <h1 class="page-title">{{ $activeBudget?->name ?? 'No budget yet' }}</h1>
                </div>
            </div>

            <div class="page-header-actions">
                @if ($activeBudget)
                    <div class="relative min-w-0 flex-1 sm:min-w-48 sm:flex-none">
                        <button x-ref="budgetTrigger" type="button" x-on:click.stop="budgetMenu.toggle($refs.budgetTrigger, $refs.budgetMenu)" class="btn-secondary w-full justify-between">
                            <span class="truncate">{{ $activeBudget->name }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        <template x-teleport="body">
                            <div x-ref="budgetMenu" x-show="budgetMenu.open" x-cloak x-transition x-bind:style="budgetMenu.style" x-on:click.outside="budgetMenu.close()" x-on:resize.window="budgetMenu.close()" wire:key="budget-picker-menu" wire:ignore.self class="floating-select-menu">
                                @foreach ($budgets as $budget)
                                    <button type="button" x-on:click="budgetMenu.close()" wire:click="selectBudget({{ $budget->id }})" wire:key="budget-picker-option-{{ $budget->id }}" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">
                                        {{ $budget->name }}
                                    </button>
                                @endforeach
                            </div>
                        </template>
                    </div>

                    <button x-ref="budgetSettingsTrigger" type="button" x-on:click.stop="budgetSettingsMenu.toggle($refs.budgetSettingsTrigger, $refs.budgetSettingsMenu)" class="btn-icon" aria-label="Budget settings" data-tooltip="Budget settings">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.348.78.748.936.236.092.466.19.69.3.38.185.833.143 1.184-.099l.737-.51a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.51.737c-.242.35-.284.804-.099 1.184.11.224.208.454.3.69.156.4.512.678.936.748l.894.149c.542.09.94.56.94 1.11v1.093c0 .55-.398 1.02-.94 1.11l-.894.149c-.424.07-.78.348-.936.748a7.02 7.02 0 0 1-.3.69c-.185.38-.143.833.099 1.184l.51.737c.32.448.27 1.061-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.45.12l-.737-.51c-.35-.242-.804-.284-1.184-.099a7.02 7.02 0 0 1-.69.3c-.4.156-.678.512-.748.936l-.149.894c-.09.542-.56.94-1.11.94h-1.093c-.55 0-1.02-.398-1.11-.94l-.149-.894a1.125 1.125 0 0 0-.748-.936 7.02 7.02 0 0 1-.69-.3c-.38-.185-.833-.143-1.184.099l-.737.51a1.125 1.125 0 0 1-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.51-.737c.242-.35.284-.804.099-1.184a7.02 7.02 0 0 1-.3-.69 1.125 1.125 0 0 0-.936-.748l-.894-.149a1.125 1.125 0 0 1-.94-1.11v-1.093c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.78-.348.936-.748.092-.236.19-.466.3-.69.185-.38.143-.833-.099-1.184l-.51-.737a1.125 1.125 0 0 1 .12-1.45l.774-.773a1.125 1.125 0 0 1 1.45-.12l.737.51c.35.242.804.284 1.184.099.224-.11.454-.208.69-.3.4-.156.678-.512.748-.936l.149-.894Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </button>

                    <template x-teleport="body">
                        <div x-ref="budgetSettingsMenu" x-show="budgetSettingsMenu.open" x-cloak x-transition x-bind:style="budgetSettingsMenu.style" x-on:click.outside="budgetSettingsMenu.close()" x-on:resize.window="budgetSettingsMenu.close()" wire:key="budget-settings-menu" wire:ignore.self class="floating-select-menu">
                            <button type="button" x-on:click="budgetSettingsMenu.close(); renameBudget = true" wire:click="startRenamingBudget" class="flex w-full items-center gap-2 px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-4 shrink-0 text-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487Z" />
                                </svg>
                                <span>Rename budget</span>
                            </button>
                            <button type="button" x-on:click="budgetSettingsMenu.close(); editIncome = true" wire:click="startEditingIncome" class="flex w-full items-center gap-2 px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-4 shrink-0 text-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182-.586-.439-1.354-.659-2.121-.659-.768 0-1.536-.22-2.121-.659-1.172-.879-1.172-2.303 0-3.182 1.171-.879 3.07-.879 4.242 0l.879.659" />
                                </svg>
                                <span>Edit income</span>
                            </button>
                            <button type="button" x-on:click="budgetSettingsMenu.close()" wire:click="duplicateActiveBudget" class="flex w-full items-center gap-2 px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-4 shrink-0 text-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125v-9.75c0-.621.504-1.125 1.125-1.125H8.25m7.5 7.5h3.375c.621 0 1.125-.504 1.125-1.125v-9.75c0-.621-.504-1.125-1.125-1.125h-9.75A1.125 1.125 0 0 0 8.25 6.375v3.375" />
                                </svg>
                                <span>Duplicate budget</span>
                            </button>
                            <button type="button" x-on:click="budgetSettingsMenu.close(); deleteBudget = true" class="flex w-full items-center gap-2 px-3 py-2 text-left text-sm text-red-500 hover:bg-red-50 dark:text-red-300 dark:hover:bg-red-500/10">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-4 shrink-0">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166M19.228 5.79 18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79" />
                                </svg>
                                <span>Delete budget</span>
                            </button>
                        </div>
                    </template>
                @endif

                <button type="button" x-on:click="openBudgetModalFromTour()" class="btn-primary px-3 sm:px-4" data-onboarding-target="new-budget" aria-label="New Budget" data-tooltip="New Budget">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span class="hidden sm:inline">New Budget</span>
                </button>
            </div>
        </div>
    </header>

    <template x-teleport="body">
        <template x-if="tour.visible && currentTourStep()">
            <div aria-hidden="true">
                <div class="onboarding-spotlight-blur" x-bind:style="tour.blurStyle.top"></div>
                <div class="onboarding-spotlight-blur" x-bind:style="tour.blurStyle.right"></div>
                <div class="onboarding-spotlight-blur" x-bind:style="tour.blurStyle.bottom"></div>
                <div class="onboarding-spotlight-blur" x-bind:style="tour.blurStyle.left"></div>
            </div>
        </template>
    </template>

    <template x-teleport="body">
        <svg
            x-show="tour.visible && currentTourStep()"
            x-cloak
            x-transition.opacity
            class="onboarding-spotlight-overlay"
            aria-hidden="true"
        >
            <defs>
                <radialGradient
                    id="onboarding-spotlight-gradient"
                    gradientUnits="userSpaceOnUse"
                    x-bind:cx="tour.spotlight.cx"
                    x-bind:cy="tour.spotlight.cy"
                    x-bind:r="tour.spotlight.r"
                >
                    <stop offset="0%" stop-color="#020617" stop-opacity="0.28" />
                    <stop offset="58%" stop-color="#020617" stop-opacity="0.58" />
                    <stop offset="100%" stop-color="#020617" stop-opacity="0.74" />
                </radialGradient>
                <mask id="onboarding-spotlight-mask">
                    <rect width="100%" height="100%" fill="white" />
                    <rect
                        x-bind:x="tour.spotlight.x"
                        x-bind:y="tour.spotlight.y"
                        x-bind:width="tour.spotlight.width"
                        x-bind:height="tour.spotlight.height"
                        x-bind:rx="tour.spotlight.radius"
                        fill="black"
                    />
                </mask>
            </defs>
            <rect width="100%" height="100%" fill="url(#onboarding-spotlight-gradient)" mask="url(#onboarding-spotlight-mask)" />
        </svg>
    </template>

    <template x-teleport="body">
        <div
            x-show="tour.visible && currentTourStep()"
            x-cloak
            x-transition.opacity
            class="onboarding-tour-tooltip"
            x-bind:style="tour.style"
        >
            <div class="onboarding-tour-kicker" x-text="currentTourStep()?.kicker"></div>
            <div class="onboarding-tour-title" x-text="currentTourStep()?.title"></div>
            <p class="onboarding-tour-copy" x-text="currentTourStep()?.copy"></p>
            <div class="mt-3 flex items-center justify-between gap-2">
                <button
                    type="button"
                    class="text-xs font-semibold text-gray-400 transition hover:text-gray-600 dark:text-slate-500 dark:hover:text-slate-300"
                    x-on:click="$wire.completeOnboarding(); skipOnboarding()"
                >
                    Skip
                </button>
                <button
                    type="button"
                    x-show="currentTourStep()?.action"
                    x-on:click="continueTour()"
                    class="btn-primary px-2.5 py-1.5 text-xs"
                    x-text="currentTourStep()?.action"
                ></button>
            </div>
        </div>
    </template>

    <livewire:create-budget />

    @if ($activeBudget)
        <div x-show="renameBudget" x-cloak x-transition class="modal-backdrop">
            <div x-on:click.away="renameBudget = false" class="modal-panel">
                <div class="flex items-center gap-3">
                    <span class="icon-box-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487Z" />
                        </svg>
                    </span>
                    <div>
                        <div class="text-lg font-semibold text-gray-950 dark:text-slate-50">Rename Budget</div>
                        <p class="text-sm text-gray-500 dark:text-slate-400">Give this plan a clearer name.</p>
                    </div>
                </div>

                <form class="mt-5 space-y-4" wire:submit="renameActiveBudget">
                    <div>
                        <input wire:model="renameBudgetName" type="text" class="input-field" placeholder="Budget name">
                        @error('renameBudgetName')
                            <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" x-on:click="renameBudget = false" class="btn-secondary">Cancel</button>
                        <button type="submit" class="btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($activeBudget)
        <div x-show="editIncome" x-cloak x-transition class="modal-backdrop">
            <div x-on:click.away="editIncome = false" class="modal-panel">
                <div class="flex items-center gap-3">
                    <span class="icon-box">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182-.586-.439-1.354-.659-2.121-.659-.768 0-1.536-.22-2.121-.659-1.172-.879-1.172-2.303 0-3.182 1.171-.879 3.07-.879 4.242 0l.879.659" />
                        </svg>
                    </span>
                    <div>
                        <div class="text-lg font-semibold text-gray-950 dark:text-slate-50">Edit Total Income</div>
                        <p class="text-sm text-gray-500 dark:text-slate-400">Update the income planned for this budget.</p>
                    </div>
                </div>

                <form class="mt-5 space-y-4" wire:submit="updateActiveBudgetIncome">
                    <div>
                        <label for="budget-income-edit" class="mb-1 block text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Total Income</label>
                        <input wire:model="incomeAmount" type="text" inputmode="numeric" autocomplete="off" data-number-format="live" id="budget-income-edit" class="input-field" placeholder="2.000.000">
                        @error('incomeAmount')
                            <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" x-on:click="editIncome = false" class="btn-secondary">Cancel</button>
                        <button type="submit" class="btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($activeBudget)
        <div x-show="deleteBudget" x-cloak x-transition class="modal-backdrop">
            <div x-on:click.away="deleteBudget = false" class="modal-panel">
                <div class="flex items-center gap-3">
                    <span class="inline-flex size-10 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-600 ring-1 ring-red-100 dark:bg-red-500/10 dark:text-red-300 dark:ring-red-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166M19.228 5.79 18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79" />
                        </svg>
                    </span>
                    <div>
                        <div class="text-lg font-semibold text-gray-950 dark:text-slate-50">Delete Budget</div>
                        <p class="text-sm text-gray-500 dark:text-slate-400">This action cannot be undone.</p>
                    </div>
                </div>

                <p class="mt-5 text-sm text-gray-600 dark:text-slate-300">
                    Delete <span class="font-semibold text-gray-950 dark:text-slate-50">{{ $activeBudget->name }}</span> and all its expenses?
                </p>

                <form class="mt-6 flex justify-end gap-2" wire:submit="deleteActiveBudget">
                    <button type="button" x-on:click="deleteBudget = false" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-danger">Delete</button>
                </form>
            </div>
        </div>
    @endif

    <main class="space-y-6 px-4 py-5 sm:px-6 sm:py-6 lg:px-8">
        @if ($activeBudget)
            <section
                class="sticky-summary summary-grid"
                x-data="{
                    stuck: false,
                    updateStickyState() {
                        this.stuck = window.scrollY > 0 && this.$el.getBoundingClientRect().top <= 1;
                    },
                }"
                x-init="updateStickyState()"
                x-on:scroll.window.throttle.50ms="updateStickyState()"
                x-on:resize.window="updateStickyState()"
                x-bind:class="stuck ? 'sticky-summary-stuck' : ''"
            >
                @foreach ($summaryCards as $card)
                    <div wire:key="budget-summary-card-{{ str($card['label'])->slug() }}" class="metric-card">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">{{ $card['label'] }}</div>
                                    @if (($card['key'] ?? null) === 'allocation' && $allocationOptions->isNotEmpty())
                                        <button
                                            x-ref="allocationTrigger"
                                            type="button"
                                            x-on:click.stop="allocationMenu.toggle($refs.allocationTrigger, $refs.allocationMenu)"
                                            class="summary-menu-button"
                                            aria-label="Choose allocation platform"
                                            data-tooltip="Choose allocation platform"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-3.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </button>
                                    @endif
                                    @if (($card['key'] ?? null) === 'investment' && $investmentOptions->isNotEmpty())
                                        <button
                                            x-ref="investmentTrigger"
                                            type="button"
                                            x-on:click.stop="investmentMenu.toggle($refs.investmentTrigger, $refs.investmentMenu)"
                                            class="summary-menu-button"
                                            aria-label="Choose investment spend"
                                            data-tooltip="Choose investment spend"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-3.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                                <div class="metric-value-lg money-value">{{ $this->rupiah($card['amount']) }}</div>
                                @if (in_array(($card['key'] ?? null), ['allocation', 'investment'], true))
                                    <div class="mt-1 truncate text-xs font-medium text-gray-500 dark:text-slate-400">{{ $card['detail'] }}</div>
                                @endif
                            </div>
                            <span class="icon-box">
                                @switch($card['label'])
                                    @case('TOTAL INCOME')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182-.586-.439-1.354-.659-2.121-.659-.768 0-1.536-.22-2.121-.659-1.172-.879-1.172-2.303 0-3.182 1.171-.879 3.07-.879 4.242 0l.879.659" /></svg>
                                        @break
                                    @case('ALLOCATION')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 8.25h16.5m-16.5 7.5h16.5M6.75 3.75h10.5A2.25 2.25 0 0 1 19.5 6v12a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 18V6a2.25 2.25 0 0 1 2.25-2.25Z" /></svg>
                                        @break
                                    @case('REMAINING')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                        @break
                                    @case('INVESTMENT')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" /></svg>
                                        @break
                                    @default
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 12m18 0v6.75A2.25 2.25 0 0 1 18.75 21H5.25A2.25 2.25 0 0 1 3 18.75V12m18 0V8.25A2.25 2.25 0 0 0 18.75 6H5.25A2.25 2.25 0 0 0 3 8.25V12" /></svg>
                                @endswitch
                            </span>
                        </div>

                        @if (($card['key'] ?? null) === 'allocation' && $allocationOptions->isNotEmpty())
                            <template x-teleport="body">
                                <div x-ref="allocationMenu" x-show="allocationMenu.open" x-cloak x-transition x-bind:style="allocationMenu.style" x-on:click.outside="allocationMenu.close()" x-on:resize.window="allocationMenu.close()" wire:key="budget-allocation-menu" wire:ignore.self class="floating-select-menu investment-select-menu">
                                    @foreach ($allocationOptions as $option)
                                        <button type="button" x-on:click="allocationMenu.close()" wire:click="selectAllocationPlatform({{ $option['id'] }})" wire:key="budget-allocation-option-{{ $option['id'] }}" class="investment-option {{ (int) $selectedAllocationPlatformId === $option['id'] ? 'investment-option-active' : '' }}">
                                            <span class="min-w-0">
                                                <span class="block truncate font-semibold text-gray-800 dark:text-slate-100">{{ $option['name'] }}</span>
                                                <span class="mt-0.5 block text-xs text-gray-400 dark:text-slate-500">{{ $option['transactions'] }} transaksi</span>
                                            </span>
                                            <span class="money-value shrink-0 text-sm font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($option['amount']) }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </template>
                        @endif

                        @if (($card['key'] ?? null) === 'investment' && $investmentOptions->isNotEmpty())
                            <template x-teleport="body">
                                <div x-ref="investmentMenu" x-show="investmentMenu.open" x-cloak x-transition x-bind:style="investmentMenu.style" x-on:click.outside="investmentMenu.close()" x-on:resize.window="investmentMenu.close()" wire:key="budget-investment-menu" wire:ignore.self class="floating-select-menu investment-select-menu">
                                    @foreach ($investmentOptions as $option)
                                        <button type="button" x-on:click="investmentMenu.close()" wire:click="selectInvestment(@js($option['key']))" wire:key="budget-investment-option-{{ str($option['key'])->slug() }}" class="investment-option {{ $selectedInvestmentKey === $option['key'] ? 'investment-option-active' : '' }}">
                                            <span class="min-w-0">
                                                <span class="block truncate font-semibold text-gray-800 dark:text-slate-100">{{ $option['name'] }}</span>
                                                <span class="mt-0.5 block text-xs text-gray-400 dark:text-slate-500">{{ $option['transactions'] }} transaksi / {{ $option['movements'] }} movements</span>
                                            </span>
                                            <span class="money-value shrink-0 text-sm font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($option['amount']) }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </template>
                        @endif
                    </div>
                @endforeach
            </section>

            <section id="expenses" class="min-w-0">
                <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-bold text-gray-950 dark:text-slate-50">Expenses</h2>
                        <p class="text-sm text-gray-500 dark:text-slate-400">Inline edit any transaction, label, platform, or status.</p>
                    </div>

                    <button type="button" x-on:click="openExpenseModalFromTour()" class="btn-primary" data-onboarding-target="expense-button">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>New Expense</span>
                    </button>
                </div>

                <livewire:show-expense :activeBudgetId="$activeBudgetId" :key="'expenses-'.$budgetRenderKey.'-'.$activeBudgetId" />
            </section>

            <section class="grid gap-4 xl:grid-cols-[minmax(0,0.9fr)_minmax(340px,1.1fr)]">
                <div class="panel px-4 py-4">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <div class="eyebrow">Budget Intelligence</div>
                            <h2 class="mt-1 text-base font-bold text-gray-950 dark:text-slate-50">Current plan signals</h2>
                        </div>
                        <span class="icon-box-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                            </svg>
                        </span>
                    </div>

                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        @foreach ($insightCards as $card)
                            <div wire:key="budget-insight-card-{{ str($card['label'])->slug() }}" class="metric-tile rounded-lg bg-gray-50 px-3 py-3 ring-1 ring-gray-100 dark:bg-slate-800/70 dark:ring-slate-700">
                                <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">{{ $card['label'] }}</div>
                                <div class="metric-value-sm {{ $card['format'] === 'money' ? 'money-value' : '' }}">
                                    {{ $card['format'] === 'money' ? $this->rupiah($card['amount']) : number_format($card['amount'], 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="panel px-4 py-4">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <div class="eyebrow">Top Expenses</div>
                            <h2 class="mt-1 text-base font-bold text-gray-950 dark:text-slate-50">Largest items in this budget</h2>
                        </div>
                        <div class="{{ $remainingBalance < 0 ? 'text-red-500' : 'text-green-500' }} money-value text-sm font-semibold">
                            {{ $this->rupiah($remainingBalance) }} left
                        </div>
                    </div>

                    <div class="mt-4 divide-y divide-gray-100 dark:divide-slate-800">
                        @forelse ($topExpenses as $expense)
                            <div wire:key="budget-top-expense-{{ $expense->id }}" class="flex items-center justify-between gap-3 py-3">
                                <div class="min-w-0">
                                    <div class="truncate font-semibold text-gray-950 dark:text-slate-50">{{ $expense->name }}</div>
                                    <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-slate-400">
                                        <span>{{ $expense->label?->name ?? 'Unlabeled' }}</span>
                                        <span>{{ $expense->platform?->name }}</span>
                                        <span>{{ $expense->status?->body }}</span>
                                    </div>
                                </div>
                                <div class="money-value shrink-0 font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($expense->getRawOriginal('amount')) }}</div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-sm text-gray-500 dark:text-slate-400">No expenses yet.</div>
                        @endforelse
                    </div>
                </div>
            </section>

            <section id="reports" class="grid gap-4 xl:grid-cols-[minmax(0,1.15fr)_minmax(320px,0.85fr)]">
                <div class="panel px-4 py-4">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex min-w-0 items-center gap-3">
                            <span class="progress-circle size-16" style="--progress: {{ $spendProgress }}; --progress-color: {{ $remainingBalance < 0 ? '#ef4444' : '#22c55e' }}">
                                <span class="progress-circle-value">{{ min(999, $spendProgress) }}%</span>
                            </span>
                            <div class="min-w-0">
                                <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Budget Pulse</h2>
                                <p class="text-xs text-gray-500 dark:text-slate-400">{{ $spendProgress }}% used</p>
                            </div>
                        </div>
                        <div class="{{ $remainingBalance < 0 ? 'text-red-500' : 'text-green-500' }} money-value text-sm font-semibold">
                            {{ $this->rupiah($remainingBalance) }}
                        </div>
                    </div>
                </div>

                <div class="panel px-4 py-4">
                    <div class="flex items-center gap-3">
                        <span class="icon-box-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 4.296 3.745 3.745 0 0 1-4.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.745 3.745 0 0 1-4.296-1.043 3.745 3.745 0 0 1-1.043-4.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-4.296 3.745 3.745 0 0 1 4.296-1.043A3.745 3.745 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.745 3.745 0 0 1 4.296 1.043 3.745 3.745 0 0 1 1.043 4.296A3.745 3.745 0 0 1 21 12Z" />
                            </svg>
                        </span>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Status</h2>
                    </div>

                    <div class="mt-3 grid gap-2 sm:grid-cols-2">
                        @forelse ($statusAnalytics as $status)
                            <div wire:key="budget-status-analytic-{{ str($status['name'])->slug() }}" class="rounded-lg bg-gray-50 px-3 py-2 ring-1 ring-gray-100 dark:bg-slate-800/70 dark:ring-slate-700">
                                <div class="flex items-center justify-between gap-2 text-sm">
                                    <span class="truncate font-semibold text-gray-700 dark:text-slate-200">{{ ucfirst($status['name']) }}</span>
                                    <span class="text-gray-500 dark:text-slate-400">{{ $status['transactions'] }}x</span>
                                </div>
                                <div class="money-value mt-2 text-sm font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($status['total']) }}</div>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500 dark:text-slate-400">No transactions yet.</div>
                        @endforelse
                    </div>
                </div>
            </section>

            <section class="panel px-4 py-4">
                <div class="flex items-center gap-3">
                    <span class="icon-box-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                        </svg>
                    </span>
                    <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Platform Distribution</h2>
                </div>

                <div class="mt-4 space-y-3">
                    @forelse ($platformAnalytics as $platform)
                        <div wire:key="budget-platform-analytic-{{ str($platform['name'])->slug() }}">
                            <div class="mb-1 flex items-center justify-between gap-3 text-sm">
                                <span class="font-semibold text-gray-700 dark:text-slate-200">{{ $platform['name'] }}</span>
                                <span class="money-value shrink-0 text-gray-500 dark:text-slate-400">{{ $this->rupiah($platform['total']) }} / {{ $platform['percentage'] }}%</span>
                            </div>
                            <div class="progress-track h-2">
                                <div class="progress-fill" style="--progress: {{ $platform['percentage'] }}%; --progress-color: #22c55e"></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-gray-500 dark:text-slate-400">No platform data yet.</div>
                    @endforelse
                </div>
            </section>

            <livewire:create-expense @saved="$refresh" :activeBudgetId="$activeBudgetId" :key="'create-expense-'.$budgetRenderKey.'-'.$activeBudgetId" />
        @else
            <section class="panel border-dashed px-6 py-12 text-center">
                <span class="icon-box mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08" />
                    </svg>
                </span>
                <div class="mt-4 text-lg font-semibold text-gray-950 dark:text-slate-50">No budget yet</div>
                <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Create your first budget to start tracking expenses.</p>
                <button type="button" x-on:click="openBudgetModalFromTour()" class="btn-primary mt-4" data-onboarding-target="new-budget">New Budget</button>
            </section>
        @endif
    </main>

</div>
