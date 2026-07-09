<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
        'name' => 'Anak Kost A',
        'email' => 'pembeli@example.com',
        'phone' => '081234567890',
        'password' => bcrypt('password'),
        'is_driver_active' => false,
    ]);

    User::create([
        'name' => 'Anak Kost B',
        'email' => 'driver@example.com',
        'phone' => '089876543210',
        'password' => bcrypt('password'),
        'is_driver_active' => true, // lagi available jadi driver
    ]);
    }
}
