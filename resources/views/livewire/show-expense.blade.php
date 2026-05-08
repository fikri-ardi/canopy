<div class="min-w-0 overflow-x-auto rounded-lg border border-gray-200 dark:border-slate-800">
    <table class="w-full min-w-[840px] table-fixed">
        <thead class="bg-slate-800 text-sm text-white dark:bg-slate-900">
            <tr>
                <th class="w-14 px-3 py-2 text-center">#</th>
                <th class="p-2 text-left">Pengeluaran</th>
                <th class="p-2 text-left">Jumlah</th>
                <th class="p-2 text-left">Label</th>
                <th class="p-2 text-left">Platform</th>
                <th class="p-2 text-left">Status</th>
                <th class="w-20 p-2 text-center">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-slate-100 text-sm dark:divide-slate-800 dark:bg-slate-900 dark:text-slate-100">
            @forelse($spends as $spend)
                <livewire:edit-expense :spend="$spend" :iteration="$loop->iteration" :key="$spend->id" />
            @empty
                <tr>
                    <td colspan="7" class="px-3 py-8 text-center text-gray-500 dark:text-slate-400">
                        No expenses recorded yet.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
