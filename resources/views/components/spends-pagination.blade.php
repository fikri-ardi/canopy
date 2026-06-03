@if ($paginator->hasPages())
    @php($pageName = $paginator->getPageName())
    @php($scrollIntoView = $scrollIntoViewJsSnippet ?? '')

    <nav role="navigation" aria-label="Spends pagination" class="panel flex flex-col gap-3 px-3 py-3 sm:flex-row sm:items-center sm:justify-between sm:px-4">
        <div class="text-xs font-semibold text-gray-500 dark:text-slate-400">
            Showing
            <span class="text-gray-950 dark:text-slate-100">{{ number_format($paginator->firstItem() ?? 0, 0, ',', '.') }}</span>
            to
            <span class="text-gray-950 dark:text-slate-100">{{ number_format($paginator->lastItem() ?? 0, 0, ',', '.') }}</span>
            of
            <span class="text-gray-950 dark:text-slate-100">{{ number_format($paginator->total(), 0, ',', '.') }}</span>
            expenses
        </div>

        <div class="flex items-center justify-between gap-2 sm:justify-end">
            <button
                type="button"
                @if ($paginator->onFirstPage()) disabled @else wire:click="previousPage('{{ $pageName }}')" x-on:click="{{ $scrollIntoView }}" @endif
                wire:loading.attr="disabled"
                class="btn-secondary min-w-0 px-2.5 disabled:cursor-not-allowed disabled:opacity-45"
                aria-label="Previous page"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
                <span class="hidden sm:inline">Previous</span>
            </button>

            <div class="flex max-w-[52vw] items-center gap-1 overflow-x-auto rounded-lg border border-gray-200 bg-white/70 p-1 shadow-sm dark:border-slate-800 dark:bg-slate-950/40 sm:max-w-none">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="inline-flex size-9 items-center justify-center rounded-md text-sm font-semibold text-gray-400 dark:text-slate-500">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="inline-flex size-9 items-center justify-center rounded-md bg-green-500 text-sm font-bold text-white shadow-sm shadow-green-500/25">{{ $page }}</span>
                            @else
                                <button
                                    type="button"
                                    wire:click="setPage({{ $page }}, '{{ $pageName }}')"
                                    x-on:click="{{ $scrollIntoView }}"
                                    wire:loading.attr="disabled"
                                    class="inline-flex size-9 items-center justify-center rounded-md text-sm font-semibold text-gray-600 transition hover:bg-green-50 hover:text-green-600 disabled:cursor-not-allowed disabled:opacity-45 dark:text-slate-300 dark:hover:bg-green-500/10 dark:hover:text-green-300"
                                    aria-label="Go to page {{ $page }}"
                                >
                                    {{ $page }}
                                </button>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            <button
                type="button"
                @if ($paginator->hasMorePages()) wire:click="nextPage('{{ $pageName }}')" x-on:click="{{ $scrollIntoView }}" @else disabled @endif
                wire:loading.attr="disabled"
                class="btn-secondary min-w-0 px-2.5 disabled:cursor-not-allowed disabled:opacity-45"
                aria-label="Next page"
            >
                <span class="hidden sm:inline">Next</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </button>
        </div>
    </nav>
@endif
