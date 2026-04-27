{{--
    ✅ status.blade.php — Halaman Cek Status Laporan (Publik)
    Simpan di: resources/views/public/status.blade.php
    Tidak perlu WA API atau email — murni web, cukup nomor HP atau ID laporan
--}}
@extends('layouts.public')

@section('title', 'Cek Status Laporan - WebGIS Banjir Bantul')

@section('content')
<style>
    .status-hero {
        background: linear-gradient(135deg, #0c4a6e 0%, #0891b2 60%, #06b6d4 100%);
        padding: 3rem 0 2rem;
        color: white;
        text-align: center;
    }
    .status-hero h1 { font-size: 2rem; font-weight: 900; margin-bottom: .5rem; }
    .status-hero p  { opacity: .85; font-size: 1rem; }

    .search-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 40px rgba(0,0,0,.12);
        padding: 2rem;
        max-width: 540px;
        margin: -2rem auto 2rem;
        position: relative;
    }
    .search-label  { font-weight: 700; color: #0c4a6e; font-size: 14px; margin-bottom: 6px; }
    .search-input  {
        width: 100%; border: 2px solid #e2e8f0; border-radius: 12px;
        padding: 12px 16px; font-size: 15px; outline: none;
        transition: border-color .2s;
    }
    .search-input:focus { border-color: #0891b2; }
    .btn-cek {
        width: 100%; background: linear-gradient(135deg, #0891b2, #06b6d4);
        color: white; border: none; border-radius: 12px; padding: 13px;
        font-size: 15px; font-weight: 700; cursor: pointer; margin-top: 14px;
        transition: opacity .2s;
    }
    .btn-cek:hover { opacity: .9; }

    /* ── Hasil ── */
    .result-wrap { max-width: 700px; margin: 0 auto 3rem; }

    .laporan-card {
        background: white; border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,.08);
        margin-bottom: 1.5rem; overflow: hidden;
        border-left: 5px solid;
        transition: transform .2s;
    }
    .laporan-card:hover { transform: translateY(-2px); }
    .laporan-card.pending  { border-color: #f59e0b; }
    .laporan-card.verified { border-color: #10b981; }
    .laporan-card.rejected { border-color: #ef4444; }

    .card-header-bar {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 20px; flex-wrap: wrap; gap: 8px;
    }
    .card-header-bar.pending  { background: #fffbeb; }
    .card-header-bar.verified { background: #f0fdf4; }
    .card-header-bar.rejected { background: #fef2f2; }

    .laporan-id { font-weight: 900; color: #0c4a6e; font-size: 15px; }

    .status-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 14px; border-radius: 20px; font-weight: 700; font-size: 13px;
    }
    .status-badge.pending  { background: #fef3c7; color: #92400e; }
    .status-badge.verified { background: #d1fae5; color: #065f46; }
    .status-badge.rejected { background: #fee2e2; color: #991b1b; }

    .card-body { padding: 16px 20px; }
    .info-grid  { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .info-item  {}
    .info-label { font-size: 11px; color: #94a3b8; font-weight: 600;
                  text-transform: uppercase; letter-spacing: .5px; margin-bottom: 2px; }
    .info-value { font-size: 13px; color: #1a1a1a; font-weight: 600; }

    .timeline {
        margin-top: 14px; padding-top: 14px;
        border-top: 1px solid #f1f5f9;
    }
    .timeline-title { font-size: 12px; font-weight: 700; color: #64748b;
                      text-transform: uppercase; letter-spacing: .5px; margin-bottom: 10px; }
    .tl-step {
        display: flex; align-items: flex-start; gap: 10px; margin-bottom: 10px;
    }
    .tl-dot {
        width: 20px; height: 20px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 10px; flex-shrink: 0; margin-top: 1px;
    }
    .tl-dot.done  { background: #10b981; color: white; }
    .tl-dot.wait  { background: #e2e8f0; color: #94a3b8; }
    .tl-dot.fail  { background: #ef4444; color: white; }
    .tl-text      { font-size: 12px; }
    .tl-text strong { color: #0f172a; font-size: 13px; }
    .tl-text small  { color: #94a3b8; display: block; }

    .not-found {
        text-align: center; padding: 2rem;
        background: white; border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,.08);
    }
    .not-found .icon { font-size: 3rem; margin-bottom: 1rem; }
    .not-found h3    { color: #0c4a6e; font-weight: 800; margin-bottom: .5rem; }
    .not-found p     { color: #64748b; font-size: 14px; }
</style>

{{-- HERO --}}
<div class="status-hero">
    <h1>🔍 Cek Status Laporan</h1>
    <p>Masukkan nomor HP atau ID laporan untuk melihat perkembangan laporan banjir kamu</p>
</div>

{{-- SEARCH CARD --}}
<div class="container">
    <div class="search-card">
        <form action="{{ route('laporan.status') }}" method="GET">
            <div class="search-label">Nomor HP atau ID Laporan</div>
            <input
                type="text"
                name="q"
                class="search-input"
                placeholder="Contoh: 0812xxxxxxxx atau #46"
                value="{{ $query ?? '' }}"
                autocomplete="off"
                required
            >
            <button type="submit" class="btn-cek">🔍 Cek Status Laporan</button>
        </form>
    </div>

    {{-- HASIL --}}
    <div class="result-wrap">
        @if(isset($results))
            @if($results->count() > 0)
                <p style="text-align:center;color:#64748b;font-size:14px;margin-bottom:1.5rem;">
                    Ditemukan <strong>{{ $results->count() }}</strong> laporan untuk "<strong>{{ $query }}</strong>"
                </p>

                @foreach($results as $item)
                @php
                    $statusIcon = $item->status === 'verified' ? '✅'
                        : ($item->status === 'rejected' ? '❌' : '⏳');
                    $statusText = $item->status === 'verified' ? 'Terverifikasi'
                        : ($item->status === 'rejected' ? 'Ditolak' : 'Menunggu Verifikasi');
                @endphp
                <div class="laporan-card {{ $item->status }}">
                    <div class="card-header-bar {{ $item->status }}">
                        <div class="laporan-id">Laporan #{{ $item->id }}</div>
                        <div class="status-badge {{ $item->status }}">
                            {{ $statusIcon }} {{ $statusText }}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Nama Pelapor</div>
                                <div class="info-value">{{ $item->nama_pelapor }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Tanggal Laporan</div>
                                <div class="info-value">{{ $item->waktu_laporan->format('d M Y, H:i') }}</div>
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

                        {{-- Timeline status --}}
                        <div class="timeline">
                            <div class="timeline-title">📋 Riwayat Status</div>

                            <div class="tl-step">
                                <div class="tl-dot done">✓</div>
                                <div class="tl-text">
                                    <strong>Laporan Dikirim</strong>
                                    <small>{{ $item->waktu_laporan->format('d M Y, H:i') }} WIB</small>
                                </div>
                            </div>

                            <div class="tl-step">
                                @if($item->status === 'pending')
                                    <div class="tl-dot wait">⋯</div>
                                    <div class="tl-text">
                                        <strong style="color:#d97706;">Sedang Ditinjau Admin</strong>
                                        <small>Laporan kamu sedang dalam proses verifikasi. Harap menunggu.</small>
                                    </div>
                                @elseif($item->status === 'verified')
                                    <div class="tl-dot done">✓</div>
                                    <div class="tl-text">
                                        <strong style="color:#059669;">Laporan Terverifikasi ✅</strong>
                                        <small>Admin BPBD Bantul telah memverifikasi laporan kamu.</small>
                                    </div>
                                @else
                                    <div class="tl-dot fail">✕</div>
                                    <div class="tl-text">
                                        <strong style="color:#dc2626;">Laporan Ditolak</strong>
                                        <small>Laporan tidak memenuhi kriteria atau data tidak lengkap.</small>
                                    </div>
                                @endif
                            </div>

                            @if($item->status === 'verified')
                            <div class="tl-step">
                                <div class="tl-dot done">✓</div>
                                <div class="tl-text">
                                    <strong style="color:#059669;">Data Ditambahkan ke Peta</strong>
                                    <small>Titik banjir kamu kini tampil di halaman Peta WebGIS.</small>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach

            @else
                <div class="not-found">
                    <div class="icon">🔍</div>
                    <h3>Laporan Tidak Ditemukan</h3>
                    <p>Tidak ada laporan dengan nomor HP atau ID "<strong>{{ $query }}</strong>".<br>
                       Periksa kembali nomor HP yang kamu gunakan saat melapor.</p>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
