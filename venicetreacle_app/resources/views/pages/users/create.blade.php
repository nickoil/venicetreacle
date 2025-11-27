<x-app-layout>

    <div class="py-12">

        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="w-full lg:w-1/2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="flex justify-between items-center mb-4">
                        <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                            {{ __('New User') }}
                        </h2>
                        <x-secondary-button-link href="{{ route($route . '.index') }}">
                            {{ __('Back') }}
                        </x-secondary-button-link>
                    </div>

                    <form action="{{ route($route . '.store') }}" method="POST">
                        @csrf

                        @if ($errors->any())
                            <div class="mb-4">
                                <div class="font-medium text-red-600">
                                    {{ __('There is an issue with the data') }}
                                </div>

                                <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <x-form-box :name="'name'" :label="__('Name')" >
                            <x-form-text :name="'name'" :value="old('name')" />
                        </x-form-box>

                        <x-form-box :name="'email'" :label="__('Email')" >
                            <x-form-text :name="'email'" :value="old('email')" />
                        </x-form-box>

                        <x-form-box :name="'role_id'" :label="__('Role')" >
                            <x-form-select :name="'role_id'" :items=$roles :selected="old('role_id')" />
                        </x-form-box>

                        <x-form-box :name="'suspended'" :label="__('Suspended')" >
                            <x-form-select :name="'suspended'" :items=$suspendedOptions :selected="old('suspended')"/>
                        </x-form-box>

                        <div class="flex justify-start mt-4">
                            <x-primary-button>
                                {{ __('Save') }}
                            </x-primary-button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>