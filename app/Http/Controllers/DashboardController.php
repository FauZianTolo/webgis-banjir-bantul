<?php

namespace App\Http\Controllers;

use App\Models\LaporanBanjir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Cache stats selama 5 menit
            $stats = Cache::remember('dashboard_stats', 300, function() {
                return [
                    'totalLaporan' => LaporanBanjir::count(),
                    'laporanPending' => LaporanBanjir::where('status', 'pending')->count(),
                    'laporanVerified' => LaporanBanjir::where('status', 'verified')->count(),
                    'laporanRejected' => LaporanBanjir::where('status', 'rejected')->count(),
                ];
            });

            // Statistik per bulan (2026)
            $laporanPerBulan = LaporanBanjir::selectRaw('MONTH(waktu_laporan) as bulan, COUNT(*) as total')
                ->whereYear('waktu_laporan', date('Y'))
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get();

            // Statistik per kecamatan (Top 5)
            $laporanPerKecamatan = LaporanBanjir::selectRaw('kecamatan, COUNT(*) as total')
                ->where('status', 'verified')
                ->groupBy('kecamatan')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get();

            // Tabel Laporan Kejadian Terbaru (Hanya Verified)
            $laporanTerbaru = LaporanBanjir::where('status', 'verified')
                ->orderBy('waktu_laporan', 'desc')
                ->take(10)
                ->get();

            // Dummy values untuk points (karena tidak digunakan lagi)
            $stats['totalPoints'] = 0;
            $stats['totalPolylines'] = 0;
            $stats['totalPolygons'] = 0;

            return view('dashboard', array_merge($stats, [
                'laporanPerBulan' => $laporanPerBulan,
                'laporanPerKecamatan' => $laporanPerKecamatan,
                'laporanTerbaru' => $laporanTerbaru,
            ]));

        } catch (\Exception $e) {
            // Fallback jika error
            return view('dashboard', [
                'totalPoints' => 0,
                'totalPolylines' => 0,
                'totalPolygons' => 0,
                'totalLaporan' => 0,
                'laporanPending' => 0,
                'laporanVerified' => 0,
                'laporanRejected' => 0,
                'laporanPerBulan' => collect([]),
                'laporanPerKecamatan' => collect([]),
                'laporanTerbaru' => collect([]),
            ]);
        }
    }

    // API untuk refresh stats (AJAX)
    public function refreshStats()
    {
        Cache::forget('dashboard_stats');

        return response()->json([
            'laporanPending' => LaporanBanjir::where('status', 'pending')->count(),
            'laporanVerified' => LaporanBanjir::where('status', 'verified')->count(),
            'laporanRejected' => LaporanBanjir::where('status', 'rejected')->count(),
            'totalLaporan' => LaporanBanjir::count(),
        ]);
    }
}
