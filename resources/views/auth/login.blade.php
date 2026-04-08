<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - WebGIS Banjir Bantul</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0c4a6e 0%, #0891b2 50%, #06b6d4 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Animated Waves */
        .wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 200%;
            height: 120px;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1200 120'%3E%3Cpath d='M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z' fill='rgba(255,255,255,0.1)'/%3E%3C/svg%3E") repeat-x;
            animation: wave 15s linear infinite;
        }

        .wave:nth-child(2) {
            bottom: 15px;
            animation: wave 10s linear infinite reverse;
            opacity: 0.5;
        }

        @keyframes wave {
            0% { background-position-x: 0; }
            100% { background-position-x: 1200px; }
        }

        /* Login Card */
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 2.5rem;
            width: 420px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 10;
            border: 2px solid rgba(255, 255, 255, 0.3);
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo {

            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .logo i {
            font-size: 2rem;
            color: white;
        }

        h1 {
            color: #0c4a6e;
            font-weight: 900;
            font-size: 1.75rem;
            margin-bottom: 0.3rem;
        }

        .subtitle {
            color: #64748b;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Alert */
        .alert {
            padding: 0.9rem;
            border-radius: 10px;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .alert-error {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        /* Form */
        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-label {
            display: block;
            color: #475569;
            font-weight: 700;
            font-size: 0.85rem;
            margin-bottom: 0.4rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
        }

        .form-input {
            width: 100%;
            padding: 0.9rem 1rem 0.9rem 2.8rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #0891b2;
            box-shadow: 0 0 0 3px rgba(8, 145, 178, 0.1);
        }

        .form-input:focus + .input-icon {
            color: #0891b2;
        }

        /* Remember & Forgot */
        .form-extras {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .remember input {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: #0891b2;
        }

        .remember label {
            color: #64748b;
            font-weight: 600;
            cursor: pointer;
        }

        .forgot {
            color: #0891b2;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }

        .forgot:hover {
            color: #0e7490;
        }

        /* Button */
        .btn {
            width: 100%;
            padding: 0.95rem;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: white;
            box-shadow: 0 8px 25px rgba(8, 145, 178, 0.3);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
        }

        .btn:hover {
            background: linear-gradient(135deg, #0e7490, #0891b2);
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(8, 145, 178, 0.4);
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #64748b;
            font-size: 0.9rem;
        }

        .footer a {
            color: #0891b2;
            font-weight: 700;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* Back Button */
        .back-btn {
            position: absolute;
            top: 1.5rem;
            left: 1.5rem;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.85rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
            z-index: 20;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateX(-3px);
        }

        @media (max-width: 480px) {
            .login-card {
                width: 90%;
                padding: 2rem;
            }

            .back-btn {
                top: 1rem;
                left: 1rem;
                padding: 0.5rem 1rem;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="wave"></div>
    <div class="wave"></div>

    <a href="{{ route('home') }}" class="back-btn">
        <i class="fas fa-arrow-left"></i> Beranda
    </a>

    <div class="login-card">
        <div class="header">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo"
                    style="height: 50px; width: auto; margin-right: 8px;">
            </div>
            <h1>Login Admin</h1>
            <p class="subtitle">WebGIS Banjir Bantul</p>
        </div>

        @if (session('status'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('status') }}</span>
        </div>
        @endif

        @if ($errors->any() || session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') ?? $errors->first() }}</span>
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Email</label>
                <div class="input-wrapper">
                    <input type="email"
                           name="email"
                           class="form-input"
                           placeholder="admin@bpbd.bantul.go.id"
                           value="{{ old('email') }}"
                           required
                           autofocus>
                    <i class="fas fa-envelope input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrapper">
                    <input type="password"
                           name="password"
                           class="form-input"
                           placeholder="Masukkan password"
                           required>
                    <i class="fas fa-lock input-icon"></i>
                </div>
            </div>

            <div class="form-extras">
                <div class="remember">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Ingat Saya</label>
                </div>
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot">Lupa Password?</a>
                @endif
            </div>

            <button type="submit" class="btn">
                <i class="fas fa-sign-in-alt"></i>
                Login Sekarang
            </button>

            <div class="footer">
                Belum punya akun? <a href="{{ route('register') }}">Daftar Admin</a>
            </div>
        </form>
    </div>
</body>
</html>
