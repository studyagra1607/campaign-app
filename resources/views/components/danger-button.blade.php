<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none ring-0 ring-offset-0 ring-transparent ring-offset-current transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
