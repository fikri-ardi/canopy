<nav class="fixed inset-x-0 bottom-0 z-40 border-t border-gray-200 bg-white/95 shadow-[0_-8px_24px_rgba(15,23,42,0.08)] backdrop-blur md:sticky md:top-0 md:h-screen md:w-56 md:shrink-0 md:border-r md:border-t-0 md:shadow-sm md:shadow-gray-200/60 dark:border-slate-800 dark:bg-slate-950/95 dark:shadow-black/20">
    <div class="mx-auto h-16 max-w-7xl px-2 md:h-screen md:px-4">
        <div class="h-full md:flex md:flex-col">
            <a href="/" class="hidden items-center justify-center py-5 md:flex md:justify-start md:space-x-2">
                <span class="inline-flex size-10 shrink-0 items-center justify-center rounded-lg bg-green-50 text-green-500 ring-1 ring-green-100 dark:bg-green-500/10 dark:ring-green-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-7">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM9 7.5A.75.75 0 0 0 9 9h1.5c.98 0 1.813.626 2.122 1.5H9A.75.75 0 0 0 9 12h3.622a2.251 2.251 0 0 1-2.122 1.5H9a.75.75 0 0 0-.53 1.28l3 3a.75.75 0 1 0 1.06-1.06L10.8 14.988A3.752 3.752 0 0 0 14.175 12H15a.75.75 0 0 0 0-1.5h-.825A3.733 3.733 0 0 0 13.5 9H15a.75.75 0 0 0 0-1.5H9Z" clip-rule="evenodd" />
                    </svg>
                </span>
                <span class="hidden text-xl font-bold text-gray-950 md:inline dark:text-slate-50">Implants</span>
            </a>

            <div class="flex h-full items-center md:block md:h-auto md:py-4">
                <div class="mb-3 hidden px-3 text-xs font-semibold uppercase text-gray-400 md:block dark:text-slate-600">Menu</div>
                <div class="grid w-full grid-cols-6 gap-1 md:flex md:flex-col md:space-y-1">
                    <a href="/" aria-label="Dashboard" class="flex min-w-0 flex-col items-center justify-center gap-1 rounded-lg px-2 py-2 text-[11px] font-semibold transition md:flex-row md:justify-start md:gap-2 md:px-3 md:py-2.5 md:text-sm{{ request()->segment(1) == '' ? ' bg-green-50 text-green-600 ring-1 ring-green-100 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20' : ' text-gray-600 hover:bg-green-50 hover:text-green-600 dark:text-slate-400 dark:hover:bg-green-500/10 dark:hover:text-green-300' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                        </svg>
                        <span class="truncate">Dashboard</span>
                    </a>
                    <a href="/budgets" aria-label="Budgets" class="flex min-w-0 flex-col items-center justify-center gap-1 rounded-lg px-2 py-2 text-[11px] font-semibold transition md:flex-row md:justify-start md:gap-2 md:px-3 md:py-2.5 md:text-sm{{ request()->segment(1) == 'budgets' ? ' bg-green-50 text-green-600 ring-1 ring-green-100 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20' : ' text-gray-600 hover:bg-green-50 hover:text-green-600 dark:text-slate-400 dark:hover:bg-green-500/10 dark:hover:text-green-300' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                        </svg>
                        <span class="truncate">Budgets</span>
                    </a>
                    <a href="/labels" aria-label="Labels" class="flex min-w-0 flex-col items-center justify-center gap-1 rounded-lg px-2 py-2 text-[11px] font-semibold transition md:flex-row md:justify-start md:gap-2 md:px-3 md:py-2.5 md:text-sm{{ request()->segment(1) == 'labels' ? ' bg-green-50 text-green-600 ring-1 ring-green-100 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20' : ' text-gray-600 hover:bg-green-50 hover:text-green-600 dark:text-slate-400 dark:hover:bg-green-500/10 dark:hover:text-green-300' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                        </svg>
                        <span class="truncate">Labels</span>
                    </a>
                    <a href="{{ route('spends') }}" aria-label="Spends" class="flex min-w-0 flex-col items-center justify-center gap-1 rounded-lg px-2 py-2 text-[11px] font-semibold transition md:flex-row md:justify-start md:gap-2 md:px-3 md:py-2.5 md:text-sm{{ request()->routeIs('spends') ? ' bg-green-50 text-green-600 ring-1 ring-green-100 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20' : ' text-gray-600 hover:bg-green-50 hover:text-green-600 dark:text-slate-400 dark:hover:bg-green-500/10 dark:hover:text-green-300' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375" />
                        </svg>
                        <span class="truncate">Spends</span>
                    </a>
                    <a href="{{ route('reports') }}" aria-label="Reports" class="flex min-w-0 flex-col items-center justify-center gap-1 rounded-lg px-2 py-2 text-[11px] font-semibold transition md:flex-row md:justify-start md:gap-2 md:px-3 md:py-2.5 md:text-sm{{ request()->routeIs('reports') ? ' bg-green-50 text-green-600 ring-1 ring-green-100 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20' : ' text-gray-600 hover:bg-green-50 hover:text-green-600 dark:text-slate-400 dark:hover:bg-green-500/10 dark:hover:text-green-300' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v7.5m2.25-6.466a9.016 9.016 0 0 0-3.461-.203" />
                        </svg>
                        <span class="truncate">Reports</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="min-w-0">
                        @csrf
                        <button type="submit" aria-label="Logout" class="flex w-full min-w-0 flex-col items-center justify-center gap-1 rounded-lg px-2 py-2 text-[11px] font-semibold text-gray-600 transition hover:bg-red-50 hover:text-red-600 md:flex-row md:justify-start md:gap-2 md:px-3 md:py-2.5 md:text-sm dark:text-slate-400 dark:hover:bg-red-500/10 dark:hover:text-red-300">
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
