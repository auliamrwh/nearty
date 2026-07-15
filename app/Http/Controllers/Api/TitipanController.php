<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTitipanRequest;
use App\Http\Resources\TitipanResource;
use App\Models\Titipan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TitipanController extends Controller
{
    /** GET /api/titipans?q=&status=&page= */
    public function index(Request $request)
    {
        $query = Titipan::with(['items', 'pembeli', 'driver'])->search($request->get('q'));

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $titipans = $query->latest()->paginate($request->get('per_page', 10));

        return TitipanResource::collection($titipans);
    }

    /** GET /api/titipans/{titipan} */
    public function show(Titipan $titipan)
    {
        $titipan->load(['items', 'pembeli', 'driver']);

        return new TitipanResource($titipan);
    }

    /** POST /api/titipans */
    public function store(StoreTitipanRequest $request)
    {
        $data = $request->validated();

        $titipan = DB::transaction(function () use ($data) {
            $t = Titipan::create([
                'pembeli_id' => Auth::id(),
                'sumber_lokasi' => $data['sumber_lokasi'],
                'lokasi_warung' => $data['lokasi_warung'],
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'alamat_antar' => $data['alamat_antar'],
                'metode_bayar' => $data['metode_bayar'],
                'ongkir' => $data['ongkir'],
                'status' => 'menunggu',
                'estimasi_total' => collect($data['items'])->sum(fn ($i) => ($i['estimasi_harga'] ?? 0) * $i['jumlah']),
            ]);

            foreach ($data['items'] as $item) {
                $t->items()->create($item);
            }

            return $t;
        });

        return (new TitipanResource($titipan->load('items')))
            ->response()
            ->setStatusCode(201);
    }

    /** PATCH /api/titipans/{titipan} - contoh: update status oleh driver. */
    public function update(Request $request, Titipan $titipan)
    {
        abort_unless(
            $titipan->pembeli_id === Auth::id() || $titipan->driver_id === Auth::id() || Auth::user()->isAdmin(),
            403
        );

        $request->validate([
            'status' => ['sometimes', 'in:menunggu,diambil_driver,dibayar,selesai,dibatalkan'],
            'total_aktual' => ['sometimes', 'numeric', 'min:0'],
        ]);

        $titipan->update($request->only(['status', 'total_aktual']));

        return new TitipanResource($titipan->fresh(['items', 'pembeli', 'driver']));
    }

    /** DELETE /api/titipans/{titipan} - soft delete (batalkan). */
    public function destroy(Titipan $titipan)
    {
        abort_unless($titipan->pembeli_id === Auth::id() || Auth::user()->isAdmin(), 403);

        $titipan->update(['status' => 'dibatalkan']);
        $titipan->delete();

        return response()->json(['message' => 'Titipan dibatalkan.']);
    }
}
