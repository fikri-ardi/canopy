<div x-show="createBudget" x-transition class="fixed left-0 top-0 z-50 flex h-full w-full bg-black bg-opacity-10 backdrop-blur-sm">
    {{-- Card --}}
    <div x-on:click.away="createBudget = false" class="m-auto w-80 rounded-2xl bg-white px-8 py-8">
        {{-- Card header --}}
        <div class="py-4 text-gray-800">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.6" stroke="currentColor" class="size-24 mx-auto">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
            </svg>
            <div class="text-center text-xl py-3">Create a New Budget</div>
        </div>

        {{-- Card body --}}
        <form class="space-y-5" wire:submit="store">
            <div class="space-y-3">
                <div>
                    <input wire:model="name" type="text" name="name" id="budget-name" placeholder="Plan name" class="w-full rounded-lg bg-gray-100 px-3 py-2">
                    @error('name')
                        <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <input wire:model="income" type="number" min="0" name="income" id="budget-income" placeholder="2000000" class="w-full rounded-lg bg-gray-100 px-3 py-2">
                    @error('income')
                        <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="flex items-center space-x-1 rounded-lg bg-green-500 p-2 text-white hover:bg-green-600">
                    <span>GO</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
