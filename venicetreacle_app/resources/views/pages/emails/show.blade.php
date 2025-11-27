<x-app-layout>
    
    <div class="py-12">

        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="w-full lg:w-1/2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form  method="POST">
                        @csrf
                        @method('PUT')

                        <div class="flex justify-between items-center mb-4">
                            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                                {{ __('Email') }}
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

                        <x-form-box :name="'email_address'" :label="__('To Address')" >
                            <x-form-text :name="'email_address'" :value="old('email_address', $email->email_address)" :disabled="'disabled'" />
                        </x-form-box>

                        <x-form-box :name="'message_type'" :label="__('Message Type')" >
                            <x-form-text :name="'message_type'" :value="old('message_type', $email->message_type)" :disabled="'disabled'"  />
                        </x-form-box>

                        <x-form-box :name="'subject'" :label="__('Subject')" >
                            <x-form-text :name="'subject'" :value="old('subject', $email->subject)" :disabled="'disabled'"  />
                        </x-form-box>

                        <x-form-box :name="'subject'" :label="__('Content')" >
                            <iframe class="shadow appearance-none border-black rounded w-full text-gray-700 
                                leading-tight pointer-events-none" srcdoc="{{ $email->body }}"
                                onload="javascript:(function(o){o.style.height=o.contentWindow.document.body.scrollHeight+"px";}(this));" ></iframe>
                        </x-form-box>

                        <x-form-box :name="'sent_time'" :label="__('Sent At')" >
                            <x-form-text :name="'sent_time'" :value="old('sent_time', $email->sent_time)" :disabled="'disabled'"  />
                        </x-form-box>

                        <x-form-box :name="'complete_time'" :label="__('Completed At')" >
                            <x-form-text :name="'complete_time'" :value="old('complete_time', $email->complete_time)" :disabled="'disabled'"  />
                        </x-form-box>

                        <x-form-box :name="'email_status_id'" :label="__('Status')" >
                            <x-form-text :name="'email_status_id'"  :value="old('suspended', $email->email_status->title)"  :disabled="'disabled'" />
                        </x-form-box>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>