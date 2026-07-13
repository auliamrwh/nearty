@props(['status'])

@php
$colors = [
    'menunggu' => 'bg-blue-100 text-blue-800',
    'diambil_driver' => 'bg-sky-100 text-sky-800',
    'diantar' => 'bg-indigo-100 text-indigo-800',
    'dibayar' => 'bg-teal-100 text-teal-800',
    'selesai' => 'bg-emerald-100 text-emerald-800',
    'dibatalkan' => 'bg-rose-100 text-rose-800',
];
$labels = \App\Models\Titipan::STATUS_LABEL;
$class = $colors[$status] ?? 'bg-slate-100 text-slate-700';
$label = $labels[$status] ?? $status;
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold $class"]) }}>
    {{ $label }}
</span>
