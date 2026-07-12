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
    <?php ($title = 'Detail Titipan'); ?>

    <div class="max-w-2xl space-y-6">

        <div class="bg-white rounded-2xl border border-stone-200 p-6 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h2 class="font-bold text-lg text-stone-800">
                        <?php echo e($titipan->lokasi_warung); ?>

                    </h2>
                    <p class="text-sm text-stone-500">
                        Antar ke: <?php echo e($titipan->alamat_antar); ?>

                    </p>
                </div>

                <?php if (isset($component)) { $__componentOriginal8c81617a70e11bcf247c4db924ab1b62 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c81617a70e11bcf247c4db924ab1b62 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status-badge','data' => ['status' => $titipan->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($titipan->status)]); ?>
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

            <dl class="grid grid-cols-2 gap-y-3 text-sm">

                <dt class="text-stone-500">Pembeli</dt>
                <dd class="text-stone-800">
                    <?php echo e($titipan->pembeli?->name); ?>

                </dd>

                <dt class="text-stone-500">Driver</dt>
                <dd class="text-stone-800">
                    <?php echo e($titipan->driver?->name ?? 'Belum ada driver'); ?>

                </dd>

                <dt class="text-stone-500">Metode Bayar</dt>
                <dd class="text-stone-800 uppercase">
                    <?php echo e($titipan->metode_bayar); ?>

                </dd>

                <dt class="text-stone-500">Ongkir</dt>
                <dd class="text-stone-800">
                    Rp <?php echo e(number_format($titipan->ongkir, 0, ',', '.')); ?>

                </dd>

                <dt class="text-stone-500">Estimasi Total</dt>
                <dd class="text-stone-800">
                    Rp <?php echo e(number_format($titipan->estimasi_total ?? 0, 0, ',', '.')); ?>

                </dd>

                <?php if($titipan->total_aktual): ?>
                    <dt class="text-stone-500">Total Aktual</dt>
                    <dd class="text-stone-800">
                        Rp <?php echo e(number_format($titipan->total_aktual, 0, ',', '.')); ?>

                    </dd>
                <?php endif; ?>

                <?php if($titipan->alasan_batal): ?>
                    <dt class="text-stone-500">Alasan Batal</dt>
                    <dd class="text-rose-700">
                        <?php echo e($titipan->alasan_batal); ?>

                    </dd>
                <?php endif; ?>

            </dl>
        </div>

        <div class="bg-white rounded-2xl border border-stone-200 p-6 shadow-sm">
            <h3 class="font-semibold text-stone-800 mb-3">
                Barang Titipan
            </h3>

            <ul class="divide-y divide-stone-100 text-sm">
                <?php $__currentLoopData = $titipan->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="py-2 flex justify-between">
                        <div>
                            <p class="text-stone-800">
                                <?php echo e($item->nama_item); ?>

                                <span class="text-stone-400">
                                    x<?php echo e($item->jumlah); ?>

                                </span>
                            </p>

                            <?php if($item->catatan): ?>
                                <p class="text-xs text-stone-400">
                                    <?php echo e($item->catatan); ?>

                                </p>
                            <?php endif; ?>
                        </div>

                        <p class="text-stone-600">
                            Rp <?php echo e(number_format($item->estimasi_harga ?? 0, 0, ',', '.')); ?>

                        </p>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>



        <?php if($bisaUlasan): ?>
            <div class="bg-white rounded-2xl border border-stone-200 p-6 shadow-sm">
                <h3 class="font-semibold text-stone-800 mb-3">Beri Ulasan</h3>

                <?php if($sudahUlasan): ?>
                    <div class="flex items-center gap-2">
                        <span class="text-amber-500 text-lg"><?php echo e(str_repeat('★', $sudahUlasan->rating)); ?><?php echo e(str_repeat('☆', 5 - $sudahUlasan->rating)); ?></span>
                    </div>
                    <?php if($sudahUlasan->komentar): ?>
                        <p class="text-sm text-stone-500 mt-1">"<?php echo e($sudahUlasan->komentar); ?>"</p>
                    <?php endif; ?>
                    <p class="text-xs text-stone-400 mt-3">Kamu sudah memberi ulasan untuk titipan ini. Lihat semua ulasanmu di menu <a href="<?php echo e(route('ulasan.index')); ?>" class="underline">Ulasan</a>.</p>
                <?php else: ?>
                    <form method="POST" action="<?php echo e(route('ulasan.store', $titipan)); ?>" class="space-y-4">
                        <?php echo csrf_field(); ?>

                        <div>
<div x-data="{ rating: <?php echo e(old('rating', 5)); ?>, hover: 0 }">

    <label class="block text-sm text-stone-600 mb-2">
        Rating
    </label>

    <input type="hidden" name="rating" x-model="rating">
<div class="flex gap-1">
    <template x-for="n in 5" :key="n">
        <button
            type="button"
            @click="rating = n"
            @mouseenter="hover = n"
            @mouseleave="hover = 0"
            style="font-size:48px; line-height:1;"
            :class="(hover ? hover : rating) >= n ? 'text-amber-500' : 'text-stone-300'">
            ★
        </button>
    </template>
</div>

    <p class="mt-2 text-sm text-stone-500">
        Rating:
        <span class="font-semibold" x-text="rating"></span>/5
    </p>

    <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('rating'),'class' => 'mt-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('rating')),'class' => 'mt-1']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
</div>
                            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('rating'),'class' => 'mt-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('rating')),'class' => 'mt-1']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                        </div>

                        <div>
                            <label class="block text-sm text-stone-600 mb-1">Komentar (opsional)</label>
                            <textarea name="komentar" rows="3" maxlength="255"
                                      class="w-full rounded-xl border-stone-300 text-sm focus:border-amber-500 focus:ring-amber-500"
                                      placeholder="Ceritakan pengalamanmu..."><?php echo e(old('komentar')); ?></textarea>
                            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('komentar'),'class' => 'mt-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('komentar')),'class' => 'mt-1']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                        </div>

                        <button type="submit"
                                class="inline-flex items-center px-4 py-2.5 rounded-xl bg-amber-500 text-[#241C19] font-semibold text-sm hover:bg-amber-400 transition">
                            Kirim Ulasan
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <a href="<?php echo e(route('titipan.index')); ?>"
           class="text-sm text-stone-500 hover:underline">
            &larr; Kembali ke daftar titipan
        </a>

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
<?php /**PATH C:\xampp\htdocs\nearty\resources\views/titipan/show.blade.php ENDPATH**/ ?>