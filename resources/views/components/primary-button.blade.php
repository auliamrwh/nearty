<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2.5 bg-blue-500 border border-transparent rounded-xl font-semibold text-xs text-[#0f172a] uppercase tracking-widest hover:bg-blue-400 focus:bg-blue-400 active:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 ease-in-out hover:scale-105 active:scale-95 ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
