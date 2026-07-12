<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('titipans', function (Blueprint $table) {
            $table->softDeletes();
            $table->string('alasan_batal')->nullable()->after('status');
            $table->boolean('sudah_dibayar')->default(false)->after('total_aktual');
            $table->boolean('sudah_diterima')->default(false)->after('sudah_dibayar');
        });

        // Perluas enum status agar mendukung alur konfirmasi bayar 2 arah.
        // MySQL: enum kolom perlu diubah lewat raw statement.
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE titipans MODIFY COLUMN status ENUM(
                'menunggu', 'diambil_driver', 'diantar', 'dibayar', 'selesai', 'dibatalkan'
            ) NOT NULL DEFAULT 'menunggu'");
        }
    }

    public function down(): void
    {
        Schema::table('titipans', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['alasan_batal', 'sudah_dibayar', 'sudah_diterima']);
        });
    }
};
