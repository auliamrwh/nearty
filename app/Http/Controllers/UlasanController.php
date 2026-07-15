<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUlasanRequest;
use App\Http\Requests\UpdateUlasanRequest;
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

        // Titipan selesai yang melibatkan user ini (sebagai pembeli/driver) tapi belum dia ulas.
        $sudahDiulasIds = $user->ulasanDiberikan()->pluck('titipan_id');
        $belumDiulas = Titipan::where('status', 'selesai')
            ->where(function ($q) use ($user) {
                $q->where('pembeli_id', $user->id)->orWhere('driver_id', $user->id);
            })
            ->whereNotIn('id', $sudahDiulasIds)
            ->with(['pembeli', 'driver'])
            ->latest()
            ->limit(5)
            ->get();

        return view('ulasan.index', compact('diterima', 'diberikan', 'belumDiulas'));
    }

    /** Beri ulasan untuk titipan yang sudah selesai. */
    public function store(StoreUlasanRequest $request, Titipan $titipan)
    {
        abort_unless($titipan->status === 'selesai', 422, 'Ulasan hanya bisa diberikan setelah titipan selesai.');

        $user = Auth::user();
        $untukUserId = null;
        $peran = null;

        if ($titipan->pembeli_id == $user->id) {
            $untukUserId = $titipan->driver_id;
            $peran = 'pembeli';
        } elseif ($titipan->driver_id == $user->id) {
            $untukUserId = $titipan->pembeli_id;
            $peran = 'driver';
        } else {
            abort(403);
        }

        abort_if(is_null($untukUserId), 422, 'Belum ada pasangan transaksi untuk diberi ulasan.');

        // Cari ulasan termasuk yang sudah di-soft-delete, agar tidak terjadi
        // UniqueConstraintViolationException saat user mencoba beri ulasan ulang.
        $ulasan = Ulasan::withTrashed()
            ->where('titipan_id', $titipan->id)
            ->where('dari_user_id', $user->id)
            ->first();

        $baru = false;
        if ($ulasan) {
            if ($ulasan->trashed()) {
                $ulasan->restore(); // kembalikan dari soft-delete
            }
            $ulasan->fill([
                'untuk_user_id' => $untukUserId,
                'peran_pemberi' => $peran,
                'rating'        => $request->rating,
                'komentar'      => $request->komentar,
            ])->save();
        } else {
            Ulasan::create([
                'titipan_id'    => $titipan->id,
                'dari_user_id'  => $user->id,
                'untuk_user_id' => $untukUserId,
                'peran_pemberi' => $peran,
                'rating'        => $request->rating,
                'komentar'      => $request->komentar,
            ]);
            $baru = true;
        }

        $pesan = $baru ? 'Terima kasih sudah memberi ulasan!' : 'Ulasan berhasil diperbarui!';

        return back()->with('success', $pesan);
    }

    /** Tampilkan form edit ulasan milik user sendiri. */
    public function edit(Ulasan $ulasan)
    {
        abort_unless($ulasan->dari_user_id === Auth::id(), 403);

        return view('ulasan.edit', compact('ulasan'));
    }

    /** Simpan perubahan ulasan milik user sendiri. */
    public function update(UpdateUlasanRequest $request, Ulasan $ulasan)
    {
        abort_unless($ulasan->dari_user_id === Auth::id(), 403);

        $ulasan->update([
            'rating'   => $request->rating,
            'komentar' => $request->komentar,
        ]);

        return redirect()->route('ulasan.index')->with('success', 'Ulasan berhasil diperbarui!');
    }

    public function destroy(Ulasan $ulasan)
    {
        abort_unless($ulasan->dari_user_id === Auth::id() || Auth::user()->isAdmin(), 403);
        $ulasan->delete();

        return back()->with('success', 'Ulasan dihapus.');
    }
}
