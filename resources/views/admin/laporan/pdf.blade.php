<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Banjir - WebGIS Bantul</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #1a1a1a;
            background: #fff;
            padding: 20px;
        }

        /* ===== HEADER ===== */
        .report-header {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px 25px;
            background: linear-gradient(135deg, #0c4a6e, #0891b2);
            color: white;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .header-logo {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            flex-shrink: 0;
        }

        .header-text h1 {
            font-size: 20px;
            font-weight: 900;
            margin-bottom: 3px;
        }

        .header-text p {
            font-size: 12px;
            opacity: 0.85;
        }

        .header-meta {
            margin-left: auto;
            text-align: right;
            font-size: 11px;
            opacity: 0.9;
        }

        /* ===== STATS GRID ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .stat-box {
            padding: 14px;
            border-radius: 10px;
            text-align: center;
            border: 2px solid;
        }

        .stat-box.total   { background: #f0f9ff; border-color: #0891b2; }
        .stat-box.pending { background: #fffbeb; border-color: #f59e0b; }
        .stat-box.verified{ background: #f0fdf4; border-color: #10b981; }
        .stat-box.rejected{ background: #fef2f2; border-color: #ef4444; }

        .stat-box .num {
            font-size: 28px;
            font-weight: 900;
            line-height: 1;
            display: block;
            margin-bottom: 4px;
        }

        .stat-box.total   .num { color: #0891b2; }
        .stat-box.pending .num { color: #d97706; }
        .stat-box.verified.num { color: #059669; }
        .stat-box.rejected .num { color: #dc2626; }

        .stat-box .label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ===== TABLE ===== */
        .section-title {
            font-size: 15px;
            font-weight: 800;
            color: #0c4a6e;
            margin-bottom: 12px;
            padding-bottom: 6px;
            border-bottom: 2px solid #0891b2;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        thead tr {
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: white;
        }

        thead th {
            padding: 10px 8px;
            text-align: left;
            font-weight: 700;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }

        tbody tr {
            border-bottom: 1px solid #e2e8f0;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        tbody tr:hover {
            background: #f0f9ff;
        }

        tbody td {
            padding: 9px 8px;
            vertical-align: top;
        }

        /* ===== STATUS BADGES ===== */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 10px;
        }

        .badge-pending  { background: #fef3c7; color: #92400e; border: 1px solid #f59e0b; }
        .badge-verified { background: #d1fae5; color: #065f46; border: 1px solid #10b981; }
        .badge-rejected { background: #fee2e2; color: #991b1b; border: 1px solid #ef4444; }

        /* ===== DEPTH BADGE ===== */
        .depth-high   { background: #ef4444; color: white; padding: 2px 6px; border-radius: 5px; font-weight: 700; font-size: 10px; }
        .depth-medium { background: #f59e0b; color: white; padding: 2px 6px; border-radius: 5px; font-weight: 700; font-size: 10px; }
        .depth-low    { background: #0891b2; color: white; padding: 2px 6px; border-radius: 5px; font-weight: 700; font-size: 10px; }

        /* ===== FOTO THUMBNAILS ===== */
        .foto-grid {
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
        }

        .foto-thumb {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #e2e8f0;
        }

        .no-foto {
            color: #94a3b8;
            font-size: 10px;
            font-style: italic;
        }

        /* ===== FOOTER ===== */
        .report-footer {
            margin-top: 24px;
            padding: 14px 20px;
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            color: #64748b;
        }

        /* ===== PRINT CONTROLS ===== */
        .print-controls {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-bottom: 16px;
        }

        .btn-print {
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: white;
            border: none;
            padding: 10px 22px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-print:hover {
            background: linear-gradient(135deg, #0e7490, #0891b2);
        }

        .btn-back {
            background: white;
            color: #64748b;
            border: 2px solid #e2e8f0;
            padding: 10px 22px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .filter-info {
            display: inline-block;
            background: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        /* ===== KEBUTUHAN CELL ===== */
        .kebutuhan-cell {
            font-size: 10px;
            color: #92400e;
            line-height: 1.4;
            background: #fffbeb;
            border-radius: 4px;
            padding: 3px 6px;
            display: inline-block;
            border: 1px solid #fde68a;
            max-width: 130px;
            word-break: break-word;
        }
        .no-kebutuhan {
            color: #94a3b8;
            font-size: 10px;
            font-style: italic;
        }

        /* ===== PRINT STYLES ===== */
        @media print {
            .print-controls { display: none !important; }

            body { padding: 10px; }

            .report-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .stats-grid    { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            thead tr       { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .badge         { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .kebutuhan-cell { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .depth-high, .depth-medium, .depth-low { -webkit-print-color-adjust: exact; print-color-adjust: exact; }

            table { page-break-inside: auto; }
            tr    { page-break-inside: avoid; }
            thead { display: table-header-group; }
        }
    </style>
</head>
<body>

    <!-- Print / Back Controls -->
    <div class="print-controls">
        <a class="btn-back" href="{{ route('admin.laporan.index') }}">
            ← Kembali
        </a>
        <button class="btn-print" onclick="window.print()">
            🖨️ Cetak / Simpan PDF
        </button>
    </div>

    <!-- Report Header -->
    <div class="report-header">
        <div class="header-logo">🌊</div>
        <div class="header-text">
            <h1>Laporan Data Banjir</h1>
            <p>WebGIS Kerawanan &amp; Pelaporan Banjir — Kabupaten Bantul, DIY</p>
            <p>BPBD Kabupaten Bantul</p>
        </div>
        <div class="header-meta">
            <div><strong>Tanggal Cetak:</strong></div>
            <div>{{ now()->format('d F Y, H:i') }} WIB</div>
            <div style="margin-top:4px;"><strong>Total Data:</strong> {{ $laporan->count() }} laporan</div>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-box total">
            <span class="num" style="color:#0891b2">{{ $stats['total'] }}</span>
            <span class="label" style="color:#0891b2">Total Laporan</span>
        </div>
        <div class="stat-box pending">
            <span class="num" style="color:#d97706">{{ $stats['pending'] }}</span>
            <span class="label" style="color:#d97706">Pending</span>
        </div>
        <div class="stat-box verified">
            <span class="num" style="color:#059669">{{ $stats['verified'] }}</span>
            <span class="label" style="color:#059669">Verified</span>
        </div>
        <div class="stat-box rejected">
            <span class="num" style="color:#dc2626">{{ $stats['rejected'] }}</span>
            <span class="label" style="color:#dc2626">Rejected</span>
        </div>
    </div>

    <!-- Filter info if filtered -->
    @if(request('status'))
    <div class="filter-info">
        Filter Status: {{ ucfirst(request('status')) }} ({{ $laporan->count() }} data)
    </div>
    @endif

    <!-- Table -->
    <div class="section-title">📋 Daftar Laporan Banjir</div>

    <table>
        <thead>
            <tr>
                <th style="width:30px">ID</th>
                <th style="width:70px">Tanggal</th>
                <th style="width:110px">Pelapor</th>
                <th style="width:100px">Lokasi</th>
                <th style="width:55px">Kedalaman</th>
                <th>Deskripsi</th>
                <th style="width:130px">Kebutuhan/Bantuan</th>
                <th style="width:60px">Status</th>
                <th style="width:100px">Foto</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $item)
            <tr>
                <td><strong>#{{ $item->id }}</strong></td>

                <td>
                    {{ $item->waktu_laporan->format('d/m/Y') }}<br>
                    <span style="color:#64748b">{{ $item->waktu_laporan->format('H:i') }}</span>
                </td>

                <td>
                    <strong>{{ $item->nama_pelapor }}</strong><br>
                    <span style="color:#64748b">{{ $item->no_telp ?? '-' }}</span>
                </td>

                <td>
                    <strong>{{ $item->kecamatan }}</strong><br>
                    <span style="color:#64748b">{{ $item->desa ?? '-' }}</span><br>
                    <span style="color:#94a3b8;font-size:9px">
                        {{ number_format($item->latitude, 4) }},
                        {{ number_format($item->longitude, 4) }}
                    </span>
                </td>

                <td>
                    @php $depth = $item->kedalaman_cm ?? 0; @endphp
                    <span class="{{ $depth >= 70 ? 'depth-high' : ($depth >= 40 ? 'depth-medium' : 'depth-low') }}">
                        {{ $depth }} cm
                    </span>
                </td>

                <td style="max-width:180px; word-break:break-word; font-size:11px;">
                    {{ Str::limit($item->deskripsi, 80) }}
                </td>

                <td>
                    @if($item->kebutuhan_bantuan)
                        <span class="kebutuhan-cell">{{ Str::limit($item->kebutuhan_bantuan, 60) }}</span>
                    @else
                        <span class="no-kebutuhan">-</span>
                    @endif
                </td>

                <td>
                    <span class="badge badge-{{ $item->status }}">
                        {{ ucfirst($item->status) }}
                    </span>
                </td>

                <td>
                    @php $fotos = array_filter([$item->foto, $item->foto2 ?? null, $item->foto3 ?? null]); @endphp
                    @if(count($fotos) > 0)
                        <div class="foto-grid">
                            @foreach($fotos as $foto)
                            <img src="{{ public_path('uploads/laporan/' . $foto) }}"
                                 alt="Foto"
                                 class="foto-thumb"
                                 onerror="this.style.display='none'">
                            @endforeach
                        </div>
                    @else
                        <span class="no-foto">Tidak ada foto</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align:center; padding:20px; color:#94a3b8;">
                    Tidak ada data laporan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="report-footer">
        <div>
            <strong>WebGIS Banjir Bantul</strong> — Sistem Informasi Kerawanan &amp; Pelaporan Banjir Kabupaten Bantul<br>
            Dikembangkan oleh: Muhammad Nashan Fauzian | Program Studi SIG, Universitas Gadjah Mada
        </div>
        <div style="text-align:right;">
            Dicetak: {{ now()->format('d/m/Y H:i') }} WIB
        </div>
    </div>

</body>
</html>
