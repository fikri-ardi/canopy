<tr>
    <td class="p-2 text-center">{{ $iteration }}</td>
    <td class="p-2">
        <input wire:model.blur="name" type="text" class="bg-transparent" />
    </td>
    <td class="p-2">
        <input wire:model.blur="amount" type="text" class="bg-transparent" />
    </td>
    <td class="p-2">
        <input type="text" class="bg-transparent" value="{{ $spend->platform->name }}"/>
    </td>
    <td class="p-2">
        <input type="text" class="bg-transparent" value="{{ $spend->status->body }}"/>
    </td>
</tr>