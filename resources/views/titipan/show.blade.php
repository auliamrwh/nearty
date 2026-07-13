<x-app-layout>
    @php($title = 'Detail Titipan')

    <div class="max-w-2xl space-y-6">

        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h2 class="font-bold text-lg text-slate-800">
                        {{ $titipan->lokasi_warung }}
                    </h2>
                    <p class="text-sm text-slate-500">
                        Antar ke: {{ $titipan->alamat_antar }}
                    </p>
                </div>

                <x-status-badge :status="$titipan->status" />
            </div>

            <dl class="grid grid-cols-2 gap-y-3 text-sm">

                <dt class="text-slate-500">Pembeli</dt>
                <dd class="text-slate-800">
                    {{ $titipan->pembeli?->name }}
                </dd>

                <dt class="text-slate-500">Driver</dt>
                <dd class="text-slate-800">
                    {{ $titipan->driver?->name ?? 'Belum ada driver' }}
                </dd>

                <dt class="text-slate-500">Metode Bayar</dt>
                <dd class="text-slate-800 uppercase">
                    {{ $titipan->metode_bayar }}
                </dd>

                <dt class="text-slate-500">Ongkir</dt>
                <dd class="text-slate-800">
                    Rp {{ number_format($titipan->ongkir, 0, ',', '.') }}
                </dd>

                <dt class="text-slate-500">Estimasi Total</dt>
                <dd class="text-slate-800">
                    Rp {{ number_format($titipan->estimasi_total ?? 0, 0, ',', '.') }}
                </dd>

                @if($titipan->total_aktual)
                    <dt class="text-slate-500">Total Aktual</dt>
                    <dd class="text-slate-800">
                        Rp {{ number_format($titipan->total_aktual, 0, ',', '.') }}
                    </dd>
                @endif

                @if($titipan->alasan_batal)
                    <dt class="text-slate-500">Alasan Batal</dt>
                    <dd class="text-rose-700">
                        {{ $titipan->alasan_batal }}
                    </dd>
                @endif

            </dl>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
            <h3 class="font-semibold text-slate-800 mb-3">
                Barang Titipan
            </h3>

            <ul class="divide-y divide-slate-100 text-sm">
                @foreach($titipan->items as $item)
                    <li class="py-2 flex justify-between">
                        <div>
                            <p class="text-slate-800">
                                {{ $item->nama_item }}
                                <span class="text-slate-400">
                                    x{{ $item->jumlah }}
                                </span>
                            </p>

                            @if($item->catatan)
                                <p class="text-xs text-slate-400">
                                    {{ $item->catatan }}
                                </p>
                            @endif
                        </div>

                        <p class="text-slate-600">
                            Rp {{ number_format($item->estimasi_harga ?? 0, 0, ',', '.') }}
                        </p>
                    </li>
                @endforeach
            </ul>
        </div>



        @if($bisaUlasan)
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                <h3 class="font-semibold text-slate-800 mb-3">Beri Ulasan</h3>

                @if($sudahUlasan)
                    <div class="flex items-center gap-2">
                        <span class="text-blue-500 text-lg">{{ str_repeat('★', $sudahUlasan->rating) }}{{ str_repeat('☆', 5 - $sudahUlasan->rating) }}</span>
                    </div>
                    @if($sudahUlasan->komentar)
                        <p class="text-sm text-slate-500 mt-1">"{{ $sudahUlasan->komentar }}"</p>
                    @endif
                    <p class="text-xs text-slate-400 mt-3">Kamu sudah memberi ulasan untuk titipan ini. Lihat semua ulasanmu di menu <a href="{{ route('ulasan.index') }}" class="underline">Ulasan</a>.</p>
                @else
                    <form method="POST" action="{{ route('ulasan.store', $titipan) }}" class="space-y-4">
                        @csrf

                        <div>
<div x-data="{ rating: {{ old('rating', 5) }}, hover: 0 }">

    <label class="block text-sm text-slate-600 mb-2">
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
            :class="(hover ? hover : rating) >= n ? 'text-blue-500' : 'text-slate-300'">
            ★
        </button>
    </template>
</div>

    <p class="mt-2 text-sm text-slate-500">
        Rating:
        <span class="font-semibold" x-text="rating"></span>/5
    </p>

    <x-input-error :messages="$errors->get('rating')" class="mt-1" />
</div>
                            <x-input-error :messages="$errors->get('rating')" class="mt-1" />
                        </div>

                        <div>
                            <label class="block text-sm text-slate-600 mb-1">Komentar (opsional)</label>
                            <textarea name="komentar" rows="3" maxlength="255"
                                      class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="Ceritakan pengalamanmu...">{{ old('komentar') }}</textarea>
                            <x-input-error :messages="$errors->get('komentar')" class="mt-1" />
                        </div>

                        <button type="submit"
                                class="inline-flex items-center px-4 py-2.5 rounded-xl bg-blue-500 text-[#0f172a] font-semibold text-sm hover:bg-blue-400 transition-all duration-300 ease-in-out hover:scale-105 active:scale-95">
                            Kirim Ulasan
                        </button>
                    </form>
                @endif
            </div>
        @endif

        <a href="{{ route('titipan.index') }}"
           class="text-sm text-slate-500 hover:underline">
            &larr; Kembali ke daftar titipan
        </a>

    </div>
</x-app-layout>
