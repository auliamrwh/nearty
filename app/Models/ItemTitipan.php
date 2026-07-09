<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemTitipan extends Model
{
    protected $fillable = ['titipan_id', 'nama_item', 'jumlah', 'catatan', 'estimasi_harga'];

public function titipan()
{
    return $this->belongsTo(Titipan::class);
}
}
