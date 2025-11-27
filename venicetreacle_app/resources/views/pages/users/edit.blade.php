<x-app-layout>
    
    <div class="py-12">

        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="w-full lg:w-1/2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route($route . '.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="flex justify-between items-center mb-4">
                            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                                {{ __('Edit User') }}
                            </h2>
                            <x-secondary-button-link href="{{ route($route . '.index') }}">
                                {{ __('Back') }}
                            </x-secondary-button-link>
                        </div>

                        @if (session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-4 rounded relative" role="alert">
                                <span class="block sm:inline">{{ session('success') }}</span>
                            </div>
                        @endif

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
                            <x-form-text :name="'name'" :value="old('name', $user->name)" />
                        </x-form-box>

                        <x-form-box :name="'email'" :label="__('Email')" >
                            <x-form-text :name="'email'" :value="old('email', $user->email)" />
                        </x-form-box>

                        <x-form-box :name="'role_id'" :label="__('Role')" >
                            <x-form-select :name="'role_id'" :items=$roles :selected="old('role_id', $user->role_id)" />
                        </x-form-box>

                        <x-form-box :name="'suspended'" :label="__('Suspended')" >
                            <x-form-select :name="'suspended'" :items=$suspendedOptions :selected="old('suspended', $user->suspended)"/>
                        </x-form-box>

                        <div class="flex items-center justify-start mt-4">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>