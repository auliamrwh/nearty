<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Hapus status 'diantar' dari enum kolom status di tabel titipans.
     * Alur status yang baru: menunggu → diambil_driver → dibayar → selesai → (dibatalkan).
     */
    public function up(): void
    {
        // Migrasi data: baris yang masih 'diantar' dipindah ke 'diambil_driver'
        DB::table('titipans')->where('status', 'diantar')->update(['status' => 'diambil_driver']);

        // Perbarui definisi ENUM di MySQL (tanpa 'diantar')
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE titipans MODIFY COLUMN status ENUM(
                'menunggu', 'diambil_driver', 'dibayar', 'selesai', 'dibatalkan'
            ) NOT NULL DEFAULT 'menunggu'");
        }
    }

    public function down(): void
    {
        // Kembalikan enum ke versi sebelumnya (dengan 'diantar')
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE titipans MODIFY COLUMN status ENUM(
                'menunggu', 'diambil_driver', 'diantar', 'dibayar', 'selesai', 'dibatalkan'
            ) NOT NULL DEFAULT 'menunggu'");
        }
    }
};
