@extends('layouts.public')

@section('styles')
    <link href='https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;900&display=swap' rel='stylesheet'>
<style>
        /* ================= HERO ================= */
        .berita-hero {
            background: linear-gradient(135deg, #0c4a6e 0%, #0891b2 50%, #06b6d4 100%);
            color: white;
            padding: 4rem 0;
            margin-bottom: 0;
            position: relative;
            overflow: hidden;
        }
        .berita-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 20% 50%, rgba(255,255,255,0.07) 0%, transparent 60%),
                        radial-gradient(ellipse at 80% 20%, rgba(6,182,212,0.15) 0%, transparent 50%);
        }
        .berita-hero::after {
            content: '';
            position: absolute;
            bottom: -2px; left: 0; right: 0;
            height: 50px;
            background: #f0f9ff;
            clip-path: ellipse(55% 100% at 50% 100%);
        }
        .berita-hero h1 { position:relative; z-index:2; font-weight:900; font-size:2.8rem; text-shadow:2px 2px 4px rgba(0,0,0,0.3); }
        .berita-hero p  { position:relative; z-index:2; font-size:1.2rem; opacity:0.95; }

        /* ================= WEATHER WRAPPER ================= */
        .weather-section {
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(8, 145, 178, .3);
            margin-bottom: 3rem;
            border: 3px solid rgba(255, 255, 255, .2);
        }

        .weather-header {
            background: rgba(255, 255, 255, .15);
            backdrop-filter: blur(10px);
            padding: 1.5rem 2rem;
            border-bottom: 2px solid rgba(255, 255, 255, .2);
        }

        .weather-header h3 {
            color: white;
            font-weight: 900;
            margin-bottom: .4rem;
            font-size: 1.6rem;
            display: flex;
            align-items: center;
            gap: .8rem;
        }

        .weather-header p {
            color: rgba(255, 255, 255, .9);
            margin: 0;
            font-size: .95rem;
        }

        .live-badge {
            display: flex;
            align-items: center;
            gap: .6rem;
            justify-content: flex-end;
        }

        .pulse-dot {
            width: 12px;
            height: 12px;
            background: #ef4444;
            border-radius: 50%;
            animation: pulseDot 2s infinite;
            flex-shrink: 0;
        }

        @keyframes pulseDot {
            0% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, .7)
            }

            70% {
                box-shadow: 0 0 0 10px rgba(239, 68, 68, 0)
            }

            100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0)
            }
        }

        .live-text {
            color: white;
            font-weight: 800;
            font-size: .9rem;
            letter-spacing: 2px;
        }

        .weather-body {
            padding: 2rem;
            background: white;
        }

        /* ================= CURRENT WEATHER PANEL ================= */
        .cw-panel {
            background: linear-gradient(135deg, #0c4a6e, #0891b2);
            color: white;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, .1);
        }

        .cw-main {
            padding: 2rem 2.5rem;
            position: relative;
        }

        .cw-main::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23fff' fill-opacity='.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .cw-icon {
            font-size: 5rem;
            line-height: 1;
            position: relative;
            z-index: 2;
            margin-bottom: .5rem;
        }

        .cw-temp {
            font-size: 3.75rem;
            font-weight: 900;
            line-height: 1;
            position: relative;
            z-index: 2;
            text-shadow: 2px 4px 8px rgba(0, 0, 0, .2);
        }

        .cw-desc {
            font-size: 1.25rem;
            font-weight: 700;
            opacity: .95;
            position: relative;
            z-index: 2;
            margin-top: .2rem;
        }

        .cw-feels {
            font-size: .9rem;
            opacity: .75;
            position: relative;
            z-index: 2;
            margin-top: .2rem;
        }

        .cw-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            position: relative;
            z-index: 2;
        }

        .cw-item {
            display: flex;
            align-items: center;
            gap: .9rem;
            background: rgba(255, 255, 255, .15);
            backdrop-filter: blur(8px);
            padding: 1rem 1.1rem;
            border-radius: 12px;
            border: 1.5px solid rgba(255, 255, 255, .2);
            transition: all .3s;
        }

        .cw-item:hover {
            background: rgba(255, 255, 255, .25);
            transform: translateY(-3px);
        }

        .cw-item i {
            font-size: 1.6rem;
            opacity: .9;
            flex-shrink: 0;
        }

        .cw-lbl {
            font-size: .8rem;
            opacity: .8;
            display: block;
        }

        .cw-val {
            font-size: 1.1rem;
            font-weight: 800;
            display: block;
        }

        .cw-extra {
            background: rgba(255, 255, 255, .1);
            border-top: 1px solid rgba(255, 255, 255, .15);
            padding: .9rem 2.5rem;
            display: flex;
            flex-wrap: wrap;
            gap: .5rem 2rem;
        }

        .cw-extra-item {
            font-size: .88rem;
            opacity: .9;
        }

        .cw-extra-item strong {
            color: #bfdbfe;
        }

        /* ================= FORECAST ================= */
        .fc-section {
            padding: 1.75rem 2rem;
            background: linear-gradient(135deg, #f8fafc, #f0f9ff);
        }

        .fc-title {
            color: #0c4a6e;
            font-weight: 800;
            margin-bottom: 1.25rem;
            font-size: 1.15rem;
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        .fc-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: .75rem;
        }

        @media(max-width:900px) {
            .fc-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media(max-width:500px) {
            .fc-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .fc-card {
            background: white;
            padding: 1.1rem .6rem;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .07);
            transition: all .3s;
            border: 2px solid transparent;
        }

        .fc-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 28px rgba(8, 145, 178, .2);
            border-color: #0891b2;
        }

        .fc-date {
            font-size: .78rem;
            font-weight: 700;
            color: #0c4a6e;
            margin-bottom: .5rem;
        }

        .fc-ico {
            font-size: 2rem;
            color: #0891b2;
            margin-bottom: .5rem;
        }

        .fc-temps {
            display: flex;
            justify-content: center;
            gap: .4rem;
            margin-bottom: .3rem;
        }

        .fc-max {
            font-size: 1.1rem;
            font-weight: 800;
            color: #ef4444;
        }

        .fc-min {
            font-size: .95rem;
            font-weight: 700;
            color: #0891b2;
        }

        .fc-wdesc {
            font-size: .72rem;
            color: #64748b;
            font-weight: 700;
            margin-bottom: .25rem;
            line-height: 1.3;
        }

        .fc-rain {
            font-size: .72rem;
            color: #0891b2;
            font-weight: 700;
        }

        /* ================= FOOTER WEATHER ================= */
        .cw-footer {
            padding: 1rem 2rem;
            background: white;
            border-top: 2px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: .5rem;
        }

        .cw-footer small {
            color: #94a3b8;
            font-size: .82rem;
        }

        .cw-src {
            font-size: .82rem;
            color: #94a3b8;
        }

        .cw-src a {
            color: #0891b2;
            font-weight: 700;
            text-decoration: none;
        }

        /* ================= UPDATE TOAST ================= */
        #w-update-toast {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            background: #0891b2;
            color: white;
            padding: .6rem 1.2rem;
            border-radius: 12px;
            font-size: .85rem;
            font-weight: 700;
            box-shadow: 0 8px 24px rgba(8, 145, 178, .4);
            z-index: 9999;
            display: none;
            align-items: center;
            gap: .5rem;
            animation: slideIn .3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateY(20px);
                opacity: 0
            }

            to {
                transform: translateY(0);
                opacity: 1
            }
        }

        /* ================= BMKG BOX ================= */
        .bmkg-box {
            border-radius: 24px;
            padding: 2.25rem;
            margin-bottom: 3.5rem;
            position: relative;
            overflow: hidden;
            transition: all .5s;
        }

        .bmkg-box::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255, 255, 255, .07) 10px, rgba(255, 255, 255, .07) 20px);
            animation: patMove 20s linear infinite;
        }

        @keyframes patMove {
            0% {
                transform: translate(0, 0)
            }

            100% {
                transform: translate(50px, 50px)
            }
        }

        .bmkg-box.lvl-ok {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            border: 3px solid #10b981;
            box-shadow: 0 15px 50px rgba(16, 185, 129, .2);
        }

        .bmkg-box.lvl-info {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            border: 3px solid #3b82f6;
            box-shadow: 0 15px 50px rgba(59, 130, 246, .2);
        }

        .bmkg-box.lvl-warn {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border: 3px solid #f59e0b;
            box-shadow: 0 15px 50px rgba(245, 158, 11, .2);
        }

        .bmkg-box.lvl-danger {
            background: linear-gradient(135deg, #fee2e2, #fca5a5);
            border: 3px solid #ef4444;
            box-shadow: 0 15px 50px rgba(239, 68, 68, .2);
        }

        .bmkg-icon-wrap {
            width: 76px;
            height: 76px;
            border-radius: 50%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.25rem;
            border: 4px solid white;
            position: relative;
            z-index: 2;
            animation: wobble 4s ease-in-out infinite;
        }

        @keyframes wobble {

            0%,
            100% {
                transform: rotate(0)
            }

            20% {
                transform: rotate(-6deg)
            }

            40% {
                transform: rotate(6deg)
            }

            60% {
                transform: rotate(-4deg)
            }

            80% {
                transform: rotate(4deg)
            }
        }

        .lvl-ok .bmkg-icon-wrap {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .lvl-info .bmkg-icon-wrap {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .lvl-warn .bmkg-icon-wrap {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .lvl-danger .bmkg-icon-wrap {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .bmkg-content {
            padding-left: 1.5rem;
            position: relative;
            z-index: 2;
        }

        .bmkg-badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: linear-gradient(135deg, #1e3a5f, #0c4a6e);
            color: white;
            padding: .38rem 1rem;
            border-radius: 20px;
            font-size: .8rem;
            font-weight: 800;
            margin-bottom: .9rem;
            letter-spacing: .75px;
        }

        .bmkg-title {
            font-weight: 900;
            margin-bottom: .75rem;
            font-size: 1.4rem;
        }

        .lvl-ok .bmkg-title {
            color: #064e3b;
        }

        .lvl-info .bmkg-title {
            color: #1e3a5f;
        }

        .lvl-warn .bmkg-title {
            color: #78350f;
        }

        .lvl-danger .bmkg-title {
            color: #7f1d1d;
        }

        .bmkg-desc {
            margin-bottom: 1.5rem;
            line-height: 1.8;
            font-size: .97rem;
        }

        .lvl-ok .bmkg-desc {
            color: #065f46;
        }

        .lvl-info .bmkg-desc {
            color: #1e3a5f;
        }

        .lvl-warn .bmkg-desc {
            color: #78350f;
        }

        .lvl-danger .bmkg-desc {
            color: #7f1d1d;
        }

        .bmkg-actions {
            display: flex;
            gap: .75rem;
            flex-wrap: wrap;
        }

        .btn-bmkg {
            background: white;
            padding: .6rem 1.25rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            font-size: .88rem;
            display: inline-flex;
            align-items: center;
            gap: .55rem;
            border: 2px solid rgba(0, 0, 0, .1);
            color: #334155;
            transition: all .3s;
        }

        .btn-bmkg:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, .15);
            color: #0891b2;
        }

        .btn-bmkg-primary {
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: white;
            border-color: #0891b2;
        }

        .btn-bmkg-primary:hover {
            background: linear-gradient(135deg, #0e7490, #0891b2);
            color: white;
        }

        /* ================= NEWS ================= */
        .news-section-title {
            color: #0c4a6e;
            font-weight: 900;
            font-size: 1.9rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: .9rem;
        }

        .news-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 36px rgba(0, 0, 0, .07);
            transition: all .3s cubic-bezier(.4, 0, .2, 1);
            height: 100%;
            border: 2px solid transparent;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: inherit;
        }

        .news-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 18px 56px rgba(8, 145, 178, .18);
            border-color: #0891b2;
            color: inherit;
        }

        .news-thumb {
            position: relative;
            overflow: hidden;
            height: 215px;
            flex-shrink: 0;
            background: linear-gradient(135deg, #0891b2, #0c4a6e);
        }

        .news-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .5s ease;
            display: block;
        }

        .news-card:hover .news-thumb img {
            transform: scale(1.08);
        }

        .news-thumb-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            gap: .6rem;
        }

        .news-thumb-fallback i {
            font-size: 3rem;
            opacity: .9;
        }

        .news-thumb-fallback span {
            font-size: .95rem;
            font-weight: 700;
            opacity: .9;
        }

        .news-cat-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            color: white;
            padding: .35rem 1rem;
            border-radius: 20px;
            font-size: .78rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, .3);
            backdrop-filter: blur(8px);
            border: 1.5px solid rgba(255, 255, 255, .3);
        }

        .news-src-logo {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: rgba(255, 255, 255, .92);
            border-radius: 8px;
            padding: .3rem .7rem;
            font-size: .72rem;
            font-weight: 800;
            color: #0c4a6e;
            display: flex;
            align-items: center;
            gap: .35rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .15);
        }

        .news-body {
            padding: 1.4rem 1.6rem;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .news-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: .7rem;
            flex-wrap: wrap;
            gap: .4rem;
        }

        .news-date {
            color: #64748b;
            font-size: .83rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: .4rem;
        }

        .news-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: #0c4a6e;
            margin-bottom: .8rem;
            line-height: 1.45;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .news-excerpt {
            color: #64748b;
            margin-bottom: 1rem;
            line-height: 1.7;
            font-size: .9rem;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .btn-berita {
            color: white;
            border: none;
            padding: .62rem 1.4rem;
            border-radius: 10px;
            font-weight: 700;
            transition: all .3s;
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, .12);
            font-size: .88rem;
            align-self: flex-start;
        }

        .btn-berita:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .2);
        }

        /* ================= CTA ================= */
        .cta-section {
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: white;
            border-radius: 25px;
            padding: 3.5rem 3rem;
            text-align: center;
            margin-top: 3.5rem;
            box-shadow: 0 20px 60px rgba(8, 145, 178, .3);
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23fff' fill-opacity='.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .cta-section h3 {
            position: relative;
            z-index: 2;
            font-weight: 900;
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .cta-section p {
            position: relative;
            z-index: 2;
            font-size: 1.1rem;
            margin-bottom: 2rem;
            opacity: .95;
        }

        .btn-cta {
            background: white;
            color: #0891b2;
            padding: 1rem 2.5rem;
            border-radius: 14px;
            font-weight: 800;
            font-size: 1.05rem;
            transition: all .3s;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .2);
            position: relative;
            z-index: 2;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .7rem;
        }

        .btn-cta:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, .3);
            background: #f0f9ff;
            color: #0891b2;
        }

        @media(max-width:991px) {
            .weather-header {
                text-align: center;
            }

            .live-badge {
                justify-content: center;
                margin-top: .75rem;
            }

            .cw-grid {
                grid-template-columns: 1fr;
            }

            .bmkg-content {
                padding-left: 0;
                margin-top: 1.25rem;
            }

            .bmkg-actions {
                justify-content: center;
            }
        }
            @media (max-width: 767px) {
            .berita-hero h1 { font-size: 2rem; }
        }
</style>
@endsection

@section('content')
    <div class="berita-hero">
        <div class="container text-center">
            <h1 class="mb-3"><i class="fas fa-newspaper"></i> Berita Banjir Terkini</h1>
            <p class="lead mb-0">Informasi dan Update Terbaru Seputar Banjir di Kabupaten Bantul</p>
        </div>
    </div>

    <div class="container mb-5">
        @php
            use Illuminate\Support\Facades\Cache;
            use Illuminate\Support\Facades\Http;

            // ✅ OPTIMASI: Cache weather payload 30 menit di blade-level
            // Ini memastikan data cuaca tidak di-proses ulang tiap request
            // CATATAN: Untuk optimasi penuh, tambahkan juga cache di PublicController@berita:
            // $weatherPayload = Cache::remember('cuaca_bantul', 1800, fn() => (new WeatherController())->getServerWeather(-7.8877, 110.3302));
            $weatherPayload = Cache::remember('cuaca_bantul_blade', 1800, function () use ($cuacaAPI) {
                return $cuacaAPI ?? [];
            });
            $wCurrent = data_get($weatherPayload, 'current', []);
            $wForecast = data_get($weatherPayload, 'forecast', []);
            $wLocation = data_get($weatherPayload, 'location', 'Kabupaten Bantul, D.I. Yogyakarta');

            $wTemp = data_get($wCurrent, 'temp', '--');
            $wFeels = data_get($wCurrent, 'feels_like', $wTemp);
            $wHumidity = data_get($wCurrent, 'humidity', '--');
            $wWind = data_get($wCurrent, 'wind_speed', '--');
            $wRain = data_get($wCurrent, 'rain_prob', '--');
            $wVisibility = data_get($wCurrent, 'visibility', '--');
            $wUv = data_get($wCurrent, 'uv_index', '--');
            $wPressure = data_get($wCurrent, 'pressure', '--');
            $wGust = data_get($wCurrent, 'wind_gust', '--');
            $wWeatherText = data_get($wCurrent, 'weather', 'Cerah Berawan');
            $wIcon = data_get($wCurrent, 'icon', 'fa-cloud-sun');
            $wUpdated = data_get($weatherPayload, 'updated', now()->format('H:i'));

            $beritaList = [
                [
                    'judul' => 'Bantul Jadi Wilayah Terdampak Cuaca Ekstrem Paling Luas di DIY — 88 Titik Bencana',
                    'excerpt' =>
                        'Cuaca ekstrem 26–27 Desember 2025 menyebabkan 88 titik bencana di delapan kecamatan Bantul. Banjir, longsor, pohon tumbang, dan kerusakan infrastruktur terjadi bersamaan.',
                    'tanggal' => '27 Desember 2025',
                    'sumber' => 'Metro TV News',
                    'icon' => 'fa-bolt',
                    'color' => '#ef4444',
                    'label' => 'Darurat',
                    'link' =>
                        'https://www.metrotvnews.com/read/NLMC80vD-bantul-jadi-wilayah-terdampak-cuaca-ekstrem-paling-luas-di-diy',
                ],
                [
                    'judul' => 'Banjir Bantul Rusak Ribuan Hektare Sawah — Padi dan Bawang Merah Gagal Panen',
                    'excerpt' =>
                        'Hujan deras 26–27 Desember 2025 merendam 4.000 hektare lahan pertanian di selatan Bantul. Petani bawang merah di Gadingarjo alami gagal panen.',
                    'tanggal' => '28 Desember 2025',
                    'sumber' => 'VIVA Jogja',
                    'icon' => 'fa-seedling',
                    'color' => '#10b981',
                    'label' => 'Dampak Banjir',
                    'link' =>
                        'https://jogja.viva.co.id/warta/6058-banjir-bantul-rusak-ribuan-hektare-sawah-bpbd-siapkan-mitigasi',
                ],
                [
                    'judul' => 'Tagana Bantul Diterjunkan — Tangani Longsor, Banjir, dan Pohon Tumbang',
                    'excerpt' =>
                        'Personel Tagana Bantul dikerahkan ke Sanden, Kretek, dan Imogiri untuk asesmen cepat, evakuasi pohon tumbang, dan distribusi logistik.',
                    'tanggal' => '27 Desember 2025',
                    'sumber' => 'Dinas Sosial Bantul',
                    'icon' => 'fa-hands-helping',
                    'color' => '#0891b2',
                    'label' => 'Penanganan',
                    'link' =>
                        'https://sosial.bantulkab.go.id/news/tagana-bantul-terlibat-aktif-dalam-penanganan-bencana-di-sejumlah-wilayah',
                ],
                [
                    'judul' => 'Hujan 6 Jam, Lima Kecamatan Bantul Terendam — Ketinggian Air 30–60 cm',
                    'excerpt' =>
                        'Hujan lebat sejak pukul 16.00 WIB memicu banjir dan longsor di Bantul dan Gunungkidul. Lima kecamatan Bantul terdampak.',
                    'tanggal' => '28 Maret 2025',
                    'sumber' => 'Tempo.co',
                    'icon' => 'fa-house-flood-water',
                    'color' => '#f59e0b',
                    'label' => 'Berita Banjir',
                    'link' =>
                        'https://www.tempo.co/lingkungan/hujan-lebih-dari-enam-jam-guyur-di-yogyakarta-sejumlah-daerah-banjir-1225538',
                ],
                [
                    'judul' => 'BPBD Bantul Aktivasi 75 Pos Pantau Banjir dan Longsor di Masa Pancaroba 2025',
                    'excerpt' =>
                        'BPBD Bantul aktifkan 75 pos pantau di seluruh kelurahan menghadapi peralihan musim Maret–April 2025. Tim TRC siaga 24 jam penuh.',
                    'tanggal' => '27 Maret 2025',
                    'sumber' => 'Antara News',
                    'icon' => 'fa-tower-broadcast',
                    'color' => '#8b5cf6',
                    'label' => 'Kesiapsiagaan',
                    'link' =>
                        'https://jogja.antaranews.com/berita/738549/bpbd-bantul-aktivasi-75-pos-pantau-banjir-dan-longsor-di-peralihan-musim',
                ],
                [
                    'judul' => 'Status Siaga Darurat Banjir-Longsor Bantul Diperpanjang Hingga 30 April 2025',
                    'excerpt' =>
                        'BPBD Bantul perpanjang status siaga darurat banjir, longsor, dan angin kencang hingga 30 April 2025 berdasarkan analisis BMKG.',
                    'tanggal' => '10 Maret 2025',
                    'sumber' => 'Harianjogja.com',
                    'icon' => 'fa-triangle-exclamation',
                    'color' => '#dc2626',
                    'label' => 'Siaga Darurat',
                    'link' =>
                        'https://jogjapolitan.harianjogja.com/read/2025/03/10/511/1206667/bpbd-bantul-waspadai-longsor-dan-banjir-di-masa-pancaroba',
                ],
                [
                    'judul' => '4.000 Hektare Lahan Bantul Terendam — Padi Terancam Puso dan Bawang Merah Rusak',
                    'excerpt' =>
                        'Genangan banjir merendam sawah dan lahan hortikultura di selatan Bantul. Petani melaporkan tanaman padi dan bawang merah rusak parah.',
                    'tanggal' => '28 Desember 2025',
                    'sumber' => 'Tribunjogja.com',
                    'icon' => 'fa-wheat-awn',
                    'color' => '#d97706',
                    'label' => 'Dampak Banjir',
                    'link' =>
                        'https://jogja.tribunnews.com/diy/1204059/banjir-di-bantul-sebabkan-gagal-panen-bawang-merah-4000-hektare-lahan-terendam',
                ],
                [
                    'judul' => 'Sungai Winongo Meluap di DAM Srigading — 60 Hektare Sawah Bantul Terendam',
                    'excerpt' =>
                        'Hujan tinggi disertai angin puting beliung akibatkan Sungai Winongo meluap di DAM Dengokan, Srigading, Sanden.',
                    'tanggal' => '28 Desember 2025',
                    'sumber' => 'TVRI Yogyakarta',
                    'icon' => 'fa-water',
                    'color' => '#0284c7',
                    'label' => 'Dampak Banjir',
                    'link' =>
                        'https://tvriyogyakartanews.com/2025/12/28/curah-hujan-tinggi-lahan-pertanian-terendam-banjir/',
                ],
                [
                    'judul' => 'Breaking: Imogiri dan Piyungan Bantul Terendam — TRC BPBD Gerak Cepat Evakuasi',
                    'excerpt' =>
                        'TRC BPBD Bantul bergerak ke Imogiri pasca luapan Sungai Waru Gedok. Lumpur dan air menggenangi rumah warga hanya 4 meter dari sungai.',
                    'tanggal' => '28 Maret 2025',
                    'sumber' => 'Harianjogja.com',
                    'icon' => 'fa-person-drowning',
                    'color' => '#7c3aed',
                    'label' => 'Breaking News',
                    'link' =>
                        'https://jogjapolitan.harianjogja.com/read/2025/03/28/511/1208649/breaking-news-hujan-deras-sejumlah-wilayah-di-bantul-terendam-banjir',
                ],
            ];

            $extractThumb = function (string $url): ?string {
                $cacheKey = 'news-thumb:v3:' . md5($url);

                return Cache::remember($cacheKey, now()->addDay(), function () use ($url) {
                    try {
                        $response = Http::timeout(14)
                            ->withHeaders([
                                'User-Agent' =>
                                    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0 Safari/537.36',
                                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                                'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
                            ])
                            ->get($url);

                        if (!$response->successful()) {
                            return null;
                        }

                        $html = $response->body();

                        $baseScheme = parse_url($url, PHP_URL_SCHEME) ?: 'https';
                        $baseHost = parse_url($url, PHP_URL_HOST) ?: '';
                        $baseUrl = $baseScheme . '://' . $baseHost;

                        $normalize = function (?string $imageUrl) use ($baseUrl): ?string {
                            if (!$imageUrl) {
                                return null;
                            }

                            $imageUrl = html_entity_decode(trim($imageUrl));
                            if ($imageUrl === '') {
                                return null;
                            }

                            if (str_starts_with($imageUrl, '//')) {
                                return 'https:' . $imageUrl;
                            }

                            if (preg_match('/^https?:\/\//i', $imageUrl)) {
                                return $imageUrl;
                            }

                            if (str_starts_with($imageUrl, '/')) {
                                return rtrim($baseUrl, '/') . $imageUrl;
                            }

                            return rtrim($baseUrl, '/') . '/' . ltrim($imageUrl, '/');
                        };

                        $patterns = [
                            '/<meta[^>]+property=["\']og:image(?:[:\w-]*)?["\'][^>]+content=["\']([^"\']+)["\']/i',
                            '/<meta[^>]+property=["\']og:image:url["\'][^>]+content=["\']([^"\']+)["\']/i',
                            '/<meta[^>]+name=["\']twitter:image(?:[:\w-]*)?["\'][^>]+content=["\']([^"\']+)["\']/i',
                            '/<meta[^>]+name=["\']twitter:image:src["\'][^>]+content=["\']([^"\']+)["\']/i',
                            '/<link[^>]+rel=["\']image_src["\'][^>]+href=["\']([^"\']+)["\']/i',
                            '/<meta[^>]+name=["\']thumbnail["\'][^>]+content=["\']([^"\']+)["\']/i',
                        ];

                        $candidates = [];

                        foreach ($patterns as $pattern) {
                            if (preg_match($pattern, $html, $match) && !empty($match[1])) {
                                $candidates[] = $normalize($match[1]);
                            }
                        }

                        if (
                            preg_match_all(
                                '/<img[^>]+(?:data-src|data-lazy-src|data-original|src)=["\']([^"\']+)["\'][^>]*>/i',
                                $html,
                                $imgMatches,
                            )
                        ) {
                            foreach ($imgMatches[1] as $img) {
                                $candidates[] = $normalize($img);
                            }
                        }

                        if (preg_match_all('/srcset=["\']([^"\']+)["\']/i', $html, $srcsetMatches)) {
                            foreach ($srcsetMatches[1] as $srcset) {
                                foreach (array_map('trim', explode(',', $srcset)) as $item) {
                                    $parts = preg_split('/\s+/', trim($item));
                                    if (!empty($parts[0])) {
                                        $candidates[] = $normalize($parts[0]);
                                    }
                                }
                            }
                        }

                        $candidates = array_values(array_filter(array_unique($candidates)));

                        foreach ($candidates as $candidate) {
                            if (!$candidate) {
                                continue;
                            }
                            if (preg_match('/logo|icon|sprite|avatar|placeholder|ads?/i', $candidate)) {
                                continue;
                            }
                            return $candidate;
                        }

                        return null;
                    } catch (\Throwable) {
                        return null;
                    }
                });
            };

            foreach ($beritaList as $index => $item) {
                $beritaList[$index]['thumb'] = $extractThumb($item['link']);
                $beritaList[$index]['host'] = parse_url($item['link'], PHP_URL_HOST) ?: $item['sumber'];
            }
        @endphp

        {{-- PRAKIRAAN CUACA --}}
        <div class="weather-section">
            <div class="weather-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3><i class="fas fa-cloud-sun-rain"></i> Prakiraan Cuaca Real-Time</h3>
                        <p>
                            <i class="fas fa-map-marker-alt"></i>
                            <span id="w-loc-text">{{ $wLocation }}</span>
                            &nbsp;|&nbsp;
                            <i class="fas fa-leaf"></i> Open-Meteo
                        </p>
                    </div>
                    <div class="col-md-4">
                        <div class="live-badge">
                            <span class="pulse-dot"></span>
                            <span class="live-text">LIVE</span>
                            <button onclick="requestLocation()" title="Gunakan lokasi saya"
                                style="background:rgba(255,255,255,0.2); border:none; border-radius:50%; width:28px; height:28px; color:white; cursor:pointer; margin-left:8px; font-size:13px;">
                                <i class="fas fa-crosshairs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="weather-body">

                <!-- Loading state -->
                <div id="w-loading"
                    style="display:none; justify-content:center; align-items:center; padding:3rem; flex-direction:column; gap:1rem;">
                    <i class="fas fa-spinner fa-spin fa-2x" style="color:#0891b2;"></i>
                    <p style="color:#64748b; margin:0;">Mendeteksi lokasi Anda...</p>
                </div>

                <!-- Konten cuaca (dibungkus w-content) -->
                <div id="w-content">
                    <div class="cw-panel">
                        <div class="cw-main">
                            <div class="row align-items-center">
                                <div class="col-md-4 text-center mb-4 mb-md-0">
                                    <div class="cw-icon">
                                        <i id="w-icon" class="fas {{ $wIcon }}"></i>
                                    </div>
                                    <div class="cw-temp"><span id="w-temp">{{ $wTemp }}</span>°C</div>
                                    <div class="cw-desc" id="w-desc">{{ $wWeatherText }}</div>
                                    <div class="cw-feels" id="w-feels">Terasa seperti {{ $wFeels }}°C</div>
                                </div>
                                <div class="col-md-8">
                                    <div class="cw-grid">
                                        <div class="cw-item">
                                            <i class="fas fa-tint"></i>
                                            <div>
                                                <span class="cw-lbl">Kelembaban</span>
                                                <span class="cw-val" id="w-hum">{{ $wHumidity }}%</span>
                                            </div>
                                        </div>
                                        <div class="cw-item">
                                            <i class="fas fa-wind"></i>
                                            <div>
                                                <span class="cw-lbl">Angin</span>
                                                <span class="cw-val" id="w-wind">{{ $wWind }} km/h</span>
                                            </div>
                                        </div>
                                        <div class="cw-item">
                                            <i class="fas fa-cloud-rain"></i>
                                            <div>
                                                <span class="cw-lbl">Peluang Hujan</span>
                                                <span class="cw-val" id="w-rain">{{ $wRain }}%</span>
                                            </div>
                                        </div>
                                        <div class="cw-item">
                                            <i class="fas fa-eye"></i>
                                            <div>
                                                <span class="cw-lbl">Visibilitas</span>
                                                <span class="cw-val" id="w-vis">{{ $wVisibility }} km</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="cw-extra">
                            <span class="cw-extra-item"><strong>Indeks UV:</strong> <span
                                    id="w-uv">{{ $wUv }}</span></span>
                            <span class="cw-extra-item"><strong>Tekanan:</strong> <span
                                    id="w-pres">{{ $wPressure }}</span> hPa</span>
                            <span class="cw-extra-item"><strong>Angin Kencang:</strong> <span
                                    id="w-gust">{{ $wGust }}</span> km/h</span>
                            <span class="cw-extra-item"><strong>Terasa Seperti:</strong> <span
                                    id="w-feels2">{{ $wFeels }}</span>°C</span>
                        </div>

                        @if (count($wForecast) > 0)
                            <div class="fc-section">
                                <h5 class="fc-title"><i class="fas fa-calendar-week"></i> Prakiraan 7 Hari ke Depan</h5>
                                <div class="fc-grid" id="w-forecast-grid">
                                    @foreach ($wForecast as $day)
                                        <div class="fc-card">
                                            <div class="fc-date">{{ $day['date'] ?? '-' }}</div>
                                            <div class="fc-ico"><i class="fas {{ $day['icon'] ?? 'fa-cloud-sun' }}"></i>
                                            </div>
                                            <div class="fc-temps">
                                                <span class="fc-max">{{ $day['temp_max'] ?? '--' }}°</span>
                                                <span class="fc-min">{{ $day['temp_min'] ?? '--' }}°</span>
                                            </div>
                                            <div class="fc-wdesc">{{ $day['weather_text'] ?? '-' }}</div>
                                            <div class="fc-rain"><i class="fas fa-tint"></i>
                                                {{ $day['rain_prob'] ?? '--' }}%</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="cw-footer">
                            <small><i class="far fa-clock me-1"></i> Diperbarui: <span
                                    id="w-updated">{{ $wUpdated }}</span></small>
                            <span class="cw-src">
                                <i class="fas fa-leaf me-1" style="color:#10b981;"></i>
                                Powered by <a href="https://open-meteo.com" target="_blank">Open-Meteo</a>
                                &amp; <a href="https://nominatim.openstreetmap.org" target="_blank">Nominatim OSM</a>
                                <small class="text-muted ms-1"></small>
                            </span>
                        </div>
                    </div>
                </div>
                {{-- akhir #w-content --}}

            </div>
        </div>

        {{-- BMKG ALERT --}}
        <div class="bmkg-box lvl-warn" id="bmkgBox">
            <div class="row align-items-center">
                <div class="col-md-2 text-center mb-3 mb-md-0">
                    <div class="bmkg-icon-wrap"><i class="fas fa-cloud-rain" id="bmkgIcon"></i></div>
                </div>
                <div class="col-md-10">
                    <div class="bmkg-content">
                        <div class="bmkg-badge"><i class="fas fa-broadcast-tower"></i> INFORMASI TERKINI</div>
                        <h5 class="bmkg-title" id="bmkgTitle">Kondisi Cuaca Kabupaten Bantul</h5>
                        <p class="bmkg-desc" id="bmkgDesc">Memuat analisis cuaca...</p>
                        <div class="bmkg-actions">
                            <a href="https://www.bmkg.go.id/cuaca/prakiraan-cuaca.bmkg" target="_blank" class="btn-bmkg">
                                <i class="fas fa-cloud-sun-rain"></i> Lihat Prakiraan BMKG
                            </a>
                            <a href="https://www.bmkg.go.id/peringatan-dini" target="_blank" class="btn-bmkg">
                                <i class="fas fa-bell"></i> Peringatan Dini
                            </a>
                            <a href="{{ route('laporan') }}" class="btn-bmkg btn-bmkg-primary">
                                <i class="fas fa-paper-plane"></i> Lapor Banjir
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- BERITA & INFORMASI TERKINI --}}
        <h3 class="news-section-title"><i class="fas fa-newspaper"></i> Berita &amp; Informasi Terkini</h3>

        <div class="row g-4">
            @foreach ($beritaList as $b)
                @php
                    $kategori = $b['label'] ?? 'Berita Banjir';
                @endphp
                <div class="col-lg-4 col-md-6">
                    <a href="{{ $b['link'] }}" target="_blank" rel="noopener noreferrer" class="news-card">
                        <div class="news-thumb">
                            @if (!empty($b['thumb']))
                                <img src="{{ $b['thumb'] }}" alt="{{ $b['judul'] }}" loading="lazy"
                                    decoding="async" referrerpolicy="no-referrer"
                                    onerror="this.style.display='none'; if(this.nextElementSibling){this.nextElementSibling.style.display='flex';}">
                                <div class="news-thumb-fallback"
                                    style="display:none;background:linear-gradient(135deg,{{ $b['color'] }},#0c4a6e);">
                                    <i class="fas {{ $b['icon'] }}"></i>
                                    <span>{{ $b['sumber'] }}</span>
                                </div>
                            @else
                                <div class="news-thumb-fallback"
                                    style="background:linear-gradient(135deg,{{ $b['color'] }},#0c4a6e);">
                                    <i class="fas {{ $b['icon'] }}"></i>
                                    <span>{{ $b['sumber'] }}</span>
                                </div>
                            @endif

                            <span class="news-cat-badge" style="background:{{ $b['color'] }};">
                                <i class="fas {{ $b['icon'] }}"></i> {{ $kategori }}
                            </span>

                            <span class="news-src-logo">
                                <i class="fas fa-newspaper" style="color:{{ $b['color'] }};font-size:10px;"></i>
                                {{ $b['host'] }}
                            </span>
                        </div>

                        <div class="news-body">
                            <div class="news-meta">
                                <span class="news-date">
                                    <i class="far fa-calendar-alt" style="color:{{ $b['color'] }};"></i>
                                    {{ $b['tanggal'] }}
                                </span>
                                <span style="color:#94a3b8;font-size:.78rem;font-weight:700;">
                                    <i class="fas fa-external-link-alt"></i> Baca Asli
                                </span>
                            </div>
                            <h3 class="news-title">{{ $b['judul'] }}</h3>
                            <p class="news-excerpt">{{ \Illuminate\Support\Str::limit($b['excerpt'], 145) }}</p>
                            <span class="btn-berita"
                                style="background:linear-gradient(135deg,{{ $b['color'] }},#0c4a6e);">
                                Baca Selengkapnya <i class="fas fa-external-link-alt" style="font-size:.8em;"></i>
                            </span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="cta-section">
            <h3><i class="fas fa-exclamation-circle"></i> Punya Informasi Banjir?</h3>
            <p class="lead">Bantu kami monitoring dengan melaporkan kejadian banjir di sekitar Anda secara real-time</p>
            <a href="{{ route('laporan') }}" class="btn-cta">
                <i class="fas fa-paper-plane"></i> Lapor Banjir Sekarang
            </a>
        </div>
    </div>

    <div id="w-update-toast">
        <i class="fas fa-map-marker-alt"></i>
        <span id="w-toast-msg">Cuaca diperbarui untuk lokasi Anda</span>
    </div>
@endsection

@section('script')
    <script>
        const W_API = "{{ url('/api/weather') }}";
        const BANTUL = {
            lat: -7.8877,
            lon: 110.3302
        };
        let _lat = BANTUL.lat;
        let _lon = BANTUL.lon;
        let _timer = null;

        const _serverData = @json($weatherPayload);

        function renderWeather(d) {
            const c = d.current || {};
            const f = Array.isArray(d.forecast) ? d.forecast : [];

            const setTxt = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.textContent = (val ?? '--');
            };

            const setCls = (id, cls) => {
                const el = document.getElementById(id);
                if (el) el.className = 'fas ' + (cls || 'fa-cloud-sun');
            };

            setCls('w-icon', c.icon);
            setTxt('w-temp', c.temp);
            setTxt('w-desc', c.weather);
            setTxt('w-feels', 'Terasa seperti ' + (c.feels_like ?? c.temp ?? '--') + '°C');
            setTxt('w-hum', (c.humidity ?? '--') + '%');
            setTxt('w-wind', (c.wind_speed ?? '--') + ' km/h');
            setTxt('w-rain', (c.rain_prob ?? '--') + '%');
            setTxt('w-vis', (c.visibility ?? '--') + ' km');
            setTxt('w-uv', c.uv_index);
            setTxt('w-pres', c.pressure);
            setTxt('w-gust', c.wind_gust);
            setTxt('w-feels2', c.feels_like ?? c.temp ?? '--');
            setTxt('w-updated', d.updated ?? '--');
            setTxt('w-loc-text', d.location ?? 'Kabupaten Bantul');

            const grid = document.getElementById('w-forecast-grid');
            if (grid && f.length) {
                grid.innerHTML = f.map(day => `
            <div class="fc-card">
                <div class="fc-date">${day.date ?? '-'}</div>
                <div class="fc-ico"><i class="fas ${day.icon ?? 'fa-cloud'}"></i></div>
                <div class="fc-temps">
                    <span class="fc-max">${day.temp_max ?? '--'}°</span>
                    <span class="fc-min">${day.temp_min ?? '--'}°</span>
                </div>
                <div class="fc-wdesc">${day.weather_text ?? '-'}</div>
                <div class="fc-rain"><i class="fas fa-tint"></i> ${day.rain_prob ?? '--'}%</div>
            </div>
        `).join('');
            }

            updateBMKGAlert(d);
        }

        function updateBMKGAlert(d) {
            const box = document.getElementById('bmkgBox');
            const icon = document.getElementById('bmkgIcon');
            const title = document.getElementById('bmkgTitle');
            const desc = document.getElementById('bmkgDesc');
            if (!box || !icon || !title || !desc) return;

            const loc = d.location || 'Lokasi Anda';
            const c = d.current || {};
            // ✅ Pakai cuaca HARI INI saja, bukan max 7 hari
            const rain = Number(c.rain_prob || 0);
            const wind = Number(c.wind_speed || 0);
            const wCode = Number(c.weather_code || 0);
            const temp = Number(c.temp || 0);

            let level, faIcon, t, dStr;

            if (wCode >= 95 || wind >= 40) {
                level = 'lvl-danger';
                faIcon = 'fa-bolt';
                t = `⚡ PERINGATAN CUACA EKSTREM — ${loc}`;
                dStr =
                    `Waspadai <strong>badai petir dan angin kencang</strong> di ${loc}. Kondisi berbahaya! Hindari aktivitas luar ruangan dan daerah rawan banjir.`;
            } else if (rain >= 70 || wCode === 65 || wCode === 82) {
                level = 'lvl-danger';
                faIcon = 'fa-cloud-showers-heavy';
                t = `🌧️ HUJAN LEBAT — ${loc}`;
                dStr =
                    `Saat ini terjadi <strong>hujan lebat</strong> di ${loc} dengan peluang hujan <strong>${rain}%</strong>. Waspadai banjir dan genangan air.`;
            } else if (rain >= 50 || wCode >= 61) {
                level = 'lvl-warn';
                faIcon = 'fa-cloud-rain';
                t = `🌦️ Potensi Hujan Sedang — ${loc}`;
                dStr =
                    `Potensi hujan di ${loc} dengan peluang <strong>${rain}%</strong>. Siapkan payung dan waspadai genangan air di jalan.`;
            } else if (rain >= 25 || wCode >= 51) {
                level = 'lvl-info';
                faIcon = 'fa-cloud-drizzle';
                t = `🌦️ Kemungkinan Gerimis — ${loc}`;
                dStr =
                    `Cuaca di ${loc} berpotensi gerimis ringan dengan peluang <strong>${rain}%</strong>. Tetap pantau perkembangan cuaca.`;
            } else {
                level = 'lvl-ok';
                faIcon = 'fa-sun';
                t = `☀️ Cuaca Aman — ${loc}`;
                dStr =
                    `Kondisi cuaca di ${loc} <strong>cerah dan aman</strong>. Suhu ${temp}°C dengan peluang hujan hanya <strong>${rain}%</strong>. Aktivitas luar ruangan aman dilakukan.`;
            }

            box.className = 'bmkg-box ' + level;
            icon.className = 'fas ' + faIcon;
            title.innerHTML = t;
            desc.innerHTML = dStr;
        }

        function showToast(msg) {
            const toast = document.getElementById('w-update-toast');
            const msgEl = document.getElementById('w-toast-msg');
            if (!toast) return;
            if (msgEl) msgEl.textContent = msg;
            toast.style.display = 'flex';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3500);
        }

        async function fetchAndRender(lat, lon, showToastMsg) {
            try {
                const resp = await fetch(`${W_API}?lat=${encodeURIComponent(lat)}&lon=${encodeURIComponent(lon)}`, {
                    headers: {
                        Accept: 'application/json'
                    },
                    cache: 'no-store',
                });
                if (!resp.ok) throw new Error('HTTP ' + resp.status);
                const data = await resp.json();
                if (data.error) throw new Error(data.error);
                if (data.current && data.current.temp !== undefined) {
                    renderWeather(data);
                    sessionStorage.setItem('_w_last_fetch', Date.now().toString()); // ✅ track waktu fetch
                    if (showToastMsg) showToast(showToastMsg);
                    return true; // ✅ sukses
                }
                return false;
            } catch (e) {
                console.warn('[Weather] Fetch gagal:', e.message);
                return false;
            }
        }

        function startRefreshTimer() {
            clearInterval(_timer);
            // ✅ OPTIMASI: Cek document.hidden — berhenti refresh saat tab tidak aktif
            _timer = setInterval(() => {
                if (document.hidden) return;
                fetchAndRender(_lat, _lon, null);
            }, 30 * 60 * 1000); // 30 menit
        }

        // ✅ OPTIMASI: Pause/resume timer saat tab tidak aktif
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden && _lat && _lon) {
                // Tab aktif kembali — refresh cuaca jika sudah > 30 menit
                const lastFetch = parseInt(sessionStorage.getItem('_w_last_fetch') || '0');
                if (Date.now() - lastFetch > 30 * 60 * 1000) {
                    fetchAndRender(_lat, _lon, null);
                }
            }
        });

        function initWeather() {
            const loadingEl = document.getElementById('w-loading');
            const contentEl = document.getElementById('w-content');

            function showContent() {
                if (loadingEl) loadingEl.style.display = 'none';
                if (contentEl) contentEl.style.display = 'block';
            }

            if (loadingEl) loadingEl.style.display = 'flex';
            if (contentEl) contentEl.style.display = 'none';

            if ('geolocation' in navigator) {
                navigator.geolocation.getCurrentPosition(
                    async pos => {
                            _lat = pos.coords.latitude;
                            _lon = pos.coords.longitude;
                            const ok = await fetchAndRender(_lat, _lon, null);
                            if (!ok) {
                                // API gagal, pakai server data
                                renderWeather(_serverData);
                            }
                            showContent();
                            startRefreshTimer();
                        },
                        (err) => {
                            console.warn('[Weather] Geolocation ditolak/gagal:', err.message);
                            renderWeather(_serverData);
                            showContent();
                            startRefreshTimer();
                        }, {
                            enableHighAccuracy: false,
                            timeout: 8000,
                            maximumAge: 0
                        } // ✅ maximumAge:0 = selalu fresh
                );
            } else {
                renderWeather(_serverData);
                showContent();
                startRefreshTimer();
            }
        }

        function requestLocation() {
            const loadingEl = document.getElementById('w-loading');
            const contentEl = document.getElementById('w-content');
            if (loadingEl) loadingEl.style.display = 'flex';
            if (contentEl) contentEl.style.display = 'none';

            navigator.geolocation.getCurrentPosition(
                async pos => {
                        _lat = pos.coords.latitude;
                        _lon = pos.coords.longitude;
                        const ok = await fetchAndRender(_lat, _lon, 'Cuaca diperbarui untuk lokasi Anda!');
                        if (!ok) renderWeather(_serverData);
                        if (loadingEl) loadingEl.style.display = 'none';
                        if (contentEl) contentEl.style.display = 'block';
                    },
                    () => {
                        alert('Izin lokasi ditolak. Aktifkan lokasi di browser kamu ya!');
                        if (loadingEl) loadingEl.style.display = 'none';
                        if (contentEl) contentEl.style.display = 'block';
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
            );
        }

        document.addEventListener('DOMContentLoaded', initWeather);
    </script>
@endsection
