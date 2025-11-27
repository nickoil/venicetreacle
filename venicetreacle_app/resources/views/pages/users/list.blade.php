@if (count($listItems) < 1)

    <p class="mb-0"> {{ __('Your search did not produce any results.') }}</p>

@else

    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr id="universal-sort-row">
                <x-sort-table-header class="flex items-center"  :sortField="'id'">
                    {{ __('ID') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'name'">
                    {{ __('Name') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'email'">
                    {{ __('Email') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'role_id'">
                    {{ __('Role') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'invited_at'">
                    {{ __('Invited At') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'first_login_at'">
                    {{ __('First Login At') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'suspended'">
                    {{ __('Suspended') }}
                </x-sort-table-header>
                <x-table-header class="text-right">
                    {{ __('Actions') }}
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
                        {{ $listItem->name }}
                    </x-table-cell>
                    <x-table-cell>
                        {{ $listItem->email }}
                    </x-table-cell>
                    <x-table-cell>
                        {{ $listItem->role ? $listItem->role->title : '' }}
                    </x-table-cell>
                    <x-table-cell>
                        {{ $listItem->invited_at }}
                    </x-table-cell>
                    <x-table-cell>
                        {{ $listItem->first_login_at }}
                    </x-table-cell>
                    <x-table-cell>
                        {{ $listItem->suspended ? 'Yes' : 'No'}}
                    </x-table-cell>
                    <x-table-cell class="text-right whitespace-nowrap">
                        <x-primary-button-link href="{{ route($route . '.edit', $listItem->id) }}" title="{{ __('Edit') }}" >
                            <i class="fa-solid fa-edit mr-1"></i>
                            {{ __('Edit') }}
                        </x-primary-button-link>
                        <x-primary-button-link href="{{ route($route . '.invite', $listItem->id) }}" title="{{ __('Invite') }}" >
                            <i class="fa-solid fa-envelope mr-1"></i>
                            {{ __('Invite') }}
                        </x-primary-button-link>
                    </x-table-cell>
                </tr>
            @endforeach
        </tbody>
    </table>

@endif
