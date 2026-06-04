<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="h-full dark"
>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @livewireStyles
    @vite('resources/css/app.css')
    <link rel="shortcut icon" href="/images/favicon.svg" type="image/svg+icon">
    <title>Canopy - Your Income Pal</title>
</head>

<body
    class="h-full font-sans"
    x-data="{ theme: localStorage.getItem('theme') || 'dark' }"
    x-init="
        document.documentElement.classList.toggle('dark', theme === 'dark');
        $watch('theme', value => {
            localStorage.setItem('theme', value);
            document.documentElement.classList.toggle('dark', value === 'dark');
        });
    "
>
    <x-flash-banner />

    <div class="relative z-10 flex min-h-full">
        <livewire:sidebar />

        <main class="min-w-0 flex-1 pb-24 md:pb-0">
            <div class="w-full">
                {{ $slot }}
            </div>
        </main>
    </div>
    <script>
        window.canopyFormatNumber = function (value) {
            const digits = String(value || '').replace(/\D/g, '');
            const normalizedDigits = digits.replace(/^0+(?=\d)/, '');

            return normalizedDigits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        };

        window.canopyFormatNumberInput = function (input) {
            if (!input) {
                return;
            }

            const previousValue = input.value;
            const previousCaret = input.selectionStart ?? previousValue.length;
            const digitsBeforeCaret = previousValue.slice(0, previousCaret).replace(/\D/g, '').length;
            const formattedValue = window.canopyFormatNumber(previousValue);

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

            window.canopyFormatNumberInput(event.target);
        }, true);

        document.addEventListener('blur', function (event) {
            if (!(event.target instanceof HTMLInputElement) || !event.target.matches('[data-number-format="live"]')) {
                return;
            }

            window.canopyFormatNumberInput(event.target);
        }, true);

        window.canopyFormatNumberInputs = function () {
            document.querySelectorAll('input[data-number-format="live"]').forEach((input) => {
                window.canopyFormatNumberInput(input);
            });
        };

        document.addEventListener('DOMContentLoaded', window.canopyFormatNumberInputs);
        document.addEventListener('livewire:navigated', window.canopyFormatNumberInputs);

        window.canopyBudgetPage = function (onboardingStep = null) {
            return {
                createBudget: false,
                budgetMenu: canopyDropdown(),
                allocationMenu: canopyDropdown({ minWidth: 300, maxWidth: 380 }),
                investmentMenu: canopyDropdown({ minWidth: 336, maxWidth: 420 }),
                createExpense: false,
                renameBudget: false,
                editIncome: false,
                deleteBudget: false,
                onboardingStep,
                init() {
                    if (this.onboardingStep === 'budget') {
                        this.$nextTick(() => {
                            this.createBudget = true;
                        });
                    } else if (this.onboardingStep === 'expense') {
                        this.$nextTick(() => {
                            document.getElementById('expenses')?.scrollIntoView({ block: 'start', behavior: 'smooth' });
                            this.createExpense = true;
                        });
                    }
                },
                afterBudgetCreated() {
                    this.createBudget = false;
                    this.budgetMenu.close();

                    if (this.onboardingStep === 'budget') {
                        this.onboardingStep = 'expense';
                        this.$nextTick(() => {
                            document.getElementById('expenses')?.scrollIntoView({ block: 'start', behavior: 'smooth' });
                            this.createExpense = true;
                        });
                    }
                },
                afterExpenseSaved() {
                    this.createExpense = false;

                    if (this.onboardingStep === 'expense') {
                        this.onboardingStep = null;
                    }
                },
            };
        };

        window.canopyDropdown = function (options = {}) {
            return {
                open: false,
                style: '',
                toggle(trigger, menu) {
                    this.open ? this.close() : this.show(trigger, menu);
                },
                show(trigger, menu) {
                    this.open = true;
                    requestAnimationFrame(() => {
                        this.position(trigger, menu);
                        requestAnimationFrame(() => {
                            if (this.open) {
                                this.position(trigger, menu);
                            }
                        });
                    });
                },
                close() {
                    this.open = false;
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
                },
            };
        };

        window.canopyTooltip = {
            element: null,
            target: null,
            ensure() {
                if (this.element) {
                    return this.element;
                }

                const tooltip = document.createElement('div');
                tooltip.className = 'canopy-ui-tooltip';
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

            if (window.canopyTooltip.isTooltipTarget(target)) {
                window.canopyTooltip.show(target);
            }
        });

        document.addEventListener('mouseout', function (event) {
            const target = event.target.closest?.('[data-tooltip], .btn-icon[aria-label], .summary-menu-button[aria-label], [data-icon-tooltip][aria-label]');

            if (window.canopyTooltip.isTooltipTarget(target) && !target.contains(event.relatedTarget)) {
                window.canopyTooltip.hide(target);
            }
        });

        document.addEventListener('focusin', function (event) {
            const target = event.target.closest?.('[data-tooltip], .btn-icon[aria-label], .summary-menu-button[aria-label], [data-icon-tooltip][aria-label]');

            if (window.canopyTooltip.isTooltipTarget(target)) {
                window.canopyTooltip.show(target);
            }
        });

        document.addEventListener('focusout', function (event) {
            window.canopyTooltip.hide(event.target);
        });

        document.addEventListener('scroll', function () {
            window.canopyTooltip.position();
        }, true);

        window.addEventListener('resize', function () {
            window.canopyTooltip.hide();
        });
    </script>
    @livewireScripts
</body>

</html>
