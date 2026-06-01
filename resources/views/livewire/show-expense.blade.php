<div class="table-shell">
    @php($sortColumns = [
        'number' => ['label' => '#', 'class' => 'w-16 px-3 py-3 text-center'],
        'name' => ['label' => 'Pengeluaran', 'class' => 'p-3 text-left'],
        'amount' => ['label' => 'Jumlah', 'class' => 'p-3 text-left'],
        'label' => ['label' => 'Label', 'class' => 'p-3 text-left'],
        'platform' => ['label' => 'Platform', 'class' => 'p-3 text-left'],
        'status' => ['label' => 'Status', 'class' => 'p-3 text-left'],
    ])

    <table class="w-full min-w-[840px] table-fixed">
        <thead class="expense-table-head">
            <tr>
                @foreach ($sortColumns as $column => $config)
                    <th class="{{ $config['class'] }}">
                        <button
                            type="button"
                            wire:click="sort('{{ $column }}')"
                            class="sort-button {{ $this->sortIcon($column) !== 'idle' ? 'sort-button-active' : '' }} {{ $column === 'number' ? 'mx-auto justify-center' : '' }}"
                            aria-label="Sort by {{ $config['label'] }}"
                        >
                            <span>{{ $config['label'] }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-3.5 transition {{ $this->sortIcon($column) === 'asc' ? 'rotate-180' : '' }} {{ $this->sortIcon($column) === 'idle' ? 'opacity-35' : 'opacity-100' }}">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 8.25 7.5 7.5 7.5-7.5" />
                            </svg>
                        </button>
                    </th>
                @endforeach
                <th class="w-20 p-3 text-center">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm dark:divide-slate-800">
            @forelse($spends as $spend)
                <livewire:edit-expense :spend="$spend" :iteration="$loop->iteration" :key="'edit-expense-'.$spend->id.'-'.$sortBy.'-'.$sortDirection.'-'.$loop->iteration" />
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
