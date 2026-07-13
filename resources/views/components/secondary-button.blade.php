<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-1 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition-all duration-300 ease-in-out hover:scale-105 active:scale-95 ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
