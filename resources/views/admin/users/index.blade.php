<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 style="color: #0c4a6e; font-weight: 900; font-size: 2rem; margin: 0;">
                <i class="fas fa-users-cog"></i> Kelola Admin / User
            </h2>
            <p style="color: #64748b; margin: 0.5rem 0 0 0; font-weight: 600;">
                Kelola akses dan approval user admin BPBD
            </p>
        </div>
    </x-slot>

    @push('styles')
    <style>
        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(180deg, var(--stat-color), transparent);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
            border-color: var(--stat-color);
        }

        .stat-card-pending {
            --stat-color: #f59e0b;
        }

        .stat-card-approved {
            --stat-color: #10b981;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 900;
            line-height: 1;
            margin-bottom: 0.5rem;
        }
        .stat-card-pending  .stat-number { color: #d97706; }
        .stat-card-approved .stat-number { color: #059669; }

        .stat-label {
            color: #64748b;
            font-weight: 700;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
            border: 2px solid rgba(8, 145, 178, 0.1);
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

        /* Table */
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
            transform: scale(1.002);
        }

        .table tbody td {
            padding: 1.25rem 1rem;
            vertical-align: middle;
        }

        /* Badges */
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
        }

        .badge-role-super {
            background: linear-gradient(135deg, #a855f7, #9333ea);
            color: white;
        }

        .badge-role-admin {
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: white;
        }

        .badge-pending {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #92400e;
            border: 2px solid #f59e0b;
        }

        .badge-approved {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
            border: 2px solid #10b981;
        }

        /* Action Buttons */
        .btn-action {
            background: none;
            border: none;
            padding: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
        }

        .btn-action:hover {
            transform: scale(1.15);
        }

        .btn-approve {
            color: #10b981;
        }

        .btn-approve:hover {
            background: #d1fae5;
        }

        .btn-delete {
            color: #ef4444;
        }

        .btn-delete:hover {
            background: #fee2e2;
        }

        /* Alert */
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
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
        }

        /* ── MOBILE RESPONSIVE ── */
        @media (max-width: 767px) {
            .stats-container { grid-template-columns: repeat(2, 1fr); gap: 0.75rem; margin-bottom: 1.25rem; }
            .stat-card { padding: 1.25rem; border-radius: 14px; }
            .stat-number { font-size: 2.2rem; }
            .stat-label { font-size: 0.82rem; }

            .table-card { padding: 1.25rem; border-radius: 14px; }
            .table-card-title { font-size: 1.05rem; margin-bottom: 1rem; }
            .table thead th { padding: 0.6rem 0.4rem; font-size: 0.72rem; }
            .table tbody td { padding: 0.6rem 0.4rem; font-size: 0.8rem; }
            .badge { padding: 0.3rem 0.7rem; font-size: 0.72rem; }
            .btn-action { width: 30px; height: 30px; }
        }
    </style>
    @endpush

    <div class="container-fluid px-0 pb-5">

        @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle fa-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card stat-card-pending">
                <div class="stat-number">{{ $pending }}</div>
                <div class="stat-label">
                    <i class="fas fa-clock"></i>
                    Menunggu Approval
                </div>
            </div>

            <div class="stat-card stat-card-approved">
                <div class="stat-number">{{ $approved }}</div>
                <div class="stat-label">
                    <i class="fas fa-check-circle"></i>
                    Sudah Di-approve
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-card">
            <h3 class="table-card-title">
                <i class="fas fa-table"></i> Daftar User / Admin
            </h3>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Terdaftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td><strong>{{ $user->id }}</strong></td>

                            <td>
                                <div style="font-weight: 700; color: #0f172a;">
                                    {{ $user->name }}
                                </div>
                            </td>

                            <td>
                                <div style="color: #64748b; font-size: 0.9rem;">
                                    {{ $user->email }}
                                </div>
                            </td>

                            <td>
                                <span class="badge {{ $user->role == 'super_admin' ? 'badge-role-super' : 'badge-role-admin' }}">
                                    @if($user->role == 'super_admin')
                                        <i class="fas fa-crown"></i>
                                    @else
                                        <i class="fas fa-user-shield"></i>
                                    @endif
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </td>

                            <td>
                                @if($user->is_approved)
                                <span class="badge badge-approved">
                                    <i class="fas fa-check-circle"></i> Approved
                                </span>
                                @else
                                <span class="badge badge-pending">
                                    <i class="fas fa-clock"></i> Pending
                                </span>
                                @endif
                            </td>

                            <td>
                                <div style="color: #64748b; font-size: 0.9rem;">
                                    {{ $user->created_at->format('d M Y') }}
                                </div>
                            </td>

                            <td style="white-space: nowrap;">
                                @if(!$user->is_approved && $user->id != Auth::id())
                                    <form action="{{ route('admin.users.approve', $user->id) }}" method="POST" class="d-inline" title="Approve User">
                                        @csrf
                                        <button type="submit" class="btn-action btn-approve">
                                            <i class="fas fa-check-circle fa-lg"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.users.reject', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')" title="Hapus User">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete">
                                            <i class="fas fa-trash fa-lg"></i>
                                        </button>
                                    </form>
                                @elseif($user->id == Auth::id())
                                    <span style="color: #94a3b8; font-size: 0.85rem; font-style: italic;">
                                        <i class="fas fa-user"></i> Anda sendiri
                                    </span>
                                @else
                                    <span style="color: #cbd5e1;">
                                        <i class="fas fa-check"></i> Approved
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3" style="display: block; color: #cbd5e1;"></i>
                                <span style="color: #94a3b8; font-weight: 600;">Belum ada user terdaftar</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
