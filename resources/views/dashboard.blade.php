<x-app-layout>
    @php($title = 'Dashboard')

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <x-stat-card label="Titipan Saya" :value="$stats['total_titipan_saya']" accent="amber" />
        <x-stat-card label="Saya Antar (Driver)" :value="$stats['total_diantar_saya']" accent="sky" />
        <x-stat-card label="Tersedia Diambil" :value="$stats['titipan_tersedia']" accent="emerald" />
        <x-stat-card label="Selesai" :value="$stats['titipan_selesai']" accent="rose" />
    </div>

    @if(isset($stats['total_user']))
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
            <x-stat-card label="Total User (Admin)" :value="$stats['total_user']" accent="amber" />
            <x-stat-card label="Total Titipan (Admin)" :value="$stats['total_titipan']" accent="sky" />
            <x-stat-card label="Ongkir Terkumpul (Admin)" :value="'Rp '.number_format($stats['total_ongkir_terkumpul'], 0, ',', '.')" accent="emerald" />
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-6">
        <div class="lg:col-span-2 bg-white rounded-2xl border border-stone-200 p-5 shadow-sm">
            <h2 class="font-semibold text-stone-800 mb-4">Tren Titipan 7 Hari Terakhir</h2>
            <canvas id="trenChart" height="110"></canvas>
        </div>

        <div class="bg-white rounded-2xl border border-stone-200 p-5 shadow-sm">
            <h2 class="font-semibold text-stone-800 mb-4">Titipan Terbaru</h2>
            <ul class="space-y-3">
                @forelse($titipanTerbaru as $t)
                    <li class="flex items-center justify-between text-sm">
                        <div class="min-w-0">
                            <p class="font-medium text-stone-700 truncate">{{ $t->lokasi_warung }}</p>
                            <p class="text-xs text-stone-400">{{ $t->pembeli?->name }}</p>
                        </div>
                        <x-status-badge :status="$t->status" />
                    </li>
                @empty
                    <li class="text-sm text-stone-400">Belum ada titipan.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="mt-6 flex flex-wrap gap-3">
        <a href="{{ route('titipan.create') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-amber-500 text-[#241C19] font-semibold text-sm hover:bg-amber-400 transition">
            + Buat Titipan Baru
        </a>
        <a href="{{ route('driver.index') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-stone-800 text-white font-semibold text-sm hover:bg-stone-700 transition">
            Aktifkan Mode Driver
        </a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.4/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('trenChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labelChart),
                datasets: [{
                    label: 'Titipan Dibuat',
                    data: @json($dataChart),
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245,158,11,0.15)',
                    tension: 0.35,
                    fill: true,
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });
    </script>
</x-app-layout>
