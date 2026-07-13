<x-app-layout>
    @php($title = 'Ubah Titipan')

    <div class="max-w-3xl">
        <form method="POST" action="{{ route('titipan.update', $titipan) }}"
              x-data="titipanForm({{ $titipan->items->map(fn($i) => [
                  'id' => $i->id, 'nama_item' => $i->nama_item, 'kategori' => $i->kategori,
                  'jumlah' => $i->jumlah, 'estimasi_harga' => $i->estimasi_harga, 'catatan' => $i->catatan,
              ])->values()->toJson() }})"
              class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-1 space-y-4">
                <h2 class="font-semibold text-slate-800">Lokasi & Pembayaran</h2>

                <div>
                    <x-input-label value="Nama / Lokasi Warung" />
                    <x-text-input name="lokasi_warung" class="w-full mt-1" value="{{ old('lokasi_warung', $titipan->lokasi_warung) }}" required />
                    <x-input-error :messages="$errors->get('lokasi_warung')" class="mt-1" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label value="Latitude" />
                        <x-text-input name="latitude" class="w-full mt-1" value="{{ old('latitude', $titipan->latitude) }}" />
                    </div>
                    <div>
                        <x-input-label value="Longitude" />
                        <x-text-input name="longitude" class="w-full mt-1" value="{{ old('longitude', $titipan->longitude) }}" />
                    </div>
                </div>

                <div>
                    <x-input-label value="Alamat Antar" />
                    <x-text-input name="alamat_antar" class="w-full mt-1" value="{{ old('alamat_antar', $titipan->alamat_antar) }}" required />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label value="Metode Bayar" />
                        <select name="metode_bayar" class="w-full mt-1 rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="cod" @selected($titipan->metode_bayar === 'cod')>COD</option>
                            <option value="qr" @selected($titipan->metode_bayar === 'qr')>QR</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label value="Ongkir (Rp)" />
                        <x-text-input type="number" name="ongkir" class="w-full mt-1" value="{{ old('ongkir', $titipan->ongkir) }}" required />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="font-semibold text-slate-800">Daftar Barang Titipan</h2>
                    <button type="button" @click="addItem()" class="text-sm font-medium text-blue-700">+ Tambah Barang</button>
                </div>

                <template x-for="(item, i) in items" :key="i">
                    <div class="grid grid-cols-12 gap-2 items-start mb-3 border-b border-slate-100 pb-3">
                        <input type="hidden" :name="`items[${i}][id]`" x-model="item.id">
                        <div class="col-span-4">
                            <input type="text" :name="`items[${i}][nama_item]`" x-model="item.nama_item" required
                                   class="w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="col-span-2">
                            <select :name="`items[${i}][kategori]`" x-model="item.kategori" class="w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="makanan">Makanan</option>
                                <option value="minuman">Minuman</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-span-1">
                            <input type="number" :name="`items[${i}][jumlah]`" x-model="item.jumlah" min="1" required
                                   class="w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="col-span-2">
                            <input type="number" :name="`items[${i}][estimasi_harga]`" x-model="item.estimasi_harga"
                                   class="w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="col-span-2">
                            <input type="text" :name="`items[${i}][catatan]`" x-model="item.catatan"
                                   class="w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="col-span-1 text-right">
                            <button type="button" @click="removeItem(i)" class="text-rose-500 text-sm" x-show="items.length > 1">Hapus</button>
                        </div>
                    </div>
                </template>
            </div>

            <button type="submit" class="px-6 py-3 rounded-xl bg-blue-500 text-[#0f172a] font-semibold hover:bg-blue-400 transition-all duration-300 ease-in-out hover:scale-105 active:scale-95">
                Simpan Perubahan
            </button>
        </form>
    </div>

    <script>
        function titipanForm(initialItems) {
            return {
                items: initialItems && initialItems.length ? initialItems : [{ nama_item: '', kategori: 'makanan', jumlah: 1, estimasi_harga: '', catatan: '' }],
                addItem() {
                    this.items.push({ nama_item: '', kategori: 'makanan', jumlah: 1, estimasi_harga: '', catatan: '' });
                },
                removeItem(i) {
                    this.items.splice(i, 1);
                }
            }
        }
    </script>
</x-app-layout>
