@props(['label', 'value', 'accent' => 'amber'])

@php
$accents = [
    'amber' => 'bg-amber-50 text-amber-700',
    'emerald' => 'bg-emerald-50 text-emerald-700',
    'sky' => 'bg-sky-50 text-sky-700',
    'rose' => 'bg-rose-50 text-rose-700',
];
$cls = $accents[$accent] ?? $accents['amber'];
@endphp

<div class="bg-white rounded-2xl border border-stone-200 p-5 shadow-sm">
    <div class="flex items-center justify-between">
        <p class="text-sm text-stone-500">{{ $label }}</p>
        <span class="w-8 h-8 rounded-lg {{ $cls }} flex items-center justify-center text-xs font-bold">●</span>
    </div>
    <p class="mt-2 text-2xl font-bold text-stone-800">{{ $value }}</p>
</div>
