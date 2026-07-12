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
    <?php ($title = 'Mode Driver'); ?>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 bg-white rounded-2xl border border-stone-200 p-5 shadow-sm">
        <div>
            <p class="font-semibold text-stone-800">Status Driver Kamu</p>
            <p class="text-sm text-stone-500">
                <?php if($user->is_driver_active): ?>
                    <span class="text-emerald-600 font-medium">● Available</span> — kamu bisa ambil titipan terdekat.
                <?php else: ?>
                    <span class="text-stone-400 font-medium">● Nonaktif</span> — aktifkan untuk mulai jadi driver dadakan.
                <?php endif; ?>
            </p>
        </div>
        <form method="POST" action="<?php echo e(route('driver.toggle')); ?>">
            <?php echo csrf_field(); ?>
            <button class="px-5 py-2.5 rounded-xl font-semibold text-sm transition <?php echo e($user->is_driver_active ? 'bg-stone-800 text-white hover:bg-stone-700' : 'bg-amber-500 text-[#241C19] hover:bg-amber-400'); ?>">
                <?php echo e($user->is_driver_active ? 'Nonaktifkan' : 'Jadi Driver Sekarang'); ?>

            </button>
        </form>
    </div>

    <?php if($sedangDiantar->count()): ?>
        <h3 class="font-semibold text-stone-800 mb-3">Sedang Kamu Antar</h3>
        <div class="grid gap-4 mb-8">
            <?php $__currentLoopData = $sedangDiantar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-2xl border border-amber-200 p-5 shadow-sm">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="font-medium text-stone-800"><?php echo e($t->lokasi_warung); ?> &rarr; <?php echo e($t->alamat_antar); ?></p>
                            <p class="text-xs text-stone-400">Pembeli: <?php echo e($t->pembeli?->name); ?> &middot; <?php echo e($t->items->pluck('nama_item')->join(', ')); ?></p>
                        </div>
                        <?php if (isset($component)) { $__componentOriginal8c81617a70e11bcf247c4db924ab1b62 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c81617a70e11bcf247c4db924ab1b62 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status-badge','data' => ['status' => $t->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($t->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8c81617a70e11bcf247c4db924ab1b62)): ?>
<?php $attributes = $__attributesOriginal8c81617a70e11bcf247c4db924ab1b62; ?>
<?php unset($__attributesOriginal8c81617a70e11bcf247c4db924ab1b62); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8c81617a70e11bcf247c4db924ab1b62)): ?>
<?php $component = $__componentOriginal8c81617a70e11bcf247c4db924ab1b62; ?>
<?php unset($__componentOriginal8c81617a70e11bcf247c4db924ab1b62); ?>
<?php endif; ?>
                    </div>
                    <form method="POST" action="<?php echo e(route('driver.status', $t)); ?>" class="flex flex-wrap items-center gap-2 mt-3">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <input type="number" name="total_aktual" placeholder="Total belanja aktual (Rp)" class="rounded-lg border-stone-300 text-sm focus:ring-amber-500 focus:border-amber-500 w-48">
                        <select name="status" class="rounded-lg border-stone-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                            <option value="diantar" <?php if($t->status==='diantar'): echo 'selected'; endif; ?>>Sedang Diantar</option>
                            <option value="dibayar">Sudah Dibayar</option>
                            <option value="selesai">Selesai</option>
                        </select>
                        <button class="px-4 py-2 rounded-lg bg-stone-800 text-white text-sm font-medium">Update Status</button>
                    </form>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    <h3 class="font-semibold text-stone-800 mb-3">Titipan Terdekat yang Bisa Diambil</h3>
    <div class="grid gap-4" x-data
         x-init="if (!new URLSearchParams(location.search).get('lat') && navigator.geolocation) {
                     navigator.geolocation.getCurrentPosition(p => {
                         const url = new URL(location.href);
                         url.searchParams.set('lat', p.coords.latitude);
                         url.searchParams.set('lng', p.coords.longitude);
                         location.replace(url.toString());
                     });
                 }">
        <?php $__empty_1 = true; $__currentLoopData = $titipans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-2xl border border-stone-200 p-5 shadow-sm flex items-start justify-between gap-4">
                <div>
                    <p class="font-medium text-stone-800"><?php echo e($t->lokasi_warung); ?> &rarr; <?php echo e($t->alamat_antar); ?></p>
                    <p class="text-xs text-stone-400 mt-1"><?php echo e($t->items->pluck('nama_item')->join(', ')); ?></p>
                    <p class="text-xs text-stone-400">Ongkir: Rp <?php echo e(number_format($t->ongkir,0,',','.')); ?> &middot; Bayar: <?php echo e(strtoupper($t->metode_bayar)); ?></p>
                    <?php if(isset($t->jarak_km)): ?>
                        <p class="text-xs font-medium text-amber-700 mt-1">&asymp; <?php echo e($t->jarak_km); ?> km dari lokasimu</p>
                    <?php endif; ?>
                </div>
                <form method="POST" action="<?php echo e(route('driver.ambil', $t)); ?>">
                    <?php echo csrf_field(); ?>
                    <button class="px-4 py-2 rounded-xl bg-amber-500 text-[#241C19] text-sm font-semibold whitespace-nowrap hover:bg-amber-400" <?php if(!$user->is_driver_active): ?> disabled <?php endif; ?>>
                        Ambil Order
                    </button>
                </form>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-sm text-stone-400">Belum ada titipan yang tersedia di sekitar kamu.</p>
        <?php endif; ?>
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
<?php /**PATH C:\xampp\htdocs\nearty\resources\views/driver/index.blade.php ENDPATH**/ ?>