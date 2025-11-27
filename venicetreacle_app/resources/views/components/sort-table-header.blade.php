<x-table-header class="sort {{ Request::input('sort_field') == $sortField ? Request::input('sort_direction') : '' }} " data-sort="{{ $sortField }}">
    {{ $slot }}
</x-table-header>