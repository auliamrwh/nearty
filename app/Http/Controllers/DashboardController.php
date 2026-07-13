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

        // Grafik tren dinamis per role
        $mulai = Carbon::now()->subDays(6)->startOfDay();
        $labelChart = [];
        $dataChart = []; // Dataset 1
        $dataChart2 = []; // Dataset 2 (khusus Admin)
        $chartTitle = 'Tren Aktivitas 7 Hari Terakhir';
        $chartLabel1 = 'Data';
        $chartLabel2 = '';

        for ($i = 6; $i >= 0; $i--) {
            $labelChart[] = Carbon::now()->subDays($i)->translatedFormat('d M');
        }

        if ($user->isAdmin()) {
            $chartTitle = 'Tren Titipan Dibuat vs Selesai';
            $chartLabel1 = 'Titipan Dibuat';
            $chartLabel2 = 'Titipan Selesai';

            $dibuat = Titipan::select(DB::raw('DATE(created_at) as tgl'), DB::raw('COUNT(*) as total'))
                ->where('created_at', '>=', $mulai)->groupBy('tgl')->pluck('total', 'tgl');
            $selesai = Titipan::select(DB::raw('DATE(updated_at) as tgl'), DB::raw('COUNT(*) as total'))
                ->where('status', 'selesai')->where('updated_at', '>=', $mulai)->groupBy('tgl')->pluck('total', 'tgl');

            for ($i = 6; $i >= 0; $i--) {
                $tgl = Carbon::now()->subDays($i)->format('Y-m-d');
                $dataChart[] = $dibuat[$tgl] ?? 0;
                $dataChart2[] = $selesai[$tgl] ?? 0;
            }
        } elseif ($user->is_driver_active) {
            $chartTitle = 'Tren Pendapatan Ongkir (Rp)';
            $chartLabel1 = 'Pendapatan';

            $pendapatan = Titipan::select(DB::raw('DATE(updated_at) as tgl'), DB::raw('SUM(ongkir) as total'))
                ->where('driver_id', $user->id)->where('status', 'selesai')
                ->where('updated_at', '>=', $mulai)->groupBy('tgl')->pluck('total', 'tgl');

            for ($i = 6; $i >= 0; $i--) {
                $tgl = Carbon::now()->subDays($i)->format('Y-m-d');
                $dataChart[] = (int) ($pendapatan[$tgl] ?? 0);
            }
        } else {
            $chartTitle = 'Tren Pengeluaran Titipan (Rp)';
            $chartLabel1 = 'Pengeluaran';

            $pengeluaran = Titipan::select(DB::raw('DATE(updated_at) as tgl'), DB::raw('SUM(COALESCE(total_aktual, estimasi_total) + ongkir) as total'))
                ->where('pembeli_id', $user->id)->where('status', 'selesai')
                ->where('updated_at', '>=', $mulai)->groupBy('tgl')->pluck('total', 'tgl');

            for ($i = 6; $i >= 0; $i--) {
                $tgl = Carbon::now()->subDays($i)->format('Y-m-d');
                $dataChart[] = (int) ($pengeluaran[$tgl] ?? 0);
            }
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
            'chartTitle' => $chartTitle,
            'chartLabel1' => $chartLabel1,
            'chartLabel2' => $chartLabel2,
            'labelChart' => $labelChart,
            'dataChart' => $dataChart,
            'dataChart2' => $dataChart2,
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
