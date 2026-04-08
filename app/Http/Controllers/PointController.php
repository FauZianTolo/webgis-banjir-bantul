<?php

namespace App\Http\Controllers;

use App\Models\LaporanBanjir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class PointController extends Controller
{
    /**
     * Display a listing of verified reports.
     */
    public function index()
    {
        $laporan = LaporanBanjir::where('status', 'verified')
            ->orderBy('waktu_laporan', 'desc')
            ->paginate(15);

        return view('admin.points.index', compact('laporan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $laporan = LaporanBanjir::findOrFail($id);

        return view('admin.points.edit', compact('laporan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $laporan = LaporanBanjir::findOrFail($id);

        $validated = $request->validate([
            'nama_pelapor' => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:20',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'kecamatan' => 'required|string|max:100',
            'desa' => 'nullable|string|max:100',
            'deskripsi' => 'required|string',
            'kedalaman_cm' => 'nullable|numeric',
            'waktu_laporan' => 'required|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle foto upload jika ada foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($laporan->foto && file_exists(public_path('uploads/laporan/' . $laporan->foto))) {
                unlink(public_path('uploads/laporan/' . $laporan->foto));
            }

            $foto = $request->file('foto');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('uploads/laporan'), $fotoName);
            $validated['foto'] = $fotoName;
        }

        $laporan->update($validated);

        // Clear cache
        Cache::forget('laporan_verified');
        Cache::forget('dashboard_stats');

        return redirect()->route('admin.points.index')
            ->with('success', 'Data laporan berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $laporan = LaporanBanjir::findOrFail($id);

        // Hapus foto jika ada
        if ($laporan->foto && file_exists(public_path('uploads/laporan/' . $laporan->foto))) {
            unlink(public_path('uploads/laporan/' . $laporan->foto));
        }

        $laporan->delete(); // Soft delete

        // Clear cache
        Cache::forget('laporan_verified');
        Cache::forget('dashboard_stats');

        return redirect()->back()
            ->with('success', 'Data laporan berhasil dihapus!');
    }
}
