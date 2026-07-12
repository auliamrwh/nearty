<?php

namespace App\Http\Controllers;

use App\Models\Titipan;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dashboard dibedakan per role sesuai Checkpoint 3:
     * - Admin  : ringkasan seluruh sistem (user, driver, titipan per status).
     * - Pembeli: ringkasan titipan milik dia sendiri.
     * - Driver : ringkasan tugas dia sebagai driver (hanya tampil kalau is_driver_active).
     */
    public function index()
    {
        $user = Auth::user();

        $stats = [];

        if ($user->isAdmin()) {
            $stats['admin'] = $this->statsAdmin();
        }

        $stats['pembeli'] = $this->statsPembeli($user);

        if ($user->is_driver_active) {
            $stats['driver'] = $this->statsDriver($user);
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
            ->when(! $user->isAdmin(), function ($q) use ($user) {
                // Non-admin cuma lihat titipan yang berhubungan dengan dia sendiri.
                $q->where(function ($sub) use ($user) {
                    $sub->where('pembeli_id', $user->id)
                        ->orWhere('driver_id', $user->id);
                });
            })
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

    /** Statistik untuk panel Admin: total user, total driver, dan titipan per status. */
    private function statsAdmin(): array
    {
        return [
            'total_user' => User::count(),
            'total_driver' => User::where('is_driver_active', true)->count(),
            'total_titipan' => Titipan::count(),
            'titipan_menunggu' => Titipan::where('status', 'menunggu')->count(),
            'titipan_diproses' => Titipan::whereIn('status', ['diambil_driver', 'diantar', 'dibayar'])->count(),
            'titipan_selesai' => Titipan::where('status', 'selesai')->count(),
            'titipan_dibatalkan' => Titipan::where('status', 'dibatalkan')->count(),
            'total_ongkir_terkumpul' => Titipan::where('status', 'selesai')->sum('ongkir'),
        ];
    }

    /** Statistik untuk panel Pembeli: titipan milik dia sendiri, dipecah per status. */
    private function statsPembeli(User $user): array
    {
        $milikSaya = Titipan::where('pembeli_id', $user->id);

        return [
            'jumlah_titipan_saya' => (clone $milikSaya)->count(),
            'sedang_diproses' => (clone $milikSaya)->whereIn('status', ['menunggu', 'diambil_driver', 'diantar', 'dibayar'])->count(),
            'selesai' => (clone $milikSaya)->where('status', 'selesai')->count(),
            'dibatalkan' => (clone $milikSaya)->where('status', 'dibatalkan')->count(),
            'riwayat' => Titipan::where('pembeli_id', $user->id)
                ->whereIn('status', ['selesai', 'dibatalkan'])
                ->latest()
                ->limit(5)
                ->get(),
        ];
    }

    /** Statistik untuk panel Driver: titipan tersedia, yang sedang diambil, dan riwayat antar dia. */
    private function statsDriver(User $user): array
    {
        return [
            'titipan_tersedia' => Titipan::tersedia()->where('pembeli_id', '!=', $user->id)->count(),
            'titipan_diambil' => Titipan::where('driver_id', $user->id)
                ->whereIn('status', ['diambil_driver', 'diantar'])
                ->count(),
            'riwayat' => Titipan::where('driver_id', $user->id)
                ->whereIn('status', ['selesai', 'dibayar'])
                ->latest()
                ->limit(5)
                ->get(),
            'riwayat_count' => Titipan::where('driver_id', $user->id)
                ->whereIn('status', ['selesai', 'dibayar'])
                ->count(),
        ];
    }
}
