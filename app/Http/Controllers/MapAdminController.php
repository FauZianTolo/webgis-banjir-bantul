<?php

namespace App\Http\Controllers;

use App\Models\LaporanBanjir;
use Illuminate\Http\Request;

class MapAdminController extends Controller
{
    public function index()
    {
        // Ambil SEMUA laporan (pending, verified, rejected)
        $laporan = LaporanBanjir::orderBy('waktu_laporan', 'desc')->get();

        return view('admin.peta.index', compact('laporan'));
    }

    /**
 * Display route navigation page for admin
 */
public function route(Request $request)
{
    // Validasi parameter
    $lat = $request->query('lat');
    $lng = $request->query('lng');
    $title = $request->query('title', 'Lokasi Tujuan');

    if (!$lat || !$lng) {
        return redirect()->route('admin.peta')->with('error', 'Koordinat tidak valid');
    }

    return view('admin.peta.route', [
        'title' => 'Navigasi Rute - Admin',
        'targetLat' => $lat,
        'targetLng' => $lng,
        'targetTitle' => $title
    ]);
}
}
