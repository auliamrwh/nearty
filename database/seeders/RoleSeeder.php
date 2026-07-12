<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Nearty tidak punya role tetap "pembeli"/"driver" (satu akun bisa dua-duanya,
     * ditentukan per baris titipan). Role Spatie di sini hanya membedakan hak akses
     * panel: "admin" (kelola user & moderasi) vs "user" (pengguna aplikasi biasa),
     * sesuai syarat wajib tugas besar (minimal 2 role).
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
    }
}
