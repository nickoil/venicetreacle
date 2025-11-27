<a {{ $attributes->merge(['class' => 'w-auto px-2 py-1 ml-1 font-medium text-sm text-center text-grey-700 bg-white border border-grey-300 rounded 
    hover:bg-primary-600 hover:text-white focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 whitespace-nowrap']) }}>
    {{ $slot }}
</a>
