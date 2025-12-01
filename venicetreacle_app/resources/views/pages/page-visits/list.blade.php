@if (count($listItems) < 1)

    <p class="mb-0"> {{ __('Your search did not produce any results.') }}</p>

@else

    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr id="universal-sort-row">
                <x-sort-table-header :sortField="'created_at'">
                    {{ __('Time') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'page'">
                    {{ __('Page') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'src'">
                    {{ __('Source') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'ref'">
                    {{ __('Reference') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'ip'">
                    {{ __('IP Address') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'user_agent'">
                    {{ __('User Agent') }}
                </x-sort-table-header>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($listItems as $listItem)
                <tr>
                    <x-table-cell>
                        {{ $listItem->created_at }}
                    </x-table-cell>
                    <x-table-cell>
                        {{ $listItem->page }}
                    </x-table-cell>
                    <x-table-cell>
                        {{ $listItem->src }}
                    </x-table-cell>
                    <x-table-cell>
                        {{ $listItem->ref }}
                    </x-table-cell>
                    <x-table-cell>
                        {{ $listItem->ip }}
                    </x-table-cell>
                    <x-table-cell>
                        {{ $listItem->user_agent }}
                    </x-table-cell>

                </tr>
            @endforeach
        </tbody>
    </table>

@endif
