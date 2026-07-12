<x-app-layout>
    @php($title = 'Mode Driver')

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 bg-white rounded-2xl border border-stone-200 p-5 shadow-sm">
        <div>
            <p class="font-semibold text-stone-800">Status Driver Kamu</p>
            <p class="text-sm text-stone-500">
                @if($user->is_driver_active)
                    <span class="text-emerald-600 font-medium">● Available</span> — kamu bisa ambil titipan terdekat.
                @else
                    <span class="text-stone-400 font-medium">● Nonaktif</span> — aktifkan untuk mulai jadi driver dadakan.
                @endif
            </p>
        </div>
        <form method="POST" action="{{ route('driver.toggle') }}">
            @csrf
            <button class="px-5 py-2.5 rounded-xl font-semibold text-sm transition {{ $user->is_driver_active ? 'bg-stone-800 text-white hover:bg-stone-700' : 'bg-amber-500 text-[#241C19] hover:bg-amber-400' }}">
                {{ $user->is_driver_active ? 'Nonaktifkan' : 'Jadi Driver Sekarang' }}
            </button>
        </form>
    </div>

    @if($sedangDiantar->count())
        <h3 class="font-semibold text-stone-800 mb-3">Sedang Kamu Antar</h3>
        <div class="grid gap-4 mb-8">
            @foreach($sedangDiantar as $t)
                <div class="bg-white rounded-2xl border border-amber-200 p-5 shadow-sm">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="font-medium text-stone-800">{{ $t->lokasi_warung }} &rarr; {{ $t->alamat_antar }}</p>
                            <p class="text-xs text-stone-400">Pembeli: {{ $t->pembeli?->name }} &middot; {{ $t->items->pluck('nama_item')->join(', ') }}</p>
                        </div>
                        <x-status-badge :status="$t->status" />
                    </div>
                    <form method="POST" action="{{ route('driver.status', $t) }}" class="flex flex-wrap items-center gap-2 mt-3">
                        @csrf @method('PATCH')
                        <input type="number" name="total_aktual" placeholder="Total belanja aktual (Rp)" class="rounded-lg border-stone-300 text-sm focus:ring-amber-500 focus:border-amber-500 w-48">
                        <select name="status" class="rounded-lg border-stone-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                            <option value="diantar" @selected($t->status==='diantar')>Sedang Diantar</option>
                            <option value="dibayar">Sudah Dibayar</option>
                            <option value="selesai">Selesai</option>
                        </select>
                        <button class="px-4 py-2 rounded-lg bg-stone-800 text-white text-sm font-medium">Update Status</button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif

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
        @forelse($titipans as $t)
            <div class="bg-white rounded-2xl border border-stone-200 p-5 shadow-sm flex items-start justify-between gap-4">
                <div>
                    <p class="font-medium text-stone-800">{{ $t->lokasi_warung }} &rarr; {{ $t->alamat_antar }}</p>
                    <p class="text-xs text-stone-400 mt-1">{{ $t->items->pluck('nama_item')->join(', ') }}</p>
                    <p class="text-xs text-stone-400">Ongkir: Rp {{ number_format($t->ongkir,0,',','.') }} &middot; Bayar: {{ strtoupper($t->metode_bayar) }}</p>
                    @if(isset($t->jarak_km))
                        <p class="text-xs font-medium text-amber-700 mt-1">&asymp; {{ $t->jarak_km }} km dari lokasimu</p>
                    @endif
                </div>
                <form method="POST" action="{{ route('driver.ambil', $t) }}">
                    @csrf
                    <button class="px-4 py-2 rounded-xl bg-amber-500 text-[#241C19] text-sm font-semibold whitespace-nowrap hover:bg-amber-400" @if(!$user->is_driver_active) disabled @endif>
                        Ambil Order
                    </button>
                </form>
            </div>
        @empty
            <p class="text-sm text-stone-400">Belum ada titipan yang tersedia di sekitar kamu.</p>
        @endforelse
    </div>
</x-app-layout>
