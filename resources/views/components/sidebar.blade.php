<nav class="fixed inset-x-0 bottom-0 z-40 border-t border-white/45 bg-white/[0.58] shadow-[0_-8px_24px_rgba(15,23,42,0.06)] backdrop-blur-2xl md:sticky md:top-0 md:h-screen md:w-56 md:shrink-0 md:border-r md:border-t-0 md:shadow-sm md:shadow-gray-200/35 dark:border-slate-800/60 dark:bg-slate-950/[0.48] dark:shadow-black/20">
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
                    <a href="{{ route('investment') }}" aria-label="Investment" class="flex min-w-20 flex-col items-center justify-center gap-1 rounded-lg px-2 py-2 text-[11px] font-semibold transition md:min-w-0 md:flex-row md:justify-start md:gap-2 md:px-3 md:py-2.5 md:text-sm{{ request()->routeIs('investment') ? ' bg-green-50 text-green-600 ring-1 ring-green-100 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20' : ' text-gray-600 hover:bg-green-50 hover:text-green-600 dark:text-slate-400 dark:hover:bg-green-500/10 dark:hover:text-green-300' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                        </svg>
                        <span class="truncate">Investment</span>
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
                    <a href="{{ route('settings') }}" aria-label="Settings" class="flex min-w-20 flex-col items-center justify-center gap-1 rounded-lg px-2 py-2 text-[11px] font-semibold transition md:min-w-0 md:flex-row md:justify-start md:gap-2 md:px-3 md:py-2.5 md:text-sm{{ request()->routeIs('settings') || request()->routeIs('settings.*') ? ' bg-green-50 text-green-600 ring-1 ring-green-100 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20' : ' text-gray-600 hover:bg-green-50 hover:text-green-600 dark:text-slate-400 dark:hover:bg-green-500/10 dark:hover:text-green-300' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.348.78.748.936.236.092.466.19.69.3.38.185.833.143 1.184-.099l.737-.51a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.51.737c-.242.35-.284.804-.099 1.184.11.224.208.454.3.69.156.4.512.678.936.748l.894.149c.542.09.94.56.94 1.11v1.093c0 .55-.398 1.02-.94 1.11l-.894.149c-.424.07-.78.348-.936.748a7.02 7.02 0 0 1-.3.69c-.185.38-.143.833.099 1.184l.51.737c.32.448.27 1.061-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.45.12l-.737-.51c-.35-.242-.804-.284-1.184-.099a7.02 7.02 0 0 1-.69.3c-.4.156-.678.512-.748.936l-.149.894c-.09.542-.56.94-1.11.94h-1.093c-.55 0-1.02-.398-1.11-.94l-.149-.894a1.125 1.125 0 0 0-.748-.936 7.02 7.02 0 0 1-.69-.3c-.38-.185-.833-.143-1.184.099l-.737.51a1.125 1.125 0 0 1-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.51-.737c.242-.35.284-.804.099-1.184a7.02 7.02 0 0 1-.3-.69 1.125 1.125 0 0 0-.936-.748l-.894-.149a1.125 1.125 0 0 1-.94-1.11v-1.093c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.78-.348.936-.748.092-.236.19-.466.3-.69.185-.38.143-.833-.099-1.184l-.51-.737a1.125 1.125 0 0 1 .12-1.45l.774-.773a1.125 1.125 0 0 1 1.45-.12l.737.51c.35.242.804.284 1.184.099.224-.11.454-.208.69-.3.4-.156.678-.512.748-.936l.149-.894Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <span class="truncate">Settings</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
