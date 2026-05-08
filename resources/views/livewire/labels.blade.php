<div class="min-w-0" x-data="{deleteLabel: false}">
    <header class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 bg-white px-6 py-4 text-sm dark:border-slate-800 dark:bg-slate-950 lg:px-8">
        <div>
            <div class="text-xs font-medium uppercase text-gray-400 dark:text-slate-500">Settings</div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-100">Labels</h1>
        </div>
    </header>

    <main class="space-y-6 px-6 py-6 lg:px-8">
        @unless ($schemaReady)
            <section class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:border-amber-900/60 dark:bg-amber-950/30 dark:text-amber-200">
                Label tables are not migrated yet. Run <span class="font-semibold">php artisan migrate --seed</span> to activate this menu.
            </section>
        @endunless

        <section class="rounded-lg border border-gray-200 bg-white px-4 py-4 dark:border-slate-800 dark:bg-slate-900">
            <form wire:submit="store" class="flex flex-wrap gap-2">
                <input
                    wire:model="name"
                    type="text"
                    placeholder="Jajan, elektronik, investasi"
                    @disabled(! $schemaReady)
                    class="min-w-0 flex-1 rounded-lg bg-gray-100 px-3 py-2 disabled:cursor-not-allowed disabled:opacity-50 dark:bg-slate-800 dark:text-slate-100"
                >
                <button type="submit" @disabled(! $schemaReady) class="rounded-lg bg-green-500 px-3 py-2 text-sm font-semibold text-white hover:bg-green-600 disabled:cursor-not-allowed disabled:opacity-50">
                    Add Label
                </button>
            </form>
            @error('name')
                <div class="mt-2 text-xs text-red-500">{{ $message }}</div>
            @enderror
        </section>

        <section class="overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-slate-800 dark:bg-slate-900">
            <div class="grid grid-cols-[1fr_120px_120px] bg-slate-800 px-4 py-2 text-sm font-semibold text-white dark:bg-slate-900">
                <div>Name</div>
                <div class="text-center">Spends</div>
                <div class="text-right">Action</div>
            </div>

            <div class="divide-y divide-gray-200 dark:divide-slate-800">
                @forelse ($labels as $label)
                    <div class="grid grid-cols-[1fr_120px_120px] items-center gap-3 px-4 py-3 text-sm">
                        <div>
                            @if ($editingLabelId === $label->id)
                                <input wire:model="editingName" type="text" class="w-full rounded-lg bg-gray-100 px-3 py-2 dark:bg-slate-800 dark:text-slate-100">
                                @error('editingName')
                                    <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                                @enderror
                            @else
                                <div class="font-semibold text-gray-900 dark:text-slate-100">{{ $label->name }}</div>
                            @endif
                        </div>
                        <div class="text-center text-gray-500 dark:text-slate-400">{{ $label->spends_count }}</div>
                        <div class="flex justify-end gap-2">
                            @if ($editingLabelId === $label->id)
                                <button type="button" wire:click="update" class="rounded-lg bg-green-500 px-3 py-2 text-xs font-semibold text-white hover:bg-green-600">Save</button>
                            @else
                                <button type="button" wire:click="startEditing({{ $label->id }})" class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Edit</button>
                            @endif
                            <button type="button" x-on:click="deleteLabel = true" wire:click="confirmDelete({{ $label->id }})" class="rounded-lg bg-red-500 px-3 py-2 text-xs font-semibold text-white hover:bg-red-600">Delete</button>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-8 text-center text-sm text-gray-500 dark:text-slate-400">No labels yet.</div>
                @endforelse
            </div>
        </section>
    </main>

    <div x-show="deleteLabel" x-cloak x-transition class="fixed inset-0 z-50 flex min-h-screen w-screen bg-black bg-opacity-10 backdrop-blur-sm dark:bg-black/40">
        <div x-on:click.away="deleteLabel = false" class="m-auto w-80 rounded-lg bg-white px-8 py-8 dark:bg-slate-900">
            <div class="text-lg font-semibold text-gray-900 dark:text-slate-100">Delete Label</div>
            <p class="mt-3 text-sm text-gray-500 dark:text-slate-400">
                Delete <span class="font-semibold text-gray-900 dark:text-slate-100">{{ $labelToDelete?->name }}</span>? Existing expenses will become unlabeled.
            </p>

            <div class="mt-6 flex justify-end gap-2">
                <button type="button" x-on:click="deleteLabel = false" class="rounded-lg px-3 py-2 text-sm font-semibold text-gray-500 hover:bg-gray-100 dark:text-slate-400 dark:hover:bg-slate-800">
                    Cancel
                </button>
                <button type="button" x-on:click="deleteLabel = false" wire:click="delete" class="rounded-lg bg-red-500 px-3 py-2 text-sm font-semibold text-white hover:bg-red-600">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>
