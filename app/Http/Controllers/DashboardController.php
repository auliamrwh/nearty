<?php

namespace App\Http\Controllers;

use App\Models\Titipan;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total_titipan_saya' => Titipan::where('pembeli_id', $user->id)->count(),
            'total_diantar_saya' => Titipan::where('driver_id', $user->id)->count(),
            'titipan_tersedia' => Titipan::tersedia()->count(),
            'titipan_selesai' => Titipan::where('status', 'selesai')->count(),
        ];

        if ($user->isAdmin()) {
            $stats['total_user'] = User::count();
            $stats['total_titipan'] = Titipan::count();
            $stats['total_ongkir_terkumpul'] = Titipan::where('status', 'selesai')->sum('ongkir');
        }

        // Grafik tren titipan 7 hari terakhir untuk dashboard.
        $mulai = Carbon::now()->subDays(6)->startOfDay();
        $tren = Titipan::select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', $mulai)
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        $labelChart = [];
        $dataChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::now()->subDays($i)->format('Y-m-d');
            $labelChart[] = Carbon::now()->subDays($i)->translatedFormat('d M');
            $dataChart[] = (int) ($tren[$tanggal]->total ?? 0);
        }

        $titipanTerbaru = Titipan::with(['pembeli', 'driver'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', [
            'stats' => $stats,
            'labelChart' => $labelChart,
            'dataChart' => $dataChart,
            'titipanTerbaru' => $titipanTerbaru,
        ]);
    }
}
