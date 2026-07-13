<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ulasan;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    /**
     * Daftar semua ulasan di sistem — untuk moderasi admin.
     * Support search by nama pemberi/penerima & pagination.
     */
    public function index(Request $request)
    {
        $query = Ulasan::with(['dariUser', 'untukUser', 'titipan'])
            ->latest();

        if ($request->filled('q')) {
            $term = $request->get('q');
            $query->whereHas('dariUser', fn ($q) => $q->where('name', 'like', "%{$term}%"))
                  ->orWhereHas('untukUser', fn ($q) => $q->where('name', 'like', "%{$term}%"))
                  ->orWhereHas('titipan', fn ($q) => $q->where('lokasi_warung', 'like', "%{$term}%"));
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->get('rating'));
        }

        $ulasans = $query->paginate(15)->withQueryString();

        return view('admin.ulasan.index', compact('ulasans'));
    }

    /** Hapus ulasan (moderasi). */
    public function destroy(Ulasan $ulasan)
    {
        $ulasan->delete();

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
