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
     */
    public function index()
    {
        // Ambil SEMUA laporan (pending, verified, rejected)
        $laporan = LaporanBanjir::orderBy('waktu_laporan', 'desc')->get();

        // ⭐ FIX: Gunakan $laporanList untuk tabel, $laporan untuk stats
        $data = [
            'title' => 'Verifikasi Laporan Banjir',
            'laporanList' => $laporan,  // ⭐ UNTUK TABEL
            'laporan' => $laporan,       // ⭐ UNTUK BACKWARD COMPATIBILITY
            'pending' => LaporanBanjir::where('status', 'pending')->count(),
            'verified' => LaporanBanjir::where('status', 'verified')->count(),
            'rejected' => LaporanBanjir::where('status', 'rejected')->count(),
        ];

        return view('admin.laporan.index', $data);
    }

    /**
     * Verifikasi (Setujui) Laporan
     */
    public function verify($id)
{
    try {
        $laporan = LaporanBanjir::findOrFail($id);
        $laporan->status = 'verified';
        $laporan->save();

        Cache::forget('laporan_verified');
        Cache::forget('dashboard_stats');

        NotificationController::createVerifiedNotification($laporan);

        return redirect()->route('admin.laporan.index')->with('success', 'Laporan berhasil diverifikasi!');
    } catch (\Exception $e) {
        return redirect()->route('admin.laporan.index')->with('error', 'Gagal memverifikasi laporan: ' . $e->getMessage());
    }
}

    /**
     * Tolak Laporan
     */
    public function reject($id)
{
    try {
        $laporan = LaporanBanjir::findOrFail($id);
        $laporan->status = 'rejected';
        $laporan->save();

        Cache::forget('laporan_verified');
        Cache::forget('dashboard_stats');

        NotificationController::createRejectedNotification($laporan);

        return redirect()->route('admin.laporan.index')->with('success', 'Laporan berhasil ditolak.');
    } catch (\Exception $e) {
        return redirect()->route('admin.laporan.index')->with('error', 'Gagal menolak laporan: ' . $e->getMessage());
    }
}

    /**
     * Delete rejected report
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

        Cache::forget('dashboard_stats');
        Cache::forget('laporan_verified');

        return redirect()->route('admin.laporan.index')->with('success', 'Laporan yang ditolak berhasil dihapus!');
    } catch (\Exception $e) {
        return redirect()->route('admin.laporan.index')->with('error', 'Gagal menghapus laporan: ' . $e->getMessage());
    }
}

    /**
 * Export laporan ke PDF (browser print)
 */
public function exportPdf(Request $request)
{
    $query = LaporanBanjir::orderBy('waktu_laporan', 'desc');

    // Filter by status jika ada
    if ($request->status && in_array($request->status, ['pending', 'verified', 'rejected'])) {
        $query->where('status', $request->status);
    }

    $laporan = $query->get();
    $stats = [
        'total'    => LaporanBanjir::count(),
        'pending'  => LaporanBanjir::where('status', 'pending')->count(),
        'verified' => LaporanBanjir::where('status', 'verified')->count(),
        'rejected' => LaporanBanjir::where('status', 'rejected')->count(),
    ];

    return view('admin.laporan.pdf', compact('laporan', 'stats'));
}
}
