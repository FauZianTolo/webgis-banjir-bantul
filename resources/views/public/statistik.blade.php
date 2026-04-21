@extends('layouts.public')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* ==================== HERO ==================== */
        .statistik-hero {
            background: linear-gradient(135deg, #0c4a6e 0%, #0369a1 50%, #0891b2 100%);
            color: white;
            padding: 3.5rem 0 5rem;
            position: relative;
            overflow: hidden;
        }
        .statistik-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(255,255,255,0.07) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(6,182,212,0.15) 0%, transparent 50%);
        }
        .statistik-hero::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0; right: 0;
            height: 60px;
            background: #f0f6ff;
            clip-path: ellipse(55% 100% at 50% 100%);
        }
        .hero-title {
            position: relative;
            z-index: 2;
            font-weight: 900;
            font-size: 2.6rem;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 12px rgba(0,0,0,0.2);
        }
        .hero-subtitle {
            position: relative;
            z-index: 2;
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 500;
        }
        .hero-badge {
            position: relative;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 100px;
            padding: 5px 16px;
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            margin-bottom: 1rem;
        }

        /* ==================== PAGE WRAPPER ==================== */
        .page-wrapper {
            background: #f0f6ff;
            padding: 0 0 4rem;
        }

        /* ==================== STATS SECTION ==================== */
        .stats-section {
            margin-top: -2.5rem;
            padding: 0 0 2rem;
        }
        .stat-card {
            background: white;
            border-radius: 18px;
            padding: 1.75rem 1.5rem;
            box-shadow: 0 4px 24px rgba(12,74,110,0.10);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            height: 100%;
            border: 1.5px solid rgba(8,145,178,0.08);
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(12,74,110,0.16);
        }
        .stat-icon-wrap {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: white;
            flex-shrink: 0;
        }
        .stat-icon-wrap.purple { background: linear-gradient(135deg, #7c3aed, #a855f7); box-shadow: 0 6px 20px rgba(124,58,237,0.3); }
        .stat-icon-wrap.green  { background: linear-gradient(135deg, #059669, #10b981); box-shadow: 0 6px 20px rgba(5,150,105,0.3); }
        .stat-icon-wrap.amber  { background: linear-gradient(135deg, #d97706, #f59e0b); box-shadow: 0 6px 20px rgba(217,119,6,0.3); }
        .stat-icon-wrap.red    { background: linear-gradient(135deg, #dc2626, #ef4444); box-shadow: 0 6px 20px rgba(220,38,38,0.3); }
        .stat-text-value {
            font-size: 2.2rem;
            font-weight: 900;
            line-height: 1;
            color: #0c4a6e;
            margin-bottom: 4px;
        }
        .stat-text-label {
            font-size: 0.88rem;
            color: #64748b;
            font-weight: 600;
            line-height: 1.3;
        }
        .stat-accent-bar {
            height: 3px;
            border-radius: 4px;
            margin-top: 6px;
        }
        .stat-accent-bar.purple { background: linear-gradient(90deg, #7c3aed, #a855f7); }
        .stat-accent-bar.green  { background: linear-gradient(90deg, #059669, #10b981); }
        .stat-accent-bar.amber  { background: linear-gradient(90deg, #d97706, #f59e0b); }
        .stat-accent-bar.red    { background: linear-gradient(90deg, #dc2626, #ef4444); }

        /* ==================== SECTION TITLE ==================== */
        .section-label {
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #0891b2;
            margin-bottom: 4px;
        }
        .section-title {
            font-size: 1.55rem;
            font-weight: 900;
            color: #0c4a6e;
            margin-bottom: 0;
            letter-spacing: -0.3px;
        }

        /* ==================== INFO BOX ==================== */
        .info-box {
            background: linear-gradient(135deg, #0369a1, #0891b2);
            color: white;
            border-radius: 20px;
            padding: 2rem 2.5rem;
            box-shadow: 0 8px 32px rgba(8,145,178,0.28);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        .info-box::before {
            content: '';
            position: absolute;
            top: -30px; right: -30px;
            width: 160px; height: 160px;
            border-radius: 50%;
            background: rgba(255,255,255,0.07);
        }
        .info-box::after {
            content: '';
            position: absolute;
            bottom: -20px; right: 80px;
            width: 100px; height: 100px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }
        .info-box-icon {
            font-size: 3.5rem;
            opacity: 0.25;
            flex-shrink: 0;
            position: relative;
            z-index: 1;
        }
        .info-box-content { position: relative; z-index: 1; }
        .info-box-content h3 {
            font-size: 1.2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }
        .info-box-content p {
            font-size: 0.95rem;
            opacity: 0.92;
            margin: 0;
            line-height: 1.7;
        }

        /* ==================== PANEL ==================== */
        .panel {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 24px rgba(12,74,110,0.08);
            border: 1.5px solid rgba(8,145,178,0.08);
            height: 100%;
        }
        .panel-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: #0c4a6e;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        .panel-title i { color: #0891b2; }

        /* ==================== YEAR FILTER ==================== */
        .year-pill {
            display: inline-flex;
            border-radius: 100px;
            overflow: hidden;
            background: #f1f5f9;
            padding: 3px;
            gap: 2px;
            flex-wrap: wrap;
        }
        .year-pill button {
            border: none;
            background: transparent;
            border-radius: 100px;
            padding: 5px 14px;
            font-size: 0.82rem;
            font-weight: 700;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .year-pill button.active,
        .year-pill button:hover {
            background: #0891b2;
            color: white;
            box-shadow: 0 2px 8px rgba(8,145,178,0.35);
        }

        /* ==================== KECAMATAN BARS ==================== */
        .kec-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.85rem;
            padding: 0.75rem 1rem;
            background: #f8fafc;
            border-radius: 12px;
            border: 1.5px solid transparent;
            transition: all 0.25s ease;
        }
        .kec-row:hover {
            background: #e0f2fe;
            border-color: #bae6fd;
            transform: translateX(4px);
        }
        .kec-rank {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: linear-gradient(135deg, #0369a1, #0891b2);
            color: white;
            font-size: 0.75rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .kec-rank.gold   { background: linear-gradient(135deg, #b45309, #f59e0b); }
        .kec-rank.silver { background: linear-gradient(135deg, #475569, #94a3b8); }
        .kec-rank.bronze { background: linear-gradient(135deg, #92400e, #d97706); }
        .kec-name {
            width: 130px;
            font-weight: 700;
            font-size: 0.88rem;
            color: #0c4a6e;
            flex-shrink: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .kec-bar-wrap {
            flex: 1;
            height: 28px;
            background: #e2e8f0;
            border-radius: 100px;
            overflow: hidden;
        }
        .kec-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #0369a1, #22d3ee);
            border-radius: 100px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 10px;
            color: white;
            font-size: 0.78rem;
            font-weight: 800;
            transition: width 1.2s cubic-bezier(0.4, 0, 0.2, 1);
            min-width: 40px;
        }
        .kec-count {
            font-size: 0.88rem;
            font-weight: 800;
            color: #0891b2;
            width: 45px;
            text-align: right;
            flex-shrink: 0;
        }

        /* ==================== TABLE ==================== */
        .stat-table thead { background: linear-gradient(135deg, #0369a1, #0891b2); }
        .stat-table thead th {
            color: white;
            border: none;
            padding: 1rem;
            font-weight: 800;
            font-size: 0.85rem;
            letter-spacing: 0.3px;
        }
        .stat-table tbody td {
            padding: 0.9rem 1rem;
            vertical-align: middle;
            font-size: 0.9rem;
        }
        .stat-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.2s;
        }
        .stat-table tbody tr:hover { background: #f0f9ff; }
        .rank-badge {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.82rem;
            color: white;
        }
        .rank-1 { background: linear-gradient(135deg, #b45309, #f59e0b); }
        .rank-2 { background: linear-gradient(135deg, #475569, #94a3b8); }
        .rank-3 { background: linear-gradient(135deg, #92400e, #d97706); }
        .rank-n { background: linear-gradient(135deg, #0369a1, #0891b2); }
        .progress { height: 22px; border-radius: 100px; background: #e2e8f0; overflow: hidden; }
        .progress-bar { font-size: 0.75rem; font-weight: 800; border-radius: 100px; }
        .badge { padding: 4px 12px; border-radius: 8px; font-weight: 700; font-size: 0.8rem; }

        /* ==================== SOURCE BADGE ==================== */
        .source-note {
            margin-top: 1.25rem;
            padding: 8px 14px;
            background: #f8fafc;
            border-radius: 10px;
            border-left: 3px solid #0891b2;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        .source-note i { color: #0891b2; font-size: 0.75rem; margin-top: 3px; flex-shrink: 0; }
        .source-note span { font-size: 0.78rem; color: #64748b; line-height: 1.5; }
        .source-note strong { color: #0c4a6e; }

        /* ==================== CTA ==================== */
        .cta-section {
            text-align: center;
            padding: 2.5rem 1.5rem;
            background: white;
            border-radius: 24px;
            box-shadow: 0 4px 24px rgba(12,74,110,0.08);
            border: 1.5px solid rgba(8,145,178,0.08);
        }
        .cta-section h4 {
            font-weight: 900;
            color: #0c4a6e;
            font-size: 1.4rem;
            margin-bottom: 0.5rem;
        }
        .cta-section p {
            color: #64748b;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }
        .btn-cta-primary {
            background: linear-gradient(135deg, #0369a1, #0891b2);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.85rem 2rem;
            font-weight: 800;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-cta-primary:hover {
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(8,145,178,0.35);
        }
        .btn-cta-success {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.85rem 2rem;
            font-weight: 800;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-cta-success:hover {
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(16,185,129,0.35);
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 767px) {
            .hero-title { font-size: 1.7rem; }
            .hero-subtitle { font-size: 0.9rem; }
            .stat-card { padding: 1.2rem; gap: 1rem; }
            .stat-icon-wrap { width: 52px; height: 52px; font-size: 1.3rem; border-radius: 14px; }
            .stat-text-value { font-size: 1.7rem; }
            .stat-text-label { font-size: 0.8rem; }
            .info-box { flex-direction: column; padding: 1.5rem; gap: 0.5rem; }
            .info-box-icon { font-size: 2.5rem; }
            .panel { padding: 1.5rem; }
            .panel-title { font-size: 1rem; }
            .kec-name { width: 100px; font-size: 0.82rem; }
            .kec-bar-fill { font-size: 0.72rem; padding-right: 7px; }
            .year-pill button { padding: 4px 10px; font-size: 0.76rem; }
            .cta-section { padding: 1.5rem 1rem; }
            .cta-section h4 { font-size: 1.1rem; }
            .btn-cta-primary, .btn-cta-success {
                padding: 0.75rem 1.5rem;
                font-size: 0.88rem;
                width: 100%;
                justify-content: center;
            }
            .stat-table thead th { font-size: 0.78rem; padding: 0.75rem 0.6rem; }
            .stat-table tbody td { font-size: 0.82rem; padding: 0.7rem 0.6rem; }
        }
        @media (max-width: 420px) {
            .hero-title { font-size: 1.4rem; }
            .kec-name { width: 80px; }
            .stats-section { margin-top: -1.5rem; }
        }
        @media (min-width: 768px) {
            .statistik-hero { padding-bottom: 5.5rem; }
        }
    </style>
@endsection

@section('content')

    <!-- ===== HERO ===== -->
    <div class="statistik-hero">
        <div class="container text-center">
            <div class="hero-badge">
                <i class="fas fa-circle" style="font-size:8px;color:#67e8f9;"></i>
                Data BPBD Kabupaten Bantul
            </div>
            <h1 class="hero-title mb-2">
                <i class="fas fa-chart-bar me-2"></i>Statistik Kejadian Banjir
            </h1>
            <p class="hero-subtitle">Analisis & Visualisasi Data Banjir Historis Kabupaten Bantul</p>
        </div>
    </div>

    <div class="page-wrapper">
        <div class="container">

            <!-- ===== STAT CARDS ===== -->
            <div class="stats-section">
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon-wrap purple">
                                <i class="fas fa-database"></i>
                            </div>
                            <div style="min-width:0;">
                                <div class="stat-text-value">{{ number_format($totalLaporan) }}</div>
                                <div class="stat-text-label">Total Data Historis</div>
                                <div class="stat-accent-bar purple"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon-wrap green">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div style="min-width:0;">
                                <div class="stat-text-value">{{ number_format($totalVerified) }}</div>
                                <div class="stat-text-label">Laporan Terverifikasi</div>
                                <div class="stat-accent-bar green"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon-wrap amber">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <div style="min-width:0;">
                                <div class="stat-text-value">{{ count($kecamatanStats) }}</div>
                                <div class="stat-text-label">Kecamatan Terdampak</div>
                                <div class="stat-accent-bar amber"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon-wrap red">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div style="min-width:0;">
                                <div class="stat-text-value">{{ $kecamatanStats[0]['total'] ?? 0 }}</div>
                                <div class="stat-text-label">Titik Terbanyak</div>
                                <div class="stat-accent-bar red"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== INFO BOX ===== -->
            <div class="info-box mb-4">
                <div class="info-box-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="info-box-content">
                    <h3><i class="fas fa-info-circle me-2"></i>Tentang Data</h3>
                    <p>
                        Statistik ini menampilkan <strong>{{ number_format($totalLaporan) }} titik historis</strong>
                        kejadian banjir dari BPBD Bantul, tersebar di
                        <strong>{{ count($kecamatanStats) }} kecamatan</strong>.
                        Grafik dan analisis membantu pemangku kebijakan dalam pengambilan keputusan terkait mitigasi banjir.
                    </p>
                </div>
            </div>

            <!-- ===== TREN BULANAN ===== -->
            <div class="panel mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <div class="panel-title mb-0">
                        <i class="fas fa-chart-line"></i>
                        Tren Kejadian Bulanan (<span id="current-year">{{ $selectedYear }}</span>)
                    </div>
                    <div class="year-pill">
                        @foreach ($availableYears as $year)
                            <button
                                class="year-filter {{ $year == $selectedYear ? 'active' : '' }}"
                                data-year="{{ $year }}"
                                onclick="changeYear({{ $year }})">
                                {{ $year }}
                            </button>
                        @endforeach
                    </div>
                </div>
                <canvas id="trendChart"></canvas>
                <div class="source-note">
                    <i class="fas fa-database"></i>
                    <span><strong>Sumber Data:</strong> Data kejadian banjir historis Kabupaten Bantul 2020–2025. Sumber: Badan Penanggulangan Bencana Daerah (BPBD) Kabupaten Bantul.</span>
                </div>
            </div>

            <!-- ===== KECAMATAN BARS + TABLE (2 kolom di desktop) ===== -->
            <div class="row g-4 mb-4">
                <!-- Bar Chart Kecamatan -->
                <div class="col-lg-5">
                    <div class="panel h-100">
                        <div class="panel-title">
                            <i class="fas fa-map-marked-alt"></i>
                            10 Kecamatan Paling Rawan
                        </div>

                        @php $maxTotal = collect($kecamatanStats)->max('total') ?? 1; @endphp

                        @forelse($kecamatanStats as $idx => $kec)
                            <div class="kec-row">
                                @php
                                    $rankClass = $idx === 0 ? 'gold' : ($idx === 1 ? 'silver' : ($idx === 2 ? 'bronze' : ''));
                                @endphp
                                <div class="kec-rank {{ $rankClass }}">{{ $idx + 1 }}</div>
                                <div class="kec-name" title="{{ $kec['kecamatan'] }}">{{ $kec['kecamatan'] }}</div>
                                <div class="kec-bar-wrap">
                                    <div class="kec-bar-fill" style="width: {{ ($kec['total'] / $maxTotal) * 100 }}%">
                                        {{ $kec['total'] }}x
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-inbox"></i> Tidak ada data
                            </div>
                        @endforelse

                        <div class="source-note">
                            <i class="fas fa-database"></i>
                            <span><strong>Sumber Data:</strong> BPBD Kabupaten Bantul 2020–2025.</span>
                        </div>
                    </div>
                </div>

                <!-- Tabel Detail -->
                <div class="col-lg-7">
                    <div class="panel h-100">
                        <div class="panel-title">
                            <i class="fas fa-table"></i>
                            Detail Statistik Per Kecamatan
                        </div>
                        <div class="table-responsive">
                            <table class="table stat-table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:50px;">No</th>
                                        <th>Kecamatan</th>
                                        <th class="text-center" style="width:80px;">Titik</th>
                                        <th>Persentase</th>
                                        <th class="text-center" style="width:80px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalAll = collect($kecamatanStats)->sum('total'); @endphp
                                    @forelse($kecamatanStats as $index => $item)
                                        @php
                                            $pct = $totalAll > 0 ? ($item['total'] / $totalAll) * 100 : 0;
                                            $status = $pct >= 10 ? 'Tinggi' : ($pct >= 7 ? 'Sedang' : 'Rendah');
                                            $cls   = $pct >= 10 ? 'danger' : ($pct >= 7 ? 'warning' : 'info');
                                            $rankBadge = $index === 0 ? 'rank-1' : ($index === 1 ? 'rank-2' : ($index === 2 ? 'rank-3' : 'rank-n'));
                                        @endphp
                                        <tr>
                                            <td>
                                                <span class="rank-badge {{ $rankBadge }}">{{ $index + 1 }}</span>
                                            </td>
                                            <td><strong>{{ $item['kecamatan'] }}</strong></td>
                                            <td class="text-center"><strong>{{ $item['total'] }}</strong></td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar bg-{{ $cls }}"
                                                        style="width: {{ $pct }}%"
                                                        role="progressbar">
                                                        {{ number_format($pct, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $cls }}">{{ $status }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="source-note">
                            <i class="fas fa-database"></i>
                            <span><strong>Sumber Data:</strong> BPBD Kabupaten Bantul 2020–2025.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== CTA ===== -->
            <div class="cta-section">
                <h4><i class="fas fa-satellite-dish me-2" style="color:#0891b2;"></i>Eksplorasi Lebih Lanjut</h4>
                <p>Lihat sebaran banjir secara spasial atau laporkan kejadian banjir di sekitar Anda.</p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ route('peta') }}" class="btn-cta-primary">
                        <i class="fas fa-map"></i> Lihat Peta Interaktif
                    </a>
                    <a href="{{ route('laporan') }}" class="btn-cta-success">
                        <i class="fas fa-paper-plane"></i> Lapor Kejadian Banjir
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const kecamatanStats = @json($kecamatanStats);
        let yearlyData = @json($yearlyData);
        let selectedYear = {{ $selectedYear }};
        const availableYears = @json($availableYears);

        let trendChart = null;

        function initTrendChart(year) {
            const monthNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
            const monthlyData = yearlyData[year] || {};
            const trendValues = [];
            for (let i = 1; i <= 12; i++) {
                trendValues.push(monthlyData[i] || 0);
            }

            if (trendChart) trendChart.destroy();

            const ctx = document.getElementById('trendChart').getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(8,145,178,0.25)');
            gradient.addColorStop(1, 'rgba(8,145,178,0.02)');

            trendChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: monthNames,
                    datasets: [{
                        label: 'Jumlah Kejadian',
                        data: trendValues,
                        borderColor: '#0891b2',
                        backgroundColor: gradient,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        pointBackgroundColor: '#0891b2',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2.5,
                        pointHoverBorderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: window.innerWidth < 768 ? 1.6 : 3,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: { size: 13, weight: 'bold', family: 'Plus Jakarta Sans' },
                                color: '#0c4a6e',
                                usePointStyle: true,
                                pointStyleWidth: 16
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(12,74,110,0.95)',
                            padding: 14,
                            titleFont: { size: 14, weight: 'bold', family: 'Plus Jakarta Sans' },
                            bodyFont:  { size: 13, family: 'Plus Jakarta Sans' },
                            borderColor: '#0891b2',
                            borderWidth: 2,
                            callbacks: {
                                label: ctx => 'Kejadian: ' + ctx.parsed.y + ' titik'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: Math.max(1, Math.ceil(Math.max(...trendValues) / 8)),
                                font: { size: 12, weight: '600', family: 'Plus Jakarta Sans' },
                                color: '#64748b'
                            },
                            grid: { color: 'rgba(0,0,0,0.04)' },
                            title: {
                                display: true,
                                text: 'Jumlah Kejadian',
                                font: { size: 12, weight: 'bold', family: 'Plus Jakarta Sans' },
                                color: '#0c4a6e'
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 12, weight: '600', family: 'Plus Jakarta Sans' }, color: '#64748b' },
                            title: {
                                display: true,
                                text: 'Bulan',
                                font: { size: 12, weight: 'bold', family: 'Plus Jakarta Sans' },
                                color: '#0c4a6e'
                            }
                        }
                    }
                }
            });
        }

        function changeYear(year) {
            selectedYear = year;
            document.getElementById('current-year').textContent = year;
            document.querySelectorAll('.year-filter').forEach(btn => {
                btn.classList.toggle('active', parseInt(btn.dataset.year) === year);
            });
            initTrendChart(year);
        }

        document.addEventListener('DOMContentLoaded', function () {
            initTrendChart(selectedYear);

            // Animate kecamatan bars on load
            setTimeout(() => {
                document.querySelectorAll('.kec-bar-fill').forEach(bar => {
                    const w = bar.style.width;
                    bar.style.width = '0%';
                    setTimeout(() => { bar.style.width = w; }, 150);
                });
            }, 400);
        });
    </script>
@endsection
