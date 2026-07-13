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
    <?php ($title = 'Titipan Saya'); ?>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        <form method="GET" class="flex flex-1 gap-2">
            <input type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="Cari lokasi warung / alamat..."
                   class="w-full sm:w-72 rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
            <select name="status" class="rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Status</option>
                <?php $__currentLoopData = \App\Models\Titipan::STATUS_LABEL; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($val); ?>" <?php if(request('status') === $val): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button class="px-4 py-2 rounded-xl bg-slate-800 text-white text-sm font-medium">Cari</button>
        </form>

        <a href="<?php echo e(route('titipan.create')); ?>" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-blue-500 text-[#0f172a] font-semibold text-sm hover:bg-blue-400 transition-all duration-300 ease-in-out hover:scale-105 active:scale-95 whitespace-nowrap">
            + Buat Titipan
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-1 overflow-hidden transition-all duration-300 hover:shadow-md">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-5 py-3">Warung</th>
                    <th class="text-left px-5 py-3">Barang</th>
                    <th class="text-left px-5 py-3">Driver</th>
                    <th class="text-left px-5 py-3">Status</th>
                    <th class="text-left px-5 py-3">Dibuat</th>
                    <th class="text-right px-5 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $__empty_1 = true; $__currentLoopData = $titipans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                        <td class="px-5 py-3">
                            <p class="font-medium text-slate-800"><?php echo e($t->lokasi_warung); ?></p>
                            <p class="text-xs text-slate-400"><?php echo e($t->alamat_antar); ?></p>
                        </td>
                        <td class="px-5 py-3 text-slate-600"><?php echo e($t->items->pluck('nama_item')->join(', ')); ?></td>
                        <td class="px-5 py-3 text-slate-600"><?php echo e($t->driver?->name ?? '-'); ?></td>
                        <td class="px-5 py-3"><?php if (isset($component)) { $__componentOriginal8c81617a70e11bcf247c4db924ab1b62 = $component; } ?>
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
<?php endif; ?></td>
                        <td class="px-5 py-3 text-slate-400"><?php echo e($t->created_at->diffForHumans()); ?></td>
                        <td class="px-5 py-3 text-right space-x-2 whitespace-nowrap">
                            <a href="<?php echo e(route('titipan.show', $t)); ?>" class="text-blue-700 hover:underline">Detail</a>
                            <?php if($t->status === 'menunggu'): ?>
                                <a href="<?php echo e(route('titipan.edit', $t)); ?>" class="text-sky-700 hover:underline">Ubah</a>
                                <button type="button"
                                        x-data
                                        @click="$dispatch('open-batal', { id: <?php echo e($t->id); ?> })"
                                        class="text-rose-600 hover:underline">Batalkan</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada titipan. Yuk buat titipan pertama!</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4"><?php echo e($titipans->links()); ?></div>

    
    <div x-data="{ show: false, id: null }" @open-batal.window="show = true; id = $event.detail.id"
         x-show="show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
        <div @click.outside="show = false" class="bg-white rounded-2xl p-6 w-full max-w-sm">
            <h3 class="font-semibold text-slate-800 mb-3">Batalkan Titipan</h3>
            <form :action="'/titipan/' + id" method="POST">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <label class="block text-sm text-slate-600 mb-1">Alasan pembatalan</label>
                <textarea name="alasan_batal" required rows="3" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" @click="show = false" class="px-4 py-2 rounded-xl text-sm text-slate-600">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 text-white text-sm font-medium">Ya, Batalkan</button>
                </div>
            </form>
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
<?php /**PATH C:\xampp\htdocs\nearty\resources\views/titipan/index.blade.php ENDPATH**/ ?>