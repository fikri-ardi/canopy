<div class="table-shell">
    <table class="w-full min-w-[900px] table-fixed">
        <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-500 dark:bg-slate-950 dark:text-slate-400">
            <tr>
                <th class="w-14 px-3 py-3 text-center">#</th>
                <th class="p-3 text-left">Pengeluaran</th>
                <th class="p-3 text-left">Jumlah</th>
                <th class="p-3 text-left">Label</th>
                <th class="p-3 text-left">Platform</th>
                <th class="p-3 text-left">Status</th>
                <th class="w-20 p-3 text-center">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm dark:divide-slate-800">
            @forelse($spends as $spend)
                <livewire:edit-expense :spend="$spend" :iteration="$loop->iteration" :key="$spend->id" />
            @empty
                <tr>
                    <td colspan="7" class="px-3 py-12 text-center">
                        <span class="icon-box mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375" />
                            </svg>
                        </span>
                        <div class="mt-3 font-semibold text-gray-950 dark:text-slate-50">No expenses recorded yet</div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Add your first expense to see it here.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
