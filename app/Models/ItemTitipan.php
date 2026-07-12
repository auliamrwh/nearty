<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemTitipan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'titipan_id', 'nama_item', 'kategori', 'jumlah', 'catatan', 'estimasi_harga',
    ];

    protected function casts(): array
    {
        return [
            'estimasi_harga' => 'decimal:2',
        ];
    }

    public function titipan()
    {
        return $this->belongsTo(Titipan::class);
    }
}
