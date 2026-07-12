@props(['active' => false, 'icon' => null])

@php
$icons = [
    'home' => 'M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4a1 1 0 001-1v-6h4v6a1 1 0 001 1h4a1 1 0 001-1V10',
    'bag' => 'M20 7h-4V5a4 4 0 00-8 0v2H4a1 1 0 00-1 1l1 12a1 1 0 001 1h14a1 1 0 001-1l1-12a1 1 0 00-1-1zM10 5a2 2 0 014 0v2h-4V5z',
    'scooter' => 'M5 17a2 2 0 100 4 2 2 0 000-4zm14 0a2 2 0 100 4 2 2 0 000-4zM4 15l2-8h5l3 5h3a2 2 0 012 2v1',
    'star' => 'M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z',
    'users' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m5-2.13a4 4 0 100-8 4 4 0 000 8zm7 0a4 4 0 10-1-7.87',
    'trash' => 'M19 7l-.87 12.14A2 2 0 0116.13 21H7.87a2 2 0 01-2-1.86L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16',
    'cog' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
];
$path = $icons[$icon] ?? $icons['home'];
@endphp

<a {{ $attributes->merge(['class' => 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition '
        . ($active ? 'bg-amber-500 text-[#241C19]' : 'text-stone-300 hover:bg-white/5 hover:text-white')]) }}>
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}" />
    </svg>
    <span>{{ $slot }}</span>
</a>
