<x-app-layout>
    @php($title = 'Titipan Dibatalkan')

    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-stone-50 text-stone-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-5 py-3">Warung</th>
                    <th class="text-left px-5 py-3">Pembeli</th>
                    <th class="text-left px-5 py-3">Alasan Batal</th>
                    <th class="text-left px-5 py-3">Dibatalkan</th>
                    <th class="text-right px-5 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($titipans as $t)
                    <tr class="hover:bg-stone-50">
                        <td class="px-5 py-3 font-medium text-stone-800">{{ $t->lokasi_warung }}</td>
                        <td class="px-5 py-3 text-stone-600">{{ $t->pembeli?->name }}</td>
                        <td class="px-5 py-3 text-stone-600">{{ $t->alasan_batal ?? '-' }}</td>
                        <td class="px-5 py-3 text-stone-400">{{ $t->deleted_at?->diffForHumans() }}</td>
                        <td class="px-5 py-3 text-right">
                            <form method="POST" action="{{ route('titipan.restore', $t->id) }}">
                                @csrf @method('DELETE')
                                <button class="text-emerald-700 hover:underline">Pulihkan</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-8 text-center text-stone-400">Tidak ada titipan yang dibatalkan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $titipans->links() }}</div>
</x-app-layout>
