<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 style="color: #0c4a6e; font-weight: 900; font-size: 2rem; margin: 0;">
                <i class="fas fa-user-circle"></i> Profile Saya
            </h2>
            <p style="color: #64748b; margin: 0.5rem 0 0 0; font-weight: 600;">
                Kelola informasi akun dan keamanan Anda
            </p>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .profile-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
            border: 2px solid rgba(8, 145, 178, 0.1);
            margin-bottom: 2rem;
        }

        .profile-card-header {
            color: #0c4a6e;
            font-weight: 800;
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .profile-card-description {
            color: #64748b;
            font-size: 0.95rem;
            margin-bottom: 2rem;
            line-height: 1.6;
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

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: #0891b2;
            box-shadow: 0 0 0 4px rgba(8, 145, 178, 0.1);
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

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border-color: #ef4444;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
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
        }

        .alert {
            border-radius: 15px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
            border-left: 5px solid #10b981;
        }
    </style>
    @endpush

    <div class="container-fluid px-0 pb-5">

        @if(session('status') === 'profile-updated')
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span>Profile berhasil diperbarui!</span>
        </div>
        @endif

        @if(session('status') === 'password-updated')
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span>Password berhasil diperbarui!</span>
        </div>
        @endif

        <!-- Profile Information Card -->
        <div class="profile-card">
            <h3 class="profile-card-header">
                <i class="fas fa-user-edit"></i> Informasi Profile
            </h3>
            <p class="profile-card-description">
                Perbarui informasi nama dan email akun Anda
            </p>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name', $user->name) }}"
                           class="form-input"
                           placeholder="Masukkan nama lengkap"
                           required>
                    @error('name')
                    <p style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem;">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email"
                           name="email"
                           value="{{ old('email', $user->email) }}"
                           class="form-input"
                           placeholder="email@example.com"
                           required>
                    @error('email')
                    <p style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem;">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </p>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </form>
        </div>

        <!-- Update Password Card -->
        <div class="profile-card">
            <h3 class="profile-card-header">
                <i class="fas fa-lock"></i> Ubah Password
            </h3>
            <p class="profile-card-description">
                Pastikan akun Anda menggunakan password yang panjang dan acak untuk keamanan
            </p>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Password Saat Ini</label>
                    <input type="password"
                           name="current_password"
                           class="form-input"
                           placeholder="Masukkan password saat ini">
                    @error('current_password')
                    <p style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem;">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <input type="password"
                           name="password"
                           class="form-input"
                           placeholder="Masukkan password baru (minimal 8 karakter)">
                    @error('password')
                    <p style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem;">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password"
                           name="password_confirmation"
                           class="form-input"
                           placeholder="Ketik ulang password baru">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-key"></i> Ubah Password
                </button>
            </form>
        </div>

        <!-- Delete Account Card -->
        <div class="profile-card" style="border-color: rgba(239, 68, 68, 0.2);">
            <h3 class="profile-card-header" style="color: #991b1b;">
                <i class="fas fa-exclamation-triangle"></i> Hapus Akun
            </h3>
            <p class="profile-card-description">
                Setelah akun Anda dihapus, semua data dan resource akan dihapus permanen. Sebelum menghapus akun, silakan download data atau informasi yang ingin Anda simpan.
            </p>

            <button type="button"
                    class="btn btn-danger"
                    onclick="showDeleteModal()">
                <i class="fas fa-trash-alt"></i> Hapus Akun
            </button>
        </div>

    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); backdrop-filter: blur(5px);">
        <div style="background: white; max-width: 500px; margin: 10% auto; padding: 2rem; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <h3 style="color: #991b1b; font-weight: 800; margin-bottom: 1rem;">
                <i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus Akun
            </h3>
            <p style="color: #64748b; margin-bottom: 1.5rem;">
                Apakah Anda yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan. Masukkan password Anda untuk konfirmasi.
            </p>

            <form method="POST" action="{{ route('profile.destroy') }}" id="deleteForm">
                @csrf
                @method('DELETE')

                <div style="margin-bottom: 1.5rem;">
                    <label class="form-label">Password Anda</label>
                    <input type="password"
                           name="password"
                           class="form-input"
                           placeholder="Masukkan password untuk konfirmasi"
                           required>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="button"
                            onclick="closeDeleteModal()"
                            class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit"
                            class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i> Ya, Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function showDeleteModal() {
            document.getElementById('deleteModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
            document.body.style.overflow = '';
        }

        // Close modal on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });

        // Close modal on outside click
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
    @endpush
</x-app-layout>
