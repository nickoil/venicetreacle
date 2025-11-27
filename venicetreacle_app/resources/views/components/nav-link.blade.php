@props(['active', 'href' => '#'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-0 py-2 border-b-2 border-red-400 text-sm font-medium leading-4 rounded-none text-gray-900 focus:border-red-700 focus:outline-none transition duration-150 ease-in-out cursor-pointer'
            : 'inline-flex items-center px-0 py-2 border-b-2 border-transparent text-sm font-medium leading-4 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out  cursor-pointer';
@endphp

<button  {{ $attributes->merge(['class' => $classes]) }} onclick="location.href='{{ $href }}'" type="button">
    {{ $slot }}
</button>
