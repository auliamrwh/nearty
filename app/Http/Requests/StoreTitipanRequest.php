<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTitipanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // sudah dibatasi middleware auth di route
    }

    public function rules(): array
    {
        return [
            'lokasi_warung' => ['required', 'string', 'max:255'],
            'alamat_antar' => ['required', 'string', 'max:255'],
            'metode_bayar' => ['required', 'in:qr,cod'],
            'ongkir' => ['required', 'numeric', 'min:0'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.nama_item' => ['required', 'string', 'max:255'],
            'items.*.kategori' => ['required', 'in:makanan,minuman,lainnya'],
            'items.*.jumlah' => ['required', 'integer', 'min:1'],
            'items.*.estimasi_harga' => ['nullable', 'numeric', 'min:0'],
            'items.*.catatan' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Tambahkan minimal 1 barang yang mau dititipkan.',
            'items.*.nama_item.required' => 'Nama barang wajib diisi.',
        ];
    }
}
