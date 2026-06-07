<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="h-full dark"
>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @livewireStyles
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400..800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <link rel="shortcut icon" href="/images/favicon.svg" type="image/svg+icon">
    <title>Alokasi - Your Income Pal</title>
</head>

<body
    class="h-full font-sans"
    x-data="{ theme: localStorage.getItem('theme') || 'dark', sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' }"
    x-bind:class="{ 'sidebar-collapsed': sidebarCollapsed }"
    x-init="
        document.documentElement.classList.toggle('dark', theme === 'dark');
        $watch('theme', value => {
            localStorage.setItem('theme', value);
            document.documentElement.classList.toggle('dark', value === 'dark');
        });
        $watch('sidebarCollapsed', value => localStorage.setItem('sidebarCollapsed', value));
    "
>
    <x-flash-banner />

    <div class="relative z-10 flex min-h-full">
        <livewire:sidebar />

        <main class="min-w-0 flex-1 pb-24 lg:pb-0">
            <div class="w-full">
                {{ $slot }}
            </div>
        </main>
    </div>
    <script>
        window.alokasiFormatNumber = function (value) {
            const digits = String(value || '').replace(/\D/g, '');
            const normalizedDigits = digits.replace(/^0+(?=\d)/, '');

            return normalizedDigits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        };

        window.alokasiFormatNumberInput = function (input) {
            if (!input) {
                return;
            }

            const previousValue = input.value;
            const previousCaret = input.selectionStart ?? previousValue.length;
            const digitsBeforeCaret = previousValue.slice(0, previousCaret).replace(/\D/g, '').length;
            const formattedValue = window.alokasiFormatNumber(previousValue);

            input.value = formattedValue;

            if (document.activeElement !== input || typeof input.setSelectionRange !== 'function') {
                return;
            }

            if (digitsBeforeCaret === 0) {
                input.setSelectionRange(0, 0);
                return;
            }

            let seenDigits = 0;
            let nextCaret = formattedValue.length;

            for (let index = 0; index < formattedValue.length; index += 1) {
                if (/\d/.test(formattedValue[index])) {
                    seenDigits += 1;
                }

                if (seenDigits >= digitsBeforeCaret) {
                    nextCaret = index + 1;
                    break;
                }
            }

            input.setSelectionRange(nextCaret, nextCaret);
        };

        document.addEventListener('input', function (event) {
            if (!(event.target instanceof HTMLInputElement) || !event.target.matches('[data-number-format="live"]')) {
                return;
            }

            window.alokasiFormatNumberInput(event.target);
        }, true);

        document.addEventListener('blur', function (event) {
            if (!(event.target instanceof HTMLInputElement) || !event.target.matches('[data-number-format="live"]')) {
                return;
            }

            window.alokasiFormatNumberInput(event.target);
        }, true);

        window.alokasiFormatNumberInputs = function () {
            document.querySelectorAll('input[data-number-format="live"]').forEach((input) => {
                window.alokasiFormatNumberInput(input);
            });
        };

        window.alokasiOpenDropdowns = window.alokasiOpenDropdowns || new Set();
        window.alokasiScrollLock = window.alokasiScrollLock || {
            syncPending: false,
        };

        window.alokasiScheduleScrollLockSync = function () {
            if (window.alokasiScrollLock.syncPending) {
                return;
            }

            window.alokasiScrollLock.syncPending = true;
            requestAnimationFrame(() => {
                window.alokasiScrollLock.syncPending = false;
                window.alokasiSyncModalScrollLock();
            });
        };

        window.alokasiQueueModalScrollLockSync = function (delays = [0, 80, 180, 360]) {
            delays.forEach((delay) => {
                setTimeout(() => window.alokasiSyncModalScrollLock?.(), delay);
            });
        };

        window.alokasiReleaseInteractionLocks = function () {
            window.alokasiOpenDropdowns.clear();
            window.alokasiTooltip?.hide();
            window.alokasiQueueModalScrollLockSync([0, 60, 140, 260, 420]);
        };

        window.alokasiPreventDropdownBackgroundScroll = function (event) {
            if (window.alokasiOpenDropdowns.size === 0) {
                return;
            }

            if (event.target?.closest?.('.floating-select-menu')) {
                return;
            }

            event.preventDefault();
        };

        window.alokasiSyncModalScrollLock = function () {
            const hasOpenModal = Array.from(document.querySelectorAll('.modal-backdrop')).some((modal) => {
                const style = window.getComputedStyle(modal);

                return style.display !== 'none' && style.visibility !== 'hidden';
            });

            document.body.classList.toggle('modal-open', hasOpenModal);
        };

        document.addEventListener('DOMContentLoaded', window.alokasiFormatNumberInputs);
        document.addEventListener('livewire:navigated', window.alokasiFormatNumberInputs);
        document.addEventListener('DOMContentLoaded', () => {
            window.alokasiSyncModalScrollLock();
            document.addEventListener('wheel', window.alokasiPreventDropdownBackgroundScroll, { passive: false });
            document.addEventListener('touchmove', window.alokasiPreventDropdownBackgroundScroll, { passive: false });

            const modalObserver = new MutationObserver((mutations) => {
                if (mutations.every((mutation) => mutation.target === document.body)) {
                    return;
                }

                window.alokasiScheduleScrollLockSync();
            });
            modalObserver.observe(document.body, {
                attributes: true,
                attributeFilter: ['class', 'style'],
                childList: true,
                subtree: true,
            });
        });
        document.addEventListener('livewire:navigated', () => {
            window.alokasiOpenDropdowns.clear();
            window.alokasiSyncModalScrollLock();
        });
        document.addEventListener('saved', () => window.alokasiReleaseInteractionLocks());
        document.addEventListener('budget-created', () => window.alokasiReleaseInteractionLocks());
        window.addEventListener('saved', () => window.alokasiReleaseInteractionLocks());
        window.addEventListener('budget-created', () => window.alokasiReleaseInteractionLocks());

        window.alokasiBudgetPage = function (initialOnboardingStep = null) {
            return {
                createBudget: false,
                budgetMenu: alokasiDropdown(),
                budgetSettingsMenu: alokasiDropdown({ minWidth: 220, maxWidth: 260 }),
                allocationMenu: alokasiDropdown({ minWidth: 300, maxWidth: 380 }),
                investmentMenu: alokasiDropdown({ minWidth: 336, maxWidth: 420 }),
                createExpense: false,
                renameBudget: false,
                editIncome: false,
                deleteBudget: false,
                onboardingStep: initialOnboardingStep,
                tour: {
                    visible: false,
                    style: '',
                    spotlight: { x: 0, y: 0, width: 0, height: 0, radius: 12, cx: 0, cy: 0, r: 0 },
                    blurStyle: { top: '', right: '', bottom: '', left: '' },
                },
                tourTarget: null,
                tourRetry: null,
                tourAdvanceTimer: null,
                onboardingReloading: false,
                onboardingStepObserver: null,
                tourInputDelay: 260,
                tourChoiceDelay: 180,
                tourSteps: {
                    'budgets-menu': {
                        kicker: 'Langkah 1',
                        title: 'Menu Budgets',
                        copy: 'Alokasi menyusun income dan expenses dari sini. Kita mulai dari halaman Budgets.',
                        action: 'Lanjut',
                        next: 'new-budget',
                    },
                    'new-budget': {
                        kicker: 'Langkah 2',
                        title: 'Buat budget baru',
                        copy: 'Klik tombol New Budget untuk membuat rencana income pertama.',
                    },
                    'budget-name': {
                        kicker: 'Budget',
                        title: 'Nama budget',
                        copy: 'Isi nama yang gampang dikenali, misalnya Juni 2026 atau Monthly Plan.',
                    },
                    'budget-income': {
                        kicker: 'Budget',
                        title: 'Total income',
                        copy: 'Masukkan income utama untuk budget ini. Angka akan dirapikan otomatis saat diketik.',
                    },
                    'budget-create': {
                        kicker: 'Budget',
                        title: 'Simpan budget',
                        copy: 'Klik Create untuk menyimpan budget dan lanjut ke expense pertama.',
                    },
                    'expense-button': {
                        kicker: 'Langkah 3',
                        title: 'Tambah expense',
                        copy: 'Klik New Expense supaya budget pertama punya transaksi awal.',
                    },
                    'expense-name': {
                        kicker: 'Expense',
                        title: 'Nama expense',
                        copy: 'Tulis transaksi singkat, misalnya Makan siang, Internet, atau Transport.',
                    },
                    'expense-amount': {
                        kicker: 'Expense',
                        title: 'Nominal expense',
                        copy: 'Masukkan jumlah transaksi. Nol di depan dibersihkan dan ribuan diformat otomatis.',
                    },
                    'expense-label': {
                        kicker: 'Expense',
                        title: 'Label',
                        copy: 'Label membantu membaca kategori pengeluaran di dashboard dan reports.',
                    },
                    'expense-platform': {
                        kicker: 'Expense',
                        title: 'Platform',
                        copy: 'Pilih sumber uang atau tempat pembayaran, seperti Cash, GoPay, Dana, atau rekening bank.',
                    },
                    'expense-status': {
                        kicker: 'Expense',
                        title: 'Status',
                        copy: 'Status menandai apakah uang belum dialokasi, sudah dialokasi, selesai, atau ditarik.',
                    },
                    'expense-create': {
                        kicker: 'Selesai',
                        title: 'Simpan expense',
                        copy: 'Klik Add Expense untuk menyimpan transaksi pertama.',
                    },
                    'dashboard-menu': {
                        kicker: 'Langkah terakhir',
                        title: 'Buka Dashboard',
                        copy: 'Klik menu Dashboard untuk melihat ringkasan dan grafik dari budget serta expense yang baru kamu buat.',
                    },
                },
                init() {
                    this.$watch('onboardingStep', () => this.updateTour());
                    this.observeServerOnboardingStep();

                    window.addEventListener('resize', () => this.updateTour());
                    window.addEventListener('scroll', () => this.positionTour(), true);
                    window.addEventListener('alokasi-dropdown-positioned', () => this.positionTour());

                    this.$nextTick(() => this.updateTour());
                },
                observeServerOnboardingStep() {
                    const sync = () => {
                        const step = this.$el.dataset.serverOnboardingStep || null;
                        this.syncServerOnboardingStep(step);
                    };

                    sync();

                    this.onboardingStepObserver = new MutationObserver(sync);
                    this.onboardingStepObserver.observe(this.$el, {
                        attributes: true,
                        attributeFilter: ['data-server-onboarding-step'],
                    });
                },
                syncServerOnboardingStep(step) {
                    if (!step || step === this.onboardingStep) {
                        return;
                    }

                    if (step === 'expense-button') {
                        this.startExpenseOnboarding();
                        return;
                    }

                    if (step === 'dashboard-menu') {
                        this.startDashboardOnboarding();
                        return;
                    }

                    this.setOnboardingStep(step);
                },
                currentTourStep() {
                    return this.tourSteps[this.onboardingStep] || null;
                },
                setOnboardingStep(step) {
                    this.clearQueuedOnboardingStep();
                    this.onboardingStep = step;
                    this.$nextTick(() => this.updateTour());
                },
                queueOnboardingStep(step, delay = this.tourInputDelay) {
                    if (!step || this.onboardingStep === step) {
                        return;
                    }

                    this.clearQueuedOnboardingStep();
                    this.tourAdvanceTimer = setTimeout(() => this.setOnboardingStep(step), delay);
                },
                clearQueuedOnboardingStep() {
                    clearTimeout(this.tourAdvanceTimer);
                    this.tourAdvanceTimer = null;
                },
                nextExistingStep(steps) {
                    return steps.find((step) => document.querySelector(`[data-onboarding-target="${step}"]`)) || null;
                },
                reloadFullPageForOnboarding() {
                    if (this.onboardingReloading) {
                        return;
                    }

                    this.onboardingReloading = true;
                    setTimeout(() => window.location.reload(), 120);
                },
                continueTour() {
                    const step = this.currentTourStep();

                    if (!step?.next) {
                        return;
                    }

                    this.setOnboardingStep(step.next);
                },
                updateTour() {
                    document.querySelectorAll('.onboarding-highlight-target').forEach((target) => {
                        target.classList.remove('onboarding-highlight-target');
                    });

                    const step = this.currentTourStep();

                    if (!step) {
                        this.tour.visible = false;
                        this.tourTarget = null;
                        this.clearQueuedOnboardingStep();
                        return;
                    }

                    const target = document.querySelector(`[data-onboarding-target="${this.onboardingStep}"]`);

                    if (!target) {
                        this.tour.visible = false;
                        this.tourTarget = null;
                        clearTimeout(this.tourRetry);
                        this.tourRetry = setTimeout(() => this.updateTour(), 120);
                        return;
                    }

                    const rect = target.getBoundingClientRect();
                    const style = window.getComputedStyle(target);
                    const isVisible = rect.width > 0 && rect.height > 0 && style.display !== 'none' && style.visibility !== 'hidden';

                    if (!isVisible) {
                        this.tour.visible = false;
                        this.tourTarget = null;
                        clearTimeout(this.tourRetry);
                        this.tourRetry = setTimeout(() => this.updateTour(), 120);
                        return;
                    }

                    clearTimeout(this.tourRetry);
                    this.tourTarget = target;
                    this.tour.visible = true;
                    target.classList.add('onboarding-highlight-target');
                    target.scrollIntoView({ block: 'center', inline: 'center', behavior: 'smooth' });

                    requestAnimationFrame(() => {
                        this.positionTour();
                        [120, 280, 520].forEach((delay) => {
                            setTimeout(() => this.positionTour(), delay);
                        });
                    });
                },
                positionTour() {
                    if (!this.tourTarget || !this.tour.visible) {
                        return;
                    }

                    const rect = this.tourTarget.getBoundingClientRect();
                    const dropdownSteps = ['expense-label', 'expense-platform', 'expense-status'];
                    const margin = 12;
                    const gap = 10;
                    const width = Math.min(320, window.innerWidth - (margin * 2));
                    const tooltip = document.querySelector('.onboarding-tour-tooltip');
                    const tooltipHeight = Math.min(
                        tooltip?.offsetHeight || 170,
                        window.innerHeight - (margin * 2)
                    );
                    const modalPanel = this.tourTarget.closest('.modal-panel');
                    const modalRect = modalPanel?.getBoundingClientRect();
                    const openMenu = Array.from(document.querySelectorAll('.floating-select-menu')).find((menu) => {
                        const menuStyle = window.getComputedStyle(menu);
                        const menuRect = menu.getBoundingClientRect();

                        return menuStyle.display !== 'none' && menuStyle.visibility !== 'hidden' && menuRect.width > 0 && menuRect.height > 0;
                    });
                    const menuRect = openMenu?.getBoundingClientRect();
                    const hasRoomAbove = rect.top >= tooltipHeight + gap + margin;
                    const shouldPreferAbove = dropdownSteps.includes(this.onboardingStep);
                    const menuOpensAboveTarget = menuRect && menuRect.bottom <= rect.top + 4;
                    const maxTooltipTop = Math.max(margin, window.innerHeight - tooltipHeight - margin);
                    const sideTooltipTop = Math.min(
                        Math.max(margin, rect.top + (rect.height / 2) - (tooltipHeight / 2)),
                        maxTooltipTop
                    );
                    const rightSideLeft = modalRect ? modalRect.right + gap : 0;
                    const leftSideLeft = modalRect ? modalRect.left - width - gap : 0;
                    const hasRightSideRoom = Boolean(modalRect && rightSideLeft + width <= window.innerWidth - margin);
                    const hasLeftSideRoom = Boolean(modalRect && leftSideLeft >= margin);
                    let left = Math.min(
                        Math.max(margin, rect.left + (rect.width / 2) - (width / 2)),
                        window.innerWidth - width - margin
                    );
                    let top;

                    if (shouldPreferAbove && modalRect && (hasRightSideRoom || hasLeftSideRoom)) {
                        left = hasRightSideRoom ? rightSideLeft : leftSideLeft;
                        top = sideTooltipTop;
                    } else {
                        const opensUp = shouldPreferAbove && menuRect
                            ? !menuOpensAboveTarget && hasRoomAbove
                            : shouldPreferAbove
                                ? hasRoomAbove
                            : rect.bottom + tooltipHeight + gap + margin > window.innerHeight && hasRoomAbove;
                        top = opensUp
                            ? Math.max(margin, rect.top - tooltipHeight - gap)
                            : Math.min(
                                Math.max(margin, rect.bottom + gap),
                                maxTooltipTop
                            );
                    }

                    const sidebarPanel = this.onboardingStep === 'dashboard-menu' ? this.tourTarget.closest('nav') : null;
                    const rawFocusRect = (modalPanel || sidebarPanel)?.getBoundingClientRect();
                    const focusRect = sidebarPanel && rawFocusRect
                        ? (
                            rawFocusRect.top > window.innerHeight / 2
                                ? { left: 0, top: rawFocusRect.top, width: window.innerWidth, height: window.innerHeight - rawFocusRect.top }
                                : { left: 0, top: 0, width: rawFocusRect.right, height: window.innerHeight }
                        )
                        : rawFocusRect;
                    const spotlightPadding = shouldPreferAbove ? 6 : 8;
                    const focusPadding = sidebarPanel ? 0 : (focusRect ? 10 : spotlightPadding);
                    const sourceRect = focusRect || rect;
                    const spotlightX = sidebarPanel ? sourceRect.left : Math.max(margin, sourceRect.left - focusPadding);
                    const spotlightY = sidebarPanel ? sourceRect.top : Math.max(margin, sourceRect.top - focusPadding);
                    const spotlightWidth = sidebarPanel
                        ? sourceRect.width
                        : Math.min(
                            window.innerWidth - (margin * 2),
                            sourceRect.width + (focusPadding * 2)
                        );
                    const baseSpotlightHeight = Math.min(
                        sidebarPanel ? window.innerHeight : window.innerHeight - (margin * 2),
                        sourceRect.height + (focusPadding * 2)
                    );
                    const spotlightHeight = !focusRect && shouldPreferAbove
                        ? Math.min(
                            window.innerHeight - spotlightY - margin,
                            Math.max(baseSpotlightHeight, rect.height + 220)
                        )
                        : baseSpotlightHeight;

                    this.tour.style = `width:${width}px;left:${Math.round(left)}px;top:${Math.round(top)}px;`;
                    this.tour.spotlight = {
                        x: Math.round(spotlightX),
                        y: Math.round(spotlightY),
                        width: Math.round(spotlightWidth),
                        height: Math.round(spotlightHeight),
                        radius: modalPanel ? 22 : (sidebarPanel ? 18 : (shouldPreferAbove ? 10 : 14)),
                        cx: Math.round(sourceRect.left + (sourceRect.width / 2)),
                        cy: Math.round(sourceRect.top + (sourceRect.height / 2)),
                        r: Math.round(Math.max(window.innerWidth, window.innerHeight) * 0.82),
                    };

                    const spotlightRight = Math.round(spotlightX + spotlightWidth);
                    const spotlightBottom = Math.round(spotlightY + spotlightHeight);

                    this.tour.blurStyle = {
                        top: `left:0;top:0;width:${window.innerWidth}px;height:${Math.round(spotlightY)}px;`,
                        left: `left:0;top:${Math.round(spotlightY)}px;width:${Math.round(spotlightX)}px;height:${Math.round(spotlightHeight)}px;`,
                        right: `left:${spotlightRight}px;top:${Math.round(spotlightY)}px;width:${Math.max(0, window.innerWidth - spotlightRight)}px;height:${Math.round(spotlightHeight)}px;`,
                        bottom: `left:0;top:${spotlightBottom}px;width:${window.innerWidth}px;height:${Math.max(0, window.innerHeight - spotlightBottom)}px;`,
                    };
                },
                openBudgetModalFromTour() {
                    this.createBudget = true;

                    if (this.onboardingStep === 'new-budget') {
                        this.setOnboardingStep('budget-name');
                    }
                },
                openExpenseModalFromTour() {
                    this.createExpense = true;

                    if (this.onboardingStep === 'expense-button') {
                        this.startExpenseFormTour();
                    }
                },
                startExpenseOnboarding() {
                    this.createBudget = false;
                    this.createExpense = false;
                    this.showExpenseButtonTour();
                },
                startExpenseFormTour() {
                    this.createExpense = true;
                    this.$nextTick(() => {
                        setTimeout(() => this.setOnboardingStep('expense-name'), 80);
                    });
                },
                showExpenseButtonTour() {
                    this.setOnboardingStep('expense-button');
                    this.$nextTick(() => {
                        setTimeout(() => {
                            document.getElementById('expenses')?.scrollIntoView({ block: 'start', behavior: 'smooth' });
                            this.updateTour();
                        }, 180);
                    });
                },
                startDashboardOnboarding() {
                    this.createExpense = false;
                    this.setOnboardingStep('dashboard-menu');
                },
                advanceBudgetName(value) {
                    if (this.onboardingStep === 'budget-name' && value.trim().length > 0) {
                        this.queueOnboardingStep('budget-income');
                    } else if (this.onboardingStep === 'budget-name') {
                        this.clearQueuedOnboardingStep();
                    }
                },
                advanceBudgetIncome(value) {
                    const amount = String(value || '').replace(/\D/g, '');

                    if (this.onboardingStep === 'budget-income' && Number(amount) > 0) {
                        this.queueOnboardingStep('budget-create');
                    } else if (this.onboardingStep === 'budget-income') {
                        this.clearQueuedOnboardingStep();
                    }
                },
                advanceExpenseName(value) {
                    if (this.onboardingStep === 'expense-name' && value.trim().length > 0) {
                        this.queueOnboardingStep('expense-amount');
                    } else if (this.onboardingStep === 'expense-name') {
                        this.clearQueuedOnboardingStep();
                    }
                },
                advanceExpenseAmount(value) {
                    const amount = String(value || '').replace(/\D/g, '');

                    if (this.onboardingStep === 'expense-amount' && Number(amount) > 0) {
                        this.queueOnboardingStep(this.nextExistingStep(['expense-label', 'expense-platform']));
                    } else if (this.onboardingStep === 'expense-amount') {
                        this.clearQueuedOnboardingStep();
                    }
                },
                advanceExpenseChoice(currentStep, nextStep) {
                    if (this.onboardingStep === currentStep) {
                        this.queueOnboardingStep(nextStep, this.tourChoiceDelay);
                    }
                },
                afterBudgetCreated() {
                    this.createBudget = false;
                    this.budgetMenu.close();
                    window.alokasiReleaseInteractionLocks?.();

                    if (['budget-name', 'budget-income', 'budget-create', 'new-budget'].includes(this.onboardingStep)) {
                        this.reloadFullPageForOnboarding();
                        return;
                    }

                    [120, 320, 560].forEach((delay) => {
                        setTimeout(() => {
                            if (this.$el.dataset.serverOnboardingStep === 'expense-button') {
                                this.startExpenseOnboarding();
                            }
                        }, delay);
                    });
                },
                afterExpenseSaved() {
                    this.createExpense = false;
                    window.alokasiReleaseInteractionLocks?.();

                    if (this.onboardingStep === 'expense-create') {
                        this.reloadFullPageForOnboarding();
                    }
                },
                skipOnboarding() {
                    this.setOnboardingStep(null);
                },
            };
        };

        window.alokasiDashboardPage = function (showWelcomeTour = false) {
            return {
                dateFilterOpen: false,
                welcomeTour: {
                    visible: showWelcomeTour,
                    style: '',
                    spotlight: { x: 0, y: 0, width: 0, height: 0, radius: 18, cx: 0, cy: 0, r: 0 },
                    blurStyle: { top: '', right: '', bottom: '', left: '' },
                },
                init() {
                    window.addEventListener('resize', () => this.updateWelcomeTour());
                    window.addEventListener('scroll', () => this.positionWelcomeTour(), true);

                    this.$nextTick(() => this.updateWelcomeTour());
                },
                updateWelcomeTour() {
                    document.querySelectorAll('.onboarding-highlight-target').forEach((target) => {
                        target.classList.remove('onboarding-highlight-target');
                    });

                    if (!this.welcomeTour.visible) {
                        return;
                    }

                    requestAnimationFrame(() => this.positionWelcomeTour());
                },
                positionWelcomeTour() {
                    if (!this.welcomeTour.visible) {
                        return;
                    }

                    const margin = 12;
                    const width = Math.min(340, window.innerWidth - (margin * 2));
                    const tooltip = document.querySelector('.dashboard-welcome-tooltip');
                    const tooltipHeight = Math.min(
                        tooltip?.offsetHeight || 180,
                        window.innerHeight - (margin * 2)
                    );
                    const left = Math.round((window.innerWidth - width) / 2);
                    const top = Math.round(Math.min(
                        Math.max(margin, (window.innerHeight - tooltipHeight) / 2),
                        window.innerHeight - tooltipHeight - margin
                    ));

                    this.welcomeTour.style = `width:${width}px;left:${left}px;top:${top}px;`;
                    this.welcomeTour.spotlight = {
                        x: 0,
                        y: 0,
                        width: 0,
                        height: 0,
                        radius: 0,
                        cx: Math.round(window.innerWidth / 2),
                        cy: Math.round(window.innerHeight / 2),
                        r: Math.round(Math.max(window.innerWidth, window.innerHeight) * 0.82),
                    };
                    this.welcomeTour.blurStyle = {
                        top: `left:0;top:0;width:${window.innerWidth}px;height:${window.innerHeight}px;`,
                        left: 'left:0;top:0;width:0;height:0;',
                        right: 'left:0;top:0;width:0;height:0;',
                        bottom: 'left:0;top:0;width:0;height:0;',
                    };
                },
                closeWelcomeTour() {
                    this.welcomeTour.visible = false;
                    document.querySelectorAll('.onboarding-highlight-target').forEach((target) => {
                        target.classList.remove('onboarding-highlight-target');
                    });
                },
            };
        };

        window.alokasiDropdown = function (options = {}) {
            return {
                lockId: `dropdown-${Math.random().toString(36).slice(2)}`,
                open: false,
                style: '',
                toggle(trigger, menu) {
                    this.open ? this.close() : this.show(trigger, menu);
                },
                show(trigger, menu) {
                    this.open = true;
                    window.alokasiOpenDropdowns.add(this.lockId);
                    window.alokasiSyncModalScrollLock?.();
                    requestAnimationFrame(() => {
                        this.position(trigger, menu);
                        window.alokasiSyncModalScrollLock?.();
                        requestAnimationFrame(() => {
                            if (this.open) {
                                this.position(trigger, menu);
                                window.alokasiSyncModalScrollLock?.();
                            }
                        });
                    });
                },
                close() {
                    this.open = false;
                    window.alokasiOpenDropdowns.delete(this.lockId);
                    window.alokasiSyncModalScrollLock?.();
                },
                position(trigger, menu) {
                    if (!trigger || !menu) {
                        return;
                    }

                    const rect = trigger.getBoundingClientRect();
                    const gap = 6;
                    const margin = 8;
                    const measuredHeight = menu.offsetHeight || menu.scrollHeight || menu.getBoundingClientRect().height || 240;
                    const menuHeight = Math.min(measuredHeight, window.innerHeight - (margin * 2));
                    const preferredWidth = Math.max(rect.width, options.minWidth || 0);
                    const menuWidth = Math.min(preferredWidth, options.maxWidth || preferredWidth, window.innerWidth - (margin * 2));
                    const left = Math.min(Math.max(margin, rect.left), window.innerWidth - menuWidth - margin);
                    const opensUp = window.innerHeight - rect.bottom < menuHeight && rect.top > window.innerHeight - rect.bottom;
                    const top = opensUp
                        ? Math.max(margin, rect.top - menuHeight - gap)
                        : Math.min(window.innerHeight - menuHeight - margin, rect.bottom + gap);

                    this.style = `top:${top}px;left:${left}px;width:${menuWidth}px;max-height:${menuHeight}px;`;
                    window.dispatchEvent(new CustomEvent('alokasi-dropdown-positioned'));
                },
            };
        };

        window.alokasiTooltip = {
            element: null,
            target: null,
            ensure() {
                if (this.element) {
                    return this.element;
                }

                const tooltip = document.createElement('div');
                tooltip.className = 'alokasi-ui-tooltip';
                tooltip.setAttribute('role', 'tooltip');
                document.body.appendChild(tooltip);
                this.element = tooltip;

                return tooltip;
            },
            labelFor(target) {
                return target?.dataset.tooltip || target?.getAttribute('aria-label') || '';
            },
            isTooltipTarget(target) {
                return target?.matches('[data-tooltip], .btn-icon[aria-label], .summary-menu-button[aria-label], [data-icon-tooltip][aria-label]');
            },
            show(target) {
                if (!target || target.disabled) {
                    return;
                }

                const label = this.labelFor(target);

                if (!label) {
                    return;
                }

                this.target = target;
                const tooltip = this.ensure();
                tooltip.textContent = label;
                tooltip.classList.add('is-visible');

                requestAnimationFrame(() => this.position());
            },
            hide(target = null) {
                if (target && target !== this.target) {
                    return;
                }

                this.target = null;
                this.element?.classList.remove('is-visible');
            },
            position() {
                if (!this.target || !this.element) {
                    return;
                }

                const rect = this.target.getBoundingClientRect();
                const tooltipRect = this.element.getBoundingClientRect();
                const margin = 8;
                const gap = 8;
                const topSpace = rect.top;
                const top = topSpace > tooltipRect.height + gap + margin
                    ? rect.top - tooltipRect.height - gap
                    : rect.bottom + gap;
                const left = Math.min(
                    Math.max(margin, rect.left + (rect.width / 2) - (tooltipRect.width / 2)),
                    window.innerWidth - tooltipRect.width - margin
                );

                this.element.style.transform = `translate(${Math.round(left)}px, ${Math.round(top)}px)`;
            },
        };

        document.addEventListener('mouseover', function (event) {
            const target = event.target.closest?.('[data-tooltip], .btn-icon[aria-label], .summary-menu-button[aria-label], [data-icon-tooltip][aria-label]');

            if (window.alokasiTooltip.isTooltipTarget(target)) {
                window.alokasiTooltip.show(target);
            }
        });

        document.addEventListener('mouseout', function (event) {
            const target = event.target.closest?.('[data-tooltip], .btn-icon[aria-label], .summary-menu-button[aria-label], [data-icon-tooltip][aria-label]');

            if (window.alokasiTooltip.isTooltipTarget(target) && !target.contains(event.relatedTarget)) {
                window.alokasiTooltip.hide(target);
            }
        });

        document.addEventListener('focusin', function (event) {
            const target = event.target.closest?.('[data-tooltip], .btn-icon[aria-label], .summary-menu-button[aria-label], [data-icon-tooltip][aria-label]');

            if (window.alokasiTooltip.isTooltipTarget(target)) {
                window.alokasiTooltip.show(target);
            }
        });

        document.addEventListener('focusout', function (event) {
            window.alokasiTooltip.hide(event.target);
        });

        document.addEventListener('scroll', function () {
            window.alokasiTooltip.position();
        }, true);

        window.addEventListener('resize', function () {
            window.alokasiTooltip.hide();
        });
    </script>
    @livewireScripts
</body>

</html>
