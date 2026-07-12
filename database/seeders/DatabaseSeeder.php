<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Urutan penting: Role dulu (biar assignRole di UserSeeder tidak error),
     * baru User, baru data transaksional Titipan (+item & ulasan).
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            TitipanSeeder::class,
        ]);
    }
}
