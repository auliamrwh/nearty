<x-app-layout>
    @php($title = 'Ulasan')

    @if($belumDiulas->isNotEmpty())
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-6">
            <h3 class="font-semibold text-blue-800 mb-2 text-sm">Titipan Selesai yang Belum Kamu Ulas</h3>
            <ul class="space-y-2">
                @foreach($belumDiulas as $t)
                    <li class="flex items-center justify-between text-sm">
                        <span class="text-slate-700">{{ $t->lokasi_warung }} &middot; {{ $t->created_at->format('d M Y') }}</span>
                        <a href="{{ route('titipan.show', $t) }}" class="text-blue-700 font-medium hover:underline">Beri Ulasan &rarr;</a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid lg:grid-cols-2 gap-6">
        <div>
            <h3 class="font-semibold text-slate-800 mb-3">Ulasan Diterima</h3>
            <div class="space-y-3">
                @forelse($diterima as $u)
                    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <p class="font-medium text-slate-800">{{ $u->dariUser?->name }}</p>
                            <span class="text-blue-500 text-sm">{{ str_repeat('★', $u->rating) }}{{ str_repeat('☆', 5-$u->rating) }}</span>
                        </div>
                        @if($u->komentar)<p class="text-sm text-slate-500 mt-1">"{{ $u->komentar }}"</p>@endif
                        <p class="text-xs text-slate-400 mt-2">{{ $u->titipan?->lokasi_warung }} &middot; {{ $u->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">Belum ada ulasan yang kamu terima.</p>
                @endforelse
            </div>
            <div class="mt-3">{{ $diterima->links() }}</div>
        </div>

        <div>
            <h3 class="font-semibold text-slate-800 mb-3">Ulasan yang Kamu Berikan</h3>
            <div class="space-y-3">
                @forelse($diberikan as $u)
                    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <p class="font-medium text-slate-800">Untuk {{ $u->untukUser?->name }}</p>
                            <span class="text-blue-500 text-sm">{{ str_repeat('★', $u->rating) }}{{ str_repeat('☆', 5-$u->rating) }}</span>
                        </div>
                        @if($u->komentar)<p class="text-sm text-slate-500 mt-1">"{{ $u->komentar }}"</p>@endif
                        <div class="flex items-center justify-between mt-2">
                            <p class="text-xs text-slate-400">{{ $u->titipan?->lokasi_warung }} &middot; {{ $u->created_at->diffForHumans() }}</p>
                            <form method="POST" action="{{ route('ulasan.destroy', $u) }}" onsubmit="return confirm('Hapus ulasan ini?')">
                                @csrf @method('DELETE')
                                <button class="text-xs text-rose-500 hover:underline">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">Kamu belum memberi ulasan.</p>
                @endforelse
            </div>
            <div class="mt-3">{{ $diberikan->links() }}</div>
        </div>
    </div>
</x-app-layout>
