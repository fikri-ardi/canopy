<tr>
    <td class="px-3 py-2 text-center">{{ $iteration }}</td>
    <td class="p-2">
        <input wire:model.blur="name" type="text" class="w-full min-w-0 bg-transparent outline-none" />
    </td>
    <td class="p-2">
        <input wire:model.blur="amount" type="text" class="w-full min-w-0 bg-transparent outline-none" />
    </td>
    <td class="p-2">
        <input type="text" class="w-full min-w-0 bg-transparent outline-none" value="{{ $spend->platform->name }}"/>
    </td>
    <td class="p-2">
        <input type="text" class="w-full min-w-0 bg-transparent outline-none" value="{{ $spend->status->body }}"/>
    </td>
    <td class="p-2 text-center">
        <button
            type="button"
            wire:click="delete"
            wire:confirm="Delete this expense?"
            class="inline-flex items-center justify-center rounded-lg bg-red-500 p-2 text-white hover:bg-red-600"
            aria-label="Delete expense"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a49.058 49.058 0 0 0-7.5 0" />
            </svg>
        </button>
    </td>
</tr>