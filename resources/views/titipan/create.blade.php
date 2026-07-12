<x-app-layout>
    @php($title = 'Buat Titipan Baru')

    <div class="max-w-3xl">
        <form method="POST" action="{{ route('titipan.store') }}" x-data="titipanForm()" class="space-y-6">
            @csrf

            <div class="bg-white rounded-2xl border border-stone-200 p-6 shadow-sm space-y-4">
                <h2 class="font-semibold text-stone-800">Lokasi & Pembayaran</h2>



                <div>
                    <x-input-label value="Nama / Lokasi Warung" />
                    <x-text-input name="lokasi_warung" class="w-full mt-1" value="{{ old('lokasi_warung') }}" required placeholder="Contoh: Warung Bu Siti, Gang 3" />
                    <x-input-error :messages="$errors->get('lokasi_warung')" class="mt-1" />
                </div>



                <div>
                    <x-input-label value="Alamat Antar (kost kamu)" />
                    <x-text-input name="alamat_antar" class="w-full mt-1" value="{{ old('alamat_antar') }}" required placeholder="Kost Melati, Kamar 5" />
                    <x-input-error :messages="$errors->get('alamat_antar')" class="mt-1" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label value="Metode Bayar" />
                        <select name="metode_bayar" class="w-full mt-1 rounded-xl border-stone-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                            <option value="cod">COD (bayar tunai saat sampai)</option>
                            <option value="qr">QR (transfer ke driver)</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label value="Ongkir yang kamu tawarkan (Rp)" />
                        <x-text-input type="number" name="ongkir" class="w-full mt-1" value="{{ old('ongkir', 3000) }}" required />
                        <x-input-error :messages="$errors->get('ongkir')" class="mt-1" />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-stone-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="font-semibold text-stone-800">Daftar Barang Titipan</h2>
                    <button type="button" @click="addItem()" class="text-sm font-medium text-amber-700">+ Tambah Barang</button>
                </div>

                <template x-for="(item, i) in items" :key="i">
                    <div class="grid grid-cols-12 gap-2 items-start mb-3 border-b border-stone-100 pb-3">
                        <div class="col-span-4">
                            <input type="text" :name="`items[${i}][nama_item]`" x-model="item.nama_item" required
                                   placeholder="Nama barang" class="w-full rounded-lg border-stone-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                        </div>
                        <div class="col-span-2">
                            <select :name="`items[${i}][kategori]`" x-model="item.kategori" class="w-full rounded-lg border-stone-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                                <option value="makanan">Makanan</option>
                                <option value="minuman">Minuman</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-span-1">
                            <input type="number" :name="`items[${i}][jumlah]`" x-model="item.jumlah" min="1" required
                                   class="w-full rounded-lg border-stone-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                        </div>
                        <div class="col-span-2">
                            <input type="number" :name="`items[${i}][estimasi_harga]`" x-model="item.estimasi_harga"
                                   placeholder="Estimasi harga" class="w-full rounded-lg border-stone-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                        </div>
                        <div class="col-span-2">
                            <input type="text" :name="`items[${i}][catatan]`" x-model="item.catatan"
                                   placeholder="Catatan (opsional)" class="w-full rounded-lg border-stone-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                        </div>
                        <div class="col-span-1 text-right">
                            <button type="button" @click="removeItem(i)" class="text-rose-500 text-sm" x-show="items.length > 1">Hapus</button>
                        </div>
                    </div>
                </template>
                <x-input-error :messages="$errors->get('items')" class="mt-1" />
            </div>

            <button type="submit" class="px-6 py-3 rounded-xl bg-amber-500 text-[#241C19] font-semibold hover:bg-amber-400 transition">
                Kirim Titipan, Cari Driver
            </button>
        </form>
    </div>

    <script>
        function titipanForm() {
            return {
                items: [{ nama_item: '', kategori: 'makanan', jumlah: 1, estimasi_harga: '', catatan: '' }],
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
