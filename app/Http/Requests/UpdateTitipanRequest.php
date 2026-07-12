<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTitipanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lokasi_warung' => ['required', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'alamat_antar' => ['required', 'string', 'max:255'],
            'metode_bayar' => ['required', 'in:qr,cod'],
            'ongkir' => ['required', 'numeric', 'min:0'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['nullable', 'integer'],
            'items.*.nama_item' => ['required', 'string', 'max:255'],
            'items.*.kategori' => ['required', 'in:makanan,minuman,lainnya'],
            'items.*.jumlah' => ['required', 'integer', 'min:1'],
            'items.*.estimasi_harga' => ['nullable', 'numeric', 'min:0'],
            'items.*.catatan' => ['nullable', 'string', 'max:255'],
        ];
    }
}
