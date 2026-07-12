<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUlasanRequest;
use App\Http\Resources\UlasanResource;
use App\Models\Titipan;
use App\Models\Ulasan;
use Illuminate\Support\Facades\Auth;

class UlasanController extends Controller
{
    /** GET /api/ulasans - ulasan yang diterima user yang sedang login. */
    public function index()
    {
        $ulasans = Ulasan::with(['dariUser', 'untukUser'])
            ->where('untuk_user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return UlasanResource::collection($ulasans);
    }

    public function show(Ulasan $ulasan)
    {
        return new UlasanResource($ulasan->load(['dariUser', 'untukUser', 'titipan']));
    }

    /** POST /api/titipans/{titipan}/ulasans */
    public function store(StoreUlasanRequest $request, Titipan $titipan)
    {
        abort_unless($titipan->status === 'selesai', 422, 'Ulasan hanya untuk titipan yang sudah selesai.');

        $user = Auth::user();
        if ($titipan->pembeli_id === $user->id) {
            $untukUserId = $titipan->driver_id;
            $peran = 'pembeli';
        } elseif ($titipan->driver_id === $user->id) {
            $untukUserId = $titipan->pembeli_id;
            $peran = 'driver';
        } else {
            abort(403);
        }

        $ulasan = Ulasan::updateOrCreate(
            ['titipan_id' => $titipan->id, 'dari_user_id' => $user->id],
            [
                'untuk_user_id' => $untukUserId,
                'peran_pemberi' => $peran,
                'rating' => $request->rating,
                'komentar' => $request->komentar,
            ]
        );

        return (new UlasanResource($ulasan))->response()->setStatusCode(201);
    }

    public function destroy(Ulasan $ulasan)
    {
        abort_unless($ulasan->dari_user_id === Auth::id() || Auth::user()->isAdmin(), 403);
        $ulasan->delete();

        return response()->json(['message' => 'Ulasan dihapus.']);
    }
}
