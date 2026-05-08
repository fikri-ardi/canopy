<div class="min-w-0 overflow-x-auto rounded-lg border border-gray-200">
    <table class="w-full min-w-[720px] table-fixed">
        <thead class="bg-slate-800 text-sm text-white">
            <tr>
                <th class="w-14 px-3 py-2 text-center">#</th>
                <th class="p-2 text-left">Pengeluaran</th>
                <th class="p-2 text-left">Jumlah</th>
                <th class="p-2 text-left">Platform</th>
                <th class="p-2 text-left">Status</th>
                <th class="w-20 p-2 text-center">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-slate-100 text-sm">
            @foreach($spends as $spend)
                <livewire:editExpense :spend="$spend" :iteration="$loop->iteration" :key="$spend->id" />
            @endforeach
        </tbody>
    </table>
</div>
