@if (count($listItems) < 1)

    <p class="mb-0"> {{ __('Your search did not produce any results.') }}</p>

@else

    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr id="universal-sort-row">
                <x-sort-table-header :sortField="'created_at'">
                    {{ __('Time') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'profile_images'">
                    {{ __('Profile Image') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'display_name'">
                    {{ __('Display Name') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'email'">
                    {{ __('Email') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'country'">
                    {{ __('Country') }}
                </x-sort-table-header>
                <x-sort-table-header :sortField="'track_id'">
                    {{ __('Track') }}
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
                        @if($listItem->profile_images)
                            @php
                                $images = is_string($listItem->profile_images) 
                                    ? json_decode($listItem->profile_images, true) 
                                    : $listItem->profile_images;
                                $smallImage = $images[2]['url'] ?? $images[0]['url'] ?? null;
                                $largeImage = $images[0]['url'] ?? null;
                            @endphp
                            
                            @if($smallImage)
                                <div class="group relative inline-block">
                                    <img src="{{ $smallImage }}" 
                                         alt="Profile" 
                                         class="w-8 h-8 rounded-sm cursor-pointer"
                                    >
                                    @if($largeImage)
                                        <div class="invisible group-hover:visible fixed left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-50 pointer-events-none">
                                            <img src="{{ $largeImage }}" 
                                                 alt="Profile Large" 
                                                 class="max-w-96 max-h-96 rounded shadow-lg border border-gray-300"
                                            >
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endif
                    </x-table-cell>
                    <x-table-cell>
                        {{ $listItem->display_name }}
                    </x-table-cell>
                    <x-table-cell>
                        {{ $listItem->email }}
                    </x-table-cell>
                    <x-table-cell>
                        {{ $listItem->country }}
                    </x-table-cell>
                    <x-table-cell>
                        {{ $listItem->track_id }}
                    </x-table-cell>
                </tr>
            @endforeach
        </tbody>
    </table>

@endif
