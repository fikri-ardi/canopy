<div x-show="createExpense" x-cloak x-transition
    class="fixed inset-0 z-50 flex min-h-screen w-screen bg-black bg-opacity-10 backdrop-blur-sm dark:bg-black/40">
    {{-- Card --}}
    <div x-on:click.away="createExpense = false" class="m-auto min-h-96 w-80 rounded-lg bg-white px-9 py-10 dark:bg-slate-900">
        {{-- Card header --}}
        <div class="py-4 text-gray-800 dark:text-slate-100">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.6" stroke="currentColor" class="size-24 mx-auto">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
            </svg>
            <div class="text-center text-xl py-3">Create a New Expense</div>
        </div>

        {{-- Card body --}}
        <form class="space-y-5" wire:submit='store'>
            <div class="space-y-3">
                {{-- Name --}}
                <div>
                    <input wire:model='name' type="text" name="name" id="expense-name" placeholder="Makan" class="w-full rounded-lg bg-gray-100 px-3 py-2 dark:bg-slate-800 dark:text-slate-100">
                    @error('name')
                        <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Amount --}}
                <div>
                    <input wire:model='amount' type="number" min="0" name="amount" id="expense-amount" placeholder="300000"
                        class="w-full rounded-lg bg-gray-100 px-3 py-2 dark:bg-slate-800 dark:text-slate-100">
                    @error('amount')
                        <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                @if ($labelsReady)
                    {{-- Label --}}
                    <div x-data="{selectLabel: false}" class="relative flex justify-center min-w-36">
                        <button type="button" x-on:click="selectLabel = true" class="flex w-full items-center justify-between rounded-lg bg-gray-100 px-4 py-2 text-gray-700 dark:bg-slate-800 dark:text-slate-100">
                            <span>{{ $selectedLabel?->name ?? 'Select label' }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        <div x-show="selectLabel" x-cloak x-on:click.away="selectLabel = false" x-transition
                            class="absolute left-0 top-full z-50 mt-1 flex w-full flex-col overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-gray-200 dark:bg-slate-900 dark:ring-slate-700">
                            @foreach ($labels as $label)
                                <button type="button" x-on:click="selectLabel = false" wire:click="selectLabel({{ $label->id }})" class="w-full px-4 py-2 text-left text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">
                                    {{ $label->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                {{-- Platform --}}
                <div x-data="{selectPlatform: false}" class="relative flex justify-center min-w-36">
                    <button type="button" x-on:click="selectPlatform = true" class="flex w-full items-center justify-between rounded-lg bg-gray-100 px-4 py-2 text-gray-700 dark:bg-slate-800 dark:text-slate-100">
                        <span>{{ $selectedPlatform?->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                
                    <div x-show="selectPlatform" x-cloak x-on:click.away="selectPlatform = false" x-transition
                        class="absolute left-0 top-full z-50 mt-1 flex w-full flex-col overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-gray-200 dark:bg-slate-900 dark:ring-slate-700">
                        @foreach ($platforms as $platform)
                            <button type="button" x-on:click="selectPlatform = false" wire:click="selectPlatform({{ $platform->id }})" class="w-full px-4 py-2 text-left text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">
                                {{ $platform->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Status --}}
                <div x-data="{selectStatus: false}" class="relative flex justify-center min-w-36">
                    <button type="button" x-on:click="selectStatus = true" class="flex w-full items-center justify-between rounded-lg bg-gray-100 px-4 py-2 text-gray-700 dark:bg-slate-800 dark:text-slate-100">
                        <span>{{ $selectedStatus?->body }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                
                    <div x-show="selectStatus" x-cloak x-on:click.away="selectStatus = false" x-transition
                        class="absolute left-0 top-full z-50 mt-1 flex w-full flex-col overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-gray-200 dark:bg-slate-900 dark:ring-slate-700">
                        @foreach ($statuses as $status)
                            <button type="button" x-on:click="selectStatus = false" wire:click="selectStatus({{ $status->id }})" class="w-full px-4 py-2 text-left text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">
                                {{ $status->body }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Button --}}
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
