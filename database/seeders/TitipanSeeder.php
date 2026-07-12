<?php

namespace Database\Seeders;

use App\Models\ItemTitipan;
use App\Models\Titipan;
use App\Models\Ulasan;
use App\Models\User;
use Illuminate\Database\Seeder;

class TitipanSeeder extends Seeder
{
    /**
     * Seed beberapa titipan dengan status berbeda-beda supaya dashboard, chart,
     * dan fitur search/pagination punya data untuk didemokan dari database kosong.
     */
    public function run(): void
    {
        $pembeli = User::where('email', 'pembeli@example.com')->first();
        $driver = User::where('email', 'driver@example.com')->first();
        $regina = User::where('email', 'regina@example.com')->first();

        // 1) Titipan masih menunggu driver (untuk fitur "Mode Driver")
        $t1 = Titipan::create([
            'pembeli_id' => $pembeli->id,
            'sumber_lokasi' => 'manual',
            'lokasi_warung' => 'Warung Bu Siti, Gang 3',
            'latitude' => -6.921500,
            'longitude' => 107.607200,
            'alamat_antar' => 'Kost Melati, Kamar 5',
            'metode_bayar' => 'cod',
            'status' => 'menunggu',
            'ongkir' => 3000,
            'estimasi_total' => 15000,
        ]);

        ItemTitipan::insert([
            ['titipan_id' => $t1->id, 'nama_item' => 'Es Teh', 'kategori' => 'minuman', 'jumlah' => 1, 'estimasi_harga' => 4000, 'created_at' => now(), 'updated_at' => now()],
            ['titipan_id' => $t1->id, 'nama_item' => 'Baso Ikan', 'kategori' => 'makanan', 'jumlah' => 1, 'estimasi_harga' => 6000, 'created_at' => now(), 'updated_at' => now()],
            ['titipan_id' => $t1->id, 'nama_item' => 'Kentang Goreng', 'kategori' => 'makanan', 'jumlah' => 1, 'catatan' => 'pedas dikit', 'estimasi_harga' => 5000, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2) Titipan sedang diantar driver
        $t2 = Titipan::create([
            'pembeli_id' => $regina->id,
            'driver_id' => $driver->id,
            'sumber_lokasi' => 'manual',
            'lokasi_warung' => 'Warung Kopi Pak Jaya',
            'latitude' => -6.922800,
            'longitude' => 107.608900,
            'alamat_antar' => 'Kost Anggrek, Kamar 2',
            'metode_bayar' => 'qr',
            'status' => 'diantar',
            'ongkir' => 4000,
            'estimasi_total' => 22000,
        ]);

        ItemTitipan::insert([
            ['titipan_id' => $t2->id, 'nama_item' => 'Kopi Susu', 'kategori' => 'minuman', 'jumlah' => 2, 'estimasi_harga' => 20000, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 3) Titipan selesai + sudah ada ulasan (demo fitur rating & riwayat)
        $t3 = Titipan::create([
            'pembeli_id' => $pembeli->id,
            'driver_id' => $driver->id,
            'sumber_lokasi' => 'manual',
            'lokasi_warung' => 'Nasi Goreng Mas Budi',
            'latitude' => -6.920100,
            'longitude' => 107.606500,
            'alamat_antar' => 'Kost Melati, Kamar 5',
            'metode_bayar' => 'cod',
            'status' => 'selesai',
            'sudah_dibayar' => true,
            'sudah_diterima' => true,
            'ongkir' => 3000,
            'estimasi_total' => 18000,
            'total_aktual' => 18000,
        ]);

        ItemTitipan::insert([
            ['titipan_id' => $t3->id, 'nama_item' => 'Nasi Goreng Spesial', 'kategori' => 'makanan', 'jumlah' => 1, 'estimasi_harga' => 18000, 'created_at' => now(), 'updated_at' => now()],
        ]);

        Ulasan::create([
            'titipan_id' => $t3->id,
            'dari_user_id' => $pembeli->id,
            'untuk_user_id' => $driver->id,
            'peran_pemberi' => 'pembeli',
            'rating' => 5,
            'komentar' => 'Cepat sampai, ramah, terima kasih!',
        ]);

        // 4) Titipan dibatalkan (demo kolom alasan_batal)
        Titipan::create([
            'pembeli_id' => $regina->id,
            'sumber_lokasi' => 'manual',
            'lokasi_warung' => 'Warung Seblak Ceu Popon',
            'alamat_antar' => 'Kost Anggrek, Kamar 2',
            'metode_bayar' => 'cod',
            'status' => 'dibatalkan',
            'alasan_batal' => 'Warungnya sudah tutup lebih awal.',
            'ongkir' => 3000,
            'estimasi_total' => 12000,
        ]);
    }
}
