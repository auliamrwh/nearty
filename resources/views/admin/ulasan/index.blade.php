<x-app-layout>
    @php($title = 'Moderasi Ulasan')

    {{-- Filter & Search --}}
    <form method="GET" class="flex flex-col sm:flex-row gap-3 mb-5">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama pemberi / penerima / warung..."
               class="flex-1 rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        <select name="rating" class="rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
            <option value="">Semua Rating</option>
            @for($r = 5; $r >= 1; $r--)
                <option value="{{ $r }}" @selected(request('rating') == $r)>{{ $r }} Bintang</option>
            @endfor
        </select>
        <button class="px-4 py-2 rounded-xl bg-slate-800 text-white text-sm font-medium">Filter</button>
        @if(request()->hasAny(['q','rating']))
            <a href="{{ route('admin.ulasan.index') }}" class="px-4 py-2 rounded-xl border border-slate-300 text-sm text-slate-600">Reset</a>
        @endif
    </form>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-1 overflow-hidden transition-all duration-300 hover:shadow-md">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-5 py-3">Pemberi</th>
                    <th class="text-left px-5 py-3">Penerima</th>
                    <th class="text-left px-5 py-3">Rating</th>
                    <th class="text-left px-5 py-3">Komentar</th>
                    <th class="text-left px-5 py-3">Titipan</th>
                    <th class="text-left px-5 py-3">Waktu</th>
                    <th class="text-right px-5 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($ulasans as $u)
                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                        <td class="px-5 py-3">
                            <p class="font-medium text-slate-800">{{ $u->dariUser?->name ?? '-' }}</p>
                            <p class="text-xs text-slate-400">{{ $u->peran_pemberi }}</p>
                        </td>
                        <td class="px-5 py-3 text-slate-700">{{ $u->untukUser?->name ?? '-' }}</td>
                        <td class="px-5 py-3">
                            <span class="text-blue-500">{{ str_repeat('★', $u->rating) }}</span><span class="text-slate-200">{{ str_repeat('★', 5 - $u->rating) }}</span>
                            <span class="text-xs text-slate-400 ml-1">({{ $u->rating }}/5)</span>
                        </td>
                        <td class="px-5 py-3 text-slate-600 max-w-xs">
                            @if($u->komentar)
                                <span class="italic">"{{ Str::limit($u->komentar, 60) }}"</span>
                            @else
                                <span class="text-slate-300">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-slate-500 text-xs">{{ $u->titipan?->lokasi_warung ?? '-' }}</td>
                        <td class="px-5 py-3 text-slate-400 text-xs whitespace-nowrap">{{ $u->created_at->diffForHumans() }}</td>
                        <td class="px-5 py-3 text-right">
                            <form method="POST" action="{{ route('admin.ulasan.destroy', $u) }}" class="inline"
                                  onsubmit="return confirm('Hapus ulasan ini? Tindakan tidak bisa dibatalkan.')">
                                @csrf @method('DELETE')
                                <button class="text-rose-600 hover:underline text-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-10 text-center text-slate-400">Belum ada ulasan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $ulasans->links() }}</div>
</x-app-layout>
