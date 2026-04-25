<?php

namespace App\Http\Controllers;

use App\Models\LaporanBanjir;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Cache;

class LaporanController extends Controller
{
    /**
     * Halaman daftar laporan untuk admin (Verifikasi Laporan)
     * ✅ OPTIMASI: paginate(20) + cache count stats 5 menit
     */
    public function index()
    {
        // ✅ OPTIMASI: Paginate 20 per halaman — tidak ambil semua sekaligus
        $laporan = LaporanBanjir::orderBy('waktu_laporan', 'desc')->paginate(20);

        // ✅ OPTIMASI: Cache count stats 5 menit — 3 query jadi 1x per 5 menit
        $stats = Cache::remember('laporan_stats_admin', 300, function () {
            return [
                'pending'  => LaporanBanjir::where('status', 'pending')->count(),
                'verified' => LaporanBanjir::where('status', 'verified')->count(),
                'rejected' => LaporanBanjir::where('status', 'rejected')->count(),
            ];
        });

        $data = [
            'title'       => 'Verifikasi Laporan Banjir',
            'laporanList' => $laporan,  // ⭐ UNTUK TABEL (sudah paginate)
            'laporan'     => $laporan,  // ⭐ UNTUK BACKWARD COMPATIBILITY
            'pending'     => $stats['pending'],
            'verified'    => $stats['verified'],
            'rejected'    => $stats['rejected'],
        ];

        return view('admin.laporan.index', $data);
    }

    /**
     * Verifikasi (Setujui) Laporan
     * ✅ OPTIMASI: tambah forget laporan_stats_admin
     */
    public function verify($id)
    {
        try {
            $laporan = LaporanBanjir::findOrFail($id);
            $laporan->status = 'verified';
            $laporan->save();

            // ✅ Hapus semua cache terkait laporan
            Cache::forget('laporan_verified');
            Cache::forget('dashboard_stats');
            Cache::forget('laporan_stats_admin'); // ✅ BARU

            NotificationController::createVerifiedNotification($laporan);

            return redirect()->route('admin.laporan.index')->with('success', 'Laporan berhasil diverifikasi!');
        } catch (\Exception $e) {
            return redirect()->route('admin.laporan.index')->with('error', 'Gagal memverifikasi laporan: ' . $e->getMessage());
        }
    }

    /**
     * Tolak Laporan
     * ✅ OPTIMASI: tambah forget laporan_stats_admin
     */
    public function reject($id)
    {
        try {
            $laporan = LaporanBanjir::findOrFail($id);
            $laporan->status = 'rejected';
            $laporan->save();

            // ✅ Hapus semua cache terkait laporan
            Cache::forget('laporan_verified');
            Cache::forget('dashboard_stats');
            Cache::forget('laporan_stats_admin'); // ✅ BARU

            NotificationController::createRejectedNotification($laporan);

            return redirect()->route('admin.laporan.index')->with('success', 'Laporan berhasil ditolak.');
        } catch (\Exception $e) {
            return redirect()->route('admin.laporan.index')->with('error', 'Gagal menolak laporan: ' . $e->getMessage());
        }
    }

    /**
     * Delete rejected report
     * ✅ OPTIMASI: tambah forget laporan_stats_admin
     */
    public function destroyRejected($id)
    {
        try {
            $laporan = LaporanBanjir::findOrFail($id);

            if ($laporan->status !== 'rejected') {
                return redirect()->route('admin.laporan.index')->with('error', 'Hanya laporan yang ditolak yang bisa dihapus!');
            }

            if ($laporan->foto && file_exists(public_path('uploads/laporan/' . $laporan->foto))) {
                unlink(public_path('uploads/laporan/' . $laporan->foto));
            }

            $laporan->delete();

            // ✅ Hapus semua cache terkait laporan
            Cache::forget('dashboard_stats');
            Cache::forget('laporan_verified');
            Cache::forget('laporan_stats_admin'); // ✅ BARU

            return redirect()->route('admin.laporan.index')->with('success', 'Laporan yang ditolak berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.laporan.index')->with('error', 'Gagal menghapus laporan: ' . $e->getMessage());
        }
    }

    /**
     * Export laporan ke PDF (browser print)
     * ✅ Tetap pakai ->get() karena PDF butuh semua data
     */
    public function exportPdf(Request $request)
    {
        $query = LaporanBanjir::orderBy('waktu_laporan', 'desc');

        // Filter by status jika ada
        if ($request->status && in_array($request->status, ['pending', 'verified', 'rejected'])) {
            $query->where('status', $request->status);
        }

        $laporan = $query->get();

        // ✅ OPTIMASI: Cache stats untuk PDF juga
        $stats = Cache::remember('laporan_stats_admin', 300, function () {
            return [
                'total'    => LaporanBanjir::count(),
                'pending'  => LaporanBanjir::where('status', 'pending')->count(),
                'verified' => LaporanBanjir::where('status', 'verified')->count(),
                'rejected' => LaporanBanjir::where('status', 'rejected')->count(),
            ];
        });

        return view('admin.laporan.pdf', compact('laporan', 'stats'));
    }
}
