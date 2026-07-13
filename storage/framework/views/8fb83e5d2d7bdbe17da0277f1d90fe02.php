<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['status']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['status']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
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
?>

<span <?php echo e($attributes->merge(['class' => "inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold $class"])); ?>>
    <?php echo e($label); ?>

</span>
<?php /**PATH C:\xampp\htdocs\nearty\resources\views/components/status-badge.blade.php ENDPATH**/ ?>