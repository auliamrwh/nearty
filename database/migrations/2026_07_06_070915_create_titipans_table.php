<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('titipans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembeli_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('sumber_lokasi', ['maps', 'manual'])->default('manual');
            $table->string('lokasi_warung');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('alamat_antar');
            $table->enum('metode_bayar', ['qr', 'cod'])->default('cod');
            $table->enum('status', ['menunggu', 'diambil_driver', 'diantar', 'selesai', 'dibatalkan'])
                  ->default('menunggu');
            $table->decimal('ongkir', 10, 2)->default(0);
            $table->decimal('estimasi_total', 10, 2)->nullable();
            $table->decimal('total_aktual', 10, 2)->nullable();
            $table->string('bukti_bayar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('titipans');
    }
};
