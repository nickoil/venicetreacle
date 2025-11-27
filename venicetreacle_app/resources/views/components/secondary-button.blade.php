<button {{ $attributes->merge(['type' => 'button', 'class' => 'px-2 py-1 mr-2 bg-gray-500 hover:bg-gray-700 text-white text-sm font-medium rounded text-gray-700 focus:outline-none disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
