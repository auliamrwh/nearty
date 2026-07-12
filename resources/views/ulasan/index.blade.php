<x-app-layout>
    @php($title = 'Ulasan')

    <div class="grid lg:grid-cols-2 gap-6">
        <div>
            <h3 class="font-semibold text-stone-800 mb-3">Ulasan Diterima</h3>
            <div class="space-y-3">
                @forelse($diterima as $u)
                    <div class="bg-white rounded-2xl border border-stone-200 p-4 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="font-medium text-stone-800">{{ $u->dariUser?->name }}</p>
                            <span class="text-amber-500 text-sm">{{ str_repeat('★', $u->rating) }}{{ str_repeat('☆', 5-$u->rating) }}</span>
                        </div>
                        @if($u->komentar)<p class="text-sm text-stone-500 mt-1">"{{ $u->komentar }}"</p>@endif
                        <p class="text-xs text-stone-400 mt-2">{{ $u->titipan?->lokasi_warung }} &middot; {{ $u->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <p class="text-sm text-stone-400">Belum ada ulasan yang kamu terima.</p>
                @endforelse
            </div>
            <div class="mt-3">{{ $diterima->links() }}</div>
        </div>

        <div>
            <h3 class="font-semibold text-stone-800 mb-3">Ulasan yang Kamu Berikan</h3>
            <div class="space-y-3">
                @forelse($diberikan as $u)
                    <div class="bg-white rounded-2xl border border-stone-200 p-4 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="font-medium text-stone-800">Untuk {{ $u->untukUser?->name }}</p>
                            <span class="text-amber-500 text-sm">{{ str_repeat('★', $u->rating) }}{{ str_repeat('☆', 5-$u->rating) }}</span>
                        </div>
                        @if($u->komentar)<p class="text-sm text-stone-500 mt-1">"{{ $u->komentar }}"</p>@endif
                        <div class="flex items-center justify-between mt-2">
                            <p class="text-xs text-stone-400">{{ $u->titipan?->lokasi_warung }} &middot; {{ $u->created_at->diffForHumans() }}</p>
                            <form method="POST" action="{{ route('ulasan.destroy', $u) }}" onsubmit="return confirm('Hapus ulasan ini?')">
                                @csrf @method('DELETE')
                                <button class="text-xs text-rose-500 hover:underline">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-stone-400">Kamu belum memberi ulasan.</p>
                @endforelse
            </div>
            <div class="mt-3">{{ $diberikan->links() }}</div>
        </div>
    </div>
</x-app-layout>
