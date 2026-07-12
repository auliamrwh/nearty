<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ulasan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'titipan_id', 'dari_user_id', 'untuk_user_id', 'peran_pemberi', 'rating', 'komentar',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    public function titipan()
    {
        return $this->belongsTo(Titipan::class);
    }

    public function dariUser()
    {
        return $this->belongsTo(User::class, 'dari_user_id');
    }

    public function untukUser()
    {
        return $this->belongsTo(User::class, 'untuk_user_id');
    }
}
