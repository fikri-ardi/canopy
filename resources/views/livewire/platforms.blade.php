<div class="min-w-0" x-data="{deletePlatform: false}">
    <header class="app-header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="eyebrow">Settings</div>
                <h1 class="page-title">Platforms</h1>
                <p class="page-subtitle">Manage payment sources, wallets, banks, and cash accounts for your expenses.</p>
            </div>
        </div>
    </header>

    <main class="space-y-6 px-4 py-5 sm:px-6 sm:py-6 lg:px-8">
        @unless ($schemaReady)
            <section class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 shadow-sm dark:border-amber-900/60 dark:bg-amber-950/30 dark:text-amber-200">
                Platform ownership is not migrated yet. Run <span class="font-semibold">php artisan migrate</span> to activate this menu.
            </section>
        @endunless

        <section class="panel px-4 py-4">
            <form wire:submit="store" class="flex flex-col gap-2 sm:flex-row">
                <div class="relative min-w-0 flex-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 12m18 0v6.75A2.25 2.25 0 0 1 18.75 21H5.25A2.25 2.25 0 0 1 3 18.75V12m18 0V8.25A2.25 2.25 0 0 0 18.75 6H5.25A2.25 2.25 0 0 0 3 8.25V12" />
                    </svg>
                    <input wire:model="name" type="text" placeholder="Cash, Jago, BNI, ShopeePay" @disabled(! $schemaReady) class="input-field pl-9">
                </div>
                <button type="submit" @disabled(! $schemaReady) class="btn-primary w-full sm:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Add Platform</span>
                </button>
            </form>
            @error('name')
                <div class="mt-2 text-xs text-red-500">{{ $message }}</div>
            @enderror
        </section>

        <section class="table-shell">
            <div class="grid min-w-[620px] grid-cols-[1fr_120px_140px] bg-gray-50 px-4 py-3 text-xs font-semibold uppercase text-gray-500 dark:bg-slate-950 dark:text-slate-400">
                <div>Name</div>
                <div class="text-center">Spends</div>
                <div class="text-right">Action</div>
            </div>

            <div class="min-w-[620px] divide-y divide-gray-100 dark:divide-slate-800">
                @forelse ($platforms as $platform)
                    <div class="grid grid-cols-[1fr_120px_140px] items-center gap-3 bg-white px-4 py-3 text-sm transition hover:bg-gray-50 dark:bg-slate-900 dark:hover:bg-slate-800/60">
                        <div class="min-w-0">
                            @if ($editingPlatformId === $platform->id)
                                <input wire:model="editingName" type="text" class="input-field">
                                @error('editingName')
                                    <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                @enderror
                            @else
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex size-8 shrink-0 items-center justify-center rounded-lg bg-green-50 text-green-600 dark:bg-green-500/10 dark:text-green-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 12m18 0v6.75A2.25 2.25 0 0 1 18.75 21H5.25A2.25 2.25 0 0 1 3 18.75V12" />
                                        </svg>
                                    </span>
                                    <div class="truncate font-semibold text-gray-950 dark:text-slate-50">{{ $platform->name }}</div>
                                </div>
                            @endif
                        </div>
                        <div class="text-center text-gray-500 dark:text-slate-400">{{ $platform->spends_count }}</div>
                        <div class="flex justify-end gap-2">
                            @if ($editingPlatformId === $platform->id)
                                <button type="button" wire:click="update" class="btn-primary px-3 py-1.5 text-xs">Save</button>
                            @else
                                <button type="button" wire:click="startEditing({{ $platform->id }})" class="btn-secondary px-3 py-1.5 text-xs">Edit</button>
                            @endif
                            <button type="button" x-on:click="deletePlatform = true" wire:click="confirmDelete({{ $platform->id }})" @disabled($platform->spends_count > 0) class="btn-danger px-3 py-1.5 text-xs disabled:cursor-not-allowed disabled:opacity-50">Delete</button>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-12 text-center text-sm">
                        <span class="icon-box mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 12m18 0v6.75A2.25 2.25 0 0 1 18.75 21H5.25A2.25 2.25 0 0 1 3 18.75V12" />
                            </svg>
                        </span>
                        <div class="mt-3 font-semibold text-gray-950 dark:text-slate-50">No platforms yet</div>
                        <p class="mt-1 text-gray-500 dark:text-slate-400">Create a platform before adding expenses.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </main>

    <div x-show="deletePlatform" x-cloak x-transition class="modal-backdrop">
        <div x-on:click.away="deletePlatform = false" class="modal-panel">
            <div class="text-lg font-semibold text-gray-950 dark:text-slate-50">Delete Platform</div>
            <p class="mt-3 text-sm text-gray-500 dark:text-slate-400">
                Delete <span class="font-semibold text-gray-950 dark:text-slate-50">{{ $platformToDelete?->name }}</span>? Only unused platforms can be deleted.
            </p>
            @error('deletePlatform')
                <div class="mt-3 text-xs text-red-500">{{ $message }}</div>
            @enderror

            <div class="mt-6 flex justify-end gap-2">
                <button type="button" x-on:click="deletePlatform = false" class="btn-secondary">Cancel</button>
                <button type="button" x-on:click="deletePlatform = false" wire:click="delete" class="btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>
