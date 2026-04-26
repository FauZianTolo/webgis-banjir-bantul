<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Export Laporan Banjir</title>
    <style>
        body  { font-family: Arial, sans-serif; font-size: 11px; }
        table { border-collapse: collapse; width: 100%; }
        th    { background: #0891b2; color: white; padding: 8px 10px;
                font-weight: bold; text-align: left; border: 1px solid #0891b2; }
        td    { padding: 6px 10px; border: 1px solid #d1d5db; vertical-align: top; }
        tr:nth-child(even) td { background: #f0f9ff; }

        /* Badge warna status */
        .s-pending  { color: #92400e; font-weight: bold; }
        .s-verified { color: #065f46; font-weight: bold; }
        .s-rejected { color: #991b1b; font-weight: bold; }
    </style>
</head>
<body>

{{-- Baris judul --}}
<table>
    <tr>
        <td colspan="12" style="font-size:16px;font-weight:900;color:#0c4a6e;border:none;padding:4px 0;">
            LAPORAN DATA BANJIR — WEBGIS BANTUL
        </td>
    </tr>
    <tr>
        <td colspan="12" style="font-size:11px;color:#64748b;border:none;padding:0 0 6px 0;">
            Tanggal Export: {{ now()->format('d F Y, H:i') }} WIB &nbsp;|&nbsp;
            Total Data: {{ $laporan->count() }} laporan &nbsp;|&nbsp;
            @if(request('status')) Filter: {{ ucfirst(request('status')) }} @else Semua Status @endif
        </td>
    </tr>
    {{-- Baris kosong pemisah --}}
    <tr><td colspan="12" style="border:none;height:8px;"></td></tr>

    {{-- Header tabel --}}
    <tr>
        <th>No</th>
        <th>ID</th>
        <th>Tanggal Laporan</th>
        <th>Nama Pelapor</th>
        <th>No. Telepon</th>
        <th>Kecamatan</th>
        <th>Desa</th>
        <th>Latitude</th>
        <th>Longitude</th>
        <th>Kedalaman (cm)</th>
        <th>Deskripsi</th>
        <th>Kebutuhan/Bantuan</th>
        <th>Status</th>
        <th>Foto 1 (URL)</th>
        <th>Foto 2 (URL)</th>
        <th>Foto 3 (URL)</th>
    </tr>

    {{-- Data rows --}}
    @forelse($laporan as $i => $item)
    @php
        // Resolve URL foto untuk semua sumber (Cloudinary & lokal)
        $resolveUrl = function($foto) {
            if (!$foto) return '';
            if (str_starts_with($foto, 'http')) return $foto;
            return url('uploads/laporan/' . $foto);
        };
        $f1 = $resolveUrl($item->foto);
        $f2 = $resolveUrl($item->foto2 ?? null);
        $f3 = $resolveUrl($item->foto3 ?? null);
    @endphp
    <tr>
        <td>{{ $i + 1 }}</td>
        <td>#{{ $item->id }}</td>
        <td>{{ $item->waktu_laporan->format('d/m/Y H:i') }}</td>
        <td>{{ $item->nama_pelapor }}</td>
        <td>{{ $item->no_telp ?? '-' }}</td>
        <td>{{ $item->kecamatan }}</td>
        <td>{{ $item->desa ?? '-' }}</td>
        <td>{{ $item->latitude }}</td>
        <td>{{ $item->longitude }}</td>
        <td style="text-align:center;">{{ $item->kedalaman_cm ?? 0 }}</td>
        <td>{{ $item->deskripsi }}</td>
        <td>{{ $item->kebutuhan_bantuan ?? '-' }}</td>
        <td class="s-{{ $item->status }}">{{ ucfirst($item->status) }}</td>
        <td>{{ $f1 ?: '-' }}</td>
        <td>{{ $f2 ?: '-' }}</td>
        <td>{{ $f3 ?: '-' }}</td>
    </tr>
    @empty
    <tr>
        <td colspan="16" style="text-align:center;color:#94a3b8;">
            Tidak ada data laporan.
        </td>
    </tr>
    @endforelse

    {{-- Baris ringkasan --}}
    <tr><td colspan="16" style="border:none;height:10px;"></td></tr>
    <tr>
        <td colspan="3" style="font-weight:bold;background:#f8fafc;">RINGKASAN</td>
        <td colspan="3" style="background:#f0f9ff;color:#0891b2;font-weight:bold;">
            Total: {{ $stats['total'] }}
        </td>
        <td colspan="3" style="background:#fffbeb;color:#d97706;font-weight:bold;">
            Pending: {{ $stats['pending'] }}
        </td>
        <td colspan="3" style="background:#f0fdf4;color:#059669;font-weight:bold;">
            Verified: {{ $stats['verified'] }}
        </td>
        <td colspan="4" style="background:#fef2f2;color:#dc2626;font-weight:bold;">
            Rejected: {{ $stats['rejected'] }}
        </td>
    </tr>

    {{-- Footer --}}
    <tr><td colspan="16" style="border:none;height:8px;"></td></tr>
    <tr>
        <td colspan="16" style="border:none;font-size:10px;color:#94a3b8;">
            WebGIS Banjir Bantul — Dikembangkan oleh Muhammad Nashan Fauzian | SIG, Universitas Gadjah Mada
        </td>
    </tr>
</table>

</body>
</html>
