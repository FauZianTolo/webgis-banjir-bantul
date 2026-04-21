@extends('layouts.public')

@section('styles')
<link href='https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap' rel='stylesheet'>
<style>
* { font-family: 'Plus Jakarta Sans', sans-serif; }

/* ══════════════════════════════════════════════════════
   HERO — identical style to laporan-hero
══════════════════════════════════════════════════════ */
.statistik-hero {
    background: linear-gradient(135deg, #0c4a6e 0%, #0891b2 50%, #06b6d4 100%);
    color: white; padding: 4rem 0; margin-bottom: 0;
    position: relative; overflow: hidden;
}
.statistik-hero::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse at 20% 50%, rgba(255,255,255,0.07) 0%, transparent 60%),
        radial-gradient(ellipse at 80% 20%, rgba(6,182,212,0.15) 0%, transparent 50%);
}
.statistik-hero::after {
    content: '';
    position: absolute;
    bottom: -2px; left: 0; right: 0;
    height: 50px;
    background: #f0f9ff;
    clip-path: ellipse(55% 100% at 50% 100%);
}
.statistik-hero h1 { position:relative; z-index:2; font-weight:900; font-size:2.8rem; text-shadow:2px 2px 4px rgba(0,0,0,0.3); }
.statistik-hero p  { position:relative; z-index:2; font-size:1.2rem; opacity:0.95; }

/* ══════════════════════════════════════════════════════
   PAGE BG
══════════════════════════════════════════════════════ */
.stat-page-wrap { background: #f0f9ff; padding: 2.5rem 0 4rem; }

/* ══════════════════════════════════════════════════════
   STAT SUMMARY CARDS
══════════════════════════════════════════════════════ */
.stat-card {
    background: white; border-radius: 20px; padding: 1.75rem 1.5rem;
    box-shadow: 0 4px 24px rgba(12,74,110,0.09);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex; align-items: center; gap: 1.25rem;
    height: 100%; border: 1.5px solid rgba(8,145,178,0.08);
    position: relative; overflow: hidden;
}
.stat-card::before {
    content:''; position:absolute; top:0; left:0; right:0; height:4px; border-radius:20px 20px 0 0;
}
.stat-card.c-blue::before   { background: linear-gradient(90deg,#667eea,#764ba2); }
.stat-card.c-green::before  { background: linear-gradient(90deg,#10b981,#059669); }
.stat-card.c-yellow::before { background: linear-gradient(90deg,#f59e0b,#d97706); }
.stat-card.c-red::before    { background: linear-gradient(90deg,#ef4444,#dc2626); }
.stat-card:hover { transform: translateY(-5px); box-shadow: 0 14px 40px rgba(12,74,110,0.14); }
.stat-icon {
    width: 62px; height: 62px; border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem; color: white; flex-shrink: 0;
}
.stat-card.c-blue   .stat-icon { background: linear-gradient(135deg,#667eea,#764ba2); box-shadow:0 6px 18px rgba(102,126,234,0.35); }
.stat-card.c-green  .stat-icon { background: linear-gradient(135deg,#10b981,#059669); box-shadow:0 6px 18px rgba(16,185,129,0.35); }
.stat-card.c-yellow .stat-icon { background: linear-gradient(135deg,#f59e0b,#d97706); box-shadow:0 6px 18px rgba(245,158,11,0.35); }
.stat-card.c-red    .stat-icon { background: linear-gradient(135deg,#ef4444,#dc2626); box-shadow:0 6px 18px rgba(239,68,68,0.35); }
/* FIX: no webkit-text-fill, use solid color per card */
.stat-num {
    font-size: 2.2rem; font-weight: 900; line-height: 1; margin-bottom: 4px;
}
.stat-card.c-blue   .stat-num { color: #5b5fa6; }
.stat-card.c-green  .stat-num { color: #059669; }
.stat-card.c-yellow .stat-num { color: #d97706; }
.stat-card.c-red    .stat-num { color: #dc2626; }
.stat-label { font-size: 0.85rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.4px; }
.stat-accent { height: 3px; border-radius: 4px; margin-top: 6px; }
.stat-card.c-blue   .stat-accent { background: linear-gradient(90deg,#667eea,#764ba2); }
.stat-card.c-green  .stat-accent { background: linear-gradient(90deg,#10b981,#059669); }
.stat-card.c-yellow .stat-accent { background: linear-gradient(90deg,#f59e0b,#d97706); }
.stat-card.c-red    .stat-accent { background: linear-gradient(90deg,#ef4444,#dc2626); }

/* ══════════════════════════════════════════════════════
   INFO BOX
══════════════════════════════════════════════════════ */
.info-box {
    background: linear-gradient(135deg,#0369a1,#0891b2);
    color: white; border-radius: 20px; padding: 2rem 2.5rem;
    box-shadow: 0 8px 32px rgba(8,145,178,0.28);
    position: relative; overflow: hidden;
    display: flex; align-items: center; gap: 2rem;
}
.info-box::before {
    content:''; position:absolute; top:-30px; right:-30px;
    width:160px; height:160px; border-radius:50%;
    background: rgba(255,255,255,0.07);
}
.info-box::after {
    content:''; position:absolute; bottom:-20px; right:80px;
    width:100px; height:100px; border-radius:50%;
    background: rgba(255,255,255,0.05);
}
.info-box-icon { font-size:3.5rem; opacity:0.25; flex-shrink:0; position:relative; z-index:1; }
.info-box-content { position:relative; z-index:1; }
.info-box-content h3 { font-size:1.2rem; font-weight:800; margin-bottom:0.5rem; }
.info-box-content p  { font-size:0.95rem; opacity:0.92; margin:0; line-height:1.7; }

/* ══════════════════════════════════════════════════════
   CHART CARDS (panels)
══════════════════════════════════════════════════════ */
.chart-card {
    background: white; border-radius: 20px; padding: 2rem;
    box-shadow: 0 4px 24px rgba(12,74,110,0.08);
    border: 1.5px solid rgba(8,145,178,0.08);
    transition: box-shadow 0.3s; height: 100%;
}
.chart-card:hover { box-shadow: 0 14px 48px rgba(8,145,178,0.13); }
.chart-card-title {
    font-size: 1.1rem; font-weight: 800; color: #0c4a6e;
    display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1.5rem;
}
.chart-card-title i { color: #0891b2; }
.chart-card-title .title-badge {
    background: linear-gradient(135deg,#0891b2,#06b6d4);
    color: white; font-size: 11px; font-weight: 700;
    padding: 3px 10px; border-radius: 20px; margin-left: auto;
}

/* ══════════════════════════════════════════════════════
   YEAR PILLS
══════════════════════════════════════════════════════ */
.year-pills { display:flex; gap:5px; flex-wrap:wrap; }
.year-pill {
    padding: 4px 13px; border-radius: 100px; font-weight: 700; font-size: 12px;
    cursor: pointer; border: 2px solid #0891b2; color: #0891b2;
    background: white; transition: all 0.2s;
}
.year-pill:hover, .year-pill.active {
    background: linear-gradient(135deg,#0891b2,#06b6d4); color: white;
    box-shadow: 0 3px 12px rgba(8,145,178,0.35); transform: translateY(-1px);
}

/* ══════════════════════════════════════════════════════
   TREND HEADER
══════════════════════════════════════════════════════ */
.trend-header {
    display: flex; justify-content: space-between; align-items: center;
    flex-wrap: wrap; gap: 10px; margin-bottom: 1.25rem;
}

/* ══════════════════════════════════════════════════════
   DONUT CHART
══════════════════════════════════════════════════════ */
.donut-wrapper { display:flex; align-items:center; gap:1.5rem; flex-wrap:wrap; }
.donut-canvas-wrap {
    flex: 0 0 auto; position: relative;
    width: 230px; height: 230px;
}
.donut-center-label {
    position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);
    text-align:center; pointer-events:none;
}
.donut-center-label .dc-num { font-size:1.8rem; font-weight:900; color:#0c4a6e; line-height:1; }
.donut-center-label .dc-sub { font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; }
.donut-legend { flex:1; min-width:200px; }
.donut-legend-item {
    display:flex; align-items:center; gap:9px;
    padding:7px 10px; border-radius:10px; margin-bottom:4px;
    transition:background 0.2s; cursor:default;
}
.donut-legend-item:hover { background:#f0f9ff; }
.donut-legend-dot { width:12px; height:12px; border-radius:3px; flex-shrink:0; }
.donut-legend-name { font-size:12px; font-weight:700; color:#334155; flex:1; }
.donut-legend-val  { font-size:12px; font-weight:800; color:#0891b2; }
.donut-legend-pct  { font-size:10px; font-weight:600; color:#94a3b8; }

/* ══════════════════════════════════════════════════════
   TABLE
══════════════════════════════════════════════════════ */
.stats-table { border-radius:12px; overflow:hidden; }
.stats-table thead { background:linear-gradient(135deg,#0369a1,#0891b2); color:white; }
.stats-table thead th { border:none; padding:0.9rem; font-weight:800; font-size:0.85rem; letter-spacing:0.4px; white-space:nowrap; }
.stats-table tbody tr { transition:all 0.2s; border-bottom:1px solid #f1f5f9; }
.stats-table tbody tr:hover { background:#f0f9ff; }
.stats-table tbody td { padding:0.85rem 0.9rem; vertical-align:middle; }
.rank-badge {
    width:30px; height:30px; border-radius:8px;
    display:inline-flex; align-items:center; justify-content:center;
    font-weight:900; font-size:12px; color:white;
}
.rank-1 { background:linear-gradient(135deg,#f59e0b,#d97706); }
.rank-2 { background:linear-gradient(135deg,#94a3b8,#64748b); }
.rank-3 { background:linear-gradient(135deg,#cd7c3a,#b86a2a); }
.rank-n { background:linear-gradient(135deg,#0369a1,#0891b2); }
.prog-wrap { background:#e2e8f0; border-radius:100px; height:22px; overflow:hidden; }
.prog-fill  {
    height:100%; border-radius:100px;
    display:flex; align-items:center; justify-content:flex-end;
    padding-right:8px; font-size:11px; font-weight:800; color:white;
    transition:width 1s ease;
}
.s-badge { padding:3px 12px; border-radius:20px; font-weight:700; font-size:11px; display:inline-block; }
.s-high   { background:#fee2e2; color:#991b1b; }
.s-medium { background:#fef3c7; color:#92400e; }
.s-low    { background:#dbeafe; color:#1e40af; }

/* ══════════════════════════════════════════════════════
   SOURCE NOTE
══════════════════════════════════════════════════════ */
.source-note {
    margin-top:12px; padding:8px 14px;
    background:#f8fafc; border-radius:8px; border-left:3px solid #0891b2;
    font-size:11px; color:#64748b; line-height:1.5;
}
.source-note strong { color:#0c4a6e; }

/* ══════════════════════════════════════════════════════
   CTA
══════════════════════════════════════════════════════ */
.cta-box {
    background: white; border-radius: 20px; padding: 2rem 1.5rem;
    box-shadow: 0 4px 24px rgba(12,74,110,0.08);
    border: 1.5px solid rgba(8,145,178,0.08);
    text-align: center;
}
.cta-box h4 { font-weight:900; color:#0c4a6e; font-size:1.3rem; margin-bottom:0.4rem; }
.cta-box p  { color:#64748b; font-size:0.92rem; margin-bottom:1.25rem; }
.btn-cta {
    padding: 0.85rem 2rem; border-radius: 12px; font-weight: 800;
    font-size: 0.95rem; border: none; transition: all 0.3s;
    display: inline-flex; align-items: center; gap: 8px;
    text-decoration: none;
}
.btn-cta-primary { background:linear-gradient(135deg,#0369a1,#0891b2); color:white; box-shadow:0 6px 20px rgba(8,145,178,0.3); }
.btn-cta-primary:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(8,145,178,0.42); color:white; }
.btn-cta-success { background:linear-gradient(135deg,#059669,#10b981); color:white; box-shadow:0 6px 20px rgba(16,185,129,0.3); }
.btn-cta-success:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(16,185,129,0.42); color:white; }

/* ══════════════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════════════ */
@media (max-width: 767px) {
    .statistik-hero { padding: 2.5rem 0; }
    .statistik-hero::after { height: 35px; }
    .statistik-hero h1 { font-size: 1.7rem; }
    .statistik-hero p  { font-size: 0.95rem; }

    .stat-card { padding: 1.2rem 1rem; gap: 1rem; }
    .stat-icon { width: 50px; height: 50px; font-size: 1.3rem; border-radius: 12px; }
    .stat-num  { font-size: 1.7rem; }
    .stat-label { font-size: 0.78rem; }

    .info-box { flex-direction: column; padding: 1.5rem; gap: 0.5rem; }
    .info-box-icon { font-size: 2rem; }
    .info-box-content h3 { font-size: 1rem; }
    .info-box-content p  { font-size: 0.88rem; }

    .chart-card { padding: 1.25rem; }
    .chart-card-title { font-size: 0.95rem; }

    .donut-wrapper { flex-direction: column; align-items: center; }
    .donut-canvas-wrap { width: 190px; height: 190px; }
    .donut-center-label .dc-num { font-size: 1.4rem; }
    .donut-legend { width: 100%; min-width: unset; }

    .stats-table thead th { padding: 0.65rem 0.5rem; font-size: 0.78rem; }
    .stats-table tbody td { padding: 0.6rem 0.5rem; font-size: 0.82rem; }

    .btn-cta { padding: 0.75rem 1.5rem; font-size: 0.88rem; width: 100%; justify-content: center; }
}
@media (max-width: 420px) {
    .statistik-hero h1 { font-size: 1.4rem; }
    .stat-num { font-size: 1.5rem; }
}
</style>
@endsection

@section('content')

{{-- ── HERO ── --}}
<div class="statistik-hero">
    <div class="container text-center">
        <h1 class="mb-2"><i class="fas fa-chart-bar"></i> Statistik Kejadian Banjir</h1>
        <p class="lead mb-0">Data &amp; Analisis Kejadian Banjir Kabupaten Bantul</p>
    </div>
</div>

<div class="stat-page-wrap">
<div class="container">

    {{-- ── STAT SUMMARY CARDS ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card c-blue">
                <div class="stat-icon"><i class="fas fa-database"></i></div>
                <div style="min-width:0;">
                    <div class="stat-num">{{ number_format($totalLaporan) }}</div>
                    <div class="stat-label">Total Historis</div>
                    <div class="stat-accent"></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card c-green">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div style="min-width:0;">
                    <div class="stat-num">{{ number_format($totalVerified) }}</div>
                    <div class="stat-label">Laporan Verified</div>
                    <div class="stat-accent"></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card c-yellow">
                <div class="stat-icon"><i class="fas fa-map-marked-alt"></i></div>
                <div style="min-width:0;">
                    <div class="stat-num">{{ count($kecamatanStats) }}</div>
                    <div class="stat-label">Kecamatan Terdampak</div>
                    <div class="stat-accent"></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card c-red">
                <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div style="min-width:0;">
                    <div class="stat-num">{{ $kecamatanStats[0]['total'] ?? 0 }}</div>
                    <div class="stat-label">Titik Terbanyak</div>
                    <div class="stat-accent"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── INFO BOX ── --}}
    <div class="info-box mb-4">
        <div class="info-box-icon"><i class="fas fa-chart-line"></i></div>
        <div class="info-box-content">
            <h3><i class="fas fa-info-circle me-2"></i>Tentang Data</h3>
            <p>
                Data ini menampilkan <strong>{{ number_format($totalLaporan) }} titik historis</strong> kejadian banjir
                dari BPBD Bantul yang tersebar di <strong>{{ count($kecamatanStats) }} kecamatan</strong>.
                Analisis digunakan untuk mendukung mitigasi bencana dan pengambilan kebijakan.
            </p>
        </div>
    </div>

    {{-- ── ROW: TREN BULANAN + DONUT ── --}}
    <div class="row g-4 mb-4">

        {{-- Tren Bulanan --}}
        <div class="col-12 col-lg-7">
            <div class="chart-card">
                <div class="trend-header">
                    <div class="chart-card-title mb-0">
                        <i class="fas fa-chart-line"></i>
                        Tren Bulanan
                        <span style="font-size:13px;font-weight:600;color:#64748b;">
                            (<span id="current-year">{{ $selectedYear }}</span>)
                        </span>
                    </div>
                    <div class="year-pills">
                        @foreach ($availableYears as $year)
                        <button class="year-pill {{ $year == $selectedYear ? 'active' : '' }}"
                                data-year="{{ $year }}"
                                onclick="changeYear({{ $year }})">{{ $year }}</button>
                        @endforeach
                    </div>
                </div>
                <canvas id="trendChart" style="max-height:280px;"></canvas>
                <div class="source-note">
                    <strong>Sumber:</strong> Data historis BPBD Kabupaten Bantul 2020–2025.
                </div>
            </div>
        </div>

        {{-- Donut Distribusi --}}
        <div class="col-12 col-lg-5">
            <div class="chart-card">
                <div class="chart-card-title">
                    <i class="fas fa-chart-pie"></i>
                    Distribusi per Kecamatan
                    <span class="title-badge">Top 10</span>
                </div>
                <div class="donut-wrapper">
                    <div class="donut-canvas-wrap">
                        <canvas id="donutChart"></canvas>
                        <div class="donut-center-label">
                            <div class="dc-num">{{ collect($kecamatanStats)->sum('total') }}</div>
                            <div class="dc-sub">Total<br>Kejadian</div>
                        </div>
                    </div>
                    <div class="donut-legend" id="donutLegend"></div>
                </div>
                <div class="source-note">
                    <strong>Sumber:</strong> Data historis BPBD Kabupaten Bantul 2020–2025.
                </div>
            </div>
        </div>
    </div>

    {{-- ── TABEL DETAIL ── --}}
    <div class="chart-card mb-4">
        <div class="chart-card-title">
            <i class="fas fa-table"></i>
            Detail Statistik Per Kecamatan
        </div>
        <div class="table-responsive">
            <table class="stats-table table table-hover w-100">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Kecamatan</th>
                        <th class="text-center">Jumlah</th>
                        <th>Proporsi</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalAll = collect($kecamatanStats)->sum('total'); @endphp
                    @forelse($kecamatanStats as $idx => $item)
                        @php
                            $pct  = $totalAll > 0 ? ($item['total'] / $totalAll) * 100 : 0;
                            $status   = $pct >= 10 ? 'Tinggi' : ($pct >= 7 ? 'Sedang' : 'Rendah');
                            $sClass   = $pct >= 10 ? 's-high'  : ($pct >= 7 ? 's-medium' : 's-low');
                            $pFill    = $pct >= 10
                                ? 'linear-gradient(90deg,#ef4444,#dc2626)'
                                : ($pct >= 7
                                    ? 'linear-gradient(90deg,#f59e0b,#d97706)'
                                    : 'linear-gradient(90deg,#3b82f6,#2563eb)');
                            $rankClass = $idx === 0 ? 'rank-1' : ($idx === 1 ? 'rank-2' : ($idx === 2 ? 'rank-3' : 'rank-n'));
                        @endphp
                        <tr>
                            <td><span class="rank-badge {{ $rankClass }}">{{ $idx + 1 }}</span></td>
                            <td><strong style="color:#0c4a6e;">{{ $item['kecamatan'] }}</strong></td>
                            <td class="text-center">
                                <strong>{{ $item['total'] }}</strong>
                                <small class="text-muted">titik</small>
                            </td>
                            <td style="min-width:130px;">
                                <div class="prog-wrap">
                                    <div class="prog-fill" style="width:{{ $pct }}%;background:{{ $pFill }};">
                                        {{ number_format($pct,1) }}%
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="s-badge {{ $sClass }}">{{ $status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="source-note">
            <strong>Sumber:</strong> Data kejadian banjir historis Kabupaten Bantul 2020–2025.
            Badan Penanggulangan Bencana Daerah (BPBD) Kabupaten Bantul.
        </div>
    </div>

    {{-- ── CTA ── --}}
    <div class="cta-box">
        <h4><i class="fas fa-satellite-dish me-2" style="color:#0891b2;"></i>Eksplorasi Lebih Lanjut</h4>
        <p>Lihat sebaran banjir secara spasial atau laporkan kejadian banjir di sekitar Anda.</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('peta') }}" class="btn-cta btn-cta-primary">
                <i class="fas fa-map-marked-alt"></i> Lihat Peta Interaktif
            </a>
            <a href="{{ route('laporan') }}" class="btn-cta btn-cta-success">
                <i class="fas fa-paper-plane"></i> Lapor Kejadian Banjir
            </a>
        </div>
    </div>

</div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
const kecamatanStats = @json($kecamatanStats);
let yearlyData   = @json($yearlyData);
let selectedYear = {{ $selectedYear }};
let trendChart   = null;
let donutChart   = null;

const PALETTE = [
    '#0891b2','#10b981','#f59e0b','#ef4444','#8b5cf6',
    '#ec4899','#06b6d4','#84cc16','#f97316','#6366f1'
];

/* ── DONUT CHART ── */
function initDonutChart() {
    const top10 = kecamatanStats.slice(0, 10);
    const total = top10.reduce((s, k) => s + k.total, 0);
    const labels = top10.map(k => k.kecamatan);
    const vals   = top10.map(k => k.total);

    const ctx = document.getElementById('donutChart').getContext('2d');
    donutChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data: vals,
                backgroundColor: PALETTE,
                borderColor: '#fff',
                borderWidth: 3,
                hoverBorderWidth: 4,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '62%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(12,74,110,0.95)',
                    padding: 12,
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont:  { size: 12 },
                    borderColor: '#0891b2', borderWidth: 1.5,
                    callbacks: {
                        label: ctx => {
                            const pct = ((ctx.parsed / total) * 100).toFixed(1);
                            return `  ${ctx.parsed} titik (${pct}%)`;
                        }
                    }
                }
            },
            animation: { animateRotate: true, duration: 1000 }
        }
    });

    /* Custom Legend */
    const legend = document.getElementById('donutLegend');
    legend.innerHTML = top10.map((k, i) => {
        const pct = ((k.total / total) * 100).toFixed(1);
        return `<div class="donut-legend-item">
            <span class="donut-legend-dot" style="background:${PALETTE[i]};"></span>
            <span class="donut-legend-name">${k.kecamatan}</span>
            <span class="donut-legend-val">${k.total}</span>
            <span class="donut-legend-pct">&nbsp;${pct}%</span>
        </div>`;
    }).join('');
}

/* ── TREND CHART ── */
function initTrendChart(year) {
    const monthNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const monthly = yearlyData[year] || {};
    const vals  = Array.from({length:12}, (_, i) => monthly[i+1] || 0);
    const maxVal = Math.max(...vals, 1);

    if (trendChart) trendChart.destroy();

    const ctx  = document.getElementById('trendChart').getContext('2d');
    const grad = ctx.createLinearGradient(0, 0, 0, 280);
    grad.addColorStop(0, 'rgba(8,145,178,0.25)');
    grad.addColorStop(1, 'rgba(8,145,178,0.01)');

    trendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthNames,
            datasets: [{
                label: 'Kejadian',
                data: vals,
                borderColor: '#0891b2',
                backgroundColor: grad,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 8,
                pointBackgroundColor: '#0891b2',
                pointBorderColor: '#fff',
                pointBorderWidth: 2.5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    mode: 'index', intersect: false,
                    backgroundColor: 'rgba(12,74,110,0.95)',
                    padding: 12,
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont:  { size: 12 },
                    borderColor: '#0891b2', borderWidth: 1.5,
                    callbacks: {
                        label: ctx => `  ${ctx.parsed.y} kejadian`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: Math.max(1, Math.ceil(maxVal / 8)),
                        font: { size: 12, weight: '600' },
                        color: '#64748b', padding: 6,
                    },
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    title: {
                        display: true, text: 'Jumlah Kejadian',
                        font: { size: 12, weight: 'bold' }, color: '#0c4a6e'
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 12, weight: '600' }, color: '#64748b' }
                }
            },
            animation: { duration: 700 }
        }
    });
}

/* ── CHANGE YEAR ── */
function changeYear(year) {
    selectedYear = year;
    document.getElementById('current-year').textContent = year;
    document.querySelectorAll('.year-pill').forEach(btn => {
        btn.classList.toggle('active', parseInt(btn.dataset.year) === year);
    });
    initTrendChart(year);
}

/* ── INIT ── */
document.addEventListener('DOMContentLoaded', () => {
    initTrendChart(selectedYear);
    initDonutChart();

    /* Animate progress bars */
    setTimeout(() => {
        document.querySelectorAll('.prog-fill').forEach(el => {
            const w = el.style.width;
            el.style.width = '0';
            requestAnimationFrame(() => setTimeout(() => { el.style.width = w; }, 80));
        });
    }, 300);
});
</script>
@endsection
