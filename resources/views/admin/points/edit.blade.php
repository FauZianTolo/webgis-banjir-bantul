<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 style="color: #0c4a6e; font-weight: 900; font-size: 2rem; margin: 0;">
                <i class="fas fa-edit"></i> Edit Data Laporan #{{ $laporan->id }}
            </h2>
            <p style="color: #64748b; margin: 0.5rem 0 0 0; font-weight: 600;">
                Perbarui informasi laporan banjir yang sudah terverifikasi
            </p>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .form-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
            border: 2px solid rgba(8, 145, 178, 0.1);
            margin-bottom: 2rem;
        }

        .section-title {
            color: #0c4a6e;
            font-weight: 800;
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 3px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            color: #475569;
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .form-label .required {
            color: #ef4444;
            margin-left: 0.25rem;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #0891b2;
            box-shadow: 0 0 0 4px rgba(8, 145, 178, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 120px;
        }

        .current-photo {
            display: inline-block;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border: 3px solid white;
            transition: all 0.3s ease;
        }

        .current-photo:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(8, 145, 178, 0.3);
        }

        .photo-label {
            color: #64748b;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            display: block;
        }

        .btn {
            padding: 0.875rem 2rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            text-decoration: none;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
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

        .btn-secondary {
            background: white;
            color: #64748b;
            border-color: #e2e8f0;
        }

        .btn-secondary:hover {
            background: #f8fafc;
            color: #475569;
            border-color: #cbd5e1;
        }

        .alert {
            border-radius: 15px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 2rem;
            border: none;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .alert-error {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
            border-left: 5px solid #ef4444;
        }

        .alert-error ul {
            margin: 0.75rem 0 0 1.5rem;
            list-style: disc;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .form-help {
            font-size: 0.85rem;
            color: #94a3b8;
            margin-top: 0.5rem;
        }
    </style>
    @endpush

    <div class="container-fluid px-0 pb-5">

        @if($errors->any())
        <div class="alert alert-error">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                <i class="fas fa-exclamation-circle fa-lg"></i>
                <strong style="font-size: 1.1rem;">Terdapat Kesalahan!</strong>
            </div>
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.points.update', $laporan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Data Pelapor -->
            <div class="form-card">
                <h3 class="section-title">
                    <i class="fas fa-user"></i> Data Pelapor
                </h3>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">
                            Nama Pelapor <span class="required">*</span>
                        </label>
                        <input type="text"
                               name="nama_pelapor"
                               value="{{ old('nama_pelapor', $laporan->nama_pelapor) }}"
                               class="form-input"
                               placeholder="Masukkan nama lengkap"
                               required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            No. Telepon
                        </label>
                        <input type="text"
                               name="no_telp"
                               value="{{ old('no_telp', $laporan->no_telp) }}"
                               class="form-input"
                               placeholder="08xxxxxxxxxx">
                    </div>
                </div>
            </div>

            <!-- Lokasi Kejadian -->
            <div class="form-card">
                <h3 class="section-title">
                    <i class="fas fa-map-marker-alt"></i> Lokasi Kejadian
                </h3>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">
                            Kecamatan <span class="required">*</span>
                        </label>
                        <select name="kecamatan" class="form-select" required>
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach(['Banguntapan', 'Bantul', 'Bambanglipuro', 'Dlingo', 'Imogiri', 'Jetis', 'Kasihan', 'Kretek', 'Pajangan', 'Pandak', 'Piyungan', 'Pleret', 'Pundong', 'Sanden', 'Sedayu', 'Sewon', 'Srandakan'] as $kec)
                            <option value="{{ $kec }}" {{ old('kecamatan', $laporan->kecamatan) == $kec ? 'selected' : '' }}>
                                {{ $kec }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Desa/Kelurahan
                        </label>
                        <input type="text"
                               name="desa"
                               value="{{ old('desa', $laporan->desa) }}"
                               class="form-input"
                               placeholder="Nama desa/kelurahan">
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Latitude <span class="required">*</span>
                        </label>
                        <input type="number"
                               step="0.000001"
                               name="latitude"
                               value="{{ old('latitude', $laporan->latitude) }}"
                               class="form-input"
                               placeholder="-7.xxxxxx"
                               required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Longitude <span class="required">*</span>
                        </label>
                        <input type="number"
                               step="0.000001"
                               name="longitude"
                               value="{{ old('longitude', $laporan->longitude) }}"
                               class="form-input"
                               placeholder="110.xxxxxx"
                               required>
                    </div>
                </div>
            </div>

            <!-- Detail Kejadian -->
            <div class="form-card">
                <h3 class="section-title">
                    <i class="fas fa-water"></i> Detail Kejadian Banjir
                </h3>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">
                            Kedalaman Air (cm)
                        </label>
                        <input type="number"
                               name="kedalaman_cm"
                               value="{{ old('kedalaman_cm', $laporan->kedalaman_cm) }}"
                               class="form-input"
                               placeholder="Contoh: 50"
                               min="0">
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Waktu Laporan <span class="required">*</span>
                        </label>
                        <input type="datetime-local"
                               name="waktu_laporan"
                               value="{{ old('waktu_laporan', $laporan->waktu_laporan->format('Y-m-d\TH:i')) }}"
                               class="form-input"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Deskripsi Kejadian <span class="required">*</span>
                    </label>
                    <textarea name="deskripsi"
                              class="form-textarea"
                              placeholder="Jelaskan kondisi banjir secara detail..."
                              required>{{ old('deskripsi', $laporan->deskripsi) }}</textarea>
                </div>
            </div>

            <!-- Foto Kejadian -->
            <div class="form-card">
                <h3 class="section-title">
                    <i class="fas fa-camera"></i> Dokumentasi Foto
                </h3>

                @if($laporan->foto)
                <div style="margin-bottom: 2rem;">
                    <span class="photo-label">
                        <i class="fas fa-image"></i> Foto Saat Ini:
                    </span>
                    <div class="current-photo">
                        <img src="{{ fotoUrl($laporan->foto) }}"
                             alt="Foto Laporan"
                             style="width: 300px; height: 300px; object-fit: cover; display: block;">
                    </div>
                </div>
                @endif

                <div class="form-group">
                    <label class="form-label">
                        Upload Foto Baru
                    </label>
                    <input type="file"
                           name="foto"
                           accept="image/*"
                           class="form-input">
                    <p class="form-help">
                        <i class="fas fa-info-circle"></i> Biarkan kosong jika tidak ingin mengubah foto. Format: JPG, PNG, JPEG (Max 2MB)
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                <a href="{{ route('admin.points.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>

        </form>

    </div>
</x-app-layout>
