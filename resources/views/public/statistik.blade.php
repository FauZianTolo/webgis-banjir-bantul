@extends('layouts.public')

@section('styles')
<style>
/* ============================================================
   STATISTIK PAGE — FULL REDESIGN
   ============================================================ */

/* ── HERO ── */
.statistik-hero {
    background: linear-gradient(135deg, #0c4a6e 0%, #0891b2 55%, #06b6d4 100%);
    color: white; padding: 4.5rem 0 3.5rem; position: relative; overflow: hidden;
}
.statistik-hero::before {
    content:''; position:absolute; inset:0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.25;
}
.statistik-hero h1 { position:relative; z-index:2; font-weight:900; font-size:2.8rem; text-shadow:2px 2px 8px rgba(0,0,0,0.25); }
.statistik-hero p  { position:relative; z-index:2; font-size:1.15rem; opacity:0.95; }

/* ── STAT CARDS (summary) ── */
.stat-card {
    background: white; border-radius: 22px; padding: 2rem 1.5rem;
    box-shadow: 0 8px 32px rgba(0,0,0,0.08);
    transition: all 0.3s cubic-bezier(.4,0,.2,1);
    text-align: center; height: 100%; position: relative; overflow: hidden;
    border: 2px solid transparent;
}
.stat-card::after {
    content:''; position:absolute; top:0; left:0; right:0; height:5px; border-radius:22px 22px 0 0;
}
.stat-card.c-blue::after   { background: linear-gradient(90deg,#667eea,#764ba2); }
.stat-card.c-green::after  { background: linear-gradient(90deg,#10b981,#059669); }
.stat-card.c-yellow::after { background: linear-gradient(90deg,#f59e0b,#d97706); }
.stat-card.c-red::after    { background: linear-gradient(90deg,#ef4444,#dc2626); }
.stat-card:hover { transform:translateY(-8px); box-shadow:0 20px 55px rgba(0,0,0,0.13); border-color:rgba(8,145,178,0.25); }
.stat-icon {
    width:72px; height:72px; margin:0 auto 1.25rem;
    border-radius:18px; display:flex; align-items:center; justify-content:center;
    font-size:2.1rem; color:white; box-shadow:0 8px 24px rgba(0,0,0,0.18);
}
.stat-card.c-blue   .stat-icon { background:linear-gradient(135deg,#667eea,#764ba2); }
.stat-card.c-green  .stat-icon { background:linear-gradient(135deg,#10b981,#059669); }
.stat-card.c-yellow .stat-icon { background:linear-gradient(135deg,#f59e0b,#d97706); }
.stat-card.c-red    .stat-icon { background:linear-gradient(135deg,#ef4444,#dc2626); }
.stat-num {
    font-size:2.8rem; font-weight:900; line-height:1; margin-bottom:0.4rem;
    -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;
}
.stat-card.c-blue   .stat-num { background:linear-gradient(135deg,#667eea,#764ba2); }
.stat-card.c-green  .stat-num { background:linear-gradient(135deg,#10b981,#059669); }
.stat-card.c-yellow .stat-num { background:linear-gradient(135deg,#f59e0b,#d97706); }
.stat-card.c-red    .stat-num { background:linear-gradient(135deg,#ef4444,#dc2626); }
.stat-label { font-size:0.95rem; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:0.4px; }

/* ── INFO BOX ── */
.info-box {
    background: linear-gradient(135deg,#0891b2,#0c4a6e);
    color: white; border-radius: 22px; padding: 2.5rem;
    margin-bottom: 2.5rem; box-shadow: 0 15px 50px rgba(8,145,178,0.3);
    position: relative; overflow: hidden;
}
.info-box::before {
    content:''; position:absolute; inset:0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.info-box h3 { position:relative; z-index:2; font-weight:800; font-size:1.4rem; }
.info-box p  { position:relative; z-index:2; line-height:1.8; opacity:0.95; margin:0; }

/* ── CHART CARDS ── */
.chart-card {
    background: white; border-radius: 22px; padding: 2rem 2rem 1.5rem;
    box-shadow: 0 8px 32px rgba(0,0,0,0.08);
    border: 1.5px solid rgba(8,145,178,0.1);
    transition: box-shadow 0.3s;
}
.chart-card:hover { box-shadow: 0 18px 55px rgba(8,145,178,0.13); }
.chart-card-title {
    font-size:1.2rem; font-weight:800; color:#0c4a6e;
    display:flex; align-items:center; gap:0.6rem; margin-bottom:1.5rem;
}
.chart-card-title .title-badge {
    background: linear-gradient(135deg,#0891b2,#06b6d4);
    color:white; font-size:11px; font-weight:700; padding:3px 10px;
    border-radius:20px; margin-left:auto;
}

/* ── YEAR PILLS ── */
.year-pills { display:flex; gap:6px; flex-wrap:wrap; }
.year-pill {
    padding:5px 14px; border-radius:20px; font-weight:700; font-size:13px; cursor:pointer;
    border: 2px solid #0891b2; color:#0891b2; background:white; transition:all 0.25s;
}
.year-pill:hover, .year-pill.active {
    background: linear-gradient(135deg,#0891b2,#06b6d4); color:white;
    box-shadow: 0 4px 14px rgba(8,145,178,0.35); transform:translateY(-1px);
}

/* ── DONUT CHART SECTION ── */
.donut-wrapper {
    display:flex; align-items:center; gap:2rem; flex-wrap:wrap;
}
.donut-canvas-wrap {
    flex:0 0 auto; position:relative;
    width:260px; height:260px;
}
.donut-center-label {
    position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);
    text-align:center; pointer-events:none;
}
.donut-center-label .dc-num {
    font-size:2rem; font-weight:900; color:#0c4a6e; line-height:1;
}
.donut-center-label .dc-sub {
    font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;
}
.donut-legend { flex:1; min-width:220px; }
.donut-legend-item {
    display:flex; align-items:center; gap:10px;
    padding:8px 12px; border-radius:10px; margin-bottom:5px;
    transition:background 0.2s; cursor:default;
}
.donut-legend-item:hover { background:#f0f9ff; }
.donut-legend-dot { width:14px; height:14px; border-radius:4px; flex-shrink:0; }
.donut-legend-name { font-size:13px; font-weight:700; color:#334155; flex:1; }
.donut-legend-val  { font-size:13px; font-weight:800; color:#0891b2; }
.donut-legend-pct  { font-size:11px; font-weight:600; color:#94a3b8; }

/* ── TREND CHART ── */
.trend-header {
    display:flex; justify-content:space-between; align-items:center;
    flex-wrap:wrap; gap:10px; margin-bottom:1.5rem;
}

/* ── TABLE ── */
.stats-table { border-radius:12px; overflow:hidden; }
.stats-table thead { background:linear-gradient(135deg,#0891b2,#06b6d4); color:white; }
.stats-table thead th { border:none; padding:1rem 0.9rem; font-weight:800; font-size:0.88rem; letter-spacing:0.4px; white-space:nowrap; }
.stats-table tbody tr { transition:all 0.25s; border-bottom:1px solid #e2e8f0; }
.stats-table tbody tr:hover { background:linear-gradient(135deg,#f0f9ff,#e0f2fe); }
.stats-table tbody td { padding:0.9rem; vertical-align:middle; }
.rank-badge {
    width:32px; height:32px; border-radius:50%;
    display:inline-flex; align-items:center; justify-content:center;
    font-weight:900; font-size:13px; color:white;
}
.rank-1 { background:linear-gradient(135deg,#f59e0b,#d97706); }
.rank-2 { background:linear-gradient(135deg,#94a3b8,#64748b); }
.rank-3 { background:linear-gradient(135deg,#cd7c3a,#b86a2a); }
.rank-n { background:linear-gradient(135deg,#0891b2,#06b6d4); }

/* ── PROGRESS BAR ── */
.prog-wrap { background:#e2e8f0; border-radius:8px; height:22px; overflow:hidden; }
.prog-fill  { height:100%; border-radius:8px; display:flex; align-items:center; justify-content:flex-end; padding-right:8px; font-size:11px; font-weight:800; color:white; transition:width 1s ease; }

/* ── STATUS BADGE ── */
.s-badge { padding:4px 12px; border-radius:20px; font-weight:700; font-size:12px; display:inline-block; }
.s-high   { background:#fee2e2; color:#991b1b; }
.s-medium { background:#fef3c7; color:#92400e; }
.s-low    { background:#dbeafe; color:#1e40af; }

/* ── SOURCE ── */
.source-note { margin-top:12px; padding:8px 14px; background:#f8fafc; border-radius:8px; border-left:3px solid #0891b2; font-size:11px; color:#64748b; }
.source-note strong { color:#0c4a6e; }

/* ── CTA ── */
.btn-cta {
    padding:0.9rem 2.2rem; border-radius:14px; font-weight:800; font-size:1rem;
    border:none; transition:all 0.3s; display:inline-flex; align-items:center; gap:8px;
}
.btn-cta-primary { background:linear-gradient(135deg,#0891b2,#06b6d4); color:white; box-shadow:0 8px 24px rgba(8,145,178,0.3); }
.btn-cta-primary:hover { transform:translateY(-4px); box-shadow:0 14px 36px rgba(8,145,178,0.45); color:white; }
.btn-cta-success { background:linear-gradient(135deg,#10b981,#059669); color:white; box-shadow:0 8px 24px rgba(16,185,129,0.3); }
.btn-cta-success:hover { transform:translateY(-4px); box-shadow:0 14px 36px rgba(16,185,129,0.45); color:white; }

/* ── RESPONSIVE ── */
@media (max-width:991px) {
    .donut-canvas-wrap { width:220px; height:220px; }
}
@media (max-width:767px) {
    .statistik-hero { padding:2.5rem 0 2rem; }
    .statistik-hero h1 { font-size:1.75rem; }
    .statistik-hero p { font-size:0.95rem; }

    /* Stat cards 2 kolom */
    .stat-cards-row .col-6 { padding:0 6px; }
    .stat-card { padding:1.4rem 1rem; border-radius:16px; }
    .stat-icon { width:54px; height:54px; font-size:1.5rem; border-radius:13px; margin-bottom:0.9rem; }
    .stat-num { font-size:2rem; }
    .stat-label { font-size:0.8rem; }

    .info-box { padding:1.5rem; border-radius:16px; }
    .info-box h3 { font-size:1.1rem; }
    .info-box p { font-size:0.9rem; }

    .chart-card { padding:1.25rem; border-radius:16px; }
    .chart-card-title { font-size:1rem; }
    .year-pills { gap:4px; }
    .year-pill { padding:4px 10px; font-size:12px; }

    .donut-wrapper { flex-direction:column; align-items:center; gap:1.2rem; }
    .donut-canvas-wrap { width:200px; height:200px; }
    .donut-center-label .dc-num { font-size:1.5rem; }
    .donut-legend { width:100%; min-width:unset; }

    .stats-table thead th { padding:0.7rem 0.5rem; font-size:0.78rem; }
    .stats-table tbody td { padding:0.65rem 0.5rem; font-size:0.82rem; }

    .btn-cta { padding:0.75rem 1.5rem; font-size:0.9rem; }
}
@media (max-width:420px) {
    .statistik-hero h1 { font-size:1.45rem; }
    .stat-num { font-size:1.75rem; }
}
</style>
@endsection

@section('content')

{{-- ── HERO ── --}}
<div class="statistik-hero">
    <div class="container text-center">
        <h1 class="mb-3"><i class="fas fa-chart-bar"></i> Statistik Kejadian Banjir</h1>
        <p class="lead mb-0">Data & Analisis Kejadian Banjir Kabupaten Bantul — {{ now()->year }}</p>
    </div>
</div>

<div class="container mb-5">

    {{-- ── STAT SUMMARY CARDS ── --}}
    <div class="row g-3 mb-4 stat-cards-row">
        <div class="col-6 col-md-3">
            <div class="stat-card c-blue">
                <div class="stat-icon"><i class="fas fa-database"></i></div>
                <div class="stat-num">{{ number_format($totalLaporan) }}</div>
                <div class="stat-label">Total Historis</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card c-green">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-num">{{ number_format($totalVerified) }}</div>
                <div class="stat-label">Laporan Verified</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card c-yellow">
                <div class="stat-icon"><i class="fas fa-map-marked-alt"></i></div>
                <div class="stat-num">{{ count($kecamatanStats) }}</div>
                <div class="stat-label">Kecamatan Terdampak</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card c-red">
                <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-num">{{ $kecamatanStats[0]['total'] ?? 0 }}</div>
                <div class="stat-label">Titik Terbanyak</div>
            </div>
        </div>
    </div>

    {{-- ── INFO BOX ── --}}
    <div class="info-box mb-4">
        <div class="row align-items-center">
            <div class="col-md-9">
                <h3 class="mb-2"><i class="fas fa-info-circle"></i> Tentang Data</h3>
                <p>
                    Data ini menampilkan <strong>{{ number_format($totalLaporan) }} titik historis</strong> kejadian banjir
                    dari BPBD Bantul yang tersebar di <strong>{{ count($kecamatanStats) }} kecamatan</strong>.
                    Analisis digunakan untuk mendukung mitigasi bencana dan pengambilan kebijakan.
                </p>
            </div>
            <div class="col-md-3 text-end d-none d-md-block">
                <i class="fas fa-chart-pie" style="font-size:5rem;opacity:0.25;"></i>
            </div>
        </div>
    </div>

    {{-- ── ROW: TREND + DONUT ── --}}
    <div class="row g-4 mb-4">

        {{-- Trend Bulanan --}}
        <div class="col-12 col-lg-7">
            <div class="chart-card h-100">
                <div class="trend-header">
                    <div class="chart-card-title mb-0">
                        <i class="fas fa-chart-line" style="color:#0891b2;"></i>
                        Tren Bulanan
                        <span style="font-size:14px;font-weight:600;color:#64748b;">(<span id="current-year">{{ $selectedYear }}</span>)</span>
                    </div>
                    <div class="year-pills">
                        @foreach ($availableYears as $year)
                        <button class="year-pill {{ $year == $selectedYear ? 'active' : '' }}"
                                data-year="{{ $year }}" onclick="changeYear({{ $year }})">{{ $year }}</button>
                        @endforeach
                    </div>
                </div>
                <canvas id="trendChart" style="max-height:280px;"></canvas>
                <div class="source-note mt-3">
                    <strong>Sumber:</strong> Data historis BPBD Kabupaten Bantul 2020–2025.
                </div>
            </div>
        </div>

        {{-- Donut Kecamatan --}}
        <div class="col-12 col-lg-5">
            <div class="chart-card h-100">
                <div class="chart-card-title">
                    <i class="fas fa-chart-pie" style="color:#0891b2;"></i>
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
                    <div class="donut-legend" id="donutLegend">
                        {{-- filled by JS --}}
                    </div>
                </div>
                <div class="source-note mt-3">
                    <strong>Sumber:</strong> Data historis BPBD Kabupaten Bantul 2020–2025.
                </div>
            </div>
        </div>
    </div>

    {{-- ── TABEL DETAIL ── --}}
    <div class="chart-card mb-4">
        <div class="chart-card-title">
            <i class="fas fa-table" style="color:#0891b2;"></i>
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
                            $status = $pct >= 10 ? 'Tinggi' : ($pct >= 7 ? 'Sedang' : 'Rendah');
                            $sClass = $pct >= 10 ? 's-high' : ($pct >= 7 ? 's-medium' : 's-low');
                            $pFill  = $pct >= 10 ? 'linear-gradient(90deg,#ef4444,#dc2626)'
                                    : ($pct >= 7 ? 'linear-gradient(90deg,#f59e0b,#d97706)'
                                                 : 'linear-gradient(90deg,#3b82f6,#2563eb)');
                            $rankClass = $idx === 0 ? 'rank-1' : ($idx === 1 ? 'rank-2' : ($idx === 2 ? 'rank-3' : 'rank-n'));
                        @endphp
                        <tr>
                            <td>
                                <span class="rank-badge {{ $rankClass }}">{{ $idx + 1 }}</span>
                            </td>
                            <td><strong style="color:#0c4a6e;">{{ $item['kecamatan'] }}</strong></td>
                            <td class="text-center"><strong>{{ $item['total'] }}</strong> <small class="text-muted">titik</small></td>
                            <td style="min-width:140px;">
                                <div class="prog-wrap">
                                    <div class="prog-fill" style="width:{{ $pct }}%;background:{{ $pFill }};">
                                        {{ number_format($pct,1) }}%
                                    </div>
                                </div>
                            </td>
                            <td class="text-center"><span class="s-badge {{ $sClass }}">{{ $status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="source-note">
            <strong>Sumber:</strong> Data kejadian banjir historis Kabupaten Bantul tahun 2020–2025.
            Sumber: Badan Penanggulangan Bencana Daerah (BPBD) Kabupaten Bantul.
        </div>
    </div>

    {{-- ── CTA ── --}}
    <div class="text-center mt-4 d-flex justify-content-center gap-3 flex-wrap">
        <a href="{{ route('peta') }}" class="btn-cta btn-cta-primary">
            <i class="fas fa-map-marked-alt"></i> Lihat Peta Interaktif
        </a>
        <a href="{{ route('laporan') }}" class="btn-cta btn-cta-success">
            <i class="fas fa-paper-plane"></i> Lapor Kejadian Banjir
        </a>
    </div>

</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
const kecamatanStats = @json($kecamatanStats);
let yearlyData  = @json($yearlyData);
let selectedYear = {{ $selectedYear }};
let trendChart  = null;
let donutChart  = null;

/* ── PALETTE ── */
const PALETTE = [
    '#0891b2','#10b981','#f59e0b','#ef4444','#8b5cf6',
    '#ec4899','#06b6d4','#84cc16','#f97316','#6366f1'
];

/* ───────────────────────────── DONUT CHART ─────────────────────────── */
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
                    bodyFont: { size: 12 },
                    borderColor: '#0891b2',
                    borderWidth: 1.5,
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

/* ───────────────────────────── TREND CHART ─────────────────────────── */
function initTrendChart(year) {
    const monthNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const monthly = yearlyData[year] || {};
    const vals = Array.from({length:12}, (_, i) => monthly[i+1] || 0);
    const maxVal = Math.max(...vals, 1);

    if (trendChart) trendChart.destroy();

    const ctx = document.getElementById('trendChart').getContext('2d');

    // Gradient fill
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
                    bodyFont: { size: 12 },
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
                        color: '#64748b',
                        padding: 6,
                    },
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    title: { display: true, text: 'Jumlah Kejadian', font: { size: 12, weight: 'bold' }, color: '#0c4a6e' }
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

/* ───────────────────────────── CHANGE YEAR ─────────────────────────── */
function changeYear(year) {
    selectedYear = year;
    document.getElementById('current-year').textContent = year;
    document.querySelectorAll('.year-pill').forEach(btn => {
        btn.classList.toggle('active', parseInt(btn.dataset.year) === year);
    });
    initTrendChart(year);
}

/* ───────────────────────────── INIT ─────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
    initTrendChart(selectedYear);
    initDonutChart();

    /* Animate progress bars */
    setTimeout(() => {
        document.querySelectorAll('.prog-fill').forEach(el => {
            const w = el.style.width;
            el.style.width = '0';
            requestAnimationFrame(() => {
                setTimeout(() => { el.style.width = w; }, 80);
            });
        });
    }, 200);
});
</script>
@endsection
