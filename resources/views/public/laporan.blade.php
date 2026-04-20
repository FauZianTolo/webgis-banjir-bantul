@extends('layouts.public')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .laporan-hero {
        background: linear-gradient(135deg, #0c4a6e 0%, #0891b2 50%, #06b6d4 100%);
        color: white; padding: 4rem 0; margin-bottom: 0;
        position: relative; overflow: hidden;
    }
    .laporan-hero::before {
        content: ''; position: absolute; top:0;left:0;right:0;bottom:0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.3;
    }
    .laporan-hero h1 { position:relative;z-index:2;font-weight:900;font-size:2.8rem;text-shadow:2px 2px 4px rgba(0,0,0,0.3); }
    .laporan-hero p  { position:relative;z-index:2;font-size:1.2rem;opacity:0.95; }

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

    /* Auto-detect badge */
    .auto-detect-badge { display:none; align-items:center; gap:6px; font-size:12px; font-weight:700; color:#059669; background:#d1fae5; border:1px solid #10b981; border-radius:8px; padding:4px 10px; margin-top:6px; }
    .auto-detect-badge.show { display:flex; }

    .form-container { background:white; border-radius:20px; padding:2.5rem; box-shadow:0 15px 50px rgba(0,0,0,0.08); border:2px solid rgba(8,145,178,0.1); }
    .form-container h4 { color:#0c4a6e; font-weight:800; font-size:1.8rem; margin-bottom:2rem; display:flex; align-items:center; gap:0.75rem; }
    .form-label { font-weight:700; color:#334155; margin-bottom:0.5rem; font-size:0.95rem; }
    .form-control, .form-select { border:2px solid #e2e8f0; border-radius:10px; padding:0.85rem 1.25rem; font-size:1rem; transition:all 0.3s; }
    .form-control:focus, .form-select:focus { border-color:#0891b2; box-shadow:0 0 0 4px rgba(8,145,178,0.1); }
    .form-control::placeholder { color:#94a3b8; }
    textarea.form-control { resize:vertical; min-height:120px; }

    .map-section { background:white; border-radius:16px; border:2px solid rgba(8,145,178,0.2); overflow:hidden; margin-bottom:1.5rem; box-shadow:0 8px 30px rgba(8,145,178,0.12); }
    .map-section-header { background:linear-gradient(135deg,#0891b2,#06b6d4); color:white; padding:1rem 1.5rem; display:flex; align-items:center; justify-content:space-between; }
    .map-section-header h6 { margin:0; font-weight:800; font-size:1rem; display:flex; align-items:center; gap:0.5rem; }
    .map-status-badge { padding:4px 12px; border-radius:20px; font-size:12px; font-weight:700; background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.4); color:white; transition:all 0.3s; }
    .map-status-badge.detected { background:rgba(16,185,129,0.3); border-color:#10b981; }
    #locationMap { height:400px; width:100%; position:relative; z-index:1; }
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
    .leaflet-tooltip { background:rgba(12,74,110,0.9); color:white; border:none; border-radius:6px; font-weight:700; font-size:11px; padding:4px 8px; }
    .leaflet-tooltip::before { border-top-color:rgba(12,74,110,0.9); }

    @media (max-width:767px) {
        #locationMap { height:300px; }
        .coord-row { grid-template-columns:1fr; }
        .laporan-hero h1 { font-size:2rem; }
        .form-container { padding:1.5rem; }
        .info-top-inner { flex-direction:column; }
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
                    <li>Kecamatan & desa akan <strong>otomatis terisi</strong> sesuai lokasi yang dipilih</li>
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
                    <h6><i class="fas fa-map-pin"></i> Klik peta atau geser marker — kecamatan & desa otomatis terdeteksi</h6>
                    <span class="map-status-badge" id="mapStatusBadge">Belum dipilih</span>
                </div>
                <div style="position:relative;">
                    <div id="locationMap"></div>
                    <div class="map-instruction" id="mapInstruction">
                        <i class="fas fa-hand-pointer"></i> Klik peta atau geser marker
                    </div>
                    <button type="button" class="btn-gps" id="btnGps" onclick="detectGPS()">
                        <i class="fas fa-crosshairs" id="gpsIcon"></i>
                        <span id="gpsText">Deteksi GPS</span>
                    </button>
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
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Desa / Kelurahan</label>
                    <select name="desa" id="selectDesa" class="form-select" disabled>
                        <option value="">-- Pilih Kecamatan dulu --</option>
                    </select>
                    <div class="auto-detect-badge" id="desaAutoBadge">
                        <i class="fas fa-magic"></i> <span id="desaAutoText">Terdeteksi otomatis dari peta</span>
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
// ── KELURAHAN DATA (fallback jika GeoJSON belum load) ─────────────────
const kelurahanData = {
    'Banguntapan': ['Banguntapan','Baturetno','Jagalan','Jambidan','Potorono','Singosaren','Tamanan','Wirokerten'],
    'Bantul':      ['Bantul','Palbapang','Ringinharjo','Sabdodadi','Trirenggo'],
    'Bambanglipuro':['Mulyodadi','Sidomulyo','Sumbermulyo'],
    'Dlingo':      ['Dlingo','Jatimulyo','Mangunan','Muntuk','Temuwuh','Terong'],
    'Imogiri':     ['Girirejo','Imogiri','Karangtalun','Karangtengah','Kebonagung','Selopamioro','Sriharjo','Wukirsari'],
    'Jetis':       ['Canden','Patalan','Sumberagung','Trimulyo'],
    'Kasihan':     ['Bangunjiwo','Ngestiharjo','Tamantirto','Tirtonirmolo'],
    'Kretek':      ['Donotirto','Parangtritis','Tirtohargo','Tirtomulyo','Tirtosari'],
    'Pajangan':    ['Guwosari','Sendangsari','Triwidadi'],
    'Pandak':      ['Caturharjo','Gilangharjo','Triharjo','Wijirejo'],
    'Piyungan':    ['Sitimulyo','Srimartani','Srimulyo'],
    'Pleret':      ['Bawuran','Pleret','Segoroyoso','Wonokromo','Wonolelo'],
    'Pundong':     ['Panjangrejo','Seloharjo','Srihardono'],
    'Sanden':      ['Gadingharjo','Gadingsari','Murtigading','Srigading'],
    'Sedayu':      ['Argodadi','Argorejo','Argomulyo','Argosari'],
    'Sewon':       ['Bangunharjo','Panggungharjo','Pendowoharjo','Timbulharjo'],
    'Srandakan':   ['Poncosari','Trimurti'],
};

// ── VARIABEL GLOBAL ────────────────────────────────────────────────────
let map, marker, locationSet = false;
let desaGeoJSON = null;   // GeoJSON level desa (bantuldesa.geojson)
let adminLayer  = null;
const BANTUL_CENTER = [-7.8700, 110.3300];

// ── INIT ───────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    // Isi desa dari old value jika ada
    const oldKec = '{{ old("kecamatan") }}';
    const oldDesa = '{{ old("desa") }}';
    if (oldKec) {
        populateDesaSelect(oldKec, oldDesa, false);
    }
    initMap();
});

// ── POPULATE DESA SELECT ───────────────────────────────────────────────
function populateDesaSelect(kecNama, selectedDesa, fromAuto) {
    const sel = document.getElementById('selectDesa');
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

    // Sembunyikan badge desa jika user pilih manual
    if (!fromAuto) {
        document.getElementById('desaAutoBadge').classList.remove('show');
    }
}

// ── EVENT: user ganti kecamatan manual ────────────────────────────────
function onKecamatanChange() {
    const kec = document.getElementById('selectKecamatan').value;
    document.getElementById('kecAutoBadge').classList.remove('show');
    document.getElementById('desaAutoBadge').classList.remove('show');
    populateDesaSelect(kec, '', false);
}

// ── RAY CASTING: point in polygon ─────────────────────────────────────
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

// ── DETEKSI DESA DAN KECAMATAN DARI TITIK ─────────────────────────────
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

// ── AUTO FILL KECAMATAN & DESA ─────────────────────────────────────────
function autoFillFromPoint(lat, lng) {
    const result = detectFromPoint(lat, lng);
    const kecBadge  = document.getElementById('kecAutoBadge');
    const kecText   = document.getElementById('kecAutoText');
    const desaBadge = document.getElementById('desaAutoBadge');
    const desaText  = document.getElementById('desaAutoText');
    const selectKec = document.getElementById('selectKecamatan');

    if (!result) {
        kecBadge.classList.remove('show');
        desaBadge.classList.remove('show');
        return;
    }

    const { kecamatan, desa } = result;

    // ── Isi kecamatan ──
    const opts = Array.from(selectKec.options);
    const matchKec = opts.find(o => o.value.toLowerCase() === kecamatan.toLowerCase());
    if (matchKec) {
        selectKec.value = matchKec.value;
        kecText.textContent = '✓ Terdeteksi: Kec. ' + matchKec.value;
        kecBadge.classList.add('show');

        // ── Isi desa ──
        populateDesaSelect(matchKec.value, desa, true);

        // Cari & pilih desa yang cocok setelah populate
        setTimeout(() => {
            const selDesa = document.getElementById('selectDesa');
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

// ── MAP INIT ──────────────────────────────────────────────────────────
function initMap() {
    map = L.map('locationMap', { center: BANTUL_CENTER, zoom: 11, zoomControl: true });
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap', maxZoom: 19
    }).addTo(map);

    const markerIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
        iconSize: [25,41], iconAnchor: [12,41], popupAnchor: [1,-34]
    });
    marker = L.marker(BANTUL_CENTER, { draggable: true, icon: markerIcon }).addTo(map);
    marker.bindPopup(buildPopupContent(BANTUL_CENTER[0], BANTUL_CENTER[1]));

    map.on('click', e => setLocation(e.latlng.lat, e.latlng.lng, 'map'));
    marker.on('dragend', () => {
        const p = marker.getLatLng();
        setLocation(p.lat, p.lng, 'drag');
    });

    // Load GeoJSON batas desa
    loadDesaGeoJSON();

    @if(old('latitude') && old('longitude'))
    setLocation({{ old('latitude') }}, {{ old('longitude') }}, 'restore');
    @endif
}

// ── LOAD GEOJSON BATAS DESA ───────────────────────────────────────────
function loadDesaGeoJSON() {
    fetch('/geojson/bantuldesa.geojson')
        .then(r => {
            if (!r.ok) throw new Error('GeoJSON tidak ditemukan');
            return r.json();
        })
        .then(data => {
            desaGeoJSON = data;
            adminLayer = L.geoJSON(data, {
                style: {
                    fillColor: 'rgba(8,145,178,0.04)',
                    color: '#1e3c72',
                    weight: 1.5,
                    opacity: 0.6,
                    dashArray: '4,4'
                },
                onEachFeature: (feature, layer) => {
                    const props = feature.properties;
                    const kec  = props.WADMKC || '';
                    const desa = props.WADMKD || props.NAMOBJ || '';
                    layer.bindTooltip(
                        `<strong>${desa}</strong><br><small>Kec. ${kec}</small>`,
                        { sticky: true, direction: 'top' }
                    );
                    layer.on('mouseover', function() {
                        this.setStyle({ fillColor:'rgba(8,145,178,0.15)', weight: 2 });
                    });
                    layer.on('mouseout', function() {
                        adminLayer.resetStyle(this);
                    });
                }
            }).addTo(map);
            console.log('✅ GeoJSON desa berhasil dimuat:', data.features.length, 'desa');
        })
        .catch(err => {
            console.warn('⚠️ GeoJSON desa tidak dimuat:', err.message);
        });
}

// ── SET LOCATION ───────────────────────────────────────────────────────
function setLocation(lat, lng, source) {
    lat = parseFloat(parseFloat(lat).toFixed(6));
    lng = parseFloat(parseFloat(lng).toFixed(6));

    document.getElementById('latitude').value  = lat;
    document.getElementById('longitude').value = lng;

    const iLat = document.getElementById('inputLat');
    const iLon = document.getElementById('inputLon');
    iLat.value = lat; iLon.value = lng;
    iLat.classList.add('has-value'); iLon.classList.add('has-value');

    marker.setLatLng([lat, lng]);
    marker.setPopupContent(buildPopupContent(lat, lng));

    if (source !== 'drag') map.flyTo([lat, lng], source === 'gps' ? 16 : 14, { duration: 1.2 });

    document.getElementById('infoLat').textContent = lat;
    document.getElementById('infoLon').textContent = lng;
    const pill = document.getElementById('coordStatusPill');
    pill.textContent = '✓ Lokasi Dipilih';
    pill.classList.add('ok');
    document.getElementById('mapStatusBadge').textContent = '✓ Lokasi Dipilih';
    document.getElementById('mapStatusBadge').classList.add('detected');

    const instr = document.getElementById('mapInstruction');
    instr.style.opacity = '0';
    setTimeout(() => instr.style.display = 'none', 400);

    locationSet = true;
    checkCanSubmit();

    // Auto-detect kecamatan & desa dari GeoJSON
    autoFillFromPoint(lat, lng);
}

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
    if (lat < -8.2 || lat > -7.6 || lng < 110.1 || lng > 110.7) {
        const ex = document.getElementById('coordErrorMsg');
        if (ex) ex.remove();
        const el = document.createElement('div');
        el.id = 'coordErrorMsg';
        el.style.cssText = 'color:#ef4444;font-size:12px;font-weight:600;padding:6px 10px;background:#fee2e2;border-radius:8px;margin-top:8px;';
        el.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Koordinat di luar wilayah Bantul';
        document.querySelector('.coord-row').after(el);
        setTimeout(() => el.remove(), 4000);
        return;
    }
    setLocation(lat, lng, 'manual');
}

function detectGPS() {
    if (!navigator.geolocation) { alert('❌ Browser tidak mendukung Geolocation!'); return; }
    const btn = document.getElementById('btnGps');
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
            setTimeout(() => { btn.classList.remove('success'); icon.className = 'fas fa-crosshairs'; text.textContent = 'Deteksi GPS'; }, 3000);
        },
        err => {
            btn.classList.remove('loading'); icon.className = 'fas fa-crosshairs'; text.textContent = 'Deteksi GPS';
            const msgs = ['Izin akses lokasi ditolak.\nAktifkan izin lokasi di address bar.','Lokasi tidak tersedia. Pastikan GPS aktif.','Waktu habis. Coba lagi.'];
            alert('❌ Gagal Mendeteksi Lokasi\n\n' + (msgs[err.code - 1] || 'Terjadi kesalahan.'));
        },
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );
}

// ── FOTO PREVIEW ───────────────────────────────────────────────────────
let fotoSelected = false;

function checkCanSubmit() {
    document.getElementById('btnSubmit').disabled = !(locationSet && fotoSelected);
}

document.getElementById('fotoInput').addEventListener('change', function () {
    const files = Array.from(this.files);
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
            alert(`⚠️ Foto ${i+1} melebihi 2MB!`); valid = false; return;
        }
        const reader = new FileReader();
        reader.onload = e2 => {
            const wrapper = document.createElement('div');
            wrapper.style.cssText = 'position:relative;display:inline-block;';
            const img = document.createElement('img');
            img.src = e2.target.result;
            img.style.cssText = 'width:90px;height:90px;object-fit:cover;border-radius:10px;border:2px solid #0891b2;box-shadow:0 4px 12px rgba(8,145,178,0.2);cursor:pointer;';
            img.onclick = () => {
                const ov = document.createElement('div');
                ov.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.9);z-index:99999;display:flex;align-items:center;justify-content:center;cursor:pointer;';
                const bi = document.createElement('img');
                bi.src = e2.target.result;
                bi.style.cssText = 'max-width:90%;max-height:90vh;border-radius:12px;';
                ov.appendChild(bi); ov.onclick = () => document.body.removeChild(ov);
                document.body.appendChild(ov);
            };
            const badge = document.createElement('span');
            badge.textContent = `Foto ${i+1}`;
            badge.style.cssText = 'position:absolute;bottom:4px;left:50%;transform:translateX(-50%);background:rgba(8,145,178,0.85);color:white;font-size:9px;font-weight:700;padding:1px 6px;border-radius:4px;white-space:nowrap;';
            wrapper.appendChild(img); wrapper.appendChild(badge);
            previewContainer.appendChild(wrapper);
        };
        reader.readAsDataURL(file);
    });

    if (valid) { fotoSelected = true; previewGrid.style.display = 'block'; }
    checkCanSubmit();
});

// ── SUBMIT VALIDATION ──────────────────────────────────────────────────
document.getElementById('formLaporan').addEventListener('submit', function (e) {
    if (!locationSet) {
        e.preventDefault();
        alert('❌ Lokasi belum ditentukan!\n\nKlik GPS, klik peta, atau masukkan koordinat manual.');
        document.getElementById('locationMap').scrollIntoView({ behavior:'smooth', block:'center' });
        return false;
    }
    if (!fotoSelected) {
        e.preventDefault();
        alert('❌ Foto belum diupload!\n\nUpload minimal 1 foto sebagai bukti kejadian banjir.');
        document.getElementById('fotoInput').scrollIntoView({ behavior:'smooth', block:'center' });
        return false;
    }
    return confirm('✓ Konfirmasi Pengiriman\n\nApakah data sudah benar?\nLaporan akan dikirim ke BPBD Bantul untuk diverifikasi.');
});
</script>
@endsection
