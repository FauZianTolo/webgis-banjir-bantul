<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 style="color: #0c4a6e; font-weight: 900; font-size: 2rem; margin: 0;">
                <i class="fas fa-map-pin"></i> Data Points - Kelola Titik Laporan
            </h2>
            <p style="color: #64748b; margin: 0.5rem 0 0 0; font-weight: 600;">
                Kelola data titik laporan banjir yang sudah terverifikasi
            </p>
        </div>
    </x-slot>

    @push('styles')
    <style>
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
            justify-content: between;
            gap: 0.75rem;
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
            border-radius: 10px;
            font-weight: 700;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            text-decoration: none;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
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

        .btn-edit {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border-color: #10b981;
        }

        .btn-edit:hover {
            background: linear-gradient(135deg, #059669, #047857);
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

        .badge-verified {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
            border: 2px solid #10b981;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.85rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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

        /* Modal Image */
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
    </style>
    @endpush

    <div class="container-fluid px-0 pb-5">

        @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <strong>Berhasil!</strong> {{ session('success') }}
        </div>
        @endif

        <div class="table-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="table-card-title mb-0">
                    <i class="fas fa-table"></i> Daftar Laporan Terverifikasi
                </h3>
                <span style="color: #64748b; font-weight: 700;">Total: {{ $laporan->total() }} laporan</span>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Foto</th>
                            <th>Waktu</th>
                            <th>Lokasi</th>
                            <th>Koordinat</th>
                            <th>Kedalaman</th>
                            <th>Pelapor</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporan as $item)
                        <tr>
                            <td><strong>{{ $item->id }}</strong></td>

                            <td>
                                @php
                                    $fotos = array_values(array_filter([$item->foto, $item->foto2 ?? null, $item->foto3 ?? null]));
                                @endphp
                                @if(count($fotos) > 0)
                                    <div style="display:flex;gap:4px;flex-wrap:wrap;align-items:center;">
                                        @foreach($fotos as $fi => $f)
                                        <div style="position:relative;">
                                            <img src="{{ asset('uploads/laporan/' . $f) }}"
                                                 alt="Foto {{ $fi+1 }}"
                                                 class="rounded"
                                                 style="width:44px;height:44px;object-fit:cover;cursor:pointer;border:2px solid #e2e8f0;transition:all 0.2s;"
                                                 onclick="openImageModal('{{ asset('uploads/laporan/' . $f) }}')"
                                                 title="Foto {{ $fi+1 }} — Klik untuk memperbesar">
                                            @if($fi === 0 && count($fotos) > 1)
                                            <span style="position:absolute;bottom:1px;right:1px;background:rgba(8,145,178,0.85);color:white;font-size:8px;font-weight:900;padding:0 3px;border-radius:3px;">
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
                                <strong>{{ $item->kecamatan }}</strong><br>
                                <small class="text-muted">{{ $item->desa }}</small>
                            </td>

                            <td style="font-size: 0.8rem;">
                                {{ number_format($item->latitude, 5) }},<br>
                                {{ number_format($item->longitude, 5) }}
                            </td>

                            <td>
                                <span class="badge
                                    @if($item->kedalaman_cm >= 70) badge-depth-high
                                    @elseif($item->kedalaman_cm >= 40) badge-depth-medium
                                    @else badge-depth-low @endif">
                                    {{ $item->kedalaman_cm ?? 0 }} cm
                                </span>
                            </td>

                            <td>
                                <strong>{{ $item->nama_pelapor }}</strong><br>
                                <small class="text-muted">{{ $item->no_telp }}</small>
                            </td>

                            <td style="white-space: nowrap;">
                                <button onclick="showDetail({{ $item->id }})" class="btn btn-sm btn-detail mb-1">
                                    <i class="fas fa-info-circle"></i> Detail
                                </button>

                                <a href="{{ route('admin.points.edit', $item->id) }}" class="btn btn-sm btn-edit mb-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                <form action="{{ route('admin.points.destroy', $item->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Hapus data ini secara permanen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-delete">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                <span class="text-muted">Belum ada data laporan terverifikasi</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $laporan->links() }}
            </div>
        </div>

    </div>

    <!-- Detail Modal -->
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
                        <div class="detail-item-value" id="detail-deskripsi" style="font-weight: 500; line-height: 1.6;">-</div>
                    </div>
                </div>

                <!-- Foto Laporan -->
                <div class="detail-section">
                    <h4 class="detail-section-title">
                        <i class="fas fa-camera"></i> Dokumentasi Foto
                    </h4>
                    <div id="detail-foto-container" style="display:flex;gap:12px;flex-wrap:wrap;justify-content:flex-start;">
                        <!-- Diisi oleh JS -->
                    </div>
                </div>
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
    <script>
        // Data laporan
        const laporanData = @json($laporan->keyBy('id'));

        // Modal Image Functions
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

            document.getElementById('detail-id').textContent = laporan.id;
            document.getElementById('detail-waktu').textContent = formatDateTime(laporan.waktu_laporan);
            document.getElementById('detail-status').innerHTML = '<span class="badge-verified"><i class="fas fa-check-circle"></i> Verified</span>';
            document.getElementById('detail-pelapor').textContent = laporan.nama_pelapor || '-';
            document.getElementById('detail-telp').textContent = laporan.no_telp || '-';
            document.getElementById('detail-kecamatan').textContent = laporan.kecamatan || '-';
            document.getElementById('detail-desa').textContent = laporan.desa || '-';
            document.getElementById('detail-koordinat').textContent = `${laporan.latitude}, ${laporan.longitude}`;
            document.getElementById('detail-kedalaman').textContent = `${laporan.kedalaman_cm || 0} cm`;
            document.getElementById('detail-deskripsi').textContent = laporan.deskripsi || 'Tidak ada deskripsi';

            // Foto - render gallery hingga 3 foto
            const fotoContainer = document.getElementById('detail-foto-container');
            const fotoFields = [laporan.foto, laporan.foto2, laporan.foto3].filter(Boolean);

            if (fotoFields.length > 0) {
                fotoContainer.innerHTML = fotoFields.map((f, i) => `
                    <div style="position:relative;">
                        <img src="/uploads/laporan/${f}"
                             alt="Foto ${i+1}"
                             class="detail-foto"
                             onclick="openImageModal('/uploads/laporan/${f}')"
                             style="width:160px;height:160px;object-fit:cover;border-radius:14px;cursor:pointer;border:3px solid #e2e8f0;box-shadow:0 6px 20px rgba(0,0,0,0.1);transition:all 0.3s;">
                        <div style="text-align:center;margin-top:6px;font-size:11px;font-weight:700;color:#64748b;">
                            Foto ${i+1}
                        </div>
                    </div>
                `).join('');
            } else {
                fotoContainer.innerHTML = '<p class="text-muted" style="width:100%;text-align:center;padding:2rem 0;"><i class="fas fa-image fa-3x mb-3 d-block"></i>Tidak ada foto</p>';
            }

            document.getElementById('detailModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.remove('show');
            document.body.style.overflow = '';
        }

        function formatDateTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                year: 'numeric', month: 'long', day: 'numeric',
                hour: '2-digit', minute: '2-digit'
            });
        }

        // ESC key to close
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
                closeDetailModal();
            }
        });

        console.log('✅ Admin Points loaded with detail modal');
        console.log('📊 Total laporan:', Object.keys(laporanData).length);
    </script>
    @endpush
</x-app-layout>
