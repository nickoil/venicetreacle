@props(['active'])

@php
$classes = ($active ?? false)
            ? 'cursor-pointer block w-full px-4 py-2 bg-red-100 font-medium text-start text-sm leading-5 text-gray-500 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out'
            : 'cursor-pointer block w-full px-4 py-2 font-medium text-start text-sm leading-5 text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out';
@endphp


<a {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
