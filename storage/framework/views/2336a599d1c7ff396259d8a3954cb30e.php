<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['label', 'value', 'accent' => 'amber']));

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

foreach (array_filter((['label', 'value', 'accent' => 'amber']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
$accents = [
    'amber' => 'bg-amber-50 text-amber-700',
    'emerald' => 'bg-emerald-50 text-emerald-700',
    'sky' => 'bg-sky-50 text-sky-700',
    'rose' => 'bg-rose-50 text-rose-700',
];
$cls = $accents[$accent] ?? $accents['amber'];
?>

<div class="bg-white rounded-2xl border border-stone-200 p-5 shadow-sm">
    <div class="flex items-center justify-between">
        <p class="text-sm text-stone-500"><?php echo e($label); ?></p>
        <span class="w-8 h-8 rounded-lg <?php echo e($cls); ?> flex items-center justify-center text-xs font-bold">●</span>
    </div>
    <p class="mt-2 text-2xl font-bold text-stone-800"><?php echo e($value); ?></p>
</div>
<?php /**PATH C:\xampp\htdocs\nearty\resources\views/components/stat-card.blade.php ENDPATH**/ ?>