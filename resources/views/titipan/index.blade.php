<x-app-layout>
    @php($title = 'Titipan Saya')

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        <form method="GET" class="flex flex-1 gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari lokasi warung / alamat..."
                   class="w-full sm:w-72 rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
            <select name="status" class="rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Status</option>
                @foreach(\App\Models\Titipan::STATUS_LABEL as $val => $label)
                    <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
                @endforeach
            </select>
            <button class="px-4 py-2 rounded-xl bg-slate-800 text-white text-sm font-medium">Cari</button>
        </form>

        <a href="{{ route('titipan.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-blue-500 text-[#0f172a] font-semibold text-sm hover:bg-blue-400 transition-all duration-300 ease-in-out hover:scale-105 active:scale-95 whitespace-nowrap">
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
                @forelse($titipans as $t)
                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                        <td class="px-5 py-3">
                            <p class="font-medium text-slate-800">{{ $t->lokasi_warung }}</p>
                            <p class="text-xs text-slate-400">{{ $t->alamat_antar }}</p>
                        </td>
                        <td class="px-5 py-3 text-slate-600">{{ $t->items->pluck('nama_item')->join(', ') }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $t->driver?->name ?? '-' }}</td>
                        <td class="px-5 py-3"><x-status-badge :status="$t->status" /></td>
                        <td class="px-5 py-3 text-slate-400">{{ $t->created_at->diffForHumans() }}</td>
                        <td class="px-5 py-3 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('titipan.show', $t) }}" class="text-blue-700 hover:underline">Detail</a>
                            @if($t->status === 'menunggu')
                                <a href="{{ route('titipan.edit', $t) }}" class="text-sky-700 hover:underline">Ubah</a>
                                <button type="button"
                                        x-data
                                        @click="$dispatch('open-batal', { id: {{ $t->id }} })"
                                        class="text-rose-600 hover:underline">Batalkan</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada titipan. Yuk buat titipan pertama!</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $titipans->links() }}</div>

    {{-- Modal alasan pembatalan --}}
    <div x-data="{ show: false, id: null }" @open-batal.window="show = true; id = $event.detail.id"
         x-show="show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
        <div @click.outside="show = false" class="bg-white rounded-2xl p-6 w-full max-w-sm">
            <h3 class="font-semibold text-slate-800 mb-3">Batalkan Titipan</h3>
            <form :action="'/titipan/' + id" method="POST">
                @csrf @method('DELETE')
                <label class="block text-sm text-slate-600 mb-1">Alasan pembatalan</label>
                <textarea name="alasan_batal" required rows="3" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" @click="show = false" class="px-4 py-2 rounded-xl text-sm text-slate-600">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 text-white text-sm font-medium">Ya, Batalkan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
