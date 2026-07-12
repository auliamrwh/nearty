<x-app-layout>
    @php($title = 'Dashboard')

    @if(isset($stats['admin']))
        <div class="mb-6">
            <h2 class="text-sm font-bold uppercase tracking-wide text-stone-400 mb-3">Dashboard Admin</h2>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat-card label="Total User" :value="$stats['admin']['total_user']" accent="amber" />
                <x-stat-card label="Total Driver" :value="$stats['admin']['total_driver']" accent="sky" />
                <x-stat-card label="Total Titipan" :value="$stats['admin']['total_titipan']" accent="emerald" />
                <x-stat-card label="Ongkir Terkumpul" :value="'Rp '.number_format($stats['admin']['total_ongkir_terkumpul'], 0, ',', '.')" accent="rose" />
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
                <x-stat-card label="Titipan Menunggu" :value="$stats['admin']['titipan_menunggu']" accent="amber" />
                <x-stat-card label="Titipan Diproses" :value="$stats['admin']['titipan_diproses']" accent="sky" />
                <x-stat-card label="Titipan Selesai" :value="$stats['admin']['titipan_selesai']" accent="emerald" />
                <x-stat-card label="Titipan Dibatalkan" :value="$stats['admin']['titipan_dibatalkan']" accent="rose" />
            </div>
        </div>
    @endif

    <div class="mb-6">
        <h2 class="text-sm font-bold uppercase tracking-wide text-stone-400 mb-3">Dashboard Pembeli (Saya)</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card label="Jumlah Titipan Saya" :value="$stats['pembeli']['jumlah_titipan_saya']" accent="amber" />
            <x-stat-card label="Sedang Diproses" :value="$stats['pembeli']['sedang_diproses']" accent="sky" />
            <x-stat-card label="Selesai" :value="$stats['pembeli']['selesai']" accent="emerald" />
            <x-stat-card label="Dibatalkan" :value="$stats['pembeli']['dibatalkan']" accent="rose" />
        </div>
    </div>

    @if(isset($stats['driver']))
        <div class="mb-6">
            <h2 class="text-sm font-bold uppercase tracking-wide text-stone-400 mb-3">Dashboard Driver</h2>
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                <x-stat-card label="Titipan Tersedia" :value="$stats['driver']['titipan_tersedia']" accent="amber" />
                <x-stat-card label="Sedang Saya Antar" :value="$stats['driver']['titipan_diambil']" accent="sky" />
                <x-stat-card label="Riwayat Antar Selesai" :value="$stats['driver']['riwayat_count']" accent="emerald" />
            </div>
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
