<div class="min-w-0" x-data="{ deleteAccount: false, budgetMode: 'all' }">
    <header class="app-header">
        <div class="page-header-layout">
            <div class="page-header-copy">
                <span class="page-hero-icon page-hero-icon-slate">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.348.78.748.936.236.092.466.19.69.3.38.185.833.143 1.184-.099l.737-.51a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.51.737c-.242.35-.284.804-.099 1.184.11.224.208.454.3.69.156.4.512.678.936.748l.894.149c.542.09.94.56.94 1.11v1.093c0 .55-.398 1.02-.94 1.11l-.894.149c-.424.07-.78.348-.936.748a7.02 7.02 0 0 1-.3.69c-.185.38-.143.833.099 1.184l.51.737c.32.448.27 1.061-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.45.12l-.737-.51c-.35-.242-.804-.284-1.184-.099a7.02 7.02 0 0 1-.69.3c-.4.156-.678.512-.748.936l-.149.894c-.09.542-.56.94-1.11.94h-1.093c-.55 0-1.02-.398-1.11-.94l-.149-.894a1.125 1.125 0 0 0-.748-.936 7.02 7.02 0 0 1-.69-.3c-.38-.185-.833-.143-1.184.099l-.737.51a1.125 1.125 0 0 1-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.51-.737c.242-.35.284-.804.099-1.184a7.02 7.02 0 0 1-.3-.69 1.125 1.125 0 0 0-.936-.748l-.894-.149a1.125 1.125 0 0 1-.94-1.11v-1.093c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.78-.348.936-.748.092-.236.19-.466.3-.69.185-.38.143-.833-.099-1.184l-.51-.737a1.125 1.125 0 0 1 .12-1.45l.774-.773a1.125 1.125 0 0 1 1.45-.12l.737.51c.35.242.804.284 1.184.099.224-.11.454-.208.69-.3.4-.156.678-.512.748-.936l.149-.894Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </span>

                <div class="min-w-0">
                    <div class="eyebrow">Account</div>
                    <h1 class="page-title">Settings</h1>
                    <p class="page-subtitle max-w-2xl">Profile, security, data portability, and account controls in one place.</p>
                </div>
            </div>
        </div>
    </header>

    <main class="grid gap-5 px-4 py-5 sm:px-6 sm:py-6 lg:grid-cols-[15rem_minmax(0,1fr)] lg:px-8">
        <aside class="lg:sticky lg:top-5 lg:self-start">
            <div class="panel p-2">
                @foreach ([
                    ['href' => '#profile', 'label' => 'Profile'],
                    ['href' => '#security', 'label' => 'Security'],
                    ['href' => '#data', 'label' => 'Export / Import'],
                    ['href' => '#delete-account', 'label' => 'Delete Account'],
                    ['href' => '#logout', 'label' => 'Logout'],
                ] as $item)
                    <a href="{{ $item['href'] }}" class="flex items-center justify-between rounded-md px-3 py-2 text-sm font-semibold text-gray-600 transition hover:bg-green-50 hover:text-green-600 dark:text-slate-300 dark:hover:bg-green-500/10 dark:hover:text-green-300">
                        <span>{{ $item['label'] }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4 text-gray-300 dark:text-slate-600">
                            <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endforeach
            </div>
        </aside>

        <div class="min-w-0 space-y-5">
            <section id="profile" class="panel scroll-mt-5 p-4 sm:p-5">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Profile</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Keep your account identity up to date.</p>
                    </div>
                </div>

                <form wire:submit="updateProfile" class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="settings-name" class="text-xs font-semibold uppercase text-gray-500 dark:text-slate-400">Name</label>
                        <input id="settings-name" wire:model="name" type="text" class="input-field mt-1">
                        @error('name')
                            <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="settings-email" class="text-xs font-semibold uppercase text-gray-500 dark:text-slate-400">Email</label>
                        <input id="settings-email" wire:model="email" type="email" class="input-field mt-1">
                        @error('email')
                            <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <button type="submit" class="btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Save Profile
                        </button>
                    </div>
                </form>
            </section>

            <section id="security" class="panel scroll-mt-5 p-4 sm:p-5">
                <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Security</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Change the password used for email sign in.</p>

                <form wire:submit="updatePassword" class="mt-4 grid gap-4 sm:grid-cols-3">
                    <div>
                        <label for="settings-current-password" class="text-xs font-semibold uppercase text-gray-500 dark:text-slate-400">Current Password</label>
                        <input id="settings-current-password" wire:model="currentPassword" type="password" class="input-field mt-1">
                        @error('currentPassword')
                            <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="settings-password" class="text-xs font-semibold uppercase text-gray-500 dark:text-slate-400">New Password</label>
                        <input id="settings-password" wire:model="password" type="password" class="input-field mt-1">
                        @error('password')
                            <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="settings-password-confirmation" class="text-xs font-semibold uppercase text-gray-500 dark:text-slate-400">Confirm Password</label>
                        <input id="settings-password-confirmation" wire:model="password_confirmation" type="password" class="input-field mt-1">
                    </div>

                    <div class="sm:col-span-3">
                        <button type="submit" class="btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0 1 19.5 12.75v6A2.25 2.25 0 0 1 17.25 21H6.75A2.25 2.25 0 0 1 4.5 18.75v-6A2.25 2.25 0 0 1 6.75 10.5Z" />
                            </svg>
                            Update Password
                        </button>
                    </div>
                </form>
            </section>

            <section id="data" class="grid scroll-mt-5 gap-5 xl:grid-cols-[minmax(0,1.6fr)_minmax(18rem,0.9fr)]">
                <div class="panel p-4 sm:p-5">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Export Data</h2>
                            <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Download budgets, expenses, and related account data. Excel and spreadsheet formats include every backup sheet.</p>
                        </div>
                        <span class="inline-flex w-fit items-center rounded-md bg-green-50 px-2.5 py-1 text-xs font-bold uppercase text-green-600 ring-1 ring-green-100 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20">Ready</span>
                    </div>

                    <form method="GET" action="{{ route('settings.export.budgets') }}" class="mt-5 space-y-5">
                        <div class="grid gap-3 sm:grid-cols-3">
                            @foreach ([
                                ['value' => 'csv', 'label' => 'CSV', 'note' => 'Expenses rows'],
                                ['value' => 'xlsx', 'label' => 'Excel', 'note' => 'Full backup'],
                                ['value' => 'ods', 'label' => 'Spreadsheet', 'note' => 'Full backup'],
                            ] as $format)
                                <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 bg-white/70 p-3 text-sm shadow-sm transition hover:border-green-200 hover:bg-green-50/50 dark:border-slate-700 dark:bg-slate-900/70 dark:hover:border-green-500/30 dark:hover:bg-green-500/10">
                                    <input type="radio" name="format" value="{{ $format['value'] }}" @checked($format['value'] === 'xlsx') class="size-4 border-gray-300 text-green-500 focus:ring-green-400">
                                    <span class="min-w-0">
                                        <span class="block font-bold text-gray-950 dark:text-slate-50">{{ $format['label'] }}</span>
                                        <span class="block text-xs text-gray-500 dark:text-slate-400">{{ $format['note'] }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('format')
                            <div class="text-xs text-red-500">{{ $message }}</div>
                        @enderror

                        <div class="grid gap-3 sm:grid-cols-2">
                            <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 bg-white/70 p-3 text-sm dark:border-slate-700 dark:bg-slate-900/70">
                                <input type="radio" name="budget_mode" value="all" x-model="budgetMode" class="size-4 border-gray-300 text-green-500 focus:ring-green-400">
                                <span>
                                    <span class="block font-bold text-gray-950 dark:text-slate-50">All Budgets</span>
                                    <span class="block text-xs text-gray-500 dark:text-slate-400">{{ $budgets->count() }} budgets available</span>
                                </span>
                            </label>
                            <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 bg-white/70 p-3 text-sm dark:border-slate-700 dark:bg-slate-900/70">
                                <input type="radio" name="budget_mode" value="selected" x-model="budgetMode" class="size-4 border-gray-300 text-green-500 focus:ring-green-400">
                                <span>
                                    <span class="block font-bold text-gray-950 dark:text-slate-50">Selected Budgets</span>
                                    <span class="block text-xs text-gray-500 dark:text-slate-400">Pick one or more budgets</span>
                                </span>
                            </label>
                        </div>

                        <div class="rounded-lg border border-gray-200 bg-gray-50/70 p-3 dark:border-slate-800 dark:bg-slate-950/40">
                            <div class="grid gap-2 sm:grid-cols-2 xl:grid-cols-3">
                                @forelse ($budgets as $budget)
                                    <label class="flex min-w-0 items-center gap-2 rounded-md bg-white px-3 py-2 text-sm ring-1 ring-gray-200 transition dark:bg-slate-900 dark:ring-slate-700" x-bind:class="budgetMode === 'all' ? 'opacity-55' : ''">
                                        <input type="checkbox" name="budgets[]" value="{{ $budget->id }}" x-bind:disabled="budgetMode === 'all'" class="size-4 rounded border-gray-300 text-green-500 focus:ring-green-400">
                                        <span class="min-w-0 flex-1 truncate font-semibold text-gray-700 dark:text-slate-200">{{ $budget->name }}</span>
                                        <span class="shrink-0 text-xs text-gray-400 dark:text-slate-500">{{ $budget->spends_count }}</span>
                                    </label>
                                @empty
                                    <div class="rounded-md border border-dashed border-gray-200 px-3 py-6 text-center text-sm text-gray-500 dark:border-slate-700 dark:text-slate-400 sm:col-span-2 xl:col-span-3">No budgets yet.</div>
                                @endforelse
                            </div>
                        </div>
                        @error('budgets')
                            <div class="text-xs text-red-500">{{ $message }}</div>
                        @enderror
                        @error('budgets.*')
                            <div class="text-xs text-red-500">{{ $message }}</div>
                        @enderror

                        <button type="submit" @disabled($budgets->isEmpty()) class="btn-primary w-full disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M7.5 10.5 12 15m0 0 4.5-4.5M12 15V3" />
                            </svg>
                            Export Data
                        </button>
                    </form>
                </div>

                <div class="panel p-4 sm:p-5">
                    <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Import Data</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Upload a Canopy export file to restore or merge budget and expense rows.</p>

                    <form wire:submit="importData" class="mt-5 rounded-lg border border-dashed border-gray-300 bg-gray-50/70 p-4 dark:border-slate-700 dark:bg-slate-950/40">
                        <label for="settings-import-file" class="text-xs font-semibold uppercase text-gray-500 dark:text-slate-400">File</label>
                        <input id="settings-import-file" wire:model="importFile" type="file" accept=".csv,.txt,.xlsx,.ods" class="input-field mt-1">
                        @error('importFile')
                            <div class="mt-2 text-xs text-red-500">{{ $message }}</div>
                        @enderror

                        <button type="submit" wire:loading.attr="disabled" wire:target="importFile,importData" class="btn-secondary mt-3 w-full disabled:cursor-not-allowed disabled:opacity-60">
                            <span wire:loading.remove wire:target="importData">Import Data</span>
                            <span wire:loading wire:target="importData">Importing...</span>
                        </button>
                    </form>
                </div>
            </section>

            <section id="delete-account" class="panel scroll-mt-5 border-red-200/80 p-4 dark:border-red-500/20 sm:p-5">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-bold text-red-600 dark:text-red-300">Delete Account</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Remove your account and all account-owned Canopy data.</p>
                    </div>
                    <button type="button" x-on:click="deleteAccount = true" class="btn-danger w-full sm:w-auto">
                        Delete Account
                    </button>
                </div>
            </section>

            <section id="logout" class="panel scroll-mt-5 p-4 sm:p-5">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Logout</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">End this session and return to the login screen.</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="btn-secondary w-full border-red-200 text-red-600 hover:bg-red-50 hover:text-red-700 dark:border-red-500/20 dark:text-red-300 dark:hover:bg-red-500/10 sm:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </main>

    <div x-show="deleteAccount" x-cloak x-transition class="modal-backdrop">
        <div x-on:click.away="deleteAccount = false" class="modal-panel">
            <div class="text-lg font-semibold text-gray-950 dark:text-slate-50">Delete Account</div>
            <p class="mt-3 text-sm text-gray-500 dark:text-slate-400">This removes your account, budgets, expenses, labels, platforms, and statuses.</p>

            <div class="mt-4">
                <label for="delete-account-password" class="text-xs font-semibold uppercase text-gray-500 dark:text-slate-400">Password</label>
                <input id="delete-account-password" wire:model="deletePassword" type="password" class="input-field mt-1">
                @error('deletePassword')
                    <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button type="button" x-on:click="deleteAccount = false" class="btn-secondary">Cancel</button>
                <button type="button" wire:click="deleteAccount" class="btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>
