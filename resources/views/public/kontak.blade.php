@extends('layouts.public')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* ==================== HERO SECTION ==================== */
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
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.3;
    }

    .kontak-hero h1 {
        position: relative;
        z-index: 2;
        font-weight: 900;
        font-size: 2.8rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .kontak-hero p {
        position: relative;
        z-index: 2;
        font-size: 1.2rem;
        opacity: 0.95;
    }

    /* ==================== EMERGENCY BOX ==================== */
    .emergency-box {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        border-radius: 25px;
        padding: 3rem;
        text-align: center;
        margin-bottom: 4rem;
        box-shadow: 0 20px 60px rgba(239, 68, 68, 0.3);
        position: relative;
        overflow: hidden;
        border: 3px solid rgba(255, 255, 255, 0.2);
    }

    .emergency-box::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: repeating-linear-gradient(
            45deg,
            transparent,
            transparent 10px,
            rgba(255, 255, 255, 0.05) 10px,
            rgba(255, 255, 255, 0.05) 20px
        );
        animation: emergencyPattern 20s linear infinite;
    }

    @keyframes emergencyPattern {
        0% { transform: translate(0, 0); }
        100% { transform: translate(50px, 50px); }
    }

    .emergency-box h3 {
        position: relative;
        z-index: 2;
        font-weight: 900;
        font-size: 1.8rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
    }

    .emergency-number {
        position: relative;
        z-index: 2;
        font-size: 4.5rem;
        font-weight: 900;
        margin: 1.5rem 0;
        text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.3);
        animation: pulse-number 2s ease-in-out infinite;
    }

    @keyframes pulse-number {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .emergency-box p {
        position: relative;
        z-index: 2;
        font-size: 1.15rem;
        margin: 0;
        font-weight: 600;
    }

    /* ==================== CONTACT CARDS ==================== */
    .contact-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem 2rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        border: 2px solid transparent;
    }

    .contact-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 60px rgba(8, 145, 178, 0.2);
        border-color: #0891b2;
    }

    .contact-icon {
        width: 90px;
        height: 90px;
        background: linear-gradient(135deg, #0891b2, #06b6d4);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.25rem;
        margin: 0 auto 2rem;
        box-shadow: 0 10px 30px rgba(8, 145, 178, 0.3);
        transition: all 0.3s ease;
        border: 4px solid rgba(8, 145, 178, 0.1);
    }

    .contact-card:hover .contact-icon {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 15px 40px rgba(8, 145, 178, 0.5);
    }

    .contact-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0c4a6e;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .contact-info {
        text-align: center;
        color: #64748b;
        line-height: 1.8;
    }

    .contact-info p {
        margin-bottom: 1.25rem;
        font-size: 1rem;
    }

    .contact-info strong {
        color: #0c4a6e;
        font-weight: 700;
    }

    .contact-info i {
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
    }

    /* ==================== MAP SECTION ==================== */
    .map-section {
        background: white;
        border-radius: 25px;
        padding: 2.5rem;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.08);
        margin-bottom: 4rem;
        border: 2px solid rgba(8, 145, 178, 0.1);
    }

    .map-section h3 {
        color: #0c4a6e;
        font-weight: 900;
        font-size: 2rem;
        margin-bottom: 2rem;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
    }

    #map-kontak {
        height: 450px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        border: 3px solid rgba(8, 145, 178, 0.2);
    }

    /* ==================== EMERGENCY NUMBERS ==================== */
    .emergency-numbers-section {
        background: white;
        border-radius: 25px;
        padding: 2.5rem;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.08);
        margin-bottom: 4rem;
        border: 2px solid rgba(8, 145, 178, 0.1);
    }

    .emergency-numbers-title {
        color: #0c4a6e;
        font-weight: 900;
        font-size: 2rem;
        margin-bottom: 2.5rem;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
    }

    .emergency-number-card {
        background: linear-gradient(135deg, #f8fafc, #f0f9ff);
        border-radius: 15px;
        padding: 2rem 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        border: 2px solid #e2e8f0;
        height: 100%;
    }

    .emergency-number-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        border-color: #0891b2;
        background: linear-gradient(135deg, #ffffff, #e0f2fe);
    }

    .emergency-number-card i {
        font-size: 2.75rem;
        margin-bottom: 1.25rem;
        filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.1));
    }

    .emergency-number-card h6 {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0c4a6e;
        margin-bottom: 0.75rem;
    }

    .emergency-number-card strong {
        font-size: 1.75rem;
        font-weight: 900;
        color: #0891b2;
    }

    /* ==================== INFO BOX ==================== */
    .info-box {
        background: linear-gradient(135deg, #0891b2, #06b6d4);
        border-radius: 25px;
        padding: 3rem;
        color: white;
        box-shadow: 0 20px 60px rgba(8, 145, 178, 0.3);
        position: relative;
        overflow: hidden;
        border: 3px solid rgba(255, 255, 255, 0.2);
    }

    .info-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .info-box-icon {
        font-size: 5rem;
        opacity: 0.2;
        position: relative;
        z-index: 1;
    }

    .info-box-content {
        position: relative;
        z-index: 2;
    }

    .info-box h5 {
        font-weight: 900;
        font-size: 1.75rem;
        margin-bottom: 1.25rem;
    }

    .info-box p {
        font-size: 1.1rem;
        margin: 0;
        opacity: 0.95;
        line-height: 1.8;
    }

    /* ==================== SOCIAL MEDIA ==================== */
    .social-links {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .social-link {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .social-link:hover {
        transform: translateY(-5px) scale(1.1);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    /* ==================== RESPONSIVE ==================== */
    @media (max-width: 991px) {
        .kontak-hero h1 {
            font-size: 2.2rem;
        }

        .emergency-number {
            font-size: 3.5rem;
        }

        #map-kontak {
            height: 350px;
        }

        .info-box {
            text-align: center;
        }

        .info-box-icon {
            margin-bottom: 1.5rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<div class="kontak-hero">
    <div class="container">
        <h1 class="text-center mb-3">
            <i class="fas fa-phone-alt"></i> Kontak Bantuan
        </h1>
        <p class="text-center lead">
            Hubungi Kami untuk Informasi dan Bantuan Darurat Banjir
        </p>
    </div>
</div>

<div class="container mb-5">

    <!-- Emergency Hotline -->
    <div class="emergency-box">
        <h3>
            <i class="fas fa-ambulance"></i> HOTLINE DARURAT BPBD BANTUL
        </h3>
        <div class="emergency-number">119</div>
        <p>Siap Melayani 24 Jam Non-Stop</p>
    </div>

    <!-- Contact Cards -->
    <div class="row mb-5 g-4">
        <div class="col-lg-4 col-md-6">
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-building"></i>
                </div>
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
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="contact-card">
                <div class="contact-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                    <i class="fas fa-phone"></i>
                </div>
                <h3 class="contact-title">Telepon & Fax</h3>
                <div class="contact-info">
                    <p>
                        <i class="fas fa-phone text-success"></i><br>
                        <strong>Telepon:</strong><br>
                        <a href="tel:+62274367319" style="color: #0891b2; text-decoration: none; font-weight: 600;">
                            (0274) 367319
                        </a>
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-fax text-info"></i><br>
                        <strong>Fax:</strong><br>
                        (0274) 367320
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="contact-card">
                <div class="contact-icon" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3 class="contact-title">Email & Media Sosial</h3>
                <div class="contact-info">
                    <p>
                        <i class="fas fa-envelope text-danger"></i><br>
                        <strong>Email:</strong><br>
                        <a href="mailto:bpbd@bantulkab.go.id" style="color: #0891b2; text-decoration: none; font-weight: 600;">
                            bpbd@bantulkab.go.id
                        </a>
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link btn btn-primary" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link btn btn-info text-white" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link btn btn-danger" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link btn btn-success" title="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Peta Lokasi -->
    <div class="map-section">
        <h3>
            <i class="fas fa-map-marked-alt"></i> Lokasi Kantor BPBD Bantul
        </h3>
        <div id="map-kontak"></div>
    </div>

    <!-- Kontak Darurat Lainnya -->
    <div class="emergency-numbers-section">
        <h3 class="emergency-numbers-title">
            <i class="fas fa-phone-volume"></i> Kontak Darurat Lainnya
        </h3>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <a href="tel:118" style="text-decoration:none;">
                <div class="emergency-number-card">
                    <i class="fas fa-ambulance text-danger"></i>
                    <h6>Ambulans</h6>
                    <strong>118</strong>
                    <div style="margin-top:8px;font-size:12px;color:#0891b2;font-weight:700;"><i class="fas fa-phone-alt"></i> Klik untuk hubungi</div>
                </div></a>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="tel:113" style="text-decoration:none;">
                <div class="emergency-number-card">
                    <i class="fas fa-fire-extinguisher text-warning"></i>
                    <h6>Pemadam Kebakaran</h6>
                    <strong>113</strong>
                    <div style="margin-top:8px;font-size:12px;color:#0891b2;font-weight:700;"><i class="fas fa-phone-alt"></i> Klik untuk hubungi</div>
                </div></a>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="tel:110" style="text-decoration:none;">
                <div class="emergency-number-card">
                    <i class="fas fa-shield-alt text-primary"></i>
                    <h6>Polisi</h6>
                    <strong>110</strong>
                    <div style="margin-top:8px;font-size:12px;color:#0891b2;font-weight:700;"><i class="fas fa-phone-alt"></i> Klik untuk hubungi</div>
                </div></a>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="tel:115" style="text-decoration:none;">
                <div class="emergency-number-card">
                    <i class="fas fa-life-ring text-success"></i>
                    <h6>SAR Nasional</h6>
                    <strong>115</strong>
                    <div style="margin-top:8px;font-size:12px;color:#0891b2;font-weight:700;"><i class="fas fa-phone-alt"></i> Klik untuk hubungi</div>
                </div></a>
            </div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="info-box">
        <div class="row align-items-center">
            <div class="col-md-2 text-center mb-4 mb-md-0">
                <div class="info-box-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
            </div>
            <div class="col-md-10">
                <div class="info-box-content">
                    <h5>
                        <i class="fas fa-info-circle"></i> Peringatan Penting
                    </h5>
                    <p>
                        Dalam kondisi darurat banjir, <strong>segera hubungi nomor 119 atau 112</strong> untuk bantuan evakuasi.
                        Jangan menunda untuk meminta bantuan jika air terus naik dan mengancam keselamatan Anda dan keluarga.
                        Tetap tenang, ikuti instruksi petugas, dan prioritaskan keselamatan!
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Kirim Pesan -->
    <div style="background:white;border-radius:25px;padding:2.5rem;box-shadow:0 15px 50px rgba(0,0,0,0.08);margin-top:3rem;border:2px solid rgba(8,145,178,0.1);">
        <div style="text-align:center;margin-bottom:2rem;">
            <h3 style="color:#0c4a6e;font-weight:900;font-size:1.9rem;display:flex;align-items:center;justify-content:center;gap:0.75rem;">
                <i class="fas fa-comment-dots" style="color:#0891b2;"></i> Kirim Pesan / Konsultasi
            </h3>
            <p style="color:#64748b;font-size:1.05rem;">Punya pertanyaan, saran, atau perlu konsultasi seputar banjir? Isi form di bawah — pesan akan langsung dikirim via WhatsApp BPBD.</p>
        </div>

        <!-- Success Alert -->
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
    // ── PETA BPBD ─────────────────────────────────────────────────────
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
                <h6 style="color:#0c4a6e;font-weight:900;margin-bottom:10px;display:flex;align-items:center;gap:7px;">
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
                    <a href="https://www.google.com/maps/dir/?api=1&destination=${BPBD_LAT},${BPBD_LNG}"
                       target="_blank"
                       style="display:inline-flex;align-items:center;gap:5px;background:linear-gradient(135deg,#0891b2,#06b6d4);color:white;padding:7px 13px;border-radius:8px;font-weight:700;font-size:12px;text-decoration:none;">
                        <i class="fas fa-directions"></i> Google Maps
                    </a>
                    <button onclick="getRouteToHere()"
                       style="display:inline-flex;align-items:center;gap:5px;background:linear-gradient(135deg,#10b981,#059669);color:white;padding:7px 13px;border-radius:8px;font-weight:700;font-size:12px;border:none;cursor:pointer;">
                        <i class="fas fa-route"></i> Rute dari Sini
                    </button>
                </div>
            </div>
        `, { maxWidth: 280 })
        .openPopup();

    // Rute dari lokasi user ke BPBD
    let routeLayer = null;
    function getRouteToHere() {
        if (!navigator.geolocation) { alert('Geolocation tidak didukung browser ini.'); return; }
        navigator.geolocation.getCurrentPosition(pos => {
            const lat = pos.coords.latitude, lng = pos.coords.longitude;
            const url = `https://router.project-osrm.org/route/v1/driving/${lng},${lat};${BPBD_LNG},${BPBD_LAT}?overview=full&geometries=geojson`;
            fetch(url).then(r => r.json()).then(data => {
                if (routeLayer) mapKontak.removeLayer(routeLayer);
                const coords = data.routes[0].geometry.coordinates.map(c => [c[1], c[0]]);
                routeLayer = L.polyline(coords, { color:'#0891b2', weight:5, opacity:0.8 }).addTo(mapKontak);
                // User marker
                L.marker([lat, lng]).addTo(mapKontak).bindPopup('📍 Lokasi Anda').openPopup();
                mapKontak.fitBounds(routeLayer.getBounds(), { padding:[30,30] });
                const dist = (data.routes[0].distance/1000).toFixed(1);
                const dur  = Math.round(data.routes[0].duration/60);
                alert(`🗺️ Rute ke BPBD Bantul\n📏 Jarak: ${dist} km\n⏱️ Estimasi: ${dur} menit`);
            }).catch(() => {
                window.open(`https://www.google.com/maps/dir/${lat},${lng}/${BPBD_LAT},${BPBD_LNG}`, '_blank');
            });
        }, () => {
            window.open(`https://www.google.com/maps/dir/?api=1&destination=${BPBD_LAT},${BPBD_LNG}`, '_blank');
        });
    }

    // ── FORM KIRIM PESAN ──────────────────────────────────────────────
    function kirimPesan() {
        const nama    = document.getElementById('pesanNama').value.trim();
        const kontak  = document.getElementById('pesanKontak').value.trim();
        const topik   = document.getElementById('pesanTopik').value;
        const isi     = document.getElementById('pesanIsi').value.trim();

        if (!nama || !kontak || !isi) {
            alert('⚠️ Nama, nomor kontak, dan isi pesan wajib diisi.');
            return;
        }

        const msg = `Halo BPBD Bantul \n\nSaya *${nama}* ingin menghubungi terkait: *${topik || 'Pertanyaan umum'}*\n\n${isi}\n\n Kontak: ${kontak}`;
        window.open('https://wa.me/6287834755177?text=' + encodeURIComponent(msg), '_blank');

        document.getElementById('pesanSuccess').style.display = 'flex';
        ['pesanNama','pesanKontak','pesanIsi'].forEach(id => document.getElementById(id).value = '');
        document.getElementById('pesanTopik').selectedIndex = 0;
        setTimeout(() => document.getElementById('pesanSuccess').style.display = 'none', 6000);
    }

    // Enter key di input pesan
    document.addEventListener('DOMContentLoaded', () => {
        const inputs = ['pesanNama','pesanKontak'];
        inputs.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('keydown', e => { if (e.key === 'Enter') kirimPesan(); });
        });
    });
</script>
@endsection
