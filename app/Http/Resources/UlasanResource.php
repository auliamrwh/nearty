<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UlasanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'titipan_id' => $this->titipan_id,
            'dari_user' => $this->dariUser?->only(['id', 'name']),
            'untuk_user' => $this->untukUser?->only(['id', 'name']),
            'peran_pemberi' => $this->peran_pemberi,
            'rating' => $this->rating,
            'komentar' => $this->komentar,
            'created_at' => $this->created_at,
        ];
    }
}
