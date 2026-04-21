@extends('layouts.public')

@section('styles')
<link href='https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap' rel='stylesheet'>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* ══════════════════════════════════════════════════════
       HERO
    ══════════════════════════════════════════════════════ */
    .laporan-hero {
        background: linear-gradient(135deg, #0c4a6e 0%, #0891b2 50%, #06b6d4 100%);
        color: white; padding: 4rem 0; margin-bottom: 0;
        position: relative; overflow: hidden;
    }
    .laporan-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse at 20% 50%, rgba(255,255,255,0.07) 0%, transparent 60%),
                    radial-gradient(ellipse at 80% 20%, rgba(6,182,212,0.15) 0%, transparent 50%);
    }
    .laporan-hero::after {
        content: '';
        position: absolute;
        bottom: -2px; left: 0; right: 0;
        height: 50px;
        background: #f0f9ff;
        clip-path: ellipse(55% 100% at 50% 100%);
    }
    .laporan-hero h1 { position:relative;z-index:2;font-weight:900;font-size:2.8rem;text-shadow:2px 2px 4px rgba(0,0,0,0.3); }
    .laporan-hero p  { position:relative;z-index:2;font-size:1.2rem;opacity:0.95; }

    /* ══════════════════════════════════════════════════════
       INFO BAR TOP
    ══════════════════════════════════════════════════════ */
    .info-top-bar { background:linear-gradient(135deg,#f0f9ff,#e0f2fe); border-bottom:2px solid rgba(8,145,178,0.15); padding:1.25rem 0; }
    .info-top-inner { display:flex; align-items:stretch; gap:16px; flex-wrap:wrap; }
    .petunjuk-box { flex:2; min-width:280px; background:white; border-radius:14px; padding:1.1rem 1.4rem; border:2px solid rgba(8,145,178,0.15); box-shadow:0 4px 16px rgba(8,145,178,0.07); }
    .petunjuk-box .petunjuk-title { font-weight:800; color:#0c4a6e; font-size:0.95rem; margin-bottom:0.6rem; display:flex; align-items:center; gap:7px; }
    .petunjuk-box ul { margin:0; padding-left:1.1rem; }
    .petunjuk-box li { font-size:12.5px; color:#475569; margin-bottom:3px; line-height:1.5; }
    .coord-display-box { flex:1; min-width:200px; background:linear-gradient(135deg,#0891b2,#06b6d4); border-radius:14px; padding:1.1rem 1.4rem; color:white; box-shadow:0 6px 20px rgba(8,145,178,0.3); display:flex; flex-direction:column; justify-content:center; }
    .coord-display-box .coord-title { font-weight:800; font-size:0.9rem; margin-bottom:0.75rem; display:flex; align-items:center; gap:6px; opacity:0.95; }
    .coord-display-box .coord-val { background:rgba(255,255,255,0.2); border-radius:8px; padding:0.6rem 0.9rem; font-family:monospace; font-size:13px; font-weight:700; }
    .coord-display-box .coord-val span { display:block; margin-bottom:3px; }
    .coord-display-box .coord-val span:last-child { margin-bottom:0; }
    .coord-display-box .coord-note { margin-top:7px; font-size:10.5px; opacity:0.8; }
    .coord-status-pill { display:inline-block; margin-top:8px; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; background:rgba(255,255,255,0.25); border:1px solid rgba(255,255,255,0.4); }
    .coord-status-pill.ok { background:rgba(16,185,129,0.35); border-color:#10b981; }
    .coord-status-pill.out { background:rgba(239,68,68,0.4); border-color:#ef4444; }

    /* Auto-detect badge */
    .auto-detect-badge { display:none; align-items:center; gap:6px; font-size:12px; font-weight:700; color:#059669; background:#d1fae5; border:1px solid #10b981; border-radius:8px; padding:4px 10px; margin-top:6px; }
    .auto-detect-badge.show { display:flex; }

    /* Outside-boundary warning badge */
    .outside-boundary-badge { display:none; align-items:center; gap:6px; font-size:12px; font-weight:700; color:#dc2626; background:#fee2e2; border:1px solid #ef4444; border-radius:8px; padding:4px 10px; margin-top:6px; }
    .outside-boundary-badge.show { display:flex; }

    /* ══════════════════════════════════════════════════════
       FORM
    ══════════════════════════════════════════════════════ */
    .form-container { background:white; border-radius:20px; padding:2.5rem; box-shadow:0 15px 50px rgba(0,0,0,0.08); border:2px solid rgba(8,145,178,0.1); }
    .form-container h4 { color:#0c4a6e; font-weight:800; font-size:1.8rem; margin-bottom:2rem; display:flex; align-items:center; gap:0.75rem; }
    .form-label { font-weight:700; color:#334155; margin-bottom:0.5rem; font-size:0.95rem; }
    .form-control, .form-select { border:2px solid #e2e8f0; border-radius:10px; padding:0.85rem 1.25rem; font-size:1rem; transition:all 0.3s; }
    .form-control:focus, .form-select:focus { border-color:#0891b2; box-shadow:0 0 0 4px rgba(8,145,178,0.1); }
    .form-control::placeholder { color:#94a3b8; }
    textarea.form-control { resize:vertical; min-height:120px; }

    /* Select — state luar batas */
    .select-cleared { border-color:#ef4444 !important; background:#fff5f5 !important; }
    .select-cleared:focus { box-shadow:0 0 0 4px rgba(239,68,68,0.12) !important; }

    /* ══════════════════════════════════════════════════════
       MAP SECTION
    ══════════════════════════════════════════════════════ */
    .map-section { background:white; border-radius:16px; border:2px solid rgba(8,145,178,0.2); overflow:hidden; margin-bottom:1.5rem; box-shadow:0 8px 30px rgba(8,145,178,0.12); }
    .map-section-header { background:linear-gradient(135deg,#0891b2,#06b6d4); color:white; padding:1rem 1.5rem; display:flex; align-items:center; justify-content:space-between; gap:8px; flex-wrap:wrap; }
    .map-section-header h6 { margin:0; font-weight:800; font-size:1rem; display:flex; align-items:center; gap:0.5rem; }
    .map-status-badge { padding:4px 12px; border-radius:20px; font-size:12px; font-weight:700; background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.4); color:white; transition:all 0.3s; white-space:nowrap; }
    .map-status-badge.detected { background:rgba(16,185,129,0.3); border-color:#10b981; }
    .map-status-badge.out-of-bounds { background:rgba(239,68,68,0.4); border-color:#ef4444; }
    #locationMap { height:440px; width:100%; position:relative; z-index:1; }
    .btn-gps { position:absolute; bottom:20px; left:50%; transform:translateX(-50%); z-index:1000; background:linear-gradient(135deg,#0891b2,#06b6d4); color:white; border:3px solid white; border-radius:30px; padding:8px 20px; font-weight:700; font-size:13px; cursor:pointer; box-shadow:0 6px 20px rgba(8,145,178,0.4); transition:all 0.3s; display:flex; align-items:center; gap:7px; white-space:nowrap; }
    .btn-gps:hover { transform:translateX(-50%) translateY(-2px); box-shadow:0 10px 28px rgba(8,145,178,0.5); }
    .btn-gps.success { background:linear-gradient(135deg,#10b981,#059669); }
    .btn-gps.loading { background:linear-gradient(135deg,#f59e0b,#d97706); pointer-events:none; }
    .coord-row { display:grid; grid-template-columns:1fr 1fr; gap:12px; padding:1rem 1.25rem; background:#f8fafc; border-top:2px solid #e2e8f0; }
    .coord-input-wrap label { font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:5px; display:flex; align-items:center; gap:5px; }
    .coord-input-wrap input { width:100%; padding:9px 14px; border:2px solid #e2e8f0; border-radius:10px; font-size:14px; font-weight:600; color:#0c4a6e; background:white; transition:all 0.3s; font-family:'Courier New',monospace; }
    .coord-input-wrap input:focus { border-color:#0891b2; box-shadow:0 0 0 3px rgba(8,145,178,0.12); outline:none; }
    .coord-input-wrap input.has-value { border-color:#10b981; background:#f0fdf4; }
    .coord-hint { font-size:10px; color:#94a3b8; margin-top:3px; }
    .map-instruction { position:absolute; top:12px; left:50%; transform:translateX(-50%); z-index:1000; background:rgba(12,74,110,0.88); color:white; padding:7px 16px; border-radius:20px; font-size:12px; font-weight:600; pointer-events:none; white-space:nowrap; backdrop-filter:blur(6px); border:1px solid rgba(255,255,255,0.2); transition:opacity 0.4s; }

    /* Outside-boundary toast overlay on map */
    .map-oob-toast { position:absolute; top:12px; right:12px; z-index:1001; background:rgba(220,38,38,0.92); color:white; padding:8px 14px; border-radius:12px; font-size:12px; font-weight:700; display:none; align-items:center; gap:6px; backdrop-filter:blur(6px); border:1px solid rgba(255,255,255,0.25); box-shadow:0 4px 16px rgba(220,38,38,0.4); max-width:240px; }
    .map-oob-toast.show { display:flex; }

    /* ══════════════════════════════════════════════════════
       MAP LEGEND
    ══════════════════════════════════════════════════════ */
    .map-legend {
        position: absolute;
        bottom: 60px;
        right: 10px;
        z-index: 1000;
        background: rgba(255,255,255,0.96);
        border-radius: 12px;
        padding: 10px 14px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.18);
        border: 1.5px solid rgba(8,145,178,0.18);
        max-height: 300px;
        overflow-y: auto;
        min-width: 170px;
        font-size: 11.5px;
        backdrop-filter: blur(6px);
    }
    .map-legend-title {
        font-weight: 800;
        color: #0c4a6e;
        font-size: 12px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 5px;
        border-bottom: 1.5px solid #e2e8f0;
        padding-bottom: 6px;
    }
    .legend-item {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 5px;
        cursor: pointer;
        border-radius: 6px;
        padding: 2px 4px;
        transition: background 0.2s;
    }
    .legend-item:hover { background: #f0f9ff; }
    .legend-color {
        width: 16px;
        height: 16px;
        border-radius: 4px;
        flex-shrink: 0;
        border: 2px solid rgba(0,0,0,0.15);
    }
    .legend-label { color: #334155; font-weight: 600; font-size: 11px; line-height: 1.3; }
    .legend-toggle-btn {
        position: absolute;
        bottom: 60px;
        right: 10px;
        z-index: 1001;
        background: white;
        border: 2px solid rgba(8,145,178,0.3);
        border-radius: 8px;
        padding: 5px 10px;
        font-size: 11px;
        font-weight: 700;
        color: #0891b2;
        cursor: pointer;
        display: none;
    }

    /* ══════════════════════════════════════════════════════
       ALERTS & MISC
    ══════════════════════════════════════════════════════ */
    .alert { border-radius:12px; border:none; padding:1.25rem 1.5rem; font-weight:600; }
    .alert-success { background:linear-gradient(135deg,#d1fae5,#a7f3d0); color:#065f46; border-left:5px solid #10b981; }
    .alert-danger  { background:linear-gradient(135deg,#fee2e2,#fecaca); color:#991b1b; border-left:5px solid #ef4444; }
    .alert-warning { background:linear-gradient(135deg,#fef3c7,#fde68a); color:#92400e; border-left:5px solid #f59e0b; }

    .btn-primary { background:linear-gradient(135deg,#0891b2,#06b6d4); border:none; padding:1.25rem 3rem; font-weight:800; font-size:1.15rem; border-radius:12px; transition:all 0.3s; box-shadow:0 10px 30px rgba(8,145,178,0.3); }
    .btn-primary:hover:not(:disabled) { transform:translateY(-5px); box-shadow:0 15px 40px rgba(8,145,178,0.5); }
    .btn-primary:disabled { background:#cbd5e1; cursor:not-allowed; box-shadow:none; }
    .section-label { display:flex; align-items:center; gap:10px; color:#0c4a6e; font-weight:800; font-size:1rem; margin-bottom:1rem; margin-top:0.5rem; }
    .section-label::after { content:''; flex:1; height:2px; background:linear-gradient(90deg,#0891b2,transparent); border-radius:2px; }
    .leaflet-popup-content-wrapper { border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,0.3); }

    /* Tooltip desa di peta */
    .leaflet-tooltip { background:rgba(12,74,110,0.92); color:white; border:none; border-radius:6px; font-weight:700; font-size:11px; padding:4px 8px; }
    .leaflet-tooltip::before { border-top-color:rgba(12,74,110,0.92); }

    /* Scrollbar legend */
    .map-legend::-webkit-scrollbar { width: 4px; }
    .map-legend::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
    .map-legend::-webkit-scrollbar-thumb { background: #0891b2; border-radius: 4px; }

    @media (max-width:767px) {
        #locationMap { height:320px; }
        .coord-row { grid-template-columns:1fr; }
        .laporan-hero h1 { font-size:2rem; }
        .form-container { padding:1.5rem; }
        .info-top-inner { flex-direction:column; }
        .map-legend { max-height:180px; font-size:10.5px; right:8px; bottom:55px; min-width:150px; padding:8px 10px; }
    }
</style>
@endsection

@section('content')
<div class="laporan-hero">
    <div class="container">
        <h1 class="text-center mb-3"><i class="fas fa-paper-plane"></i> Lapor Kejadian Banjir</h1>
        <p class="text-center lead">Bantu kami memantau kejadian banjir di sekitar Anda dengan melaporkan secara real-time</p>
    </div>
</div>

<div class="info-top-bar">
    <div class="container">
        <div class="info-top-inner">
            <div class="petunjuk-box">
                <div class="petunjuk-title"><i class="fas fa-info-circle" style="color:#0891b2;"></i> Petunjuk Pelaporan</div>
                <ul>
                    <li>Klik tombol <strong>GPS</strong> di peta untuk deteksi lokasi otomatis</li>
                    <li>Atau klik langsung di peta / geser marker ke lokasi kejadian</li>
                    <li>Kecamatan &amp; desa akan <strong>otomatis terisi</strong> sesuai lokasi yang dipilih</li>
                    <li>Jika titik <strong>di luar wilayah Bantul</strong>, form kecamatan &amp; desa akan dikosongkan</li>
                    <li>Isi formulir dengan lengkap — nomor telepon <strong>wajib</strong> diisi</li>
                    <li>Upload <strong>minimal 1 foto</strong> sebagai bukti kejadian banjir</li>
                    <li>Laporan akan diverifikasi oleh admin BPBD sebelum ditampilkan</li>
                </ul>
            </div>
            <div class="coord-display-box">
                <div class="coord-title"><i class="fas fa-map-pin"></i> Koordinat Dipilih</div>
                <div class="coord-val">
                    <span><i class="fas fa-arrows-alt-v"></i> Lat: <strong id="infoLat">—</strong></span>
                    <span><i class="fas fa-arrows-alt-h"></i> Lng: <strong id="infoLon">—</strong></span>
                </div>
                <div class="coord-note"><i class="fas fa-check-circle"></i> Lokasi siap digunakan</div>
                <span class="coord-status-pill" id="coordStatusPill">Belum dipilih</span>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4">
        <i class="fas fa-exclamation-triangle"></i> <strong>Terjadi kesalahan:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="form-container">
        <h4><i class="fas fa-edit"></i> Formulir Pelaporan</h4>

        <form action="{{ route('laporan.submit') }}" method="POST" enctype="multipart/form-data" id="formLaporan">
            @csrf
            <input type="hidden" name="latitude"  id="latitude">
            <input type="hidden" name="longitude" id="longitude">

            {{-- ══ PETA LOKASI ══ --}}
            <div class="section-label"><i class="fas fa-map-marked-alt"></i> Pilih Lokasi di Peta</div>
            <div class="map-section">
                <div class="map-section-header">
                    <h6><i class="fas fa-map-pin"></i> Klik peta atau geser marker — kecamatan &amp; desa otomatis terdeteksi</h6>
                    <span class="map-status-badge" id="mapStatusBadge">Belum dipilih</span>
                </div>
                <div style="position:relative;">
                    <div id="locationMap"></div>
                    <div class="map-instruction" id="mapInstruction">
                        <i class="fas fa-hand-pointer"></i> Klik peta atau geser marker
                    </div>
                    {{-- Toast peringatan luar batas --}}
                    <div class="map-oob-toast" id="mapOobToast">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Titik di luar wilayah Kab. Bantul!</span>
                    </div>
                    {{-- Tombol GPS --}}
                    <button type="button" class="btn-gps" id="btnGps" onclick="detectGPS()">
                        <i class="fas fa-crosshairs" id="gpsIcon"></i>
                        <span id="gpsText">Deteksi GPS</span>
                    </button>
                    {{-- Legend Kecamatan --}}
                    <div class="map-legend" id="kecLegend">
                        <div class="map-legend-title">
                            <i class="fas fa-layer-group" style="color:#0891b2;"></i> Kecamatan
                        </div>
                        <div id="legendItems">
                            {{-- diisi oleh JS --}}
                        </div>
                    </div>
                </div>
                <div class="coord-row">
                    <div class="coord-input-wrap">
                        <label><i class="fas fa-arrows-alt-v" style="color:#0891b2;"></i> Latitude (Y) <span style="color:#ef4444;">*</span></label>
                        <input type="number" id="inputLat" step="any" placeholder="-7.870000" oninput="onCoordInput()" onchange="onCoordChange()">
                        <div class="coord-hint">Contoh: -7.870000 (negatif = Selatan)</div>
                    </div>
                    <div class="coord-input-wrap">
                        <label><i class="fas fa-arrows-alt-h" style="color:#0891b2;"></i> Longitude (X) <span style="color:#ef4444;">*</span></label>
                        <input type="number" id="inputLon" step="any" placeholder="110.330000" oninput="onCoordInput()" onchange="onCoordChange()">
                        <div class="coord-hint">Contoh: 110.330000 (positif = Timur)</div>
                    </div>
                </div>
            </div>

            {{-- ══ DATA PELAPOR ══ --}}
            <div class="section-label"><i class="fas fa-user"></i> Data Pelapor</div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama_pelapor" class="form-control" placeholder="Masukkan nama lengkap Anda" value="{{ old('nama_pelapor') }}">
                    <small class="text-muted">Opsional — nama boleh dikosongkan</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">No. Telepon / WhatsApp <span class="text-danger">*</span></label>
                    <input type="text" name="no_telp" class="form-control" required placeholder="08xxxxxxxxxx" value="{{ old('no_telp') }}">
                    <small class="text-muted">Wajib — untuk konfirmasi laporan oleh BPBD</small>
                </div>
            </div>

            {{-- ══ LOKASI KEJADIAN ══ --}}
            <div class="section-label"><i class="fas fa-map-marker-alt"></i> Lokasi Kejadian</div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                    <select name="kecamatan" id="selectKecamatan" class="form-select" required onchange="onKecamatanChange()">
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach(['Banguntapan','Bantul','Bambanglipuro','Dlingo','Imogiri','Jetis','Kasihan','Kretek','Pajangan','Pandak','Piyungan','Pleret','Pundong','Sanden','Sedayu','Sewon','Srandakan'] as $kec)
                        <option value="{{ $kec }}" {{ old('kecamatan') == $kec ? 'selected' : '' }}>{{ $kec }}</option>
                        @endforeach
                    </select>
                    <div class="auto-detect-badge" id="kecAutoBadge">
                        <i class="fas fa-magic"></i> <span id="kecAutoText">Terdeteksi otomatis dari peta</span>
                    </div>
                    <div class="outside-boundary-badge" id="kecOobBadge">
                        <i class="fas fa-exclamation-triangle"></i> <span>Titik di luar wilayah Bantul — pilih manual</span>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Desa / Kelurahan</label>
                    <select name="desa" id="selectDesa" class="form-select" disabled>
                        <option value="">-- Pilih Kecamatan dulu --</option>
                    </select>
                    <div class="auto-detect-badge" id="desaAutoBadge">
                        <i class="fas fa-magic"></i> <span id="desaAutoText">Terdeteksi otomatis dari peta</span>
                    </div>
                    <div class="outside-boundary-badge" id="desaOobBadge">
                        <i class="fas fa-exclamation-triangle"></i> <span>Tidak terdeteksi — pilih manual setelah memilih kecamatan</span>
                    </div>
                    <small class="text-muted" id="desaHint">Otomatis menyesuaikan kecamatan yang dipilih</small>
                </div>
            </div>

            {{-- ══ KONDISI BANJIR ══ --}}
            <div class="section-label"><i class="fas fa-water"></i> Kondisi Banjir</div>
            <div class="mb-3">
                <label class="form-label">Deskripsi Kejadian <span class="text-danger">*</span></label>
                <textarea name="deskripsi" class="form-control" rows="4"
                          placeholder="Jelaskan detail kejadian banjir (penyebab, dampak, kondisi terkini, dll)"
                          required>{{ old('deskripsi') }}</textarea>
                <small class="text-muted"><i class="fas fa-lightbulb"></i> Berikan deskripsi sejelas mungkin untuk membantu tim BPBD</small>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-hands-helping"></i> Kebutuhan / Bantuan yang Diperlukan
                    <span class="badge" style="background:#fef3c7;color:#92400e;font-size:0.75rem;margin-left:6px;">Opsional</span>
                </label>
                <textarea name="kebutuhan_bantuan" class="form-control" rows="3"
                          placeholder="Contoh: Perahu karet, selimut, makanan siap saji, obat-obatan, pompa air, dll."
                          style="border-color:#f59e0b;">{{ old('kebutuhan_bantuan') }}</textarea>
                <small class="text-muted"><i class="fas fa-info-circle" style="color:#f59e0b;"></i> Tuliskan barang atau alat yang dibutuhkan agar BPBD dapat menyiapkan bantuan yang tepat</small>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Perkiraan Kedalaman Air (cm)</label>
                    <input type="number" name="kedalaman_cm" class="form-control" placeholder="Contoh: 50" min="1" value="{{ old('kedalaman_cm') }}">
                    <small class="text-muted"><i class="fas fa-ruler-vertical"></i> Perkiraan kedalaman genangan dalam cm</small>
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">
                        <i class="fas fa-camera"></i> Foto Kejadian <span class="text-danger">*</span>
                        <span class="badge" style="background:#e0f2fe;color:#0369a1;font-size:0.75rem;margin-left:6px;">Maks. 3 Foto</span>
                    </label>
                    <input type="file" name="fotos[]" id="fotoInput" class="form-control" required accept="image/jpeg,image/png,image/jpg" multiple>
                    <small class="text-muted"><i class="fas fa-info-circle"></i> Wajib upload min. 1 foto sebagai bukti kejadian. Format: JPG/PNG. Maks. 2MB/foto.</small>
                    <div id="fotoPreviewGrid" style="display:none;margin-top:12px;">
                        <div style="font-size:0.85rem;font-weight:700;color:#0c4a6e;margin-bottom:8px;"><i class="fas fa-images"></i> Preview Foto:</div>
                        <div id="previewContainer" style="display:flex;gap:10px;flex-wrap:wrap;"></div>
                    </div>
                </div>
            </div>

            <div class="alert alert-warning mb-4">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Penting!</strong> Pastikan lokasi sudah ditentukan di peta dan foto kejadian sudah diupload sebelum mengirim laporan!
            </div>

            <button type="submit" class="btn btn-primary w-100" id="btnSubmit" disabled>
                <i class="fas fa-paper-plane"></i> Kirim Laporan
            </button>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// ══════════════════════════════════════════════════════════════════════
//  DATA KELURAHAN (fallback jika GeoJSON belum load)
// ══════════════════════════════════════════════════════════════════════
const kelurahanData = {
    'Banguntapan':   ['Banguntapan','Baturetno','Jagalan','Jambidan','Potorono','Singosaren','Tamanan','Wirokerten'],
    'Bantul':        ['Bantul','Palbapang','Ringinharjo','Sabdodadi','Trirenggo'],
    'Bambanglipuro': ['Mulyodadi','Sidomulyo','Sumbermulyo'],
    'Dlingo':        ['Dlingo','Jatimulyo','Mangunan','Muntuk','Temuwuh','Terong'],
    'Imogiri':       ['Girirejo','Imogiri','Karangtalun','Karangtengah','Kebonagung','Selopamioro','Sriharjo','Wukirsari'],
    'Jetis':         ['Canden','Patalan','Sumberagung','Trimulyo'],
    'Kasihan':       ['Bangunjiwo','Ngestiharjo','Tamantirto','Tirtonirmolo'],
    'Kretek':        ['Donotirto','Parangtritis','Tirtohargo','Tirtomulyo','Tirtosari'],
    'Pajangan':      ['Guwosari','Sendangsari','Triwidadi'],
    'Pandak':        ['Caturharjo','Gilangharjo','Triharjo','Wijirejo'],
    'Piyungan':      ['Sitimulyo','Srimartani','Srimulyo'],
    'Pleret':        ['Bawuran','Pleret','Segoroyoso','Wonokromo','Wonolelo'],
    'Pundong':       ['Panjangrejo','Seloharjo','Srihardono'],
    'Sanden':        ['Gadingharjo','Gadingsari','Murtigading','Srigading'],
    'Sedayu':        ['Argodadi','Argorejo','Argomulyo','Argosari'],
    'Sewon':         ['Bangunharjo','Panggungharjo','Pendowoharjo','Timbulharjo'],
    'Srandakan':     ['Poncosari','Trimurti'],
};

// ══════════════════════════════════════════════════════════════════════
//  PALET WARNA PER KECAMATAN — unik, menarik, mudah dibedakan
// ══════════════════════════════════════════════════════════════════════
const KEC_COLORS = {
    'BANGUNTAPAN':   { fill: '#FF6B6B', border: '#C0392B', label: 'Banguntapan' },
    'BANTUL':        { fill: '#4FC3F7', border: '#0277BD', label: 'Bantul' },
    'BAMBANGLIPURO': { fill: '#81C784', border: '#2E7D32', label: 'Bambanglipuro' },
    'DLINGO':        { fill: '#FFD54F', border: '#F57F17', label: 'Dlingo' },
    'IMOGIRI':       { fill: '#CE93D8', border: '#6A1B9A', label: 'Imogiri' },
    'JETIS':         { fill: '#80DEEA', border: '#00838F', label: 'Jetis' },
    'KASIHAN':       { fill: '#FFAB91', border: '#BF360C', label: 'Kasihan' },
    'KRETEK':        { fill: '#A5D6A7', border: '#1B5E20', label: 'Kretek' },
    'PAJANGAN':      { fill: '#F48FB1', border: '#880E4F', label: 'Pajangan' },
    'PANDAK':        { fill: '#B39DDB', border: '#4527A0', label: 'Pandak' },
    'PIYUNGAN':      { fill: '#FFF176', border: '#F9A825', label: 'Piyungan' },
    'PLERET':        { fill: '#80CBC4', border: '#004D40', label: 'Pleret' },
    'PUNDONG':       { fill: '#FFCC80', border: '#E65100', label: 'Pundong' },
    'SANDEN':        { fill: '#90CAF9', border: '#0D47A1', label: 'Sanden' },
    'SEDAYU':        { fill: '#EF9A9A', border: '#B71C1C', label: 'Sedayu' },
    'SEWON':         { fill: '#C5E1A5', border: '#33691E', label: 'Sewon' },
    'SRANDAKAN':     { fill: '#F8BBD0', border: '#AD1457', label: 'Srandakan' },
};

// ══════════════════════════════════════════════════════════════════════
//  VARIABEL GLOBAL
// ══════════════════════════════════════════════════════════════════════
let map, marker, locationSet = false, isOutOfBounds = false;
let desaGeoJSON = null;
let adminLayer  = null;
let kecLayers   = {};   // { 'BANGUNTAPAN': L.GeoJSON, ... }
const BANTUL_CENTER = [-7.8700, 110.3300];

// ══════════════════════════════════════════════════════════════════════
//  INIT
// ══════════════════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function () {
    const oldKec  = '{{ old("kecamatan") }}';
    const oldDesa = '{{ old("desa") }}';
    if (oldKec) populateDesaSelect(oldKec, oldDesa, false);
    initMap();
});

// ══════════════════════════════════════════════════════════════════════
//  POPULATE DESA SELECT
// ══════════════════════════════════════════════════════════════════════
function populateDesaSelect(kecNama, selectedDesa, fromAuto) {
    const sel  = document.getElementById('selectDesa');
    const hint = document.getElementById('desaHint');
    sel.innerHTML = '<option value="">-- Pilih Desa/Kelurahan --</option>';

    const list = kelurahanData[kecNama] || [];
    list.sort().forEach(d => {
        const opt = document.createElement('option');
        opt.value = d;
        opt.textContent = d;
        if (selectedDesa && d.toLowerCase() === selectedDesa.toLowerCase()) opt.selected = true;
        sel.appendChild(opt);
    });
    sel.disabled = list.length === 0;
    if (hint) hint.style.display = list.length > 0 ? 'block' : 'none';

    if (!fromAuto) {
        document.getElementById('desaAutoBadge').classList.remove('show');
    }
}

// ══════════════════════════════════════════════════════════════════════
//  EVENT: user ganti kecamatan manual
// ══════════════════════════════════════════════════════════════════════
function onKecamatanChange() {
    const kec = document.getElementById('selectKecamatan').value;
    document.getElementById('kecAutoBadge').classList.remove('show');
    document.getElementById('desaAutoBadge').classList.remove('show');
    document.getElementById('kecOobBadge').classList.remove('show');
    document.getElementById('desaOobBadge').classList.remove('show');
    document.getElementById('selectKecamatan').classList.remove('select-cleared');
    document.getElementById('selectDesa').classList.remove('select-cleared');
    populateDesaSelect(kec, '', false);
}

// ══════════════════════════════════════════════════════════════════════
//  RAY CASTING — point in polygon
// ══════════════════════════════════════════════════════════════════════
function pointInRing(lat, lng, ring) {
    let inside = false;
    const x = lng, y = lat;
    for (let i = 0, j = ring.length - 1; i < ring.length; j = i++) {
        const xi = ring[i][0], yi = ring[i][1];
        const xj = ring[j][0], yj = ring[j][1];
        if (((yi > y) !== (yj > y)) && (x < (xj - xi) * (y - yi) / (yj - yi) + xi)) {
            inside = !inside;
        }
    }
    return inside;
}

function pointInFeature(lat, lng, feature) {
    const geom = feature.geometry;
    if (!geom) return false;
    if (geom.type === 'Polygon') {
        return pointInRing(lat, lng, geom.coordinates[0]);
    } else if (geom.type === 'MultiPolygon') {
        for (const poly of geom.coordinates) {
            if (pointInRing(lat, lng, poly[0])) return true;
        }
    }
    return false;
}

// ══════════════════════════════════════════════════════════════════════
//  DETEKSI DESA DAN KECAMATAN DARI TITIK
// ══════════════════════════════════════════════════════════════════════
function detectFromPoint(lat, lng) {
    if (!desaGeoJSON || !desaGeoJSON.features) return null;
    for (const feature of desaGeoJSON.features) {
        if (pointInFeature(lat, lng, feature)) {
            const props = feature.properties;
            return {
                kecamatan: (props.WADMKC || '').trim(),
                desa:      (props.WADMKD || props.NAMOBJ || '').trim()
            };
        }
    }
    return null;
}

// ══════════════════════════════════════════════════════════════════════
//  AUTO FILL — atau KOSONGKAN jika di luar batas
// ══════════════════════════════════════════════════════════════════════
function autoFillFromPoint(lat, lng) {
    const result    = detectFromPoint(lat, lng);
    const kecBadge  = document.getElementById('kecAutoBadge');
    const kecText   = document.getElementById('kecAutoText');
    const desaBadge = document.getElementById('desaAutoBadge');
    const desaText  = document.getElementById('desaAutoText');
    const kecOob    = document.getElementById('kecOobBadge');
    const desaOob   = document.getElementById('desaOobBadge');
    const selectKec = document.getElementById('selectKecamatan');
    const selectDes = document.getElementById('selectDesa');
    const toast     = document.getElementById('mapOobToast');

    // ── GeoJSON belum load — skip silently ───────────────────────────
    if (!desaGeoJSON) return;

    // ── TITIK DI LUAR BATAS ──────────────────────────────────────────
    if (!result) {
        isOutOfBounds = true;

        // Kosongkan & highlight merah kecamatan
        selectKec.value = '';
        selectKec.classList.add('select-cleared');
        kecBadge.classList.remove('show');
        kecOob.classList.add('show');

        // Kosongkan & disable desa
        selectDes.innerHTML = '<option value="">-- Tidak terdeteksi (di luar Bantul) --</option>';
        selectDes.value = '';
        selectDes.disabled = true;
        selectDes.classList.add('select-cleared');
        desaBadge.classList.remove('show');
        desaOob.classList.add('show');

        // Status badge header peta
        const badge = document.getElementById('mapStatusBadge');
        badge.textContent = '⚠ Di Luar Wilayah Bantul';
        badge.classList.remove('detected');
        badge.classList.add('out-of-bounds');

        // Pill koordinat
        const pill = document.getElementById('coordStatusPill');
        pill.textContent = '⚠ Di Luar Bantul';
        pill.classList.remove('ok');
        pill.classList.add('out');

        // Toast di atas peta
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 4000);

        console.warn('⚠️ Titik di luar wilayah Bantul — form dikosongkan');
        return;
    }

    // ── TITIK DI DALAM BATAS ─────────────────────────────────────────
    isOutOfBounds = false;
    toast.classList.remove('show');
    selectKec.classList.remove('select-cleared');
    selectDes.classList.remove('select-cleared');
    kecOob.classList.remove('show');
    desaOob.classList.remove('show');

    const { kecamatan, desa } = result;

    // Isi kecamatan
    const opts     = Array.from(selectKec.options);
    const matchKec = opts.find(o => o.value.toLowerCase() === kecamatan.toLowerCase());

    if (matchKec) {
        selectKec.value = matchKec.value;
        kecText.textContent = '✓ Terdeteksi: Kec. ' + matchKec.value;
        kecBadge.classList.add('show');

        // Isi desa
        populateDesaSelect(matchKec.value, desa, true);

        setTimeout(() => {
            const selDesa   = document.getElementById('selectDesa');
            const matchDesa = Array.from(selDesa.options).find(
                o => o.value.toLowerCase() === desa.toLowerCase()
            );
            if (matchDesa) {
                selDesa.value = matchDesa.value;
                desaText.textContent = '✓ Terdeteksi: ' + matchDesa.value;
                desaBadge.classList.add('show');
            } else {
                desaBadge.classList.remove('show');
            }
        }, 50);
    } else {
        kecBadge.classList.remove('show');
        desaBadge.classList.remove('show');
    }
}

// ══════════════════════════════════════════════════════════════════════
//  INISIALISASI PETA
// ══════════════════════════════════════════════════════════════════════
function initMap() {
    map = L.map('locationMap', { center: BANTUL_CENTER, zoom: 11, zoomControl: true });

    // Basemap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors', maxZoom: 19
    }).addTo(map);

    // Marker merah
    const markerIcon = L.icon({
        iconUrl:    'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl:  'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
        iconSize:   [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34]
    });
    marker = L.marker(BANTUL_CENTER, { draggable: true, icon: markerIcon }).addTo(map);
    marker.bindPopup(buildPopupContent(BANTUL_CENTER[0], BANTUL_CENTER[1]));

    map.on('click', e => setLocation(e.latlng.lat, e.latlng.lng, 'map'));
    marker.on('dragend', () => {
        const p = marker.getLatLng();
        setLocation(p.lat, p.lng, 'drag');
    });

    // Load GeoJSON
    loadDesaGeoJSON();

    @if(old('latitude') && old('longitude'))
    setLocation({{ old('latitude') }}, {{ old('longitude') }}, 'restore');
    @endif
}

// ══════════════════════════════════════════════════════════════════════
//  LOAD GEOJSON + WARNA PER KECAMATAN
// ══════════════════════════════════════════════════════════════════════
function getKecColor(kecNama) {
    const key = (kecNama || '').toUpperCase().trim();
    return KEC_COLORS[key] || { fill: '#B0BEC5', border: '#546E7A' };
}

function loadDesaGeoJSON() {
    fetch('/geojson/bantuldesa.geojson')
        .then(r => {
            if (!r.ok) throw new Error('GeoJSON tidak ditemukan (' + r.status + ')');
            return r.json();
        })
        .then(data => {
            desaGeoJSON = data;

            adminLayer = L.geoJSON(data, {
                style: feature => {
                    const kec   = (feature.properties.WADMKC || '').trim();
                    const color = getKecColor(kec);
                    return {
                        fillColor:   color.fill,
                        fillOpacity: 0.30,
                        color:       color.border,
                        weight:      2.2,
                        opacity:     0.85,
                        dashArray:   null,
                    };
                },
                onEachFeature: (feature, layer) => {
                    const props = feature.properties;
                    const kec   = props.WADMKC || '';
                    const desa  = props.WADMKD || props.NAMOBJ || '';
                    const color = getKecColor(kec);

                    layer.bindTooltip(
                        `<strong>${desa}</strong><br><small style="opacity:0.85;">Kec. ${kec}</small>`,
                        { sticky: true, direction: 'top', offset: [0, -6] }
                    );

                    layer.on('mouseover', function () {
                        this.setStyle({
                            fillOpacity: 0.60,
                            weight: 3,
                            color: color.border,
                        });
                        this.bringToFront();
                    });
                    layer.on('mouseout', function () {
                        adminLayer.resetStyle(this);
                    });
                    layer.on('click', function (e) {
                        // Klik di layer desa = pilih titik di tengah desa
                        L.DomEvent.stopPropagation(e);
                        setLocation(e.latlng.lat, e.latlng.lng, 'map');
                    });
                }
            }).addTo(map);

            // Sesuaikan batas peta dengan GeoJSON
            try { map.fitBounds(adminLayer.getBounds(), { padding: [20, 20] }); } catch (e2) {}

            console.log('✅ GeoJSON desa berhasil dimuat:', data.features.length, 'desa');

            // Bangun legenda
            buildLegend(data);
        })
        .catch(err => {
            console.warn('⚠️ GeoJSON tidak dimuat:', err.message);
            // Fallback: tampilkan warning sederhana di legenda
            document.getElementById('legendItems').innerHTML =
                '<div style="color:#94a3b8;font-size:10.5px;font-style:italic;">GeoJSON belum tersedia</div>';
        });
}

// ══════════════════════════════════════════════════════════════════════
//  BUILD LEGENDA KECAMATAN
// ══════════════════════════════════════════════════════════════════════
function buildLegend(geojsonData) {
    // Kumpulkan kecamatan unik dari GeoJSON
    const kecSet = new Set();
    geojsonData.features.forEach(f => {
        const kec = (f.properties.WADMKC || '').trim();
        if (kec) kecSet.add(kec);
    });

    const sorted = Array.from(kecSet).sort();
    const container = document.getElementById('legendItems');
    container.innerHTML = '';

    sorted.forEach(kec => {
        const color = getKecColor(kec);
        const item  = document.createElement('div');
        item.className = 'legend-item';
        item.title     = 'Kec. ' + (color.label || kec);
        item.innerHTML = `
            <div class="legend-color" style="background:${color.fill};border-color:${color.border};"></div>
            <div class="legend-label">${color.label || kec}</div>
        `;
        // Klik legenda → zoom ke kecamatan itu
        item.addEventListener('click', () => zoomToKecamatan(kec, geojsonData));
        container.appendChild(item);
    });
}

function zoomToKecamatan(kecNama, geojsonData) {
    const features = geojsonData.features.filter(
        f => (f.properties.WADMKC || '').trim().toUpperCase() === kecNama.toUpperCase()
    );
    if (!features.length) return;
    const tmpLayer = L.geoJSON({ type: 'FeatureCollection', features });
    map.flyToBounds(tmpLayer.getBounds(), { padding: [30, 30], duration: 0.8 });
}

// ══════════════════════════════════════════════════════════════════════
//  SET LOCATION (dipanggil dari klik peta, GPS, drag, atau manual)
// ══════════════════════════════════════════════════════════════════════
function setLocation(lat, lng, source) {
    lat = parseFloat(parseFloat(lat).toFixed(6));
    lng = parseFloat(parseFloat(lng).toFixed(6));

    // Update hidden inputs
    document.getElementById('latitude').value  = lat;
    document.getElementById('longitude').value = lng;

    // Update input koordinat
    const iLat = document.getElementById('inputLat');
    const iLon = document.getElementById('inputLon');
    iLat.value = lat; iLon.value = lng;
    iLat.classList.add('has-value'); iLon.classList.add('has-value');

    // Geser marker
    marker.setLatLng([lat, lng]);
    marker.setPopupContent(buildPopupContent(lat, lng));

    if (source !== 'drag') {
        map.flyTo([lat, lng], source === 'gps' ? 16 : 14, { duration: 1.2 });
    }

    // Update info koordinat di atas
    document.getElementById('infoLat').textContent = lat;
    document.getElementById('infoLon').textContent = lng;

    // Reset badge status (akan di-update ulang oleh autoFillFromPoint)
    const pill = document.getElementById('coordStatusPill');
    pill.textContent = '✓ Lokasi Dipilih';
    pill.classList.add('ok');
    pill.classList.remove('out');

    const badge = document.getElementById('mapStatusBadge');
    badge.textContent = '✓ Lokasi Dipilih';
    badge.classList.add('detected');
    badge.classList.remove('out-of-bounds');

    // Sembunyikan instruksi awal
    const instr = document.getElementById('mapInstruction');
    instr.style.opacity = '0';
    setTimeout(() => { instr.style.display = 'none'; }, 400);

    locationSet = true;
    checkCanSubmit();

    // Auto-detect / kosongkan kecamatan & desa
    autoFillFromPoint(lat, lng);
}

// ══════════════════════════════════════════════════════════════════════
//  POPUP CONTENT
// ══════════════════════════════════════════════════════════════════════
function buildPopupContent(lat, lng) {
    return `<div style="min-width:180px;padding:4px;">
        <div style="font-weight:800;color:#0c4a6e;margin-bottom:8px;font-size:14px;">
            <i class="fas fa-map-pin" style="color:#ef4444;"></i> Lokasi Kejadian
        </div>
        <div style="background:#f0f9ff;border-radius:8px;padding:8px;font-family:monospace;font-size:12px;">
            <div>Lat: <strong>${lat}</strong></div>
            <div>Lng: <strong>${lng}</strong></div>
        </div>
        <div style="font-size:11px;color:#64748b;margin-top:6px;">
            <i class="fas fa-arrows-alt"></i> Geser marker untuk mengubah posisi
        </div>
    </div>`;
}

// ══════════════════════════════════════════════════════════════════════
//  INPUT KOORDINAT MANUAL
// ══════════════════════════════════════════════════════════════════════
function onCoordInput() {
    const lat = parseFloat(document.getElementById('inputLat').value);
    const lng = parseFloat(document.getElementById('inputLon').value);
    if (!isNaN(lat)) document.getElementById('inputLat').classList.add('has-value');
    if (!isNaN(lng)) document.getElementById('inputLon').classList.add('has-value');
}

function onCoordChange() {
    const lat = parseFloat(document.getElementById('inputLat').value);
    const lng = parseFloat(document.getElementById('inputLon').value);
    if (isNaN(lat) || isNaN(lng)) return;

    // Validasi kasar batas Bantul sebelum mengirim ke setLocation
    if (lat < -8.2 || lat > -7.6 || lng < 110.1 || lng > 110.7) {
        const ex = document.getElementById('coordErrorMsg');
        if (ex) ex.remove();
        const el = document.createElement('div');
        el.id = 'coordErrorMsg';
        el.style.cssText = 'color:#ef4444;font-size:12px;font-weight:600;padding:6px 10px;' +
                           'background:#fee2e2;border-radius:8px;margin-top:8px;';
        el.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Koordinat di luar batas wilayah Bantul';
        document.querySelector('.coord-row').after(el);
        setTimeout(() => { if (el.parentNode) el.remove(); }, 5000);
        return;
    }
    setLocation(lat, lng, 'manual');
}

// ══════════════════════════════════════════════════════════════════════
//  GPS
// ══════════════════════════════════════════════════════════════════════
function detectGPS() {
    if (!navigator.geolocation) {
        alert('❌ Browser tidak mendukung Geolocation!');
        return;
    }
    const btn  = document.getElementById('btnGps');
    const icon = document.getElementById('gpsIcon');
    const text = document.getElementById('gpsText');
    btn.classList.add('loading');
    icon.className = 'fas fa-spinner fa-spin';
    text.textContent = 'Mendeteksi...';

    navigator.geolocation.getCurrentPosition(
        pos => {
            setLocation(pos.coords.latitude, pos.coords.longitude, 'gps');
            btn.classList.remove('loading'); btn.classList.add('success');
            icon.className = 'fas fa-check-circle'; text.textContent = 'GPS Aktif';
            setTimeout(() => {
                btn.classList.remove('success');
                icon.className = 'fas fa-crosshairs';
                text.textContent = 'Deteksi GPS';
            }, 3000);
        },
        err => {
            btn.classList.remove('loading');
            icon.className = 'fas fa-crosshairs';
            text.textContent = 'Deteksi GPS';
            const msgs = [
                'Izin akses lokasi ditolak.\nAktifkan izin lokasi di address bar.',
                'Lokasi tidak tersedia. Pastikan GPS aktif.',
                'Waktu habis. Coba lagi.'
            ];
            alert('❌ Gagal Mendeteksi Lokasi\n\n' + (msgs[err.code - 1] || 'Terjadi kesalahan.'));
        },
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );
}

// ══════════════════════════════════════════════════════════════════════
//  FOTO PREVIEW
// ══════════════════════════════════════════════════════════════════════
let fotoSelected = false;

function checkCanSubmit() {
    document.getElementById('btnSubmit').disabled = !(locationSet && fotoSelected);
}

document.getElementById('fotoInput').addEventListener('change', function () {
    const files           = Array.from(this.files);
    const previewContainer = document.getElementById('previewContainer');
    const previewGrid      = document.getElementById('fotoPreviewGrid');
    previewContainer.innerHTML = '';

    if (files.length === 0) {
        previewGrid.style.display = 'none';
        fotoSelected = false;
        checkCanSubmit();
        return;
    }
    if (files.length > 3) alert('⚠️ Maksimal 3 foto!\nHanya 3 foto pertama yang digunakan.');

    const allowed = files.slice(0, 3);
    let valid = true;
    allowed.forEach((file, i) => {
        if (file.size > 2 * 1024 * 1024) {
            alert(`⚠️ Foto ${i + 1} melebihi 2MB!`); valid = false; return;
        }
        const reader = new FileReader();
        reader.onload = e2 => {
            const wrapper = document.createElement('div');
            wrapper.style.cssText = 'position:relative;display:inline-block;';
            const img = document.createElement('img');
            img.src = e2.target.result;
            img.style.cssText = 'width:90px;height:90px;object-fit:cover;border-radius:10px;' +
                                'border:2px solid #0891b2;box-shadow:0 4px 12px rgba(8,145,178,0.2);cursor:pointer;';
            img.onclick = () => {
                const ov = document.createElement('div');
                ov.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;' +
                                   'background:rgba(0,0,0,0.9);z-index:99999;display:flex;' +
                                   'align-items:center;justify-content:center;cursor:pointer;';
                const bi = document.createElement('img');
                bi.src = e2.target.result;
                bi.style.cssText = 'max-width:90%;max-height:90vh;border-radius:12px;';
                ov.appendChild(bi);
                ov.onclick = () => document.body.removeChild(ov);
                document.body.appendChild(ov);
            };
            const badge = document.createElement('span');
            badge.textContent = `Foto ${i + 1}`;
            badge.style.cssText = 'position:absolute;bottom:4px;left:50%;transform:translateX(-50%);' +
                                  'background:rgba(8,145,178,0.85);color:white;font-size:9px;font-weight:700;' +
                                  'padding:1px 6px;border-radius:4px;white-space:nowrap;';
            wrapper.appendChild(img); wrapper.appendChild(badge);
            previewContainer.appendChild(wrapper);
        };
        reader.readAsDataURL(file);
    });

    if (valid) { fotoSelected = true; previewGrid.style.display = 'block'; }
    checkCanSubmit();
});

// ══════════════════════════════════════════════════════════════════════
//  SUBMIT VALIDATION
// ══════════════════════════════════════════════════════════════════════
document.getElementById('formLaporan').addEventListener('submit', function (e) {
    if (!locationSet) {
        e.preventDefault();
        alert('❌ Lokasi belum ditentukan!\n\nKlik GPS, klik peta, atau masukkan koordinat manual.');
        document.getElementById('locationMap').scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
    }
    if (!fotoSelected) {
        e.preventDefault();
        alert('❌ Foto belum diupload!\n\nUpload minimal 1 foto sebagai bukti kejadian banjir.');
        document.getElementById('fotoInput').scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
    }
    const kecVal = document.getElementById('selectKecamatan').value;
    if (!kecVal) {
        e.preventDefault();
        alert('❌ Kecamatan belum dipilih!\n\nPilih titik di dalam wilayah Kabupaten Bantul, atau pilih kecamatan secara manual.');
        document.getElementById('selectKecamatan').scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
    }
    return confirm('✓ Konfirmasi Pengiriman\n\nApakah data sudah benar?\nLaporan akan dikirim ke BPBD Bantul untuk diverifikasi.');
});
</script>
@endsection
