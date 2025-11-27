@if (count($listItems) < 1)

    <p class="mb-0"> {{ __('Your search did not produce any results.') }}</p>

@else

    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr id="universal-sort-row">
                <x-sort-table-header class="flex items-center" :sortField="'id'">
                    {{ __('ID') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'email_address'">
                    {{ __('Email Address') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'sent_time'">
                    {{ __('Excluded At') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'email_status_id'">
                    {{ __('Reason') }}
                </x-sort-table-header>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($listItems as $listItem)
                <tr>
                    <x-table-cell>
                        {{ $listItem->id }}
                    </x-table-cell>
                    <x-table-cell>
                        {{ $listItem->email_address }}
                    </x-table-cell>
                    <x-table-cell>
                        {{ $listItem->excluded_time }}
                    </x-table-cell>
                    <x-table-cell>
                        {{ $listItem->email_status->title }}
                    </x-table-cell>
                </tr>
            @endforeach
        </tbody>
    </table>

@endif
