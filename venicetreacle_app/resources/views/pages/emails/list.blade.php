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
                    {{ __('To Address') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'message_type'">
                    {{ __('Type') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'sent_time'">
                    {{ __('Sent At') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'completed_time'">
                    {{ __('Completed At') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'email_status_id'">
                    {{ __('Status') }}
                </x-sort-table-header>
                <x-table-header class="text-right">
                    {{ __('Actions') }}
                </x-table-header>
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
                        {{ $listItem->message_type}}
                    </x-table-cell>
                    <x-table-cell>
                        {{ $listItem->sent_time }}
                    </x-table-cell>
                    <x-table-cell>
                        {{ $listItem->complete_time }}
                    </x-table-cell>
                    <x-table-cell>
                        {{ $listItem->email_status->title }}
                    </x-table-cell>
                    <x-table-cell class="text-right whitespace-nowrap">
                        <x-primary-button-link href="{{ route($route . '.show', $listItem->id) }}" title="{{ __('Show') }}" >
                            <i class="fa-solid fa-eye mr-1"></i>
                            {{ __('Show') }}
                        </x-primary-button-link>
                    </x-table-cell>
                </tr>
            @endforeach
        </tbody>
    </table>

@endif
