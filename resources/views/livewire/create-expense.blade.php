<div x-show="createExpense" x-transition
    class="fixed z-50  pointer-events-none flex left-0 top-0  h-full w-full bg-black bg-opacity-10 backdrop-blur-sm">
    {{-- Card --}}
    <div x-on:click.away="createExpense = false" class="pointer-events-auto m-auto -translate-y-20  px-9 py-10 min-h-96 w-80 bg-white rounded-3xl">
        {{-- Card header --}}
        <div class="py-4 text-gray-800">
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
                    <input wire:model='name' type="text" name="name" id="name" placeholder="Makan" class="bg-gray-100 px-3 py-2 rounded-xl">
                </div>

                {{-- Amount --}}
                <div>
                    <input wire:model='amount' type="number" min="50000" name="name" id="name" placeholder="300.000"
                        class="bg-gray-100 px-3 py-2 rounded-xl">
                </div>
                
                {{-- Platform --}}
                <div x-data="{selectPlatform: false}" class="relative flex justify-center min-w-36">
                    {{-- Dropdown toggler --}}
                    <div x-on:click="selectPlatform = true" class="cursor-pointer flex w-full items-center justify-between bg-gray-100 px-4 py-2 rounded-xl">
                        <span>{{ $selectedPlatform->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                
                    {{-- Dropdown menu --}}
                    <div x-show="selectPlatform" x-on:click.away="selectPlatform = false" x-transition
                        class="z-50 absolute flex flex-col left-0 top-full mt-1  w-full bg-gray-100 rounded-xl overflow-hidden">
                        @foreach ($platforms as $platform)
                        <div x-on:click="selectPlatform = false" wire:click="selectPlatform({{ $platform }})" class="cursor-pointer w-full px-4 py-2 active:bg-gray-200">
                            {{ $platform->name }}
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Status --}}
                <div x-data="{selectStatus: false}" class="relative flex justify-center min-w-36">
                    {{-- Dropdown toggler --}}
                    <div x-on:click="selectStatus = true" class="cursor-pointer flex w-full items-center justify-between bg-gray-100 px-4 py-2 rounded-xl">
                        <span>{{ $selectedStatus->body }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                
                    {{-- Dropdown menu --}}
                    <div x-show="selectStatus" x-on:click.away="selectStatus = false" x-transition
                        class="z-50 absolute flex flex-col left-0 top-full mt-1 w-full bg-gray-100 rounded-xl overflow-hidden">
                        @foreach ($statuses as $status)
                        <div x-on:click="selectStatus = false" wire:click="selectStatus({{ $status }})" class="cursor-pointer w-full px-4 py-2 active:bg-gray-200">
                            {{ $status->body }}
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Button --}}
            <div class="flex justify-end">
                <button type="submit" class="flex items-center space-x-1 bg-green-500 text-white p-2 rounded-xl">
                    <span>GO</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>