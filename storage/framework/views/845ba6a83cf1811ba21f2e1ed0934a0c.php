<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php ($title = 'Ulasan'); ?>

    <?php if($belumDiulas->isNotEmpty()): ?>
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6">
            <h3 class="font-semibold text-amber-800 mb-2 text-sm">Titipan Selesai yang Belum Kamu Ulas</h3>
            <ul class="space-y-2">
                <?php $__currentLoopData = $belumDiulas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="flex items-center justify-between text-sm">
                        <span class="text-stone-700"><?php echo e($t->lokasi_warung); ?> &middot; <?php echo e($t->created_at->format('d M Y')); ?></span>
                        <a href="<?php echo e(route('titipan.show', $t)); ?>" class="text-amber-700 font-medium hover:underline">Beri Ulasan &rarr;</a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="grid lg:grid-cols-2 gap-6">
        <div>
            <h3 class="font-semibold text-stone-800 mb-3">Ulasan Diterima</h3>
            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $diterima; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-white rounded-2xl border border-stone-200 p-4 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="font-medium text-stone-800"><?php echo e($u->dariUser?->name); ?></p>
                            <span class="text-amber-500 text-sm"><?php echo e(str_repeat('★', $u->rating)); ?><?php echo e(str_repeat('☆', 5-$u->rating)); ?></span>
                        </div>
                        <?php if($u->komentar): ?><p class="text-sm text-stone-500 mt-1">"<?php echo e($u->komentar); ?>"</p><?php endif; ?>
                        <p class="text-xs text-stone-400 mt-2"><?php echo e($u->titipan?->lokasi_warung); ?> &middot; <?php echo e($u->created_at->diffForHumans()); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-sm text-stone-400">Belum ada ulasan yang kamu terima.</p>
                <?php endif; ?>
            </div>
            <div class="mt-3"><?php echo e($diterima->links()); ?></div>
        </div>

        <div>
            <h3 class="font-semibold text-stone-800 mb-3">Ulasan yang Kamu Berikan</h3>
            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $diberikan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-white rounded-2xl border border-stone-200 p-4 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="font-medium text-stone-800">Untuk <?php echo e($u->untukUser?->name); ?></p>
                            <span class="text-amber-500 text-sm"><?php echo e(str_repeat('★', $u->rating)); ?><?php echo e(str_repeat('☆', 5-$u->rating)); ?></span>
                        </div>
                        <?php if($u->komentar): ?><p class="text-sm text-stone-500 mt-1">"<?php echo e($u->komentar); ?>"</p><?php endif; ?>
                        <div class="flex items-center justify-between mt-2">
                            <p class="text-xs text-stone-400"><?php echo e($u->titipan?->lokasi_warung); ?> &middot; <?php echo e($u->created_at->diffForHumans()); ?></p>
                            <form method="POST" action="<?php echo e(route('ulasan.destroy', $u)); ?>" onsubmit="return confirm('Hapus ulasan ini?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="text-xs text-rose-500 hover:underline">Hapus</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-sm text-stone-400">Kamu belum memberi ulasan.</p>
                <?php endif; ?>
            </div>
            <div class="mt-3"><?php echo e($diberikan->links()); ?></div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\nearty\resources\views/ulasan/index.blade.php ENDPATH**/ ?>