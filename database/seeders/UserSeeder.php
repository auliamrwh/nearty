<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Akun demo untuk keperluan review & video demo (JANGAN demo dengan database kosong).
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin Nearty',
            'email' => 'admin@nearty.test',
            'phone' => '081200000000',
            'password' => bcrypt('password'),
            'is_driver_active' => false,
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        $pembeli = User::create([
            'name' => 'Aulia (Anak Kost A)',
            'email' => 'pembeli@example.com',
            'phone' => '081234567890',
            'password' => bcrypt('password'),
            'is_driver_active' => false,
            'email_verified_at' => now(),
        ]);
        $pembeli->assignRole('user');

        $driver = User::create([
            'name' => 'Hawa (Anak Kost B)',
            'email' => 'driver@example.com',
            'phone' => '089876543210',
            'password' => bcrypt('password'),
            'is_driver_active' => true, // lagi available jadi driver
            'email_verified_at' => now(),
        ]);
        $driver->assignRole('user');

        $keduanya = User::create([
            'name' => 'Regina (Anak Kost C)',
            'email' => 'regina@example.com',
            'phone' => '081211112222',
            'password' => bcrypt('password'),
            'is_driver_active' => true,
            'email_verified_at' => now(),
        ]);
        $keduanya->assignRole('user');
    }
}
