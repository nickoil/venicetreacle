<select name="{{ $name }}" class="py-1 px-3 pe-11 block w-full border-gray-200 rounded-e text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
    <option value="">{{ __('All') }}</option>
    @foreach($items as $index => $item)
        <option value="{{ $index }}" {{ $selected !== null && intval($selected) === intval($index) ? 'selected' : '' }}>{{ $item }}</option>
    @endforeach
</select>