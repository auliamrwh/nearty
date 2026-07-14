<?php

namespace App\Http\Requests;

class UpdateUlasanRequest extends StoreUlasanRequest
{
    // Mewarisi authorize() & rules() dari StoreUlasanRequest.
    // Otorisasi sebenarnya dilakukan di controller (cek dari_user_id),
    // sehingga di sini cukup return true.
}
