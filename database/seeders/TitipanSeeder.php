<?php

namespace Database\Seeders;

use App\Models\ItemTitipan;
use App\Models\Titipan;
use App\Models\Ulasan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TitipanSeeder extends Seeder
{
    /**
     * Seed titipan dengan tanggal tersebar di 7 hari terakhir supaya
     * grafik tren di dashboard langsung kelihatan bergerak — bukan flat 0.
     */
    public function run(): void
    {
        $pembeli = User::where('email', 'pembeli@example.com')->first();
        $driver  = User::where('email', 'driver@example.com')->first();
        $regina  = User::where('email', 'regina@example.com')->first();

        // ── Data historis untuk grafik (6 hari lalu sampai kemarin) ─────────
        $historis = [
            // [hari_lalu, status, pembeli, driver_id, ongkir, estimasi]
            [6, 'selesai',   $pembeli, $driver->id,  3000, 15000],
            [5, 'selesai',   $regina,  $driver->id,  4000, 22000],
            [5, 'dibatalkan',$pembeli, null,          3000, 12000],
            [4, 'selesai',   $pembeli, $driver->id,  3000, 18000],
            [4, 'selesai',   $regina,  $driver->id,  5000, 25000],
            [3, 'selesai',   $pembeli, $driver->id,  3000, 14000],
            [3, 'dibatalkan',$regina,  null,          3000, 10000],
            [2, 'selesai',   $pembeli, $driver->id,  4000, 20000],
            [2, 'selesai',   $regina,  $driver->id,  3000, 16000],
            [1, 'selesai',   $pembeli, $driver->id,  3000, 18000],
            [1, 'diambil_driver', $regina,  $driver->id,  4000, 22000],
        ];

        $warungList = [
            'Warung Bu Siti, Gang 3',
            'Warung Kopi Pak Jaya',
            'Nasi Goreng Mas Budi',
            'Warung Seblak Ceu Popon',
            'Bakso Pak Hendra',
            'Martabak Mang Ucok',
        ];
        $itemList = [
            [['nama_item' => 'Es Teh', 'kategori' => 'minuman', 'jumlah' => 1, 'estimasi_harga' => 4000]],
            [['nama_item' => 'Kopi Susu', 'kategori' => 'minuman', 'jumlah' => 2, 'estimasi_harga' => 10000]],
            [['nama_item' => 'Nasi Goreng Spesial', 'kategori' => 'makanan', 'jumlah' => 1, 'estimasi_harga' => 18000]],
            [['nama_item' => 'Baso Ikan', 'kategori' => 'makanan', 'jumlah' => 2, 'estimasi_harga' => 12000]],
        ];

        foreach ($historis as $idx => [$hariLalu, $status, $pembeli_, $driverId, $ongkir, $estimasi]) {
            $tgl = Carbon::now()->subDays($hariLalu);
            $t = Titipan::create([
                'pembeli_id'     => $pembeli_->id,
                'driver_id'      => $driverId,
                'sumber_lokasi'  => 'manual',
                'lokasi_warung'  => $warungList[$idx % count($warungList)],
                'latitude'       => -6.9215 + ($idx * 0.001),
                'longitude'      => 107.6072 + ($idx * 0.001),
                'alamat_antar'   => 'Kost ' . ($idx % 2 === 0 ? 'Melati, Kamar 5' : 'Anggrek, Kamar 2'),
                'metode_bayar'   => $idx % 2 === 0 ? 'cod' : 'qr',
                'status'         => $status,
                'sudah_dibayar'  => $status === 'selesai',
                'sudah_diterima' => $status === 'selesai',
                'ongkir'         => $ongkir,
                'estimasi_total' => $estimasi,
                'total_aktual'   => $status === 'selesai' ? $estimasi : null,
                'alasan_batal'   => $status === 'dibatalkan' ? 'Warungnya sudah tutup.' : null,
                'created_at'     => $tgl,
                'updated_at'     => $status === 'selesai' ? $tgl->copy()->addHours(2) : $tgl,
            ]);

            $items = $itemList[$idx % count($itemList)];
            foreach ($items as $item) {
                ItemTitipan::create(array_merge($item, [
                    'titipan_id' => $t->id,
                    'created_at' => $tgl,
                    'updated_at' => $tgl,
                ]));
            }

            // Buat ulasan untuk titipan yang sudah selesai & ada driver
            if ($status === 'selesai' && $driverId) {
                Ulasan::create([
                    'titipan_id'    => $t->id,
                    'dari_user_id'  => $pembeli_->id,
                    'untuk_user_id' => $driverId,
                    'peran_pemberi' => 'pembeli',
                    'rating'        => rand(4, 5),
                    'komentar'      => $idx % 2 === 0 ? 'Cepat sampai, terima kasih!' : 'Pelayanan memuaskan!',
                    'created_at'    => $tgl->copy()->addHours(3),
                    'updated_at'    => $tgl->copy()->addHours(3),
                ]);
            }
        }

        // ── Data hari ini (untuk fitur aktif yang bisa didemo langsung) ──────

        // Titipan menunggu driver — bisa diambil di Mode Driver
        $t_hari_ini = Titipan::create([
            'pembeli_id'    => $pembeli->id,
            'sumber_lokasi' => 'manual',
            'lokasi_warung' => 'Warung Bu Siti, Gang 3',
            'latitude'      => -6.921500,
            'longitude'     => 107.607200,
            'alamat_antar'  => 'Kost Melati, Kamar 5',
            'metode_bayar'  => 'cod',
            'status'        => 'menunggu',
            'ongkir'        => 3000,
            'estimasi_total'=> 15000,
        ]);
        ItemTitipan::create([
            'titipan_id'    => $t_hari_ini->id,
            'nama_item'     => 'Es Teh',
            'kategori'      => 'minuman',
            'jumlah'        => 1,
            'estimasi_harga'=> 4000,
        ]);
        ItemTitipan::create([
            'titipan_id'    => $t_hari_ini->id,
            'nama_item'     => 'Baso Ikan',
            'kategori'      => 'makanan',
            'jumlah'        => 1,
            'estimasi_harga'=> 6000,
        ]);

        // Titipan sedang diambil driver (status diambil_driver untuk demo driver)
        $t_diantar = Titipan::create([
            'pembeli_id'    => $regina->id,
            'driver_id'     => $driver->id,
            'sumber_lokasi' => 'manual',
            'lokasi_warung' => 'Warung Kopi Pak Jaya',
            'latitude'      => -6.922800,
            'longitude'     => 107.608900,
            'alamat_antar'  => 'Kost Anggrek, Kamar 2',
            'metode_bayar'  => 'qr',
            'status'        => 'diambil_driver',
            'ongkir'        => 4000,
            'estimasi_total'=> 22000,
        ]);
        ItemTitipan::create([
            'titipan_id'    => $t_diantar->id,
            'nama_item'     => 'Kopi Susu',
            'kategori'      => 'minuman',
            'jumlah'        => 2,
            'estimasi_harga'=> 20000,
        ]);
    }
}
