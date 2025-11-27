<select name="{{ $name }}" id="{{ $name }}" class="shadow appearance-none border rounded w-full py-1 px-3 text-gray-700 
    leading-tight focus:ring-primary-500 focus:border-primary-500">

    @if(isset($unselectedAvailable) && $unselectedAvailable===true)
    <option value="" > {{ __('Please select ...') }}</option>
    @endif

    @foreach($items as $index => $item)
        <option value="{{ $index }}" {{ $selected === $index ? 'selected' : '' }}>{{ $item }}</option>
    @endforeach
</select>

