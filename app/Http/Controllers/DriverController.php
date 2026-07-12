<?php

namespace App\Http\Controllers;

use App\Models\Titipan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DriverController extends Controller
{
    /** Halaman mode driver: daftar titipan terdekat yang bisa diambil. */
    public function index(Request $request)
    {
        $user = Auth::user();

        $lat = $request->get('lat');
        $lng = $request->get('lng');

        $titipans = Titipan::tersedia()
            ->where('pembeli_id', '!=', $user->id)
            ->with('items', 'pembeli')
            ->latest()
            ->get();

        if ($lat && $lng) {
            $titipans = $titipans
                ->map(function ($t) use ($lat, $lng) {
                    $t->jarak_km = $t->jarakDariKm((float) $lat, (float) $lng);

                    return $t;
                })
                ->sortBy('jarak_km')
                ->values();
        }

        $sedangDiantar = Titipan::where('driver_id', $user->id)
            ->whereIn('status', ['diambil_driver', 'diantar'])
            ->with('items', 'pembeli')
            ->get();

        return view('driver.index', compact('titipans', 'sedangDiantar', 'user'));
    }

    /** Toggle status "available jadi driver" (kolom is_driver_active). */
    public function toggle()
    {
        $user = Auth::user();
        $user->update(['is_driver_active' => ! $user->is_driver_active]);

        return back()->with('success', $user->is_driver_active
            ? 'Kamu sekarang available jadi driver.'
            : 'Kamu berhenti jadi driver.');
    }

    /** Ambil order (assign driver_id ke titipan yang masih menunggu). */
    public function ambil(Titipan $titipan)
    {
        abort_unless(Auth::user()->is_driver_active, 403, 'Aktifkan mode driver dulu.');

        if ($titipan->status !== 'menunggu' || $titipan->driver_id !== null) {
            return back()->with('error', 'Titipan sudah diambil driver lain.');
        }

        $titipan->update([
            'driver_id' => Auth::id(),
            'status' => 'diambil_driver',
        ]);

        return back()->with('success', 'Titipan berhasil diambil. Yuk belanja & antar!');
    }

    /** Update status pengantaran oleh driver: diambil_driver -> diantar -> dibayar -> selesai. */
    public function updateStatus(Request $request, Titipan $titipan)
    {
        abort_unless($titipan->driver_id === Auth::id(), 403);

        $request->validate([
            'status' => ['required', Rule::in(['diantar', 'dibayar', 'selesai'])],
            'total_aktual' => ['nullable', 'numeric', 'min:0'],
        ]);

        $data = ['status' => $request->status];

        if ($request->status === 'dibayar') {
            $data['sudah_dibayar'] = true;
            if ($request->filled('total_aktual')) {
                $data['total_aktual'] = $request->total_aktual;
            }
        }

        if ($request->status === 'selesai') {
            $data['sudah_diterima'] = true;
        }

        $titipan->update($data);

        return back()->with('success', 'Status titipan diperbarui: '.$titipan->fresh()->statusLabel());
    }
}
