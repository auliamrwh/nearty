<?php

namespace App\Http\Controllers;

use App\Models\ItemTitipan;
use App\Models\Titipan;
use App\Http\Requests\StoreTitipanRequest;
use App\Http\Requests\UpdateTitipanRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class TitipanController extends Controller
{
    /** Daftar titipan milik user (sebagai pembeli), dengan search & pagination. */
    public function index(\Illuminate\Http\Request $request)
    {
        $query = Titipan::with(['items', 'driver'])
            ->where('pembeli_id', Auth::id())
            ->search($request->get('q'));

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $titipans = $query->latest()->paginate(8)->withQueryString();

        return view('titipan.index', compact('titipans'));
    }

    public function create()
    {
        return view('titipan.create');
    }

    public function store(StoreTitipanRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $titipan = Titipan::create([
                'pembeli_id' => Auth::id(),
                'lokasi_warung' => $data['lokasi_warung'],
                'alamat_antar' => $data['alamat_antar'],
                'metode_bayar' => $data['metode_bayar'],
                'ongkir' => $data['ongkir'],
                'status' => 'menunggu',
                'estimasi_total' => collect($data['items'])->sum(fn ($i) => ($i['estimasi_harga'] ?? 0) * $i['jumlah']),
            ]);

            foreach ($data['items'] as $item) {
                $titipan->items()->create($item);
            }
        });

        return redirect()->route('titipan.index')->with('success', 'Titipan berhasil dibuat, menunggu driver terdekat.');
    }

    public function show(Titipan $titipan)
{
    $titipan->load(['items', 'pembeli', 'driver', 'ulasan']);

    $authId = auth()->id();

    $bisaUlasan = $titipan->status === 'selesai'
        && (
            $titipan->pembeli_id == $authId ||
            $titipan->driver_id == $authId
        );

    $sudahUlasan = $titipan->ulasan()
        ->where('dari_user_id', $authId)
        ->first();

    return view('titipan.show', compact(
        'titipan',
        'bisaUlasan',
        'sudahUlasan'
    ));
}

    public function edit(Titipan $titipan)
    {
        $this->authorizePemilikDanMasihMenunggu($titipan);
        $titipan->load('items');

        return view('titipan.edit', compact('titipan'));
    }

    public function update(UpdateTitipanRequest $request, Titipan $titipan)
    {
        $this->authorizePemilikDanMasihMenunggu($titipan);
        $data = $request->validated();

        DB::transaction(function () use ($data, $titipan) {
            $titipan->update([
                'lokasi_warung' => $data['lokasi_warung'],
                'alamat_antar' => $data['alamat_antar'],
                'metode_bayar' => $data['metode_bayar'],
                'ongkir' => $data['ongkir'],
                'estimasi_total' => collect($data['items'])->sum(fn ($i) => ($i['estimasi_harga'] ?? 0) * $i['jumlah']),
            ]);

            $idKeep = [];
            foreach ($data['items'] as $item) {
                if (! empty($item['id'])) {
                    $titipan->items()->where('id', $item['id'])->update($item);
                    $idKeep[] = $item['id'];
                } else {
                    $idKeep[] = $titipan->items()->create($item)->id;
                }
            }
            // Barang yang dihapus dari form ikut dihapus (soft delete).
            $titipan->items()->whereNotIn('id', $idKeep)->delete();
        });

        return redirect()->route('titipan.index')->with('success', 'Titipan berhasil diperbarui.');
    }

    /** Soft delete (batalkan) titipan, wajib isi alasan pembatalan. */
    public function destroy(\Illuminate\Http\Request $request, Titipan $titipan)
    {
        $this->authorizePemilikDanMasihMenunggu($titipan);

        $request->validate(['alasan_batal' => ['required', 'string', 'max:255']]);

        $titipan->update([
            'status' => 'dibatalkan',
            'alasan_batal' => $request->alasan_batal,
        ]);
        $titipan->delete();

        return redirect()->route('titipan.index')->with('success', 'Titipan dibatalkan.');
    }

    /** Riwayat termasuk yang sudah soft-deleted (dibatalkan), khusus admin. */
    public function trashed()
    {
        Gate::authorize('admin');

        $titipans = Titipan::onlyTrashed()->with(['pembeli', 'driver'])->latest()->paginate(10);

        return view('titipan.trashed', compact('titipans'));
    }

    public function restore($id)
    {
        Gate::authorize('admin');

        $titipan = Titipan::onlyTrashed()->findOrFail($id);
        $titipan->restore();

        return back()->with('success', 'Titipan berhasil dipulihkan.');
    }

    private function authorizePemilikDanMasihMenunggu(Titipan $titipan): void
    {
        abort_unless($titipan->pembeli_id === Auth::id(), 403);
        abort_unless($titipan->status === 'menunggu', 422, 'Titipan yang sudah diambil driver tidak bisa diubah/dibatalkan lagi.');
    }
}
