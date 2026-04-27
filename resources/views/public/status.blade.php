{{--
    status.blade.php — Halaman Cek Status Laporan (Publik)
    Tampilan diseragamkan dengan halaman Lapor Kejadian Banjir.
--}}
@extends('layouts.public')

@section('styles')
    <style>
        /* ══════════════════════════════════════════════════════
           HERO — disamakan dengan halaman Lapor Kejadian Banjir
        ══════════════════════════════════════════════════════ */
        .laporan-hero {
            background: linear-gradient(135deg, #0c4a6e 0%, #0891b2 50%, #06b6d4 100%);
            color: white;
            padding: 4rem 0;
            margin-bottom: 0;
            position: relative;
            overflow: hidden;
        }

        .laporan-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 20% 50%, rgba(255, 255, 255, 0.07) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(6, 182, 212, 0.15) 0%, transparent 50%);
        }

        .laporan-hero::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 50px;
            background: #f0f9ff;
            clip-path: ellipse(55% 100% at 50% 100%);
        }

        .laporan-hero h1 {
            position: relative;
            z-index: 2;
            font-weight: 900;
            font-size: 2.8rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .laporan-hero p {
            position: relative;
            z-index: 2;
            font-size: 1.2rem;
            opacity: 0.95;
        }

        /* ══════════════════════════════════════════════════════
           CONTENT
        ══════════════════════════════════════════════════════ */
        .status-page-wrap {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            padding: 2.5rem 0 4rem;
        }

        .status-container {
            max-width: 960px;
            margin: 0 auto;
        }

        .status-form-container {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.08);
            border: 2px solid rgba(8, 145, 178, 0.1);
            margin-bottom: 2rem;
        }

        .status-heading-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 1.7rem;
        }

        .status-title {
            color: #0c4a6e;
            font-weight: 800;
            font-size: 1.8rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .status-subtitle {
            color: #64748b;
            font-size: 0.95rem;
            line-height: 1.7;
            margin-bottom: 1.5rem;
        }

        .btn-back-laporan {
            background: #ffffff;
            color: #0c4a6e;
            border: 2px solid rgba(8, 145, 178, 0.25);
            border-radius: 10px;
            padding: 9px 18px;
            font-weight: 800;
            font-size: 13px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(8, 145, 178, 0.10);
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .btn-back-laporan:hover {
            background: #e0f2fe;
            color: #0c4a6e;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(8, 145, 178, 0.18);
        }

        .form-label {
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.85rem 1.25rem;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #0891b2;
            box-shadow: 0 0 0 4px rgba(8, 145, 178, 0.1);
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .btn-cek-status {
            width: 100%;
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.95rem 1.5rem;
            font-weight: 800;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(8, 145, 178, 0.3);
            transition: all 0.2s ease;
        }

        .btn-cek-status:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(8, 145, 178, 0.4);
        }

        .tracking-alert {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #10b981;
            border-radius: 14px;
            padding: 14px 18px;
            font-weight: 700;
            box-shadow: 0 6px 24px rgba(16, 185, 129, 0.12);
            margin-bottom: 1.5rem;
        }

        .result-note {
            text-align: center;
            color: #64748b;
            font-size: 14px;
            margin: 0 0 1rem;
        }

        .refresh-note {
            text-align: center;
            color: #0c4a6e;
            background: #e0f2fe;
            border: 1px solid #7dd3fc;
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        /* ══════════════════════════════════════════════════════
           RESULT CARD
        ══════════════════════════════════════════════════════ */
        .laporan-card {
            background: white;
            border-radius: 18px;
            box-shadow: 0 10px 35px rgba(15, 23, 42, 0.08);
            margin-bottom: 1.5rem;
            overflow: hidden;
            border: 2px solid rgba(8, 145, 178, 0.08);
            border-left: 6px solid #f59e0b;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .laporan-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 45px rgba(15, 23, 42, 0.11);
        }

        .laporan-card.pending {
            border-left-color: #f59e0b;
        }

        .laporan-card.verified {
            border-left-color: #10b981;
        }

        .laporan-card.rejected {
            border-left-color: #ef4444;
        }

        .card-header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.3rem;
            flex-wrap: wrap;
            gap: 8px;
        }

        .card-header-bar.pending {
            background: #fffbeb;
        }

        .card-header-bar.verified {
            background: #f0fdf4;
        }

        .card-header-bar.rejected {
            background: #fef2f2;
        }

        .laporan-id {
            font-weight: 900;
            color: #0c4a6e;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 14px;
            border-radius: 999px;
            font-weight: 800;
            font-size: 13px;
        }

        .status-badge.pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.verified {
            background: #d1fae5;
            color: #065f46;
        }

        .status-badge.rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .card-body-custom {
            padding: 1.2rem 1.3rem 1.4rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .info-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.85rem 1rem;
        }

        .info-label {
            font-size: 11px;
            color: #64748b;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 14px;
            color: #0f172a;
            font-weight: 700;
            line-height: 1.5;
        }

        .timeline {
            margin-top: 1.1rem;
            padding-top: 1.1rem;
            border-top: 1px solid #e2e8f0;
        }

        .timeline-title {
            font-size: 12px;
            font-weight: 900;
            color: #0c4a6e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .tl-step {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 12px;
        }

        .tl-step:last-child {
            margin-bottom: 0;
        }

        .tl-dot {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            flex-shrink: 0;
            margin-top: 1px;
            font-weight: 900;
        }

        .tl-dot.done {
            background: #10b981;
            color: white;
        }

        .tl-dot.wait {
            background: #fef3c7;
            color: #92400e;
        }

        .tl-dot.fail {
            background: #ef4444;
            color: white;
        }

        .tl-text {
            font-size: 13px;
            color: #475569;
            line-height: 1.5;
        }

        .tl-text strong {
            color: #0f172a;
            font-size: 14px;
        }

        .tl-text small {
            color: #64748b;
            display: block;
            margin-top: 2px;
        }

        .not-found {
            text-align: center;
            padding: 2.5rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.08);
            border: 2px solid rgba(8, 145, 178, 0.1);
        }

        .not-found .icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #e0f2fe;
            color: #0891b2;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .not-found h3 {
            color: #0c4a6e;
            font-weight: 900;
            margin-bottom: 0.5rem;
        }

        .not-found p {
            color: #64748b;
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 1.4rem;
        }

        @media (max-width: 767px) {
            .laporan-hero h1 {
                font-size: 2rem;
            }

            .status-form-container {
                padding: 1.5rem;
            }

            .status-title {
                font-size: 1.45rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div class="laporan-hero">
        <div class="container">
            <h1 class="text-center mb-3"><i class="fas fa-search-location"></i> Cek Status Laporan</h1>
            <p class="text-center lead">Pantau perkembangan laporan banjir menggunakan nomor HP pelapor atau ID laporan</p>
        </div>
    </div>

    <div class="status-page-wrap">
        <div class="container status-container">
            @if (session('success'))
                <div class="tracking-alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <div class="status-form-container">
                <div class="status-heading-row">
                    <h4 class="status-title"><i class="fas fa-clipboard-check"></i> Pencarian Status</h4>
                    <a href="{{ route('laporan') }}" class="btn-back-laporan">
                        <i class="fas fa-arrow-left"></i> Kembali ke Laporan
                    </a>
                </div>

                <p class="status-subtitle">
                    Masukkan <strong>nomor HP pelapor</strong> atau <strong>ID laporan</strong>. Input angka pendek seperti <strong>11</strong> akan dibaca sebagai ID laporan, sedangkan nomor HP dicocokkan secara exact setelah normalisasi format.
                </p>

                <form action="{{ route('laporan.status') }}" method="GET">
                    <div class="mb-3">
                        <label for="q" class="form-label">Nomor HP Pelapor atau ID Laporan</label>
                        <input
                            type="text"
                            name="q"
                            id="q"
                            class="form-control"
                            placeholder="Contoh: 0812xxxxxxxx atau 11"
                            value="{{ $query ?? '' }}"
                            inputmode="tel"
                            autocomplete="tel"
                            required
                        >
                    </div>
                    <button type="submit" class="btn-cek-status">
                        <i class="fas fa-search"></i> Cek Status Laporan
                    </button>
                </form>
            </div>

            @if (!empty($inputError))
                <div class="not-found" style="padding: 1.5rem; margin-bottom: 1.5rem;">
                    <div class="icon" style="width: 52px; height: 52px; font-size: 1.4rem;"><i class="fas fa-triangle-exclamation"></i></div>
                    <h3>Input Tidak Valid</h3>
                    <p>{{ $inputError }}</p>
                </div>
            @endif

            @if (isset($results) && empty($inputError))
                @if ($results->count() > 0)
                    <p class="result-note">
                        @if (($searchMode ?? null) === 'id')
                            Ditemukan <strong>{{ $results->count() }}</strong> laporan untuk ID laporan "<strong>{{ $query }}</strong>".
                        @else
                            Ditemukan <strong>{{ $results->count() }}</strong> laporan untuk nomor HP "<strong>{{ $query }}</strong>".
                        @endif
                    </p>

                    @if ($results->contains(fn($item) => $item->status === 'pending'))
                        <div class="refresh-note">
                            <i class="fas fa-sync-alt"></i>
                            Status akan dicek ulang otomatis setiap 30 detik selama masih menunggu verifikasi.
                        </div>
                    @endif

                    @foreach ($results as $item)
                        @php
                            $statusIcon = $item->status === 'verified' ? 'fa-check-circle'
                                : ($item->status === 'rejected' ? 'fa-times-circle' : 'fa-clock');
                            $statusText = $item->status === 'verified' ? 'Terverifikasi'
                                : ($item->status === 'rejected' ? 'Ditolak' : 'Menunggu Verifikasi');
                        @endphp

                        <div class="laporan-card {{ $item->status }}">
                            <div class="card-header-bar {{ $item->status }}">
                                <div class="laporan-id">
                                    <i class="fas fa-hashtag"></i> Laporan {{ $item->id }}
                                </div>
                                <div class="status-badge {{ $item->status }}">
                                    <i class="fas {{ $statusIcon }}"></i> {{ $statusText }}
                                </div>
                            </div>

                            <div class="card-body-custom">
                                <div class="info-grid">
                                    <div class="info-item">
                                        <div class="info-label">Nama Pelapor</div>
                                        <div class="info-value">{{ $item->nama_pelapor }}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Tanggal Laporan</div>
                                        <div class="info-value">{{ $item->waktu_laporan->format('d M Y, H:i') }} WIB</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Lokasi</div>
                                        <div class="info-value">{{ $item->kecamatan }}{{ $item->desa ? ', ' . $item->desa : '' }}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Kedalaman</div>
                                        <div class="info-value">{{ $item->kedalaman_cm ?? '-' }} cm</div>
                                    </div>
                                </div>

                                <div class="timeline">
                                    <div class="timeline-title">
                                        <i class="fas fa-list-check"></i> Riwayat Status
                                    </div>

                                    <div class="tl-step">
                                        <div class="tl-dot done"><i class="fas fa-check"></i></div>
                                        <div class="tl-text">
                                            <strong>Laporan Dikirim</strong>
                                            <small>{{ $item->waktu_laporan->format('d M Y, H:i') }} WIB</small>
                                        </div>
                                    </div>

                                    <div class="tl-step">
                                        @if ($item->status === 'pending')
                                            <div class="tl-dot wait"><i class="fas fa-hourglass-half"></i></div>
                                            <div class="tl-text">
                                                <strong style="color:#d97706;">Sedang Ditinjau Admin</strong>
                                                <small>Laporan kamu sedang dalam proses verifikasi. Silakan cek kembali secara berkala.</small>
                                            </div>
                                        @elseif ($item->status === 'verified')
                                            <div class="tl-dot done"><i class="fas fa-check"></i></div>
                                            <div class="tl-text">
                                                <strong style="color:#059669;">Laporan Terverifikasi</strong>
                                                <small>Admin BPBD Bantul telah memverifikasi laporan kamu.</small>
                                            </div>
                                        @else
                                            <div class="tl-dot fail"><i class="fas fa-xmark"></i></div>
                                            <div class="tl-text">
                                                <strong style="color:#dc2626;">Laporan Ditolak</strong>
                                                <small>Laporan tidak memenuhi kriteria, data kurang lengkap, atau belum dapat dikonfirmasi.</small>
                                            </div>
                                        @endif
                                    </div>

                                    @if ($item->status === 'verified')
                                        <div class="tl-step">
                                            <div class="tl-dot done"><i class="fas fa-map-marker-alt"></i></div>
                                            <div class="tl-text">
                                                <strong style="color:#059669;">Data Ditambahkan ke Peta</strong>
                                                <small>Titik laporan banjir dapat ditampilkan pada halaman Peta WebGIS.</small>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="not-found">
                        <div class="icon"><i class="fas fa-search"></i></div>
                        <h3>Laporan Tidak Ditemukan</h3>
                        <p>
                            @if (($searchMode ?? null) === 'id')
                                Tidak ada laporan dengan ID "<strong>{{ $query }}</strong>".<br>
                                Periksa kembali ID laporan yang dimasukkan.
                            @else
                                Tidak ada laporan dengan nomor HP "<strong>{{ $query }}</strong>".<br>
                                Periksa kembali nomor HP yang digunakan saat mengirim laporan.
                            @endif
                        </p>
                        <a href="{{ route('laporan') }}" class="btn-back-laporan">
                            <i class="fas fa-arrow-left"></i> Kembali ke Laporan
                        </a>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection

@section('script')
    @if (isset($results) && $results->count() > 0 && $results->contains(fn($item) => $item->status === 'pending'))
        <script>
            setTimeout(() => {
                window.location.reload();
            }, 30000);
        </script>
    @endif
@endsection
