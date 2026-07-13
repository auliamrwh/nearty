<x-app-layout>
    @php($title = 'Buat Titipan Baru')

    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="max-w-3xl">
        <form method="POST" action="{{ route('titipan.store') }}" x-data="titipanForm()" class="space-y-6">
            @csrf

            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-1 space-y-4">
                <h2 class="font-semibold text-slate-800">Lokasi & Pembayaran</h2>

                <div>
                    <x-input-label value="Nama / Lokasi Warung" />
                    <x-text-input name="lokasi_warung" class="w-full mt-1" value="{{ old('lokasi_warung') }}" required placeholder="Contoh: Warung Bu Siti, Gang 3" />
                    <x-input-error :messages="$errors->get('lokasi_warung')" class="mt-1" />
                </div>

                {{-- Map picker --}}
                <div>
                    <x-input-label value="Pin Lokasi Warung di Peta" />
                    <p class="text-xs text-slate-400 mb-2">Klik peta untuk menandai lokasi warung. Ini digunakan driver untuk menghitung jarak.</p>
                    <div id="map-picker" class="w-full rounded-xl border border-slate-300 overflow-hidden" style="height: 260px;"></div>
                    <div id="map-info" class="hidden mt-2 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg px-3 py-2">
                        📍 Lokasi ditandai — koordinat tersimpan.
                    </div>
                    <input type="hidden" name="latitude"  id="input-lat"  value="{{ old('latitude') }}">
                    <input type="hidden" name="longitude" id="input-lng"  value="{{ old('longitude') }}">
                </div>

                <div>
                    <x-input-label value="Alamat Antar (kost kamu)" />
                    <x-text-input name="alamat_antar" class="w-full mt-1" value="{{ old('alamat_antar') }}" required placeholder="Kost Melati, Kamar 5" />
                    <x-input-error :messages="$errors->get('alamat_antar')" class="mt-1" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label value="Metode Bayar" />
                        <select name="metode_bayar" class="w-full mt-1 rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
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

            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="font-semibold text-slate-800">Daftar Barang Titipan</h2>
                    <button type="button" @click="addItem()" class="text-sm font-medium text-blue-700">+ Tambah Barang</button>
                </div>

                <template x-for="(item, i) in items" :key="i">
                    <div class="grid grid-cols-12 gap-2 items-start mb-3 border-b border-slate-100 pb-3">
                        <div class="col-span-4">
                            <input type="text" :name="`items[${i}][nama_item]`" x-model="item.nama_item" required
                                   placeholder="Nama barang" class="w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
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
                                   placeholder="Estimasi harga" class="w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="col-span-2">
                            <input type="text" :name="`items[${i}][catatan]`" x-model="item.catatan"
                                   placeholder="Catatan (opsional)" class="w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="col-span-1 text-right">
                            <button type="button" @click="removeItem(i)" class="text-rose-500 text-sm" x-show="items.length > 1">Hapus</button>
                        </div>
                    </div>
                </template>
                <x-input-error :messages="$errors->get('items')" class="mt-1" />
            </div>

            <button type="submit" class="px-6 py-3 rounded-xl bg-blue-500 text-[#0f172a] font-semibold hover:bg-blue-400 transition-all duration-300 ease-in-out hover:scale-105 active:scale-95">
                Kirim Titipan, Cari Driver
            </button>
        </form>
    </div>

    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

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

        // Initialize Leaflet map
        document.addEventListener('DOMContentLoaded', function () {
            const defaultLat = {{ old('latitude', -6.2088) }};
            const defaultLng = {{ old('longitude', 106.8456) }};

            const map = L.map('map-picker').setView([defaultLat, defaultLng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            let marker = null;

            // Restore marker if old value exists (after validation error)
            @if(old('latitude') && old('longitude'))
                marker = L.marker([{{ old('latitude') }}, {{ old('longitude') }}]).addTo(map);
                document.getElementById('map-info').classList.remove('hidden');
            @endif

            // Try to center on user's current location
            if (navigator.geolocation && !{{ old('latitude') ? 'true' : 'false' }}) {
                navigator.geolocation.getCurrentPosition(function (pos) {
                    map.setView([pos.coords.latitude, pos.coords.longitude], 16);
                });
            }

            map.on('click', function (e) {
                const { lat, lng } = e.latlng;

                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng]).addTo(map);
                }

                document.getElementById('input-lat').value = lat.toFixed(7);
                document.getElementById('input-lng').value = lng.toFixed(7);
                document.getElementById('map-info').classList.remove('hidden');
            });
        });
    </script>
</x-app-layout>

