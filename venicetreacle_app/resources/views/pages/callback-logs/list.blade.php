@if (count($listItems) < 1)

    <p class="mb-0"> {{ __('Your search did not produce any results.') }}</p>

@else

    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr id="universal-sort-row">
                <x-sort-table-header :sortField="'service'">
                    {{ __('Service') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'state'">
                    {{ __('State') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'created_at'">
                    {{ __('Log Time') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'success'">
                    {{ __('Success') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'message'">
                    {{ __('Message') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'body'">
                    {{ __('Body') }}
                </x-sort-table-header>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($listItems as $listItem)
                <tr>
                    <x-table-cell>
                        {{ $listItem->service }}
                    </x-table-cell>
                    <x-table-cell>
                        {{ $listItem->state }}
                    </x-table-cell>
                    <x-table-cell>
                        {{ $listItem->created_at }}
                    </x-table-cell>
                    <x-table-cell>
                        @if ($listItem->success)
                            <span class="text-green-600 font-semibold">{{ __('Yes') }}</span>
                        @else
                            <span class="text-red-600 font-semibold">{{ __('No') }}</span>
                        @endif
                    </x-table-cell>
                    <x-table-cell>
                        {{ $listItem->message }}
                    </x-table-cell>
  
                    <x-table-cell>
                        <button 
                            type="button" 
                            class="group w-auto px-2 py-1 ml-1 font-medium text-sm text-center text-grey-700 bg-white border border-grey-300 rounded hover:bg-primary-600 hover:text-white focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                        >
                            {{ __('Show Body') }}
                            <span class="invisible group-hover:visible fixed left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-96 max-h-96 overflow-auto p-4 bg-white text-gray-700 border border-gray-300 text-sm text-left rounded shadow-lg z-50 break-words ">
                                {{ $listItem->body }}
                            </span>
                        </button>
                    </x-table-cell>
                </tr>
            @endforeach
        </tbody>
    </table>

@endif
