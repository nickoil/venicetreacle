@props(['name', 'value', 'disabled' => ''])

<input type="text" name="{{ $name }}" id="{{ $name }}" value="{{ $value }}" {{ $disabled }} class="shadow appearance-none border rounded w-full py-1 px-3 text-gray-700 
leading-tight focus:ring-primary-500 focus:border-primary-500" />