<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware khusus role Driver.
 *
 * Nearty tidak punya kolom role "driver" tersendiri di tabel roles karena satu akun
 * bisa jadi pembeli sekaligus driver (fleksibel, sesuai kebiasaan anak kost saling
 * titip). Status "aktif jadi driver" ditandai lewat kolom `is_driver_active` di
 * tabel users. Middleware ini memastikan aksi-aksi milik driver (ambil order,
 * update status antar) hanya bisa diakses user yang sedang mengaktifkan mode driver.
 */
class EnsureUserIsDriver
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user || ! $user->is_driver_active) {
            return back()->with('error', 'Aktifkan mode driver dulu sebelum mengambil atau memperbarui titipan.');
        }

        return $next($request);
    }
}
