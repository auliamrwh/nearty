<x-app-layout>
    @php($title = 'Dashboard')

    @if(isset($stats['admin']))
        <div class="mb-6">
            <h2 class="text-sm font-bold uppercase tracking-wide text-slate-400 mb-3">Dashboard Admin</h2>
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
        <h2 class="text-sm font-bold uppercase tracking-wide text-slate-400 mb-3">Dashboard Pembeli (Saya)</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card label="Jumlah Titipan Saya" :value="$stats['pembeli']['jumlah_titipan_saya']" accent="amber" />
            <x-stat-card label="Sedang Diproses" :value="$stats['pembeli']['sedang_diproses']" accent="sky" />
            <x-stat-card label="Selesai" :value="$stats['pembeli']['selesai']" accent="emerald" />
            <x-stat-card label="Dibatalkan" :value="$stats['pembeli']['dibatalkan']" accent="rose" />
        </div>
    </div>

    @if(isset($stats['driver']))
        <div class="mb-6">
            <h2 class="text-sm font-bold uppercase tracking-wide text-slate-400 mb-3">Dashboard Driver</h2>
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                <x-stat-card label="Titipan Tersedia" :value="$stats['driver']['titipan_tersedia']" accent="amber" />
                <x-stat-card label="Sedang Saya Antar" :value="$stats['driver']['titipan_diambil']" accent="sky" />
                <x-stat-card label="Riwayat Antar Selesai" :value="$stats['driver']['riwayat_count']" accent="emerald" />
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-6">
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-5 shadow-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
            <h2 class="font-semibold text-slate-800 mb-4">{{ $chartTitle }}</h2>
            <canvas id="trenChart" height="110"></canvas>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
            <h2 class="font-semibold text-slate-800 mb-4">Titipan Terbaru</h2>
            <ul class="space-y-3">
                @forelse($titipanTerbaru as $t)
                    <li class="flex items-center justify-between text-sm">
                        <div class="min-w-0">
                            <p class="font-medium text-slate-700 truncate">{{ $t->lokasi_warung }}</p>
                            <p class="text-xs text-slate-400">{{ $t->pembeli?->name }}</p>
                        </div>
                        <x-status-badge :status="$t->status" />
                    </li>
                @empty
                    <li class="text-sm text-slate-400">Belum ada titipan.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="mt-6 flex flex-wrap gap-3">
        <a href="{{ route('titipan.create') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-blue-500 text-[#0f172a] font-semibold text-sm hover:bg-blue-400 transition-all duration-300 ease-in-out hover:scale-105 active:scale-95">
            + Buat Titipan Baru
        </a>
        <a href="{{ route('driver.index') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-slate-800 text-white font-semibold text-sm hover:bg-slate-700 transition-all duration-300 ease-in-out hover:scale-105 active:scale-95">
            Aktifkan Mode Driver
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('trenChart');
        const datasets = [
            {
                label: '{{ $chartLabel1 }}',
                data: @json($dataChart),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.15)',
                tension: 0.35,
                fill: true,
            }
        ];

        @if(!empty($dataChart2))
        datasets.push({
            label: '{{ $chartLabel2 }}',
            data: @json($dataChart2),
            borderColor: '#10b981', // emerald-500
            backgroundColor: 'rgba(16,185,129,0.15)',
            tension: 0.35,
            fill: true,
        });
        @endif

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labelChart),
                datasets: datasets
            },
            options: {
                plugins: { legend: { display: datasets.length > 1 } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });
    </script>
</x-app-layout>
