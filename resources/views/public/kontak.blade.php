@extends('layouts.public')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .kontak-hero {
        background: linear-gradient(135deg, #0c4a6e 0%, #0891b2 50%, #06b6d4 100%);
        color: white;
        padding: 4rem 0;
        margin-bottom: 3rem;
        position: relative;
        overflow: hidden;
    }
    .kontak-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.3;
    }
    .kontak-hero h1 { position: relative; z-index: 2; font-weight: 900; font-size: 2.8rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); }
    .kontak-hero p  { position: relative; z-index: 2; font-size: 1.2rem; opacity: 0.95; }

    /* ── EMERGENCY BOX ── */
    .emergency-box {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        border-radius: 25px;
        padding: 3rem;
        text-align: center;
        margin-bottom: 4rem;
        box-shadow: 0 20px 60px rgba(239,68,68,0.3);
        position: relative;
        overflow: hidden;
        border: 3px solid rgba(255,255,255,0.2);
        text-decoration: none;
        display: block;
        transition: all 0.3s ease;
    }
    .emergency-box:hover { transform: translateY(-4px); box-shadow: 0 28px 70px rgba(239,68,68,0.45); color: white; }
    .emergency-box::before {
        content: '';
        position: absolute;
        top: -50%; right: -50%;
        width: 200%; height: 200%;
        background: repeating-linear-gradient(45deg,transparent,transparent 10px,rgba(255,255,255,0.05) 10px,rgba(255,255,255,0.05) 20px);
        animation: emergencyPattern 20s linear infinite;
    }
    @keyframes emergencyPattern { 0%{transform:translate(0,0)} 100%{transform:translate(50px,50px)} }
    .emergency-box h3 { position:relative;z-index:2;font-weight:900;font-size:1.8rem;margin-bottom:1.5rem;display:flex;align-items:center;justify-content:center;gap:1rem; }
    .emergency-number { position:relative;z-index:2;font-size:4.5rem;font-weight:900;margin:1.5rem 0;text-shadow:3px 3px 6px rgba(0,0,0,0.3);animation:pulse-number 2s ease-in-out infinite; }
    @keyframes pulse-number { 0%,100%{transform:scale(1)} 50%{transform:scale(1.05)} }
    .emergency-box p { position:relative;z-index:2;font-size:1.15rem;margin:0;font-weight:600; }
    .emergency-call-badge { position:relative;z-index:2;display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.2);border:2px solid rgba(255,255,255,0.4);border-radius:50px;padding:10px 24px;margin-top:1rem;font-weight:700;font-size:1rem;backdrop-filter:blur(10px); }

    /* ── CONTACT CARDS ── */
    .contact-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem 2rem;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
        transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
        height: 100%;
        border: 2px solid transparent;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .contact-card:hover { transform:translateY(-10px);box-shadow:0 20px 60px rgba(8,145,178,0.2);border-color:#0891b2; }
    .contact-icon { width:90px;height:90px;background:linear-gradient(135deg,#0891b2,#06b6d4);color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2.25rem;margin:0 auto 2rem;box-shadow:0 10px 30px rgba(8,145,178,0.3);transition:all 0.3s ease;border:4px solid rgba(8,145,178,0.1); }
    .contact-card:hover .contact-icon { transform:scale(1.1) rotate(5deg);box-shadow:0 15px 40px rgba(8,145,178,0.5); }
    .contact-title { font-size:1.5rem;font-weight:800;color:#0c4a6e;margin-bottom:1.5rem;text-align:center; }
    .contact-info { text-align:center;color:#64748b;line-height:1.8;width:100%; }
    .contact-info p { margin-bottom:1.25rem;font-size:1rem; }
    .contact-info strong { color:#0c4a6e;font-weight:700; }
    .contact-info a { color:#0891b2;text-decoration:none;font-weight:600; }
    .contact-info a:hover { text-decoration:underline; }
    .contact-action-btn { display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#0891b2,#06b6d4);color:white;padding:8px 18px;border-radius:50px;font-weight:700;font-size:13px;text-decoration:none;margin-top:8px;transition:all 0.3s;box-shadow:0 4px 15px rgba(8,145,178,0.3); }
    .contact-action-btn:hover { transform:translateY(-2px);box-shadow:0 8px 25px rgba(8,145,178,0.5);color:white; }

    /* ── SOCIAL LINKS ── */
    .social-links { display:flex;gap:10px;justify-content:center;flex-wrap:wrap;margin-top:12px; }
    .social-link {
        width:44px;height:44px;border-radius:50%;
        display:flex;align-items:center;justify-content:center;
        font-size:1.1rem;transition:all 0.3s ease;
        text-decoration:none;color:white;
        box-shadow:0 4px 15px rgba(0,0,0,0.15);
    }
    .social-link:hover { transform:translateY(-4px) scale(1.1);box-shadow:0 8px 25px rgba(0,0,0,0.25);color:white; }
    .sl-fb   { background:linear-gradient(135deg,#1877f2,#0d65d9); }
    .sl-ig   { background:linear-gradient(135deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888); }
    .sl-wa   { background:linear-gradient(135deg,#25d366,#128c7e); }
    .sl-yt   { background:linear-gradient(135deg,#ff0000,#cc0000); }

    /* ── MAP ── */
    .map-section { background:white;border-radius:25px;padding:2.5rem;box-shadow:0 15px 50px rgba(0,0,0,0.08);margin-bottom:4rem;border:2px solid rgba(8,145,178,0.1); }
    .map-section h3 { color:#0c4a6e;font-weight:900;font-size:2rem;margin-bottom:2rem;text-align:center;display:flex;align-items:center;justify-content:center;gap:1rem; }
    #map-kontak { height:450px;border-radius:20px;box-shadow:0 10px 30px rgba(0,0,0,0.15);border:3px solid rgba(8,145,178,0.2); }

    /* ── EMERGENCY NUMBERS (simpel & minimalis) ── */
    .emergency-numbers-section { margin-bottom:4rem; }
    .emergency-numbers-title { color:#0c4a6e;font-weight:900;font-size:1.8rem;margin-bottom:2rem;text-align:center;display:flex;align-items:center;justify-content:center;gap:0.75rem; }
    .emg-grid { display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px; }
    .emg-card {
        display:flex;flex-direction:column;align-items:center;justify-content:center;
        gap:6px;padding:1.5rem 1rem;border-radius:16px;
        text-decoration:none;transition:all 0.25s ease;
        border:2px solid transparent;
    }
    .emg-card:hover { transform:translateY(-4px);box-shadow:0 12px 30px rgba(0,0,0,0.12); }
    .emg-icon { font-size:2rem;margin-bottom:4px; }
    .emg-name { font-size:0.85rem;font-weight:700;color:#64748b;letter-spacing:0.5px;text-transform:uppercase; }
    .emg-num  { font-size:2.2rem;font-weight:900;line-height:1; }
    .emg-cta  { font-size:11px;font-weight:600;display:flex;align-items:center;gap:4px;padding:4px 12px;border-radius:50px;margin-top:4px; }

    .emg-ambulans  { background:#fff5f5;border-color:#fecaca; }
    .emg-ambulans:hover { background:#fee2e2;border-color:#ef4444; }
    .emg-ambulans .emg-num  { color:#dc2626; }
    .emg-ambulans .emg-cta  { background:#fca5a5;color:#7f1d1d; }

    .emg-damkar { background:#fffbeb;border-color:#fed7aa; }
    .emg-damkar:hover { background:#ffedd5;border-color:#f97316; }
    .emg-damkar .emg-num  { color:#ea580c; }
    .emg-damkar .emg-cta  { background:#fdba74;color:#7c2d12; }

    .emg-polisi { background:#eff6ff;border-color:#bfdbfe; }
    .emg-polisi:hover { background:#dbeafe;border-color:#3b82f6; }
    .emg-polisi .emg-num  { color:#1d4ed8; }
    .emg-polisi .emg-cta  { background:#93c5fd;color:#1e3a8a; }

    .emg-sar { background:#f0fdf4;border-color:#bbf7d0; }
    .emg-sar:hover { background:#dcfce7;border-color:#22c55e; }
    .emg-sar .emg-num  { color:#16a34a; }
    .emg-sar .emg-cta  { background:#86efac;color:#14532d; }

    .emg-bpbd { background:#fdf4ff;border-color:#e9d5ff; }
    .emg-bpbd:hover { background:#f3e8ff;border-color:#a855f7; }
    .emg-bpbd .emg-num  { color:#7c3aed; }
    .emg-bpbd .emg-cta  { background:#c4b5fd;color:#3b0764; }

    /* ── INFO BOX ── */
    .info-box { background:linear-gradient(135deg,#0891b2,#06b6d4);border-radius:25px;padding:3rem;color:white;box-shadow:0 20px 60px rgba(8,145,178,0.3);position:relative;overflow:hidden;border:3px solid rgba(255,255,255,0.2); }
    .info-box::before { content:'';position:absolute;top:0;left:0;right:0;bottom:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); }
    .info-box-icon { font-size:5rem;opacity:0.2;position:relative;z-index:1; }
    .info-box-content { position:relative;z-index:2; }
    .info-box h5 { font-weight:900;font-size:1.75rem;margin-bottom:1.25rem; }
    .info-box p  { font-size:1.1rem;margin:0;opacity:0.95;line-height:1.8; }

    @media(max-width:991px){
        .kontak-hero h1{font-size:2.2rem;}
        .emergency-number{font-size:3.5rem;}
        #map-kontak{height:350px;}
        .info-box{text-align:center;}
    }
</style>
@endsection

@section('content')
<!-- Hero -->
<div class="kontak-hero">
    <div class="container">
        <h1 class="text-center mb-3"><i class="fas fa-phone-alt"></i> Kontak Bantuan</h1>
        <p class="text-center lead">Hubungi Kami untuk Informasi dan Bantuan Darurat Banjir</p>
    </div>
</div>

<div class="container mb-5">

    {{-- ── HOTLINE DARURAT (bisa diklik untuk telepon) ── --}}
    <a href="tel:119" class="emergency-box">
        <h3><i class="fas fa-ambulance"></i> HOTLINE DARURAT BPBD BANTUL</h3>
        <div class="emergency-number">119</div>
        <p>Siap Melayani 24 Jam Non-Stop</p>
        <div class="emergency-call-badge">
            <i class="fas fa-phone-alt"></i> Ketuk untuk Menelepon Sekarang
        </div>
    </a>

    {{-- ── 3 CONTACT CARDS ── --}}
    <div class="row mb-5 g-4">

        {{-- Card 1: Alamat → klik ke GMaps --}}
        <div class="col-lg-4 col-md-6">
            <a href="https://www.google.com/maps/dir/?api=1&destination=-7.9083,110.3686" target="_blank" class="contact-card">
                <div class="contact-icon"><i class="fas fa-building"></i></div>
                <h3 class="contact-title">Kantor BPBD Bantul</h3>
                <div class="contact-info">
                    <p>
                        <i class="fas fa-map-marker-alt text-primary"></i><br>
                        <strong>Alamat:</strong><br>
                        Jl. Lingkar Timur, Manding, Trirenggo<br>
                        Bantul, Yogyakarta 55714
                    </p>
                    <p class="mb-0">
                        <i class="far fa-clock text-success"></i><br>
                        <strong>Jam Operasional:</strong><br>
                        Senin - Jumat: 08.00 - 16.00 WIB
                    </p>
                    <div class="contact-action-btn" style="margin-top:16px;">
                        <i class="fas fa-directions"></i> Buka di Google Maps
                    </div>
                </div>
            </a>
        </div>

        {{-- Card 2: Telepon → klik untuk call --}}
        <div class="col-lg-4 col-md-6">
            <a href="tel:+62274367319" class="contact-card">
                <div class="contact-icon" style="background:linear-gradient(135deg,#f093fb,#f5576c);">
                    <i class="fas fa-phone"></i>
                </div>
                <h3 class="contact-title">Telepon & Fax</h3>
                <div class="contact-info">
                    <p>
                        <i class="fas fa-phone text-success"></i><br>
                        <strong>Telepon:</strong><br>
                        <span style="color:#0891b2;font-weight:700;">(0274) 367319</span>
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-fax text-info"></i><br>
                        <strong>Fax:</strong><br>
                        (0274) 367320
                    </p>
                    <div class="contact-action-btn" style="background:linear-gradient(135deg,#f093fb,#f5576c);">
                        <i class="fas fa-phone-alt"></i> Telepon Sekarang
                    </div>
                </div>
            </a>
        </div>

        {{-- Card 3: Email & Sosmed --}}
        <div class="col-lg-4 col-md-6">
            <div class="contact-card" style="cursor:default;">
                <div class="contact-icon" style="background:linear-gradient(135deg,#4facfe,#00f2fe);">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3 class="contact-title">Email & Media Sosial</h3>
                <div class="contact-info">
                    <p>
                        <i class="fas fa-envelope text-danger"></i><br>
                        <strong>Email:</strong><br>
                        <a href="mailto:bpbd@bantulkab.go.id">bpbd@bantulkab.go.id</a>
                    </p>
                    <p style="font-weight:700;color:#0c4a6e;margin-bottom:8px;">Ikuti Kami:</p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/bpbdbantul" target="_blank" class="social-link sl-fb" title="Facebook BPBD Bantul">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://www.instagram.com/bpbdbantul" target="_blank" class="social-link sl-ig" title="Instagram BPBD Bantul">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://wa.me/6287834755177" target="_blank" class="social-link sl-wa" title="WhatsApp BPBD Bantul">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="https://www.youtube.com/@bpbdbantul" target="_blank" class="social-link sl-yt" title="YouTube BPBD Bantul">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── PETA LOKASI ── --}}
    <div class="map-section">
        <h3><i class="fas fa-map-marked-alt"></i> Lokasi Kantor BPBD Bantul</h3>
        <div id="map-kontak"></div>
    </div>

    {{-- ── KONTAK DARURAT LAINNYA (simpel & minimalis) ── --}}
    <div class="emergency-numbers-section">
        <h3 class="emergency-numbers-title">
            <i class="fas fa-phone-volume"></i> Kontak Darurat Lainnya
        </h3>
        <div class="emg-grid">
            <a href="tel:118" class="emg-card emg-ambulans">
                <div class="emg-icon">🚑</div>
                <div class="emg-name">Ambulans</div>
                <div class="emg-num">118</div>
                <div class="emg-cta"><i class="fas fa-phone-alt"></i> Telepon</div>
            </a>
            <a href="tel:113" class="emg-card emg-damkar">
                <div class="emg-icon">🚒</div>
                <div class="emg-name">Pemadam Kebakaran</div>
                <div class="emg-num">113</div>
                <div class="emg-cta"><i class="fas fa-phone-alt"></i> Telepon</div>
            </a>
            <a href="tel:110" class="emg-card emg-polisi">
                <div class="emg-icon">🚓</div>
                <div class="emg-name">Polisi</div>
                <div class="emg-num">110</div>
                <div class="emg-cta"><i class="fas fa-phone-alt"></i> Telepon</div>
            </a>
            <a href="tel:115" class="emg-card emg-sar">
                <div class="emg-icon">🛟</div>
                <div class="emg-name">SAR Nasional</div>
                <div class="emg-num">115</div>
                <div class="emg-cta"><i class="fas fa-phone-alt"></i> Telepon</div>
            </a>
            <a href="tel:119" class="emg-card emg-bpbd">
                <div class="emg-icon">🏥</div>
                <div class="emg-name">BPBD / Darurat</div>
                <div class="emg-num">119</div>
                <div class="emg-cta"><i class="fas fa-phone-alt"></i> Telepon</div>
            </a>
        </div>
    </div>

    {{-- ── INFO BOX ── --}}
    <div class="info-box">
        <div class="row align-items-center">
            <div class="col-md-2 text-center mb-4 mb-md-0">
                <div class="info-box-icon"><i class="fas fa-exclamation-circle"></i></div>
            </div>
            <div class="col-md-10">
                <div class="info-box-content">
                    <h5><i class="fas fa-info-circle"></i> Peringatan Penting</h5>
                    <p>
                        Dalam kondisi darurat banjir, <strong>segera hubungi nomor 119 atau 112</strong> untuk bantuan evakuasi.
                        Jangan menunda untuk meminta bantuan jika air terus naik dan mengancam keselamatan Anda dan keluarga.
                        Tetap tenang, ikuti instruksi petugas, dan prioritaskan keselamatan!
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── FORM KIRIM PESAN ── --}}
    <div style="background:white;border-radius:25px;padding:2.5rem;box-shadow:0 15px 50px rgba(0,0,0,0.08);margin-top:3rem;border:2px solid rgba(8,145,178,0.1);">
        <div style="text-align:center;margin-bottom:2rem;">
            <h3 style="color:#0c4a6e;font-weight:900;font-size:1.9rem;display:flex;align-items:center;justify-content:center;gap:0.75rem;">
                <i class="fas fa-comment-dots" style="color:#0891b2;"></i> Kirim Pesan / Konsultasi
            </h3>
            <p style="color:#64748b;font-size:1.05rem;">Punya pertanyaan, saran, atau perlu konsultasi seputar banjir? Isi form di bawah — pesan akan langsung dikirim via WhatsApp BPBD.</p>
        </div>
        <div id="pesanSuccess" style="display:none;background:linear-gradient(135deg,#d1fae5,#a7f3d0);border-left:5px solid #10b981;border-radius:12px;padding:1rem 1.5rem;margin-bottom:1.5rem;color:#065f46;font-weight:700;align-items:center;gap:10px;">
            <i class="fas fa-check-circle fa-lg"></i>
            <span>Pesan berhasil disiapkan! WhatsApp akan terbuka — silakan kirim pesannya.</span>
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <label style="font-weight:700;color:#334155;margin-bottom:6px;display:block;">Nama Lengkap <span style="color:#ef4444;">*</span></label>
                <input type="text" id="pesanNama" placeholder="Masukkan nama Anda"
                       style="width:100%;border:2px solid #e2e8f0;border-radius:10px;padding:.85rem 1.2rem;font-size:1rem;outline:none;transition:all .3s;font-family:inherit;"
                       onfocus="this.style.borderColor='#0891b2'" onblur="this.style.borderColor='#e2e8f0'">
            </div>
            <div class="col-md-6">
                <label style="font-weight:700;color:#334155;margin-bottom:6px;display:block;">No. Telepon / WhatsApp <span style="color:#ef4444;">*</span></label>
                <input type="text" id="pesanKontak" placeholder="08xxxxxxxxxx"
                       style="width:100%;border:2px solid #e2e8f0;border-radius:10px;padding:.85rem 1.2rem;font-size:1rem;outline:none;transition:all .3s;font-family:inherit;"
                       onfocus="this.style.borderColor='#0891b2'" onblur="this.style.borderColor='#e2e8f0'">
            </div>
            <div class="col-12">
                <label style="font-weight:700;color:#334155;margin-bottom:6px;display:block;">Topik</label>
                <select id="pesanTopik" style="width:100%;border:2px solid #e2e8f0;border-radius:10px;padding:.85rem 1.2rem;font-size:1rem;outline:none;cursor:pointer;font-family:inherit;"
                        onfocus="this.style.borderColor='#0891b2'" onblur="this.style.borderColor='#e2e8f0'">
                    <option value="">-- Pilih Topik --</option>
                    <option>Cara melaporkan kejadian banjir</option>
                    <option>Informasi zona kerawanan banjir</option>
                    <option>Bantuan teknis penggunaan BANTARA</option>
                    <option>Saran dan masukan sistem</option>
                    <option>Konsultasi penanganan darurat banjir</option>
                    <option>Lainnya</option>
                </select>
            </div>
            <div class="col-12">
                <label style="font-weight:700;color:#334155;margin-bottom:6px;display:block;">Isi Pesan <span style="color:#ef4444;">*</span></label>
                <textarea id="pesanIsi" rows="4" placeholder="Tulis pertanyaan, saran, atau deskripsi masalah Anda..."
                          style="width:100%;border:2px solid #e2e8f0;border-radius:10px;padding:.85rem 1.2rem;font-size:1rem;outline:none;resize:vertical;min-height:120px;font-family:inherit;transition:all .3s;"
                          onfocus="this.style.borderColor='#0891b2'" onblur="this.style.borderColor='#e2e8f0'"></textarea>
            </div>
            <div class="col-12">
                <button onclick="kirimPesan()"
                        style="width:100%;background:linear-gradient(135deg,#0891b2,#06b6d4);color:white;border:none;padding:1.15rem;border-radius:12px;font-weight:800;font-size:1.1rem;cursor:pointer;transition:all .3s;box-shadow:0 8px 25px rgba(8,145,178,0.3);display:flex;align-items:center;justify-content:center;gap:10px;"
                        onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 35px rgba(8,145,178,0.5)'"
                        onmouseout="this.style.transform='';this.style.boxShadow='0 8px 25px rgba(8,145,178,0.3)'">
                    <i class="fab fa-whatsapp fa-lg"></i> Kirim via WhatsApp
                </button>
                <p style="text-align:center;font-size:12px;color:#94a3b8;margin-top:8px;">
                    <i class="fas fa-lock"></i> Pesan dikirim langsung ke WhatsApp BPBD Bantul — data Anda aman
                </p>
            </div>
        </div>
    </div>

</div>
@endsection

@section('script')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const BPBD_LAT = -7.9083, BPBD_LNG = 110.3686;
    var mapKontak = L.map('map-kontak', { zoomControl: false }).setView([BPBD_LAT, BPBD_LNG], 15);
    L.control.zoom({ position: 'bottomright' }).addTo(mapKontak);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(mapKontak);

    var bpbdIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
        iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
    });

    L.marker([BPBD_LAT, BPBD_LNG], { icon: bpbdIcon }).addTo(mapKontak)
        .bindPopup(`
            <div style="min-width:240px;padding:8px;">
                <h6 style="color:#0c4a6e;font-weight:900;margin-bottom:10px;">
                    <i class="fas fa-building" style="color:#0891b2;"></i> BPBD Kabupaten Bantul
                </h6>
                <p style="margin-bottom:8px;font-size:13px;color:#475569;line-height:1.6;">
                    <i class="fas fa-map-marker-alt" style="color:#ef4444;"></i>
                    Jl. Lingkar Timur, Manding, Trirenggo<br>Bantul, Yogyakarta 55714
                </p>
                <p style="margin-bottom:10px;font-size:13px;color:#475569;">
                    <i class="fas fa-phone" style="color:#10b981;"></i>
                    <a href="tel:+620274367319" style="color:#0891b2;font-weight:700;text-decoration:none;">(0274) 367319</a>
                </p>
                <div style="display:flex;gap:7px;flex-wrap:wrap;">
                    <a href="https://www.google.com/maps/dir/?api=1&destination=${BPBD_LAT},${BPBD_LNG}" target="_blank"
                       style="display:inline-flex;align-items:center;gap:5px;background:linear-gradient(135deg,#0891b2,#06b6d4);color:white;padding:7px 13px;border-radius:8px;font-weight:700;font-size:12px;text-decoration:none;">
                        <i class="fas fa-directions"></i> Google Maps
                    </a>
                    <a href="tel:+620274367319"
                       style="display:inline-flex;align-items:center;gap:5px;background:linear-gradient(135deg,#10b981,#059669);color:white;padding:7px 13px;border-radius:8px;font-weight:700;font-size:12px;text-decoration:none;">
                        <i class="fas fa-phone-alt"></i> Telepon
                    </a>
                </div>
            </div>
        `, { maxWidth: 280 })
        .openPopup();

    function kirimPesan() {
        const nama   = document.getElementById('pesanNama').value.trim();
        const kontak = document.getElementById('pesanKontak').value.trim();
        const topik  = document.getElementById('pesanTopik').value;
        const isi    = document.getElementById('pesanIsi').value.trim();
        if (!nama || !kontak || !isi) { alert('⚠️ Nama, nomor kontak, dan isi pesan wajib diisi.'); return; }
        const msg = `Halo BPBD Bantul \n\nSaya *${nama}* ingin menghubungi terkait: *${topik || 'Pertanyaan umum'}*\n\n${isi}\n\nKontak: ${kontak}`;
        window.open('https://wa.me/6287834755177?text=' + encodeURIComponent(msg), '_blank');
        document.getElementById('pesanSuccess').style.display = 'flex';
        ['pesanNama','pesanKontak','pesanIsi'].forEach(id => document.getElementById(id).value = '');
        document.getElementById('pesanTopik').selectedIndex = 0;
        setTimeout(() => document.getElementById('pesanSuccess').style.display = 'none', 6000);
    }

    document.addEventListener('DOMContentLoaded', () => {
        ['pesanNama','pesanKontak'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('keydown', e => { if (e.key === 'Enter') kirimPesan(); });
        });
    });
</script>
@endsection
