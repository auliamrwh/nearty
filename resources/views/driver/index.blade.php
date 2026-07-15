<x-app-layout>
    @php($title = 'Mode Driver')

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 bg-white rounded-2xl border border-slate-200 p-5 shadow-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
        <div>
            <p class="font-semibold text-slate-800">Status Driver Kamu</p>
            <p class="text-sm text-slate-500">
                @if($user->is_driver_active)
                    <span class="text-emerald-600 font-medium">● Available</span> — kamu bisa ambil titipan terdekat.
                @else
                    <span class="text-slate-400 font-medium">● Nonaktif</span> — aktifkan untuk mulai jadi driver dadakan.
                @endif
            </p>
        </div>
        <form method="POST" action="{{ route('driver.toggle') }}">
            @csrf
            <button class="px-5 py-2.5 rounded-xl font-semibold text-sm transition-all duration-300 ease-in-out hover:scale-105 active:scale-95 {{ $user->is_driver_active ? 'bg-slate-800 text-white hover:bg-slate-700' : 'bg-blue-500 text-[#0f172a] hover:bg-blue-400' }}">
                {{ $user->is_driver_active ? 'Nonaktifkan' : 'Jadi Driver Sekarang' }}
            </button>
        </form>
    </div>

    @if($sedangDiantar->count())
        <h3 class="font-semibold text-slate-800 mb-3">Sedang Kamu Proses</h3>
        <div class="grid gap-4 mb-8">
            @foreach($sedangDiantar as $t)
                <div class="bg-white rounded-2xl border border-blue-200 p-5 shadow-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="font-medium text-slate-800">{{ $t->lokasi_warung }} &rarr; {{ $t->alamat_antar }}</p>
                            <p class="text-xs text-slate-400">Pembeli: {{ $t->pembeli?->name }} &middot; {{ $t->items->pluck('nama_item')->join(', ') }}</p>
                        </div>
                        <x-status-badge :status="$t->status" />
                    </div>
                    <form method="POST" action="{{ route('driver.status', $t) }}" class="flex flex-wrap items-center gap-2 mt-3">
                        @csrf @method('PATCH')
                        <input type="number" name="total_aktual" placeholder="Total belanja aktual (Rp)" class="rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500 w-48">
                        <select name="status" class="rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="menunggu" @selected($t->status === 'menunggu')>Menunggu Driver</option>
                            <option value="diambil_driver" @selected($t->status === 'diambil_driver')>Diambil Driver</option>
                            <option value="dibayar" @selected($t->status === 'dibayar')>Sudah Bayar</option>
                            <option value="selesai" @selected($t->status === 'selesai')>Selesai</option>
                        </select>
                        <button class="px-4 py-2 rounded-lg bg-slate-800 text-white text-sm font-medium">Update Status</button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif

    <h3 class="font-semibold text-slate-800 mb-3">Titipan Terdekat yang Bisa Diambil</h3>
    <div class="grid gap-4" x-data
         x-init="if (!new URLSearchParams(location.search).get('lat') && navigator.geolocation) {
                     navigator.geolocation.getCurrentPosition(p => {
                         const url = new URL(location.href);
                         url.searchParams.set('lat', p.coords.latitude);
                         url.searchParams.set('lng', p.coords.longitude);
                         location.replace(url.toString());
                     });
                 }">
        @forelse($titipans as $t)
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-1 flex items-start justify-between gap-4">
                <div>
                    <p class="font-medium text-slate-800">{{ $t->lokasi_warung }} &rarr; {{ $t->alamat_antar }}</p>
                    <p class="text-xs text-slate-400 mt-1">{{ $t->items->pluck('nama_item')->join(', ') }}</p>
                    <p class="text-xs text-slate-400">Ongkir: Rp {{ number_format($t->ongkir,0,',','.') }} &middot; Bayar: {{ strtoupper($t->metode_bayar) }}</p>
                    @if(isset($t->jarak_km))
                        <p class="text-xs font-medium text-blue-700 mt-1">&asymp; {{ $t->jarak_km }} km dari lokasimu</p>
                    @endif
                </div>
                <form method="POST" action="{{ route('driver.ambil', $t) }}">
                    @csrf
                    <button class="px-4 py-2 rounded-xl bg-blue-500 text-[#0f172a] text-sm font-semibold whitespace-nowrap hover:bg-blue-400" @if(!$user->is_driver_active) disabled @endif>
                        Ambil Order
                    </button>
                </form>
            </div>
        @empty
            <p class="text-sm text-slate-400">Belum ada titipan yang tersedia di sekitar kamu.</p>
        @endforelse
    </div>
</x-app-layout>
