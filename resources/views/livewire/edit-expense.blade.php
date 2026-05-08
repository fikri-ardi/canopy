<tr x-data="{selectLabel: false, selectPlatform: false, selectStatus: false, deleteExpense: false}" class="text-gray-800 dark:text-slate-100">
    <td class="px-3 py-2 text-center">{{ $iteration }}</td>
    <td class="p-2">
        <input wire:model.blur="name" type="text" class="w-full min-w-0 rounded bg-transparent px-2 py-1 outline-none hover:bg-white dark:hover:bg-slate-800" />
        @error('name')
            <div class="px-2 text-xs text-red-500">{{ $message }}</div>
        @enderror
    </td>
    <td class="p-2">
        <input wire:model.blur="amount" type="text" inputmode="numeric" class="w-full min-w-0 rounded bg-transparent px-2 py-1 outline-none hover:bg-white dark:hover:bg-slate-800" />
        @error('amount')
            <div class="px-2 text-xs text-red-500">{{ $message }}</div>
        @enderror
    </td>
    <td class="p-2">
        @if ($labelsReady)
            <div class="relative">
                <button type="button" x-on:click="selectLabel = true" class="flex w-full items-center justify-between rounded-lg bg-white px-3 py-2 text-left shadow-sm ring-1 ring-gray-200 dark:bg-slate-800 dark:ring-slate-700">
                    <span class="truncate">{{ $spend->label?->name ?? 'Unlabeled' }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <div x-show="selectLabel" x-cloak x-on:click.away="selectLabel = false" x-transition class="absolute left-0 top-full z-50 mt-1 flex w-full flex-col overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-gray-200 dark:bg-slate-900 dark:ring-slate-700">
                    @foreach ($labels as $label)
                        <button type="button" x-on:click="selectLabel = false" wire:click="$set('label_id', {{ $label->id }})" class="w-full px-3 py-2 text-left text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">
                            {{ $label->name }}
                        </button>
                    @endforeach
                </div>
            </div>
        @else
            <span class="block rounded-lg bg-white px-3 py-2 text-gray-400 shadow-sm ring-1 ring-gray-200 dark:bg-slate-800 dark:ring-slate-700">Pending migration</span>
        @endif
    </td>
    <td class="p-2">
        <div class="relative">
            <button type="button" x-on:click="selectPlatform = true" class="flex w-full items-center justify-between rounded-lg bg-white px-3 py-2 text-left shadow-sm ring-1 ring-gray-200 dark:bg-slate-800 dark:ring-slate-700">
                <span class="truncate">{{ $spend->platform->name }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            <div x-show="selectPlatform" x-cloak x-on:click.away="selectPlatform = false" x-transition class="absolute left-0 top-full z-50 mt-1 flex w-full flex-col overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-gray-200 dark:bg-slate-900 dark:ring-slate-700">
                @foreach ($platforms as $platform)
                    <button type="button" x-on:click="selectPlatform = false" wire:click="$set('platform_id', {{ $platform->id }})" class="w-full px-3 py-2 text-left text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">
                        {{ $platform->name }}
                    </button>
                @endforeach
            </div>
        </div>
    </td>
    <td class="p-2">
        <div class="relative">
            <button type="button" x-on:click="selectStatus = true" class="flex w-full items-center justify-between rounded-lg bg-white px-3 py-2 text-left shadow-sm ring-1 ring-gray-200 dark:bg-slate-800 dark:ring-slate-700">
                <span class="truncate">{{ $spend->status->body }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            <div x-show="selectStatus" x-cloak x-on:click.away="selectStatus = false" x-transition class="absolute left-0 top-full z-50 mt-1 flex w-full flex-col overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-gray-200 dark:bg-slate-900 dark:ring-slate-700">
                @foreach ($statuses as $status)
                    <button type="button" x-on:click="selectStatus = false" wire:click="$set('status_id', {{ $status->id }})" class="w-full px-3 py-2 text-left text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">
                        {{ $status->body }}
                    </button>
                @endforeach
            </div>
        </div>
    </td>
    <td class="p-2 text-center">
        <button
            type="button"
            x-on:click="deleteExpense = true"
            class="inline-flex items-center justify-center rounded-lg bg-red-500 p-2 text-white hover:bg-red-600"
            aria-label="Delete expense"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a49.058 49.058 0 0 0-7.5 0" />
            </svg>
        </button>

        <template x-teleport="body">
            <div x-show="deleteExpense" x-cloak x-transition class="fixed inset-0 z-50 flex min-h-screen w-screen bg-black bg-opacity-10 backdrop-blur-sm dark:bg-black/40">
                <div x-on:click.away="deleteExpense = false" class="m-auto w-80 rounded-lg bg-white px-8 py-8 text-left dark:bg-slate-900">
                    <div class="text-lg font-semibold text-gray-900 dark:text-slate-100">Delete Expense</div>
                    <p class="mt-3 text-sm text-gray-500 dark:text-slate-400">
                        Delete <span class="font-semibold text-gray-900 dark:text-slate-100">{{ $name }}</span>? This action cannot be undone.
                    </p>

                    <div class="mt-6 flex justify-end gap-2">
                        <button type="button" x-on:click="deleteExpense = false" class="rounded-lg px-3 py-2 text-sm font-semibold text-gray-500 hover:bg-gray-100 dark:text-slate-400 dark:hover:bg-slate-800">
                            Cancel
                        </button>
                        <button type="button" x-on:click="deleteExpense = false" wire:click="delete" class="rounded-lg bg-red-500 px-3 py-2 text-sm font-semibold text-white hover:bg-red-600">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </td>
</tr>
