@props(['name', 'value', 'disabled' => ''])

<textarea name="{{ $name }}" id="{{ $name }}" rows="8" class="shadow appearance-none border rounded w-full py-1 px-3 text-gray-700 
leading-tight focus:ring-primary-500 focus:border-primary-500" >{{ $value }}</textarea>