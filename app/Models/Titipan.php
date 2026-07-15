<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Titipan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'pembeli_id', 'driver_id', 'sumber_lokasi', 'lokasi_warung',
        'latitude', 'longitude', 'alamat_antar', 'metode_bayar',
        'status', 'alasan_batal', 'ongkir', 'estimasi_total', 'total_aktual',
        'sudah_dibayar', 'sudah_diterima', 'bukti_bayar',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'ongkir' => 'decimal:2',
            'estimasi_total' => 'decimal:2',
            'total_aktual' => 'decimal:2',
            'sudah_dibayar' => 'boolean',
            'sudah_diterima' => 'boolean',
        ];
    }

    public const STATUS_LABEL = [
        'menunggu' => 'Menunggu Driver',
        'diambil_driver' => 'Diambil Driver',
        'dibayar' => 'Sudah Dibayar',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
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

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class);
    }

    public function statusLabel(): string
    {
        return self::STATUS_LABEL[$this->status] ?? $this->status;
    }

    /** Titipan yang masih terbuka untuk diambil driver (belum ada driver, belum batal). */
    public function scopeTersedia(Builder $query): Builder
    {
        return $query->whereNull('driver_id')->where('status', 'menunggu');
    }

    /** Pencarian sederhana untuk fitur search di tabel index. */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('lokasi_warung', 'like', "%{$term}%")
              ->orWhere('alamat_antar', 'like', "%{$term}%")
              ->orWhere('status', 'like', "%{$term}%");
        });
    }

    /**
     * Jarak (km) dari titik driver ke lokasi warung memakai rumus Haversine sederhana.
     * Dipakai untuk fitur "Fokus Jarak Dekat" pada mode driver.
     */
    public function jarakDariKm(float $lat, float $lng): ?float
    {
        if (is_null($this->latitude) || is_null($this->longitude)) {
            return null;
        }

        $earthRadius = 6371;
        $dLat = deg2rad((float) $this->latitude - $lat);
        $dLng = deg2rad((float) $this->longitude - $lng);

        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat)) * cos(deg2rad((float) $this->latitude)) * sin($dLng / 2) ** 2;
        $c = 2 * asin(sqrt($a));

        return round($earthRadius * $c, 2);
    }
}
