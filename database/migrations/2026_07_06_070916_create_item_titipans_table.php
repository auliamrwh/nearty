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
        Schema::create('item_titipans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('titipan_id')->constrained('titipans')->cascadeOnDelete();
            $table->string('nama_item');
            $table->integer('jumlah')->default(1);
            $table->string('catatan')->nullable();
            $table->decimal('estimasi_harga', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_titipans');
    }
};
