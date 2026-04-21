<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 style="color: #0c4a6e; font-weight: 900; font-size: 2rem; margin: 0;">
                <i class="fas fa-tachometer-alt"></i> Dashboard Admin BPBD Bantul
            </h2>
            <p style="color: #64748b; margin: 0.5rem 0 0 0; font-weight: 600;">
                <i class="fas fa-calendar"></i> {{ now()->isoFormat('dddd, D MMMM YYYY') }}
            </p>
        </div>
    </x-slot>

    @push('styles')
        <style>
            /* ==================== STATS CARDS ==================== */
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 1rem;
                margin-bottom: 2rem;
            }

            .stats-card {
                background: white;
                border-radius: 20px;
                padding: 2rem;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                border: 2px solid transparent;
                position: relative;
                overflow: hidden;
            }

            .stats-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 5px;
                transition: all 0.3s ease;
            }

            .stats-card.blue::before {
                background: linear-gradient(90deg, #3b82f6, #2563eb);
            }

            .stats-card.green::before {
                background: linear-gradient(90deg, #10b981, #059669);
            }

            .stats-card.yellow::before {
                background: linear-gradient(90deg, #f59e0b, #d97706);
            }

            .stats-card.red::before {
                background: linear-gradient(90deg, #ef4444, #dc2626);
            }

            .stats-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
            }

            .stats-card.blue:hover {
                border-color: #3b82f6;
            }

            .stats-card.green:hover {
                border-color: #10b981;
            }

            .stats-card.yellow:hover {
                border-color: #f59e0b;
            }

            .stats-card.red:hover {
                border-color: #ef4444;
            }

            .stats-icon {
                width: 70px;
                height: 70px;
                border-radius: 15px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 2rem;
                color: white;
                margin-bottom: 1.5rem;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            }

            .stats-card.blue .stats-icon {
                background: linear-gradient(135deg, #3b82f6, #2563eb);
            }

            .stats-card.green .stats-icon {
                background: linear-gradient(135deg, #10b981, #059669);
            }

            .stats-card.yellow .stats-icon {
                background: linear-gradient(135deg, #f59e0b, #d97706);
            }

            .stats-card.red .stats-icon {
                background: linear-gradient(135deg, #ef4444, #dc2626);
            }

            .stats-number {
                font-size: 3rem;
                font-weight: 900;
                margin-bottom: 0.5rem;
                color: #0891b2;
            }

            .stats-card.blue .stats-number  { color: #2563eb; }
            .stats-card.green .stats-number  { color: #059669; }
            .stats-card.yellow .stats-number { color: #d97706; }
            .stats-card.red .stats-number    { color: #dc2626; }

            .stats-label {
                font-size: 1rem;
                color: #64748b;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                font-weight: 700;
            }

            /* ==================== QUICK ACTIONS ==================== */
            .quick-actions-card {
                background: white;
                border-radius: 25px;
                padding: 2.5rem;
                box-shadow: 0 15px 50px rgba(0, 0, 0, 0.05);
                margin-bottom: 2.5rem;
                border: 2px solid rgba(8, 145, 178, 0.1);
            }

            .quick-actions-title {
                color: #0c4a6e;
                font-weight: 900;
                font-size: 1.75rem;
                margin-bottom: 2rem;
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .quick-actions-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
                gap: 1rem;
            }

            .quick-action-item {
                background: linear-gradient(135deg, #f8fafc, #f0f9ff);
                border-radius: 15px;
                padding: 2rem 1.5rem;
                text-align: center;
                transition: all 0.3s ease;
                cursor: pointer;
                border: 2px solid #e2e8f0;
                text-decoration: none;
                color: inherit;
                display: block;
            }

            .quick-action-item:hover {
                transform: translateY(-8px);
                box-shadow: 0 15px 40px rgba(8, 145, 178, 0.15);
                border-color: #0891b2;
                background: linear-gradient(135deg, #ffffff, #e0f2fe);
            }

            .quick-action-icon {
                font-size: 2.75rem;
                margin-bottom: 1.25rem;
                transition: all 0.3s ease;
            }

            .quick-action-item:hover .quick-action-icon {
                transform: scale(1.15);
            }

            .quick-action-title {
                font-weight: 800;
                color: #0c4a6e;
                margin-bottom: 0.5rem;
                font-size: 1.1rem;
            }

            .quick-action-subtitle {
                font-size: 0.85rem;
                color: #64748b;
                font-weight: 600;
            }

            /* ==================== CHARTS ==================== */
            .charts-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 1.5rem;
                margin-bottom: 2rem;
            }

            .chart-card {
                background: white;
                border-radius: 20px;
                padding: 2.5rem;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
                border: 2px solid rgba(8, 145, 178, 0.1);
            }

            .chart-title {
                color: #0c4a6e;
                font-weight: 800;
                font-size: 1.3rem;
                margin-bottom: 2rem;
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            /* ==================== TABLE ==================== */
            .table-card {
                background: white;
                border-radius: 20px;
                padding: 2.5rem;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
                border: 2px solid rgba(8, 145, 178, 0.1);
            }

            .table-title {
                color: #0c4a6e;
                font-weight: 800;
                font-size: 1.5rem;
                margin-bottom: 2rem;
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

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

            .table img {
                transition: all 0.3s ease;
                border: 2px solid #e2e8f0;
            }

            .table img:hover {
                transform: scale(1.1);
                border-color: #0891b2;
                box-shadow: 0 5px 15px rgba(8, 145, 178, 0.3);
            }

            .badge {
                padding: 0.5rem 1rem;
                border-radius: 8px;
                font-weight: 700;
                font-size: 0.9rem;
            }

            .btn {
                border-radius: 10px;
                font-weight: 700;
                padding: 0.6rem 1.25rem;
                transition: all 0.3s ease;
            }

            .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            }

            /* ==================== MODAL ==================== */
            .modal-image-viewer {
                display: none;
                position: fixed;
                z-index: 99999;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.95);
                backdrop-filter: blur(8px);
            }

            .modal-image-content {
                position: relative;
                margin: auto;
                padding: 20px;
                width: 90%;
                max-width: 1200px;
                top: 50%;
                transform: translateY(-50%);
            }

            .modal-image-viewer img {
                width: 100%;
                height: auto;
                max-height: 85vh;
                object-fit: contain;
                border-radius: 15px;
                box-shadow: 0 20px 80px rgba(0, 0, 0, 0.6);
            }

            .modal-close-btn {
                position: absolute;
                top: -15px;
                right: -15px;
                color: #fff;
                background: #ef4444;
                font-size: 32px;
                font-weight: bold;
                width: 55px;
                height: 55px;
                border-radius: 50%;
                border: 4px solid white;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
                z-index: 100000;
                line-height: 1;
                box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
            }

            .modal-close-btn:hover {
                background: #dc2626;
                transform: scale(1.15) rotate(90deg);
            }

            /* ==================== RESPONSIVE ==================== */
            @media (max-width: 767px) {
                /* Stats - 2 kolom di HP */
                .stats-grid {
                    grid-template-columns: repeat(2, 1fr);
                    gap: 0.75rem;
                    margin-bottom: 1.25rem;
                }
                .stats-card { padding: 1.25rem 1rem; border-radius: 14px; }
                .stats-icon { width: 50px; height: 50px; font-size: 1.4rem; margin-bottom: 1rem; border-radius: 12px; }
                .stats-number { font-size: 2rem; }
                .stats-label { font-size: 0.78rem; letter-spacing: 0; }

                /* Quick actions - 2 kolom */
                .quick-actions-card { padding: 1.25rem; border-radius: 16px; margin-bottom: 1.25rem; }
                .quick-actions-title { font-size: 1.2rem; margin-bottom: 1rem; }
                .quick-actions-grid { grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }
                .quick-action-item { padding: 1.25rem 0.75rem; border-radius: 12px; }
                .quick-action-icon { font-size: 1.8rem; margin-bottom: 0.75rem; }
                .quick-action-title { font-size: 0.88rem; margin-bottom: 0.25rem; }
                .quick-action-subtitle { font-size: 0.75rem; }

                /* Charts - 1 kolom */
                .charts-grid { grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1.25rem; }
                .chart-card { padding: 1.25rem; border-radius: 14px; }
                .chart-title { font-size: 1rem; margin-bottom: 1rem; }

                /* Table */
                .table-card { padding: 1.25rem; border-radius: 14px; }
                .table-title { font-size: 1.1rem; margin-bottom: 1rem; }
                .table thead th { padding: 0.65rem 0.5rem; font-size: 0.75rem; }
                .table tbody td { padding: 0.65rem 0.5rem; font-size: 0.82rem; }
            }
            @media (max-width: 420px) {
                .stats-number { font-size: 1.75rem; }
                .quick-action-item { padding: 1rem 0.5rem; }
                .quick-action-icon { font-size: 1.5rem; }
            }
        </style>
    @endpush

    <div class="container-fluid px-0 pb-5">

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stats-card blue">
                <div class="stats-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-number" data-stat="pending">{{ $laporanPending ?? 0 }}</div>
                <div class="stats-label">Laporan Pending</div>
            </div>

            <div class="stats-card green">
                <div class="stats-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-number" data-stat="verified">{{ $laporanVerified ?? 0 }}</div>
                <div class="stats-label">Laporan Verified</div>
            </div>

            <div class="stats-card yellow">
                <div class="stats-icon">
                    <i class="fas fa-map-pin"></i>
                </div>
                <div class="stats-number">{{ $totalPoints ?? 0 }}</div>
                <div class="stats-label">Total Data Points</div>
            </div>

            <div class="stats-card red">
                <div class="stats-icon">
                    <i class="fas fa-database"></i>
                </div>
                <div class="stats-number" data-stat="total">{{ $totalLaporan ?? 0 }}</div>
                <div class="stats-label">Total Laporan</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions-card">
            <h4 class="quick-actions-title">
                <i class="fas fa-bolt text-warning"></i> Quick Actions
            </h4>
            <div class="quick-actions-grid">
                <a href="{{ route('admin.laporan.index') }}" class="quick-action-item">
                    <div class="quick-action-icon text-primary">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div class="quick-action-title">Verifikasi Laporan</div>
                    <div class="quick-action-subtitle">{{ $laporanPending ?? 0 }} laporan pending</div>
                </a>

                <a href="{{ route('admin.peta') }}" class="quick-action-item">
                    <div class="quick-action-icon text-success">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <div class="quick-action-title">Peta Monitoring</div>
                    <div class="quick-action-subtitle">Lihat semua titik banjir</div>
                </a>

                <a href="{{ route('admin.points.index') }}" class="quick-action-item">
                    <div class="quick-action-icon" style="color: #8b5cf6;">
                        <i class="fas fa-table"></i>
                    </div>
                    <div class="quick-action-title">Data Points</div>
                    <div class="quick-action-subtitle">Kelola titik banjir</div>
                </a>

                <a href="{{ route('home') }}" target="_blank" class="quick-action-item">
                    <div class="quick-action-icon text-danger">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="quick-action-title">WebGIS Public</div>
                    <div class="quick-action-subtitle">Tampilan untuk masyarakat</div>
                </a>
            </div>
        </div>





        <!-- Charts Section -->
        <div class="charts-grid">
            <div class="chart-card">
                <h5 class="chart-title">
                    <i class="fas fa-chart-line" style="color:#0891b2;"></i>
                    Tren Laporan Per Bulan ({{ now()->year }})
                </h5>
                <canvas id="chartLaporanBulan"></canvas>
            </div>
            <div class="chart-card">
                <h5 class="chart-title">
                    <i class="fas fa-map-marked-alt" style="color:#8b5cf6;"></i>
                    Top 5 Kecamatan Terdampak
                </h5>
                <canvas id="chartKecamatan"></canvas>
            </div>
        </div>

        <!-- Recent Reports Table -->
        <div class="table-card">
            <h4 class="table-title">
                <i class="fas fa-table" style="color: #8b5cf6;"></i>
                Data Kejadian Banjir Terbaru (Verified)
            </h4>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Foto</th>
                            <th>Waktu</th>
                            <th>Lokasi</th>
                            <th>Kedalaman</th>
                            <th>Pelapor</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporanTerbaru as $index => $item)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>

                                <td>
                                    @if ($item->foto)
                                        @php $fotoUrl = str_starts_with($item->foto, 'http') ? $item->foto : asset('uploads/laporan/' . $item->foto); @endphp
                                        <img src="{{ $fotoUrl }}" alt="Foto" class="rounded"
                                            style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;"
                                            onclick="openImageModal('{{ $fotoUrl }}')"
                                            title="Klik untuk memperbesar" onerror="this.style.display='none'">
                                    @else
                                        <span class="text-muted small">Tidak ada foto</span>
                                    @endif
                                </td>

                                <td>{{ $item->waktu_laporan->format('d/m/Y H:i') }}</td>
                                <td>
                                    <strong>{{ $item->kecamatan }}</strong><br>
                                    <small class="text-muted">{{ $item->desa }}</small>
                                </td>
                                <td>
                                    <span
                                        class="badge
                                    @if ($item->kedalaman_cm >= 70) bg-danger
                                    @elseif($item->kedalaman_cm >= 40) bg-warning
                                    @else bg-info @endif">
                                        {{ $item->kedalaman_cm }} cm
                                    </span>
                                </td>
                                <td>{{ $item->nama_pelapor }}</td>
                                <td>
                                    <a href="{{ route('admin.peta') }}?lat={{ $item->latitude }}&lng={{ $item->longitude }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-map-marker-alt"></i> Lihat di Peta
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    Belum ada laporan terverifikasi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Modal Image Viewer -->
    <div id="imageModal" class="modal-image-viewer" onclick="closeImageModal()">
        <div class="modal-image-content" onclick="event.stopPropagation()">
            <span class="modal-close-btn" onclick="closeImageModal()">×</span>
            <img id="modalImage" src="" alt="Foto Besar">
        </div>
    </div>

    @push('scripts')
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            function getFotoUrl(f) {
                if (!f) return '';
                return f.startsWith('http') ? f : '/uploads/laporan/' + f;
            }
            // Chart Laporan Per Bulan
            const bulanNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const laporanData = @json($laporanPerBulan);
            const dataPerBulan = Array(12).fill(0);

            laporanData.forEach(item => {
                dataPerBulan[item.bulan - 1] = item.total;
            });

            new Chart(document.getElementById('chartLaporanBulan'), {
                type: 'line',
                data: {
                    labels: bulanNames,
                    datasets: [{
                        label: 'Jumlah Laporan',
                        data: dataPerBulan,
                        borderColor: '#0891b2',
                        backgroundColor: 'rgba(8, 145, 178, 0.1)',
                        borderWidth: 4,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 6,
                        pointHoverRadius: 9,
                        pointBackgroundColor: '#0891b2',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
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
                            borderWidth: 2
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: {
                                    size: 13,
                                    weight: '600'
                                },
                                color: '#64748b'
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 13,
                                    weight: '600'
                                },
                                color: '#64748b'
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Chart Per Kecamatan
            const kecamatanData = @json($laporanPerKecamatan);

            new Chart(document.getElementById('chartKecamatan'), {
                type: 'bar',
                data: {
                    labels: kecamatanData.map(k => k.kecamatan),
                    datasets: [{
                        label: 'Jumlah Kejadian',
                        data: kecamatanData.map(k => k.total),
                        backgroundColor: [
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(8, 145, 178, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(139, 92, 246, 0.8)'
                        ],
                        borderColor: [
                            '#ef4444',
                            '#f59e0b',
                            '#0891b2',
                            '#10b981',
                            '#8b5cf6'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    indexAxis: 'y',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
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
                            borderWidth: 2
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: {
                                    size: 13,
                                    weight: '600'
                                },
                                color: '#64748b'
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        y: {
                            ticks: {
                                font: {
                                    size: 13,
                                    weight: '700'
                                },
                                color: '#0c4a6e'
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Modal Functions
            function openImageModal(src) {
                event.stopPropagation();
                event.preventDefault();
                document.getElementById('modalImage').src = src;
                document.getElementById('imageModal').style.display = 'block';
                document.body.style.overflow = 'hidden';
            }

            function closeImageModal() {
                document.getElementById('imageModal').style.display = 'none';
                document.body.style.overflow = '';
            }

            // Close on ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeImageModal();
            });

            console.log('✅ Dashboard loaded successfully');
        </script>
    @endpush
</x-app-layout>
