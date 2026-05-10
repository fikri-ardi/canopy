<nav class="fixed inset-x-0 bottom-0 z-40 border-t border-gray-200 bg-white/95 shadow-[0_-8px_24px_rgba(15,23,42,0.08)] backdrop-blur md:sticky md:top-0 md:h-screen md:w-56 md:shrink-0 md:border-r md:border-t-0 md:shadow-sm md:shadow-gray-200/60 dark:border-slate-800 dark:bg-slate-950/95 dark:shadow-black/20">
    <div class="mx-auto h-[4.75rem] max-w-7xl px-2 md:h-screen md:px-4">
        <div class="h-full md:flex md:flex-col">
            <a href="/" class="hidden items-center justify-center py-5 md:flex md:justify-start md:space-x-2">
                <span class="inline-flex size-10 shrink-0 items-center justify-center rounded-lg bg-green-50 text-green-500 ring-1 ring-green-100 dark:bg-green-500/10 dark:ring-green-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 19.5 12 4.5l8.25 15H3.75Zm4.5-6h7.5" />
                    </svg>
                </span>
                <span class="hidden text-xl font-bold text-gray-950 md:inline dark:text-slate-50">Canopy</span>
            </a>

            <div class="flex h-full items-center overflow-x-auto md:block md:h-auto md:overflow-visible md:py-4">
                <div class="mb-3 hidden px-3 text-xs font-semibold uppercase text-gray-400 md:block dark:text-slate-600">Menu</div>
                <div class="flex min-w-max gap-1 py-2 md:min-w-0 md:flex-col md:space-y-1 md:py-0">
                    <a href="/" aria-label="Dashboard" class="flex min-w-20 flex-col items-center justify-center gap-1 rounded-lg px-2 py-2 text-[11px] font-semibold transition md:min-w-0 md:flex-row md:justify-start md:gap-2 md:px-3 md:py-2.5 md:text-sm{{ request()->segment(1) == '' ? ' bg-green-50 text-green-600 ring-1 ring-green-100 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20' : ' text-gray-600 hover:bg-green-50 hover:text-green-600 dark:text-slate-400 dark:hover:bg-green-500/10 dark:hover:text-green-300' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5h6.75v6.75H3.75V13.5Zm9.75 0h6.75v6.75H13.5V13.5Zm0-9.75h6.75v6.75H13.5V3.75Zm-9.75 0h6.75v6.75H3.75V3.75Z" />
                        </svg>
                        <span class="truncate">Dashboard</span>
                    </a>
                    <a href="/budgets" aria-label="Budgets" class="flex min-w-20 flex-col items-center justify-center gap-1 rounded-lg px-2 py-2 text-[11px] font-semibold transition md:min-w-0 md:flex-row md:justify-start md:gap-2 md:px-3 md:py-2.5 md:text-sm{{ request()->segment(1) == 'budgets' ? ' bg-green-50 text-green-600 ring-1 ring-green-100 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20' : ' text-gray-600 hover:bg-green-50 hover:text-green-600 dark:text-slate-400 dark:hover:bg-green-500/10 dark:hover:text-green-300' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.75V18a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18V6a2.25 2.25 0 0 1 2.25-2.25h10.5L21 9v3.75Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 3.75V9H21M7.5 13.5h9M7.5 16.5h5.25" />
                        </svg>
                        <span class="truncate">Budgets</span>
                    </a>
                    <a href="{{ route('spends') }}" aria-label="Spends" class="flex min-w-20 flex-col items-center justify-center gap-1 rounded-lg px-2 py-2 text-[11px] font-semibold transition md:min-w-0 md:flex-row md:justify-start md:gap-2 md:px-3 md:py-2.5 md:text-sm{{ request()->routeIs('spends') ? ' bg-green-50 text-green-600 ring-1 ring-green-100 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20' : ' text-gray-600 hover:bg-green-50 hover:text-green-600 dark:text-slate-400 dark:hover:bg-green-500/10 dark:hover:text-green-300' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h7.5M8.25 10.5h7.5M8.25 14.25h3.75M6 21h12a2.25 2.25 0 0 0 2.25-2.25V5.25A2.25 2.25 0 0 0 18 3H6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 6 21Z" />
                        </svg>
                        <span class="truncate">Spends</span>
                    </a>
                    <a href="/labels" aria-label="Labels" class="flex min-w-20 flex-col items-center justify-center gap-1 rounded-lg px-2 py-2 text-[11px] font-semibold transition md:min-w-0 md:flex-row md:justify-start md:gap-2 md:px-3 md:py-2.5 md:text-sm{{ request()->segment(1) == 'labels' ? ' bg-green-50 text-green-600 ring-1 ring-green-100 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20' : ' text-gray-600 hover:bg-green-50 hover:text-green-600 dark:text-slate-400 dark:hover:bg-green-500/10 dark:hover:text-green-300' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.008v.008H6.75V6.75Z" />
                        </svg>
                        <span class="truncate">Label</span>
                    </a>
                    <a href="{{ route('platforms') }}" aria-label="Platforms" class="flex min-w-20 flex-col items-center justify-center gap-1 rounded-lg px-2 py-2 text-[11px] font-semibold transition md:min-w-0 md:flex-row md:justify-start md:gap-2 md:px-3 md:py-2.5 md:text-sm{{ request()->routeIs('platforms') ? ' bg-green-50 text-green-600 ring-1 ring-green-100 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20' : ' text-gray-600 hover:bg-green-50 hover:text-green-600 dark:text-slate-400 dark:hover:bg-green-500/10 dark:hover:text-green-300' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5m-18 0V6A2.25 2.25 0 0 1 6 3.75h12A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V8.25Zm3 6h4.5" />
                        </svg>
                        <span class="truncate">Platform</span>
                    </a>
                    <a href="{{ route('statuses') }}" aria-label="Statuses" class="flex min-w-20 flex-col items-center justify-center gap-1 rounded-lg px-2 py-2 text-[11px] font-semibold transition md:min-w-0 md:flex-row md:justify-start md:gap-2 md:px-3 md:py-2.5 md:text-sm{{ request()->routeIs('statuses') ? ' bg-green-50 text-green-600 ring-1 ring-green-100 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20' : ' text-gray-600 hover:bg-green-50 hover:text-green-600 dark:text-slate-400 dark:hover:bg-green-500/10 dark:hover:text-green-300' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M4.5 6.75h15M4.5 17.25h15M6.75 21h10.5A2.25 2.25 0 0 0 19.5 18.75V5.25A2.25 2.25 0 0 0 17.25 3H6.75A2.25 2.25 0 0 0 4.5 5.25v13.5A2.25 2.25 0 0 0 6.75 21Z" />
                        </svg>
                        <span class="truncate">Statuses</span>
                    </a>
                    <a href="{{ route('reports') }}" aria-label="Reports" class="flex min-w-20 flex-col items-center justify-center gap-1 rounded-lg px-2 py-2 text-[11px] font-semibold transition md:min-w-0 md:flex-row md:justify-start md:gap-2 md:px-3 md:py-2.5 md:text-sm{{ request()->routeIs('reports') ? ' bg-green-50 text-green-600 ring-1 ring-green-100 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20' : ' text-gray-600 hover:bg-green-50 hover:text-green-600 dark:text-slate-400 dark:hover:bg-green-500/10 dark:hover:text-green-300' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5V4.5m0 15h15M8.25 16.5v-5.25m4.5 5.25V8.25m4.5 8.25V6" />
                        </svg>
                        <span class="truncate">Reports</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="min-w-20 md:min-w-0">
                        @csrf
                        <button type="submit" aria-label="Logout" class="flex w-full flex-col items-center justify-center gap-1 rounded-lg px-2 py-2 text-[11px] font-semibold text-gray-600 transition hover:bg-red-50 hover:text-red-600 md:flex-row md:justify-start md:gap-2 md:px-3 md:py-2.5 md:text-sm dark:text-slate-400 dark:hover:bg-red-500/10 dark:hover:text-red-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                            <span class="truncate">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
