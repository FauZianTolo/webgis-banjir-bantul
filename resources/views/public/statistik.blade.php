@extends('layouts.public')

@section('styles')
    <style>
        /* ==================== HERO SECTION ==================== */
        .statistik-hero {
            background: linear-gradient(135deg, #0c4a6e 0%, #0891b2 50%, #06b6d4 100%);
            color: white;
            padding: 4rem 0;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
        }

        .statistik-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.3;
        }

        .statistik-hero h1 {
            position: relative;
            z-index: 2;
            font-weight: 900;
            font-size: 2.8rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .statistik-hero p {
            position: relative;
            z-index: 2;
            font-size: 1.2rem;
            opacity: 0.95;
        }

        /* ==================== STAT CARDS ==================== */
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-align: center;
            height: 100%;
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            border-color: rgba(8, 145, 178, 0.3);
        }

        .stat-card.bg-gradient-primary::before {
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .stat-card.bg-gradient-success::before {
            background: linear-gradient(90deg, #10b981, #059669);
        }

        .stat-card.bg-gradient-warning::before {
            background: linear-gradient(90deg, #f59e0b, #d97706);
        }

        .stat-card.bg-gradient-danger::before {
            background: linear-gradient(90deg, #ef4444, #dc2626);
        }

        .stat-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #0891b2, #22d3ee);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
            box-shadow: 0 10px 30px rgba(8, 145, 178, 0.3);
        }

        .stat-card.bg-gradient-primary .stat-icon {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .stat-card.bg-gradient-success .stat-icon {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .stat-card.bg-gradient-warning .stat-icon {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .stat-card.bg-gradient-danger .stat-icon {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .stat-info h3 {
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-card.bg-gradient-primary .stat-info h3 {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stat-card.bg-gradient-success .stat-info h3 {
            background: linear-gradient(135deg, #10b981, #059669);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stat-card.bg-gradient-warning .stat-info h3 {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stat-card.bg-gradient-danger .stat-info h3 {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stat-info p {
            font-size: 1.1rem;
            font-weight: 600;
            color: #64748b;
            margin: 0;
        }

        /* ==================== INFO BOX ==================== */
        .info-box {
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: white;
            border-radius: 20px;
            padding: 2.5rem;
            margin-bottom: 3rem;
            box-shadow: 0 15px 50px rgba(8, 145, 178, 0.3);
            position: relative;
            overflow: hidden;
        }

        .info-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.2;
        }

        .info-box h3 {
            position: relative;
            z-index: 2;
            font-weight: 800;
            font-size: 1.5rem;
        }

        .info-box p {
            position: relative;
            z-index: 2;
            line-height: 1.8;
            opacity: 0.95;
        }

        /* ==================== CHART CARDS ==================== */
        .chart-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            height: 100%;
            border: 2px solid rgba(8, 145, 178, 0.1);
            transition: all 0.3s ease;
        }

        .chart-card:hover {
            box-shadow: 0 20px 60px rgba(8, 145, 178, 0.15);
            border-color: rgba(8, 145, 178, 0.3);
        }

        .chart-card h5 {
            color: #0c4a6e;
            font-weight: 800;
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-group .btn {
            border-radius: 8px;
            font-weight: 700;
            padding: 0.5rem 1.25rem;
            transition: all 0.3s ease;
        }

        .btn-outline-primary {
            border-color: #0891b2;
            color: #0891b2;
        }

        .btn-outline-primary:hover,
        .btn-outline-primary.active {
            background: #0891b2;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(8, 145, 178, 0.3);
        }

        /* ==================== KECAMATAN BARS ==================== */
        .chart-container {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
            border: 2px solid rgba(8, 145, 178, 0.1);
        }

        .chart-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #0c4a6e;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .kecamatan-bar {
            display: flex;
            align-items: center;
            margin-bottom: 1.25rem;
            padding: 1.25rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 12px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .kecamatan-bar:hover {
            background: linear-gradient(135deg, #e0f2fe, #dbeafe);
            transform: translateX(8px);
            border-color: #0891b2;
            box-shadow: 0 5px 20px rgba(8, 145, 178, 0.15);
        }

        .kecamatan-name {
            width: 160px;
            font-weight: 700;
            color: #0c4a6e;
            font-size: 1rem;
        }

        .bar-wrapper {
            flex: 1;
            height: 40px;
            background: #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            margin: 0 1.25rem;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #0891b2, #22d3ee);
            border-radius: 12px;
            transition: width 1.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 12px;
            color: white;
            font-weight: 800;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(8, 145, 178, 0.4);
        }

        /* ==================== TABLE ==================== */
        .table {
            border-radius: 12px;
            overflow: hidden;
        }

        .table thead {
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: white;
        }

        .table thead th {
            border: none;
            padding: 1.25rem 1rem;
            font-weight: 800;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #e2e8f0;
        }

        .table tbody tr:hover {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            transform: scale(1.01);
        }

        .table tbody td {
            padding: 1.25rem 1rem;
            vertical-align: middle;
        }

        .progress {
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .badge {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.9rem;
        }

        /* ==================== SOURCE BADGE ==================== */
        .source-badge {
            margin-top: 10px;
            padding: 8px 14px;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 3px solid #0891b2;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .source-badge i {
            color: #0891b2;
            font-size: 11px;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .source-badge span {
            font-size: 11px;
            color: #64748b;
            line-height: 1.5;
        }

        .source-badge strong {
            color: #0c4a6e;
        }

        /* ==================== CTA BUTTONS ==================== */
        .btn-lg {
            padding: 1rem 2.5rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(8, 145, 178, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            border: none;
        }

        .btn-success:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(16, 185, 129, 0.4);
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 768px) {
            .statistik-hero h1 {
                font-size: 2rem;
            }

            .kecamatan-name {
                width: 120px;
                font-size: 0.9rem;
            }

            .bar-wrapper {
                height: 35px;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Hero Section -->
    <div class="statistik-hero">
        <div class="container">
            <h1 class="text-center mb-3">
                <i class="fas fa-chart-bar"></i> Statistik Kejadian Banjir
            </h1>
            <p class="text-center lead">
                Data dan Analisis Kejadian Banjir di Kabupaten Bantul
            </p>
        </div>
    </div>

    <div class="container mb-5">

        <!-- Stats Cards -->
        <div class="row mb-5 g-4">
            <div class="col-md-3">
                <div class="stat-card bg-gradient-primary">
                    <div class="stat-icon">
                        <i class="fas fa-database"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ number_format($totalLaporan) }}</h3>
                        <p>Total Data Historis</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card bg-gradient-success">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ number_format($totalVerified) }}</h3>
                        <p>Laporan Terverifikasi</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card bg-gradient-warning">
                    <div class="stat-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ count($kecamatanStats) }}</h3>
                        <p>Kecamatan Terdampak</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card bg-gradient-danger">
                    <div class="stat-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $kecamatanStats[0]['total'] ?? 0 }}</h3>
                        <p>Titik Terbanyak</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3><i class="fas fa-info-circle"></i> Tentang Data</h3>
                    <p class="mb-0">
                        Data statistik ini menampilkan {{ number_format($totalLaporan) }} titik historis kejadian banjir
                        dari BPBD Bantul
                        yang tersebar di {{ count($kecamatanStats) }} kecamatan. Grafik dan analisis membantu pemangku
                        kebijakan
                        dalam pengambilan keputusan terkait mitigasi banjir.
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <i class="fas fa-chart-line" style="font-size: 6rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>

        <!-- Chart: Tren Bulanan -->
        <div class="chart-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                <h5 class="mb-2 mb-md-0">
                    <i class="fas fa-chart-line"></i>
                    Tren Kejadian Bulanan (<span id="current-year">{{ $selectedYear }}</span>)
                </h5>

                <div class="btn-group" role="group">
                    @foreach ($availableYears as $year)
                        <button type="button"
                            class="btn btn-sm btn-outline-primary year-filter {{ $year == $selectedYear ? 'active' : '' }}"
                            data-year="{{ $year }}" onclick="changeYear({{ $year }})">
                            {{ $year }}
                        </button>
                    @endforeach
                </div>
            </div>

            <canvas id="trendChart"></canvas>
            <div class="source-badge">
                <i class="fas fa-database"></i>
                <span><strong>Sumber Data:</strong> Data kejadian banjir historis Kabupaten Bantul tahun 2020–2025. Sumber: Badan Penanggulangan Bencana Daerah (BPBD) Kabupaten Bantul.</span>
            </div>
        </div>

        <!-- Top 10 Kecamatan Rawan -->
        <div class="chart-container">
            <h4 class="chart-title">
                <i class="fas fa-map-marked-alt"></i>
                10 Kecamatan Paling Rawan Banjir
            </h4>

            @php
                $maxTotal = collect($kecamatanStats)->max('total') ?? 1;
            @endphp

            @forelse($kecamatanStats as $kec)
                <div class="kecamatan-bar">
                    <div class="kecamatan-name">{{ $kec['kecamatan'] }}</div>
                    <div class="bar-wrapper">
                        <div class="bar-fill" style="width: {{ ($kec['total'] / $maxTotal) * 100 }}%;">
                            {{ $kec['total'] }}x
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-4">
                    <i class="fas fa-inbox"></i> Tidak ada data
                </div>
            @endforelse
            <div class="source-badge">
                <i class="fas fa-database"></i>
                <span><strong>Sumber Data:</strong> Data kejadian banjir historis Kabupaten Bantul tahun 2020–2025. Sumber: Badan Penanggulangan Bencana Daerah (BPBD) Kabupaten Bantul.</span>
            </div>
        </div>

        <!-- Tabel Detail Per Kecamatan -->
        <div class="chart-container">
            <h4 class="chart-title">
                <i class="fas fa-table"></i>
                Detail Statistik Per Kecamatan
            </h4>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Ranking</th>
                            <th>Kecamatan</th>
                            <th class="text-center">Jumlah Titik</th>
                            <th class="text-center">Persentase</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalAll = collect($kecamatanStats)->sum('total');
                        @endphp

                        @forelse($kecamatanStats as $index => $item)
                            @php
                                $percentage = $totalAll > 0 ? ($item['total'] / $totalAll) * 100 : 0;
                                $status = $percentage >= 10 ? 'Tinggi' : ($percentage >= 7 ? 'Sedang' : 'Rendah');
                                $badgeClass = $percentage >= 10 ? 'danger' : ($percentage >= 7 ? 'warning' : 'info');
                            @endphp
                            <tr>
                                <td><strong>#{{ $index + 1 }}</strong></td>
                                <td><strong>{{ $item['kecamatan'] }}</strong></td>
                                <td class="text-center">{{ $item['total'] }} titik</td>
                                <td>
                                    <div class="progress" style="height: 28px;">
                                        <div class="progress-bar bg-{{ $badgeClass }}"
                                            style="width: {{ $percentage }}%" role="progressbar">
                                            <strong>{{ number_format($percentage, 1) }}%</strong>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $badgeClass }}">{{ $status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Tidak ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="source-badge">
                <i class="fas fa-database"></i>
                <span><strong>Sumber Data:</strong> Data kejadian banjir historis Kabupaten Bantul tahun 2020–2025. Sumber: Badan Penanggulangan Bencana Daerah (BPBD) Kabupaten Bantul.</span>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="text-center mt-5">
            <a href="{{ route('peta') }}" class="btn btn-lg btn-primary me-2 mb-2">
                <i class="fas fa-map"></i> Lihat Peta Interaktif
            </a>
            <a href="{{ route('laporan') }}" class="btn btn-lg btn-success mb-2">
                <i class="fas fa-paper-plane"></i> Lapor Kejadian Banjir
            </a>
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
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const monthlyData = yearlyData[year] || {};
            const trendValues = [];

            for (let i = 1; i <= 12; i++) {
                trendValues.push(monthlyData[i] || 0);
            }

            if (trendChart) {
                trendChart.destroy();
            }

            const ctx = document.getElementById('trendChart').getContext('2d');
            trendChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: monthNames,
                    datasets: [{
                        label: 'Jumlah Kejadian',
                        data: trendValues,
                        borderColor: '#0891b2',
                        backgroundColor: 'rgba(8, 145, 178, 0.1)',
                        borderWidth: 4,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 6,
                        pointHoverRadius: 9,
                        pointBackgroundColor: '#0891b2',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 3,
                        pointHoverBorderWidth: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 2.5,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: {
                                    size: 14,
                                    weight: 'bold',
                                    family: 'Inter'
                                },
                                color: '#0c4a6e'
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(12, 74, 110, 0.95)',
                            padding: 15,
                            titleFont: {
                                size: 15,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 14
                            },
                            borderColor: '#0891b2',
                            borderWidth: 2,
                            callbacks: {
                                label: function(context) {
                                    return 'Jumlah Kejadian: ' + context.parsed.y + ' titik';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: Math.max(1, Math.ceil(Math.max(...trendValues) / 10)),
                                font: {
                                    size: 13,
                                    weight: '600'
                                },
                                color: '#64748b'
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            },
                            title: {
                                display: true,
                                text: 'Jumlah Kejadian',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                color: '#0c4a6e'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 13,
                                    weight: '600'
                                },
                                color: '#64748b'
                            },
                            title: {
                                display: true,
                                text: 'Bulan',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                color: '#0c4a6e'
                            }
                        }
                    }
                }
            });

            console.log(`✅ Chart loaded for year ${year}`);
        }

        function changeYear(year) {
            selectedYear = year;
            document.getElementById('current-year').textContent = year;

            document.querySelectorAll('.year-filter').forEach(btn => {
                btn.classList.remove('active');
                if (parseInt(btn.dataset.year) === year) {
                    btn.classList.add('active');
                }
            });

            initTrendChart(year);
            console.log(`📅 Year changed to ${year}`);
        }

        document.addEventListener('DOMContentLoaded', function() {
            initTrendChart(selectedYear);

            setTimeout(() => {
                document.querySelectorAll('.bar-fill').forEach(bar => {
                    const width = bar.style.width;
                    bar.style.width = '0%';
                    setTimeout(() => {
                        bar.style.width = width;
                    }, 100);
                });
            }, 300);
        });

        console.log('✅ Statistik page loaded');
    </script>
@endsection
