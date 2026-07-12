<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasRoles, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'is_driver_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_driver_active' => 'boolean',
        ];
    }

    /** Titipan yang dibuat user ini sebagai pembeli. */
    public function titipanSebagaiPembeli()
    {
        return $this->hasMany(Titipan::class, 'pembeli_id');
    }

    /** Titipan yang diambil user ini sebagai driver. */
    public function titipanSebagaiDriver()
    {
        return $this->hasMany(Titipan::class, 'driver_id');
    }

    /** Ulasan yang diberikan user ini ke orang lain. */
    public function ulasanDiberikan()
    {
        return $this->hasMany(Ulasan::class, 'dari_user_id');
    }

    /** Ulasan yang diterima user ini dari orang lain. */
    public function ulasanDiterima()
    {
        return $this->hasMany(Ulasan::class, 'untuk_user_id');
    }

    /** Rata-rata rating yang diterima user ini (sebagai driver maupun pembeli). */
    public function getRataRatingAttribute(): ?float
    {
        $avg = $this->ulasanDiterima()->avg('rating');

        return $avg ? round($avg, 1) : null;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
}
