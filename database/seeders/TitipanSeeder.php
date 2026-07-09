<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Titipan;
use App\Models\ItemTitipan;
use App\Models\User;

class TitipanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pembeli = User::where('email', 'pembeli@example.com')->first();

    $titipan = Titipan::create([
        'pembeli_id' => $pembeli->id,
        'sumber_lokasi' => 'manual',
        'lokasi_warung' => 'Warung Bu Siti, Gang 3',
        'alamat_antar' => 'Kost Melati, Kamar 5',
        'metode_bayar' => 'cod',
        'status' => 'menunggu',
        'ongkir' => 3000,
        'estimasi_total' => 15000,
    ]);

    ItemTitipan::insert([
        ['titipan_id' => $titipan->id, 'nama_item' => 'Es Teh', 'jumlah' => 1, 'estimasi_harga' => 4000, 'created_at' => now(), 'updated_at' => now()],
        ['titipan_id' => $titipan->id, 'nama_item' => 'Baso Ikan', 'jumlah' => 1, 'estimasi_harga' => 6000, 'created_at' => now(), 'updated_at' => now()],
        ['titipan_id' => $titipan->id, 'nama_item' => 'Kentang Goreng', 'jumlah' => 1, 'catatan' => 'pedas dikit', 'estimasi_harga' => 5000, 'created_at' => now(), 'updated_at' => now()],
    ]);
    }
}
