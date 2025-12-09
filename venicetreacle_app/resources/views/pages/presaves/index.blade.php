<x-app-layout>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-4 rounded relative" role="alert">
                <span class="block sm:inline">{!! session('success') !!}</span>
            </div>
        @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-4">
                        <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                            {{ __('Pre-Saves') }}
                        </h2>
                        <div>
                            @if(in_array(auth()->user()->role->title, ['Administrator']))
                            <x-primary-button-link href="{{ route('presaves.save-track-to-all', ['track_uri' => 'bad-aji:2JFZlBx722B6hvuwNLdLFt']) }}" >
                                <i class="fa-solid fa-save mr-1"></i>
                                {{ __('Save Bad Aji') }}
                            </x-primary-button-link>
                            @endif
                            <x-primary-button-link href="{{ route($route . '.export') }}" >
                                <i class="fa-solid fa-file-export mr-1"></i>
                                {{ __('Export') }}
                            </x-primary-button-link>
                            <x-primary-button-link id="clear-filter" href="#" >
                                <i class="fa-solid fa-filter-slash mr-1"></i>
                                {{ __('Clear Filter') }}
                            </x-primary-button-link>
                        </div>
                    </div> 

                    <!-- filter -->
                    <form id="universal-search-form" method="GET">

                        <input id="sort_field" name="sort_field" type="hidden">
                        <input id="sort_direction" name="sort_direction" type="hidden">

                        <div class="min-w-full flex flex-wrap mb-4">
                            <x-filter-box :title="__('Track ID')">
                                <x-filter-select :name="'track_id'" :items="$trackOptions" :selected="Request::input('track_id')" />
                            </x-filter-box>
                            <x-filter-box :title="__('Display Name')">
                                <x-filter-text :name="'display_name'" />
                            </x-filter-box>
                            <x-filter-box :title="__('Email')">    
                                <x-filter-text :name="'email'" />
                            </x-filter-box>
                            <x-filter-box :title="__('Country')">
                                <x-filter-select :name="'country'" :items="$countryOptions" :selected="Request::input('country')" />
                            </x-filter-box>
                            <x-filter-box :title="__('Date From')">    
                                <x-filter-date :name="'date_from'" />
                            </x-filter-box>
                            <x-filter-box :title="__('Date To')">    
                                <x-filter-date :name="'date_to'" />
                            </x-filter-box>
                        </div>
                    </form>
                    
                    <div id="universal-table-wrapper">
                        @include('pages.' . $route . '.list')
                    </div>

                    <div id="universal-pagination-wrapper" class="mt-4">
                        {{ $listItems->appends(request()->except('page'))->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    @once
        @push('page-styles')
            @vite(['resources/css/table-handler.css'])
        @endpush
        @push('page-scripts')
            @vite(['resources/js/table-handler.js'])
        @endpush
    @endonce

</x-app-layout>
