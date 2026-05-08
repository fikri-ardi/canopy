<div class="my-4 space-y-2">
    <table class="table-auto w-full rounded-xl overflow-hidden">
        <thead class="bg-slate-800 text-white">
            <tr>
                <th class="py-2 px-4 text-center">#</th>
                <th class="p-2 text-left">Pengeluaran</th>
                <th class="p-2 text-left">Jumlah</th>
                <th class="p-2 text-left">Platform</th>
                <th class="p-2 text-left">Status</th>
            </tr>
        </thead>
        @foreach ($spends as $spend)
            <h1>{{ $spend->name }}</h1>
        @endforeach
        <tbody class="bg-slate-100 rounded-xl overflow-hidden">
            @foreach($activeBudget->spends as $spend)
            <livewire:editExpense :spend="$spend" :iteration="$loop->iteration" :key="$spend->id" />
            @endforeach
        </tbody>
    </table>
</div>