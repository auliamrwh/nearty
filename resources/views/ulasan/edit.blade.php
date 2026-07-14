<x-app-layout>
    @php($title = 'Edit Ulasan')

    <div class="max-w-lg mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('ulasan.index') }}"
               class="text-slate-400 hover:text-slate-700 transition-colors duration-200">
                ← Kembali
            </a>
            <h2 class="font-semibold text-slate-800 text-lg">Edit Ulasan</h2>
        </div>

        {{-- Info titipan --}}
        @if($ulasan->titipan)
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-5 text-sm text-blue-800">
                <p class="font-medium">{{ $ulasan->titipan->lokasi_warung }}</p>
                <p class="text-blue-600 mt-0.5">{{ $ulasan->titipan->created_at->format('d M Y') }}</p>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <form action="{{ route('ulasan.update', $ulasan) }}" method="POST">
                @csrf
                @method('PATCH')

                {{-- Rating bintang interaktif --}}
                <div class="mb-5" x-data="{ rating: {{ old('rating', $ulasan->rating) }} }">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Rating</label>

                    <div class="flex gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button"
                                    @click="rating = {{ $i }}"
                                    class="text-3xl transition-all duration-150 focus:outline-none hover:scale-110"
                                    :class="rating >= {{ $i }} ? 'text-amber-400' : 'text-slate-300'">
                                ★
                            </button>
                        @endfor
                    </div>

                    <input type="hidden" name="rating" :value="rating">

                    @error('rating')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Komentar --}}
                <div class="mb-6">
                    <label for="komentar" class="block text-sm font-medium text-slate-700 mb-1">
                        Komentar <span class="text-slate-400 font-normal">(opsional)</span>
                    </label>
                    <textarea id="komentar" name="komentar" rows="4"
                              maxlength="255"
                              placeholder="Tulis komentar kamu di sini..."
                              class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                    >{{ old('komentar', $ulasan->komentar) }}</textarea>
                    @error('komentar')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('ulasan.index') }}"
                       class="px-4 py-2 rounded-xl text-sm text-slate-600 hover:bg-slate-100 transition-colors duration-200">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-5 py-2 rounded-xl bg-blue-500 text-white text-sm font-semibold hover:bg-blue-400 transition-all duration-300 hover:scale-105 active:scale-95">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
