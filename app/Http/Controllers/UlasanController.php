<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUlasanRequest;
use App\Models\Titipan;
use App\Models\Ulasan;
use Illuminate\Support\Facades\Auth;

class UlasanController extends Controller
{
    /** Riwayat ulasan yang diterima & diberikan user ini. */
    public function index()
    {
        $user = Auth::user();

        $diterima = $user->ulasanDiterima()->with(['dariUser', 'titipan'])->latest()->paginate(6, ['*'], 'diterima');
        $diberikan = $user->ulasanDiberikan()->with(['untukUser', 'titipan'])->latest()->paginate(6, ['*'], 'diberikan');

        return view('ulasan.index', compact('diterima', 'diberikan'));
    }

    /** Beri ulasan untuk titipan yang sudah selesai. */
    public function store(StoreUlasanRequest $request, Titipan $titipan)
    {
        abort_unless($titipan->status === 'selesai', 422, 'Ulasan hanya bisa diberikan setelah titipan selesai.');

        $user = Auth::user();
        $untukUserId = null;
        $peran = null;

        if ($titipan->pembeli_id === $user->id) {
            $untukUserId = $titipan->driver_id;
            $peran = 'pembeli';
        } elseif ($titipan->driver_id === $user->id) {
            $untukUserId = $titipan->pembeli_id;
            $peran = 'driver';
        } else {
            abort(403);
        }

        abort_if(is_null($untukUserId), 422, 'Belum ada pasangan transaksi untuk diberi ulasan.');

        Ulasan::updateOrCreate(
            ['titipan_id' => $titipan->id, 'dari_user_id' => $user->id],
            [
                'untuk_user_id' => $untukUserId,
                'peran_pemberi' => $peran,
                'rating' => $request->rating,
                'komentar' => $request->komentar,
            ]
        );

        return back()->with('success', 'Terima kasih sudah memberi ulasan!');
    }

    public function destroy(Ulasan $ulasan)
    {
        abort_unless($ulasan->dari_user_id === Auth::id() || Auth::user()->isAdmin(), 403);
        $ulasan->delete();

        return back()->with('success', 'Ulasan dihapus.');
    }
}
