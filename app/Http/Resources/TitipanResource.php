<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TitipanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pembeli' => $this->pembeli?->only(['id', 'name']),
            'driver' => $this->driver?->only(['id', 'name']),
            'lokasi_warung' => $this->lokasi_warung,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'alamat_antar' => $this->alamat_antar,
            'metode_bayar' => $this->metode_bayar,
            'status' => $this->status,
            'status_label' => $this->statusLabel(),
            'alasan_batal' => $this->alasan_batal,
            'ongkir' => $this->ongkir,
            'estimasi_total' => $this->estimasi_total,
            'total_aktual' => $this->total_aktual,
            'sudah_dibayar' => $this->sudah_dibayar,
            'sudah_diterima' => $this->sudah_diterima,
            'items' => $this->whenLoaded('items', fn () => $this->items->map(fn ($i) => [
                'id' => $i->id,
                'nama_item' => $i->nama_item,
                'kategori' => $i->kategori,
                'jumlah' => $i->jumlah,
                'estimasi_harga' => $i->estimasi_harga,
                'catatan' => $i->catatan,
            ])),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
