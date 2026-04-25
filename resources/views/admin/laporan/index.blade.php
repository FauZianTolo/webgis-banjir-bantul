<x-app-layout>
        <x-slot name="header">
            <div class="d-flex justify-content-between align-items-center header-flex-wrap">
                <div>
                    <h2 style="color: #0c4a6e; font-weight: 900; font-size: 2rem; margin: 0;">
                        <i class="fas fa-clipboard-check"></i> Verifikasi Laporan Banjir
                    </h2>
                    <p style="color: #64748b; margin: 0.5rem 0 0 0; font-weight: 600;">
                        Kelola dan verifikasi laporan banjir dari masyarakat
                    </p>
                </div>
                <div style="display:flex;gap:10px;">
                    <a href="{{ route('admin.laporan.exportPdf') }}" target="_blank"
                        style="background:linear-gradient(135deg,#ef4444,#dc2626);color:white;border:none;padding:0.65rem 1.4rem;border-radius:10px;font-weight:700;font-size:0.95rem;display:inline-flex;align-items:center;gap:8px;text-decoration:none;box-shadow:0 4px 15px rgba(239,68,68,0.3);transition:all 0.3s;"
                        title="Export semua laporan ke PDF">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                </div>
            </div>
        </x-slot>

        @push('styles')
            <style>
                /* ==================== STATS CARDS ==================== */
                .stats-mini-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                    gap: 1rem;
                    margin-bottom: 1.5rem;
                }

                .stats-mini {
                    background: white;
                    border-radius: 16px;
                    padding: 1.5rem;
                    box-shadow: 0 6px 24px rgba(0, 0, 0, 0.06);
                    transition: all 0.3s ease;
                    border: 2px solid transparent;
                    position: relative;
                    overflow: hidden;
                }

                .stats-mini::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    height: 5px;
                }

                .stats-mini.pending::before {
                    background: linear-gradient(90deg, #f59e0b, #d97706);
                }

                .stats-mini.verified::before {
                    background: linear-gradient(90deg, #10b981, #059669);
                }

                .stats-mini.rejected::before {
                    background: linear-gradient(90deg, #ef4444, #dc2626);
                }

                .stats-mini:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
                }

                .stats-mini.pending:hover {
                    border-color: #f59e0b;
                }

                .stats-mini.verified:hover {
                    border-color: #10b981;
                }

                .stats-mini.rejected:hover {
                    border-color: #ef4444;
                }

                .stats-mini-icon {
                    width: 60px;
                    height: 60px;
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 1.75rem;
                    color: white;
                    margin-bottom: 1.25rem;
                }

                .stats-mini.pending .stats-mini-icon {
                    background: linear-gradient(135deg, #f59e0b, #d97706);
                }

                .stats-mini.verified .stats-mini-icon {
                    background: linear-gradient(135deg, #10b981, #059669);
                }

                .stats-mini.rejected .stats-mini-icon {
                    background: linear-gradient(135deg, #ef4444, #dc2626);
                }

                .stats-mini h3 {
                    font-size: 2.2rem;
                    font-weight: 900;
                    margin-bottom: 0.5rem;
                }

                .stats-mini.pending h3 {
                    background: linear-gradient(135deg, #f59e0b, #d97706);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                }

                .stats-mini.verified h3 {
                    background: linear-gradient(135deg, #10b981, #059669);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                }

                .stats-mini.rejected h3 {
                    background: linear-gradient(135deg, #ef4444, #dc2626);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                }

                .stats-mini p {
                    color: #64748b;
                    font-weight: 700;
                    font-size: 1rem;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }

                /* ==================== TABLE CARD ==================== */
                .table-card {
                    background: white;
                    border-radius: 20px;
                    padding: 2.5rem;
                    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
                    border: 2px solid rgba(8, 145, 178, 0.1);
                    margin-bottom: 2rem;
                }

                .table-card-title {
                    color: #0c4a6e;
                    font-weight: 900;
                    font-size: 1.5rem;
                    margin-bottom: 2rem;
                    display: flex;
                    align-items: center;
                    gap: 0.75rem;
                }

                /* ==================== TABLE ==================== */
                .table-responsive {
                    overflow-x: auto;
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
                    font-size: 0.85rem;
                    letter-spacing: 0.5px;
                    text-transform: uppercase;
                    white-space: nowrap;
                }

                .table tbody tr {
                    transition: all 0.3s ease;
                    border-bottom: 1px solid #e2e8f0;
                }

                .table tbody tr:hover {
                    background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
                    transform: scale(1.005);
                }

                .table tbody td {
                    padding: 1.25rem 1rem;
                    vertical-align: middle;
                }

                .table tbody td img {
                    transition: all 0.3s ease;
                    border: 2px solid #e2e8f0;
                }

                .table tbody td img:hover {
                    transform: scale(1.2);
                    border-color: #0891b2;
                    box-shadow: 0 8px 25px rgba(8, 145, 178, 0.3);
                }

                /* ==================== BADGES ==================== */
                .badge {
                    padding: 0.5rem 1rem;
                    border-radius: 10px;
                    font-weight: 700;
                    font-size: 0.85rem;
                    display: inline-block;
                }

                .badge-pending {
                    background: linear-gradient(135deg, #fef3c7, #fde68a);
                    color: #92400e;
                    border: 2px solid #f59e0b;
                }

                .badge-verified {
                    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
                    color: #065f46;
                    border: 2px solid #10b981;
                }

                .badge-rejected {
                    background: linear-gradient(135deg, #fee2e2, #fecaca);
                    color: #991b1b;
                    border: 2px solid #ef4444;
                }

                .badge-depth-high {
                    background: #ef4444;
                    color: white;
                }

                .badge-depth-medium {
                    background: #f59e0b;
                    color: white;
                }

                .badge-depth-low {
                    background: #0891b2;
                    color: white;
                }

                /* ==================== BUTTONS ==================== */
                .btn {
                    border-radius: 8px;
                    font-weight: 700;
                    padding: 0.4rem 0.75rem;
                    transition: all 0.3s ease;
                    border: 2px solid transparent;
                    display: inline-flex;
                    align-items: center;
                    gap: 0.35rem;
                    font-size: 0.8rem;
                    white-space: nowrap;
                }

                .btn:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
                }

                .btn-detail {
                    background: linear-gradient(135deg, #0891b2, #06b6d4);
                    color: white;
                    border-color: #0891b2;
                }

                .btn-detail:hover {
                    background: linear-gradient(135deg, #0e7490, #0891b2);
                    color: white;
                }

                .btn-verify {
                    background: linear-gradient(135deg, #10b981, #059669);
                    color: white;
                    border-color: #10b981;
                }

                .btn-verify:hover {
                    background: linear-gradient(135deg, #059669, #047857);
                    color: white;
                }

                .btn-reject {
                    background: linear-gradient(135deg, #ef4444, #dc2626);
                    color: white;
                    border-color: #ef4444;
                }

                .btn-reject:hover {
                    background: linear-gradient(135deg, #dc2626, #b91c1c);
                    color: white;
                }

                .btn-delete {
                    background: transparent;
                    color: #ef4444;
                    border-color: #ef4444;
                }

                .btn-delete:hover {
                    background: #ef4444;
                    color: white;
                }

                .btn-primary {
                    background: linear-gradient(135deg, #0891b2, #06b6d4);
                    color: white;
                    border-color: #0891b2;
                }

                .btn-primary:hover {
                    background: linear-gradient(135deg, #0e7490, #0891b2);
                    color: white;
                }

                /* ==================== DETAIL MODAL ==================== */
                .detail-modal {
                    display: none;
                    position: fixed;
                    z-index: 99998;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.8);
                    backdrop-filter: blur(5px);
                    animation: fadeIn 0.3s ease;
                }

                .detail-modal.show {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .detail-modal-content {
                    background: white;
                    border-radius: 25px;
                    width: 90%;
                    max-width: 900px;
                    max-height: 85vh;
                    overflow-y: auto;
                    box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5);
                    position: relative;
                    animation: slideUp 0.3s ease;
                }

                .detail-modal-header {
                    background: linear-gradient(135deg, #0891b2, #06b6d4);
                    color: white;
                    padding: 2rem;
                    border-radius: 25px 25px 0 0;
                    position: sticky;
                    top: 0;
                    z-index: 10;
                }

                .detail-modal-header h3 {
                    margin: 0;
                    font-size: 1.75rem;
                    font-weight: 900;
                }

                .detail-modal-close {
                    position: absolute;
                    top: 1.5rem;
                    right: 1.5rem;
                    background: rgba(255, 255, 255, 0.2);
                    border: 2px solid rgba(255, 255, 255, 0.5);
                    color: white;
                    width: 45px;
                    height: 45px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    font-size: 1.5rem;
                    font-weight: bold;
                    transition: all 0.3s ease;
                }

                .detail-modal-close:hover {
                    background: rgba(255, 255, 255, 0.3);
                    transform: rotate(90deg);
                }

                .detail-modal-body {
                    padding: 2.5rem;
                }

                .detail-section {
                    margin-bottom: 2rem;
                }

                .detail-section-title {
                    color: #0c4a6e;
                    font-weight: 800;
                    font-size: 1.25rem;
                    margin-bottom: 1.25rem;
                    display: flex;
                    align-items: center;
                    gap: 0.75rem;
                    padding-bottom: 0.75rem;
                    border-bottom: 3px solid #e2e8f0;
                }

                .detail-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                    gap: 1.5rem;
                }

                .detail-item {
                    background: #f8fafc;
                    border-radius: 12px;
                    padding: 1.25rem;
                    border: 2px solid #e2e8f0;
                }

                .detail-item-label {
                    color: #64748b;
                    font-size: 0.85rem;
                    font-weight: 700;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    margin-bottom: 0.5rem;
                }

                .detail-item-value {
                    color: #0f172a;
                    font-size: 1.1rem;
                    font-weight: 700;
                }

                .detail-foto {
                    width: 100%;
                    max-width: 500px;
                    border-radius: 15px;
                    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
                    cursor: pointer;
                    transition: all 0.3s ease;
                }

                .detail-foto:hover {
                    transform: scale(1.05);
                    box-shadow: 0 15px 50px rgba(8, 145, 178, 0.3);
                }

                @keyframes fadeIn {
                    from {
                        opacity: 0;
                    }

                    to {
                        opacity: 1;
                    }
                }

                @keyframes slideUp {
                    from {
                        opacity: 0;
                        transform: translateY(50px);
                    }

                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                /* ==================== ALERT ==================== */
                .alert {
                    border-radius: 15px;
                    padding: 1.25rem 1.5rem;
                    margin-bottom: 2rem;
                    border: none;
                    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
                }

                .alert-success {
                    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
                    color: #065f46;
                    border-left: 5px solid #10b981;
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

                /* ── MOBILE RESPONSIVE ── */
                @media (max-width: 767px) {
                    /* Header */
                    .header-flex-wrap { flex-direction: column; align-items: flex-start !important; gap: 0.75rem; }
                    .header-flex-wrap h2 { font-size: 1.3rem !important; }
                    .header-flex-wrap p { font-size: 0.82rem !important; }
                    .header-flex-wrap a[href*="exportPdf"] {
                        padding: 0.5rem 0.9rem !important;
                        font-size: 0.82rem !important;
                    }

                    /* Stats */
                    .stats-mini-grid { grid-template-columns: repeat(3, 1fr); gap: 0.6rem; }
                    .stats-mini { padding: 1rem 0.75rem; border-radius: 12px; }
                    .stats-mini h3 { font-size: 1.6rem; }
                    .stats-mini p { font-size: 0.75rem; }
                    .stats-mini-icon { width: 40px; height: 40px; font-size: 1.2rem; margin-bottom: 0.75rem; }

                    /* Filter form */
                    .filter-card { padding: 1.25rem; border-radius: 14px; }
                    .filter-form-row { flex-wrap: wrap; gap: 0.5rem !important; }
                    .filter-form-row select,
                    .filter-form-row input { font-size: 0.82rem; padding: 0.5rem 0.75rem; }

                    /* Table */
                    .data-table-card { padding: 1.25rem; border-radius: 14px; }
                    .data-table-card h4 { font-size: 1.05rem; }
                    .table thead th { padding: 0.6rem 0.4rem; font-size: 0.72rem; }
                    .table tbody td { padding: 0.6rem 0.4rem; font-size: 0.8rem; }
                    .badge { padding: 0.3rem 0.6rem; font-size: 0.72rem; }
                }
                @media (max-width: 420px) {
                    .stats-mini-grid { grid-template-columns: repeat(3, 1fr); gap: 0.4rem; }
                    .stats-mini h3 { font-size: 1.4rem; }
                }
            </style>
        @endpush

        <div class="container-fluid px-0 pb-5">

            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <strong>Berhasil!</strong> {{ session('success') }}
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="stats-mini-grid">
                <div class="stats-mini pending">
                    <div class="stats-mini-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>{{ $pending }}</h3>
                    <p>Pending</p>
                </div>

                <div class="stats-mini verified">
                    <div class="stats-mini-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3>{{ $verified }}</h3>
                    <p>Verified</p>
                </div>

                <div class="stats-mini rejected">
                    <div class="stats-mini-icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <h3>{{ $rejected }}</h3>
                    <p>Rejected</p>
                </div>
            </div>

            <!-- Tabel Laporan -->
            <div class="table-card">
                <h3 class="table-card-title">
                    <i class="fas fa-list"></i> Daftar Laporan Banjir
                </h3>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Foto</th>
                                <th>Tanggal</th>
                                <th>Pelapor</th>
                                <th>Lokasi</th>
                                <th>Kedalaman</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($laporanList as $item)
                                <tr>
                                    <td><strong>{{ $item->id }}</strong></td>

                                    <td>
                                        @php
                                            $fotos = array_values(
                                                array_filter([$item->foto, $item->foto2 ?? null, $item->foto3 ?? null]),
                                            );
                                        @endphp
                                        @if (count($fotos) > 0)
                                            <div style="display:flex;gap:4px;flex-wrap:wrap;align-items:center;">
                                                @foreach ($fotos as $fi => $f)
                                                    <div style="position:relative;">
                                                        <img src="{{ str_starts_with($f, 'http') ? $f : asset('uploads/laporan/' . $f) }}" loading="lazy" decoding="async"
                                                            alt="Foto {{ $fi + 1 }}" class="rounded"
                                                            style="width:44px;height:44px;object-fit:cover;cursor:pointer;border:2px solid #e2e8f0;transition:all 0.2s;"
                                                            onclick="openImageModal('{{ asset('uploads/laporan/' . $f) }}')"
                                                            title="Foto {{ $fi + 1 }} — Klik untuk memperbesar">
                                                        @if ($fi === 0 && count($fotos) > 1)
                                                            <span
                                                                style="position:absolute;bottom:1px;right:1px;background:rgba(8,145,178,0.85);color:white;font-size:8px;font-weight:900;padding:0 3px;border-radius:3px;">
                                                                +{{ count($fotos) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div style="white-space: nowrap;">
                                            {{ $item->waktu_laporan->format('d/m/Y') }}<br>
                                            <small class="text-muted">{{ $item->waktu_laporan->format('H:i') }}</small>
                                        </div>
                                    </td>

                                    <td>
                                        <strong>{{ $item->nama_pelapor }}</strong><br>
                                        <small class="text-muted">{{ $item->no_telp }}</small>
                                    </td>

                                    <td>
                                        <strong>{{ $item->kecamatan }}</strong><br>
                                        <small class="text-muted">{{ $item->desa }}</small>
                                    </td>

                                    <td>
                                        <span
                                            class="badge
                                    @if ($item->kedalaman_cm >= 70) badge-depth-high
                                    @elseif($item->kedalaman_cm >= 40) badge-depth-medium
                                    @else badge-depth-low @endif">
                                            {{ $item->kedalaman_cm ?? 0 }} cm
                                        </span>
                                    </td>

                                    <td style="max-width: 200px;">
                                        <small>{{ Str::limit($item->deskripsi, 50) }}</small>
                                    </td>

                                    <td>
                                        @if ($item->status === 'pending')
                                            <span class="badge badge-pending">Pending</span>
                                        @elseif($item->status === 'verified')
                                            <span class="badge badge-verified">Verified</span>
                                        @else
                                            <span class="badge badge-rejected">Rejected</span>
                                        @endif
                                    </td>

                                    <td style="white-space: nowrap;">
                                        <!-- DETAIL BUTTON (SELALU ADA) -->
                                        <button onclick="showDetail({{ $item->id }})"
                                            class="btn btn-sm btn-detail">
                                            <i class="fas fa-info-circle"></i> Detail
                                        </button>

                                        @if ($item->status === 'pending')
                                            <form action="{{ route('admin.laporan.verify', $item->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Verifikasi laporan ini?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-verify">
                                                    <i class="fas fa-check"></i> Verifikasi
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.laporan.reject', $item->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Tolak laporan ini?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-reject">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                            </form>
                                        @elseif($item->status === 'rejected')
                                            <form action="{{ route('admin.laporan.destroyRejected', $item->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Hapus laporan ini secara permanen?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-delete">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                        <span class="text-muted">Belum ada laporan</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Link -->
            <div>
                <a href="{{ route('admin.peta') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-map"></i> Lihat Semua Laporan di Peta
                </a>
            </div>

        </div>

        <!-- Modal Image Viewer -->
        <div id="imageModal" class="modal-image-viewer" onclick="closeImageModal()">
            <div class="modal-image-content" onclick="event.stopPropagation()">
                <span class="modal-close-btn" onclick="closeImageModal()">×</span>
                <img id="modalImage" src="" alt="Foto Besar">
            </div>
        </div>

        <!-- Modal Detail Laporan -->
        <div id="detailModal" class="detail-modal" onclick="closeDetailModal()">
            <div class="detail-modal-content" onclick="event.stopPropagation()">
                <div class="detail-modal-header">
                    <h3><i class="fas fa-file-alt"></i> Detail Laporan Banjir</h3>
                    <button class="detail-modal-close" onclick="closeDetailModal()">×</button>
                </div>

                <div class="detail-modal-body">
                    <!-- Informasi Laporan -->
                    <div class="detail-section">
                        <h4 class="detail-section-title">
                            <i class="fas fa-info-circle"></i> Informasi Laporan
                        </h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <div class="detail-item-label">ID Laporan</div>
                                <div class="detail-item-value" id="detail-id">-</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-item-label">Tanggal & Waktu</div>
                                <div class="detail-item-value" id="detail-waktu">-</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-item-label">Status</div>
                                <div class="detail-item-value" id="detail-status">-</div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Pelapor -->
                    <div class="detail-section">
                        <h4 class="detail-section-title">
                            <i class="fas fa-user"></i> Data Pelapor
                        </h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <div class="detail-item-label">Nama Pelapor</div>
                                <div class="detail-item-value" id="detail-pelapor">-</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-item-label">No. Telepon</div>
                                <div class="detail-item-value" id="detail-telp">-</div>
                            </div>
                        </div>
                    </div>

                    <!-- Lokasi Banjir -->
                    <div class="detail-section">
                        <h4 class="detail-section-title">
                            <i class="fas fa-map-marker-alt"></i> Lokasi Banjir
                        </h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <div class="detail-item-label">Kecamatan</div>
                                <div class="detail-item-value" id="detail-kecamatan">-</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-item-label">Desa</div>
                                <div class="detail-item-value" id="detail-desa">-</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-item-label">Koordinat</div>
                                <div class="detail-item-value" id="detail-koordinat">-</div>
                            </div>
                        </div>
                    </div>

                    <!-- Kondisi Banjir -->
                    <div class="detail-section">
                        <h4 class="detail-section-title">
                            <i class="fas fa-water"></i> Kondisi Banjir
                        </h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <div class="detail-item-label">Kedalaman Air</div>
                                <div class="detail-item-value" id="detail-kedalaman">-</div>
                            </div>
                        </div>
                        <div class="detail-item mt-3">
                            <div class="detail-item-label">Deskripsi</div>
                            <div class="detail-item-value" id="detail-deskripsi"
                                style="font-weight: 500; line-height: 1.6;">-</div>
                        </div>
                    </div>

                    <!-- Kebutuhan / Bantuan -->
                    <div class="detail-section" id="section-kebutuhan">
                        <h4 class="detail-section-title">
                            <i class="fas fa-hands-helping" style="color:#f59e0b;"></i> Kebutuhan / Bantuan yang Diperlukan
                        </h4>
                        <div class="detail-item">
                            <div class="detail-item-value" id="detail-kebutuhan"
                                style="font-weight:500;line-height:1.6;background:#fffbeb;border:1.5px solid #fde68a;border-radius:10px;padding:10px 14px;color:#92400e;">-</div>
                        </div>
                    </div>

                    <!-- Foto Laporan -->
                    <div class="detail-section">
                        <h4 class="detail-section-title">
                            <i class="fas fa-camera"></i> Dokumentasi Foto
                        </h4>
                        <div id="detail-foto-container"
                            style="display:flex;gap:12px;flex-wrap:wrap;justify-content:flex-start;">
                            <!-- Diisi oleh JS -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                // Helper: handle Cloudinary URL atau local file
                function getFotoUrl(f) {
                    if (!f) return '';
                    return f.startsWith('http') ? f : '/uploads/laporan/' + f;
                }

                // Data laporan untuk detail modal
                const laporanData = @json($laporanList->keyBy('id'));

                // Image Modal Functions
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

                // Detail Modal Functions
                function showDetail(laporanId) {
                    const laporan = laporanData[laporanId];

                    if (!laporan) {
                        alert('Data laporan tidak ditemukan');
                        return;
                    }

                    // Populate modal dengan data
                    document.getElementById('detail-id').textContent = laporan.id;
                    document.getElementById('detail-waktu').textContent = formatDateTime(laporan.waktu_laporan);

                    // Status dengan badge
                    const statusBadge = getStatusBadge(laporan.status);
                    document.getElementById('detail-status').innerHTML = statusBadge;

                    document.getElementById('detail-pelapor').textContent = laporan.nama_pelapor || '-';
                    document.getElementById('detail-telp').textContent = laporan.no_telp || '-';
                    document.getElementById('detail-kecamatan').textContent = laporan.kecamatan || '-';
                    document.getElementById('detail-desa').textContent = laporan.desa || '-';
                    document.getElementById('detail-koordinat').textContent = `${laporan.latitude}, ${laporan.longitude}`;
                    document.getElementById('detail-kedalaman').textContent = `${laporan.kedalaman_cm || 0} cm`;
                    document.getElementById('detail-deskripsi').textContent = laporan.deskripsi || 'Tidak ada deskripsi';

                    // Kebutuhan / Bantuan
                    const kebutuhanEl = document.getElementById('detail-kebutuhan');
                    const kebutuhanSection = document.getElementById('section-kebutuhan');
                    if (laporan.kebutuhan_bantuan && laporan.kebutuhan_bantuan.trim()) {
                        kebutuhanEl.textContent = laporan.kebutuhan_bantuan;
                        kebutuhanSection.style.display = 'block';
                    } else {
                        kebutuhanSection.style.display = 'none';
                    }

                    // Foto - render gallery hingga 3 foto
                    const fotoContainer = document.getElementById('detail-foto-container');
                    const fotoFields = [laporan.foto, laporan.foto2, laporan.foto3].filter(Boolean);

                    if (fotoFields.length > 0) {
                        fotoContainer.innerHTML = fotoFields.map((f, i) => `
                    <div style="position:relative;">
                        <img src="${getFotoUrl(f)}"
                             alt="Foto ${i+1}"
                             class="detail-foto"
                             onclick="openImageModal('${getFotoUrl(f)}')"
                             style="width:160px;height:160px;object-fit:cover;border-radius:14px;cursor:pointer;border:3px solid #e2e8f0;box-shadow:0 6px 20px rgba(0,0,0,0.1);transition:all 0.3s;">
                        <div style="text-align:center;margin-top:6px;font-size:11px;font-weight:700;color:#64748b;">
                            Foto ${i+1}
                        </div>
                    </div>
                `).join('');
                    } else {
                        fotoContainer.innerHTML =
                            '<p class="text-muted" style="width:100%;text-align:center;padding:2rem 0;"><i class="fas fa-image fa-3x mb-3 d-block"></i>Tidak ada foto</p>';
                    }

                    // Show modal
                    const modal = document.getElementById('detailModal');
                    modal.classList.add('show');
                    document.body.style.overflow = 'hidden';
                }

                function closeDetailModal() {
                    const modal = document.getElementById('detailModal');
                    modal.classList.remove('show');
                    document.body.style.overflow = '';
                }

                // Helper Functions
                function formatDateTime(dateString) {
                    const date = new Date(dateString);
                    const options = {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    };
                    return date.toLocaleDateString('id-ID', options);
                }

                function getStatusBadge(status) {
                    const badges = {
                        'pending': '<span class="badge badge-pending"><i class="fas fa-clock"></i> Pending</span>',
                        'verified': '<span class="badge badge-verified"><i class="fas fa-check-circle"></i> Verified</span>',
                        'rejected': '<span class="badge badge-rejected"><i class="fas fa-times-circle"></i> Rejected</span>'
                    };
                    return badges[status] || '<span class="badge">Unknown</span>';
                }

                // Close modals on ESC key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closeImageModal();
                        closeDetailModal();
                    }
                });

                console.log('✅ Verifikasi Laporan page loaded');
                console.log('📊 Total laporan:', Object.keys(laporanData).length);
            </script>
        @endpush
    </x-app-layout>
