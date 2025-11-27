<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full px-2 py-1 mr-2 text-sm font-medium text-center text-grey-700 bg-white border border-grey-300 rounded
    hover:bg-primary-600 hover:text-white focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800']) }}>
    {{ $slot }}
</button>
