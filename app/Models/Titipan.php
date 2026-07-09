<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Titipan extends Model
{
    protected $fillable = [
        'pembeli_id', 'driver_id', 'sumber_lokasi', 'lokasi_warung',
        'latitude', 'longitude', 'alamat_antar', 'metode_bayar',
        'status', 'ongkir', 'estimasi_total', 'total_aktual', 'bukti_bayar',
    ];

    public function pembeli()
    {
        return $this->belongsTo(User::class, 'pembeli_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function items()
    {
        return $this->hasMany(ItemTitipan::class);
    }
}
