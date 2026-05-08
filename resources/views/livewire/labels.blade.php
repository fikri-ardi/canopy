<div class="min-w-0" x-data="{deleteLabel: false}">
    <header class="app-header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="eyebrow">Settings</div>
                <h1 class="page-title">Labels</h1>
                <p class="page-subtitle">Organize expenses into clean spending categories.</p>
            </div>
        </div>
    </header>

    <main class="space-y-6 px-4 py-5 sm:px-6 sm:py-6 lg:px-8">
        @unless ($schemaReady)
            <section class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 shadow-sm dark:border-amber-900/60 dark:bg-amber-950/30 dark:text-amber-200">
                Label tables are not migrated yet. Run <span class="font-semibold">php artisan migrate --seed</span> to activate this menu.
            </section>
        @endunless

        <section class="panel px-4 py-4">
            <form wire:submit="store" class="flex flex-col gap-2 sm:flex-row">
                <div class="relative min-w-0 flex-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                    </svg>
                    <input wire:model="name" type="text" placeholder="Jajan, elektronik, investasi" @disabled(! $schemaReady) class="input-field pl-9">
                </div>
                <button type="submit" @disabled(! $schemaReady) class="btn-primary w-full sm:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Add Label</span>
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
                @forelse ($labels as $label)
                    <div class="grid grid-cols-[1fr_120px_140px] items-center gap-3 bg-white px-4 py-3 text-sm transition hover:bg-gray-50 dark:bg-slate-900 dark:hover:bg-slate-800/60">
                        <div class="min-w-0">
                            @if ($editingLabelId === $label->id)
                                <input wire:model="editingName" type="text" class="input-field">
                                @error('editingName')
                                    <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                @enderror
                            @else
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex size-8 shrink-0 items-center justify-center rounded-lg bg-green-50 text-green-600 dark:bg-green-500/10 dark:text-green-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                        </svg>
                                    </span>
                                    <div class="truncate font-semibold text-gray-950 dark:text-slate-50">{{ $label->name }}</div>
                                </div>
                            @endif
                        </div>
                        <div class="text-center text-gray-500 dark:text-slate-400">{{ $label->spends_count }}</div>
                        <div class="flex justify-end gap-2">
                            @if ($editingLabelId === $label->id)
                                <button type="button" wire:click="update" class="btn-primary px-3 py-1.5 text-xs">Save</button>
                            @else
                                <button type="button" wire:click="startEditing({{ $label->id }})" class="btn-secondary px-3 py-1.5 text-xs">Edit</button>
                            @endif
                            <button type="button" x-on:click="deleteLabel = true" wire:click="confirmDelete({{ $label->id }})" class="btn-danger px-3 py-1.5 text-xs">Delete</button>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-12 text-center text-sm">
                        <span class="icon-box mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                            </svg>
                        </span>
                        <div class="mt-3 font-semibold text-gray-950 dark:text-slate-50">No labels yet</div>
                        <p class="mt-1 text-gray-500 dark:text-slate-400">Create labels to keep expenses easier to scan.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </main>

    <div x-show="deleteLabel" x-cloak x-transition class="modal-backdrop">
        <div x-on:click.away="deleteLabel = false" class="modal-panel">
            <div class="text-lg font-semibold text-gray-950 dark:text-slate-50">Delete Label</div>
            <p class="mt-3 text-sm text-gray-500 dark:text-slate-400">
                Delete <span class="font-semibold text-gray-950 dark:text-slate-50">{{ $labelToDelete?->name }}</span>? Existing expenses will become unlabeled.
            </p>

            <div class="mt-6 flex justify-end gap-2">
                <button type="button" x-on:click="deleteLabel = false" class="btn-secondary">Cancel</button>
                <button type="button" x-on:click="deleteLabel = false" wire:click="delete" class="btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>
