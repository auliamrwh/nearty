<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('item_titipans', function (Blueprint $table) {
            $table->softDeletes();
            $table->enum('kategori', ['makanan', 'minuman', 'lainnya'])
                  ->default('lainnya')
                  ->after('nama_item');
        });
    }

    public function down(): void
    {
        Schema::table('item_titipans', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('kategori');
        });
    }
};
