@extends('layouts.public')

@section('styles')
    <link href='https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap'
        rel='stylesheet'>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ==================== HERO ==================== */
        .peta-hero {
            background: linear-gradient(135deg, #0c4a6e 0%, #0891b2 50%, #06b6d4 100%);
            color: white;
            padding: 4rem 0;
            position: relative;
            overflow: hidden;
            margin-bottom: 0;
        }

        .peta-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(255, 255, 255, 0.07) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(6, 182, 212, 0.15) 0%, transparent 50%);
        }

        .peta-hero::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 50px;
            background: #f0f9ff;
            clip-path: ellipse(55% 100% at 50% 100%);
        }

        .peta-hero h1 {
            font-weight: 900;
            font-size: 2.8rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 2;
        }

        .peta-hero p {
            font-size: 1.2rem;
            opacity: 0.95;
            position: relative;
            z-index: 2;
        }

        /* ==================== MAP WRAPPER ==================== */
        .map-outer-wrapper {
            position: relative;
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(8, 145, 178, 0.15);
            overflow: hidden;
        }

        #map {
            height: 620px;
            width: 100%;
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border: 4px solid white;
        }

        /* ==================== FULLSCREEN ==================== */
        .map-outer-wrapper.is-fullscreen {
            position: fixed !important;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 99990;
            border-radius: 0;
            margin: 0;
        }

        .map-outer-wrapper.is-fullscreen #map {
            height: 100vh !important;
            border-radius: 0;
            border: none;
        }

        .map-outer-wrapper.is-fullscreen .layer-panel {
            top: 16px;
            right: 16px;
        }

        .map-outer-wrapper.is-fullscreen .map-toolbar {
            top: 16px;
            left: 16px;
        }

        .map-outer-wrapper.is-fullscreen .search-panel {
            top: 16px;
            left: 50%;
            transform: translateX(-50%);
        }

        /* ==================== TOOLBAR (kiri atas peta) ==================== */
        .map-toolbar {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .toolbar-btn {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            border: 2px solid white;
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(8, 145, 178, 0.4);
            transition: all 0.25s;
            font-size: 15px;
            outline: none;
        }

        .toolbar-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(8, 145, 178, 0.55);
            background: linear-gradient(135deg, #0e7490, #0891b2);
        }

        .toolbar-btn.exit-fs {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            display: none;
        }

        .toolbar-btn.exit-fs:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
        }

        .is-fullscreen .toolbar-btn.exit-fs {
            display: flex;
        }

        .is-fullscreen .toolbar-btn.enter-fs {
            display: none;
        }

        /* ==================== SEARCH PANEL (tengah atas) ==================== */
        .search-panel {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            width: 290px;
        }

        .search-inner {
            display: flex;
            background: white;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.22);
            border: 2px solid rgba(8, 145, 178, 0.25);
            overflow: hidden;
        }

        .search-inner select {
            flex: 1;
            border: none;
            outline: none;
            padding: 10px 12px;
            font-size: 13px;
            font-weight: 600;
            color: #0c4a6e;
            background: transparent;
            cursor: pointer;
        }

        .search-inner select option {
            color: #334155;
            font-weight: 600;
        }

        .search-btn {
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: white;
            border: none;
            padding: 10px 14px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: 700;
        }

        .search-btn:hover {
            background: linear-gradient(135deg, #0e7490, #0891b2);
        }

        .search-clear {
            background: #f1f5f9;
            color: #64748b;
            border: none;
            padding: 10px 10px;
            font-size: 13px;
            cursor: pointer;
            border-left: 1px solid #e2e8f0;
            display: none;
            align-items: center;
            transition: all 0.2s;
        }

        .search-clear:hover {
            background: #fee2e2;
            color: #ef4444;
        }

        .search-clear.visible {
            display: flex;
        }

        .search-result-badge {
            margin-top: 7px;
            background: rgba(8, 145, 178, 0.9);
            color: white;
            border-radius: 8px;
            padding: 5px 12px;
            font-size: 11px;
            font-weight: 700;
            text-align: center;
            display: none;
            backdrop-filter: blur(6px);
        }

        .search-result-badge.visible {
            display: block;
        }

        /* ==================== LAYER PANEL (kanan atas) ==================== */
        .layer-panel {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1000;
            width: 240px;
            font-size: 13px;
        }

        .layer-panel-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: white;
            padding: 10px 14px;
            border-radius: 12px;
            cursor: pointer;
            user-select: none;
            box-shadow: 0 6px 20px rgba(8, 145, 178, 0.4);
            transition: all 0.2s;
            border: none;
            width: 100%;
        }

        .layer-panel-toggle:hover {
            background: linear-gradient(135deg, #0e7490, #0891b2);
        }

        .layer-panel-toggle span {
            font-weight: 700;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .layer-panel-toggle .arrow {
            transition: transform 0.3s;
            font-size: 11px;
        }

        .layer-panel-toggle.open .arrow {
            transform: rotate(180deg);
        }

        .layer-panel-body {
            background: white;
            border-radius: 0 0 14px 14px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.18);
            border: 2px solid rgba(8, 145, 178, 0.15);
            border-top: none;
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.4s;
        }

        .layer-panel-body.open {
            max-height: 600px;
        }

        /* FIX 4: inner scrollable agar tidak keluar viewport, dengan scrollbar tipis */
        .layer-panel-body-inner {
            padding: 14px;
            max-height: calc(100vh - 160px);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #0891b2 #f0f9ff;
        }

        .layer-panel-body-inner::-webkit-scrollbar {
            width: 5px;
        }

        .layer-panel-body-inner::-webkit-scrollbar-track {
            background: #f0f9ff;
            border-radius: 3px;
        }

        .layer-panel-body-inner::-webkit-scrollbar-thumb {
            background: #0891b2;
            border-radius: 3px;
        }

        .panel-section-hdr {
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            padding: 8px 10px;
            border-radius: 8px;
            background: #f0f9ff;
            margin-bottom: 10px;
            border: 1px solid #bae6fd;
            user-select: none;
            transition: background 0.2s;
        }

        .panel-section-hdr:hover {
            background: #e0f2fe;
        }

        .panel-section-hdr .title {
            font-weight: 700;
            color: #0c4a6e;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .panel-section-hdr .arrow-sm {
            font-size: 10px;
            color: #0891b2;
            transition: transform 0.25s;
        }

        .panel-section-hdr.open .arrow-sm {
            transform: rotate(180deg);
        }

        .panel-section-body {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.3s;
        }

        /* FIX 3: section tematik bisa di-scroll saat banyak layer aktif */
        .panel-section-body.open {
            max-height: 500px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #94a3b8 #f0f9ff;
        }

        .panel-section-body.open::-webkit-scrollbar {
            width: 4px;
        }

        .panel-section-body.open::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 3px;
        }

        .panel-divider {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 10px 0;
        }

        /* ==================== TEMATIK ACCORDION ==================== */
        .tematik-item {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            margin-bottom: 6px;
            overflow: hidden;
        }

        .tematik-item-header {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 10px;
            background: #f8fafc;
            cursor: pointer;
            user-select: none;
            transition: background 0.2s;
        }

        .tematik-item-header:hover {
            background: #f0f9ff;
        }

        .tematik-item-header input[type="checkbox"] {
            width: 14px;
            height: 14px;
            accent-color: #0891b2;
            cursor: pointer;
            flex-shrink: 0;
        }

        .tematik-item-title {
            font-weight: 700;
            color: #0c4a6e;
            font-size: 11px;
            flex: 1;
        }

        .tematik-item-arrow {
            font-size: 9px;
            color: #0891b2;
            transition: transform 0.25s;
            flex-shrink: 0;
        }

        .tematik-item-arrow.open {
            transform: rotate(180deg);
        }

        .tematik-item-body {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: white;
            padding: 0 10px;
        }

        .tematik-item-body.open {
            max-height: 400px;
            padding: 8px 10px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f0f9ff;
        }

        .tematik-item-body.open::-webkit-scrollbar {
            width: 4px;
        }

        .tematik-item-body.open::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .layer-item {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 7px 8px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
            margin-bottom: 4px;
        }

        .layer-item:hover {
            background: #f0f9ff;
        }

        .layer-item input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: #0891b2;
            cursor: pointer;
            flex-shrink: 0;
        }

        .layer-item-label {
            font-weight: 600;
            color: #334155;
            font-size: 12px;
            line-height: 1.3;
        }

        .legend-box {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            flex-shrink: 0;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .legend-box.circle {
            border-radius: 50%;
        }

        .basemap-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 7px;
            padding-top: 2px;
        }

        .basemap-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding: 8px 4px;
            border-radius: 9px;
            border: 2px solid #e2e8f0;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
        }

        .basemap-btn:hover {
            border-color: #0891b2;
            background: #f0f9ff;
            transform: translateY(-2px);
        }

        .basemap-btn.active {
            border-color: #0891b2;
            background: linear-gradient(135deg, #e0f2fe, #f0f9ff);
            box-shadow: 0 4px 12px rgba(8, 145, 178, 0.25);
        }

        .basemap-icon {
            width: 36px;
            height: 28px;
            border-radius: 5px;
            background-size: cover;
            background-position: center;
            border: 1px solid #e2e8f0;
        }

        .basemap-label {
            font-size: 10px;
            font-weight: 700;
            color: #475569;
        }

        .basemap-btn.active .basemap-label {
            color: #0891b2;
        }

        /* ==================== LOADING OVERLAY ==================== */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.98);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            border-radius: 20px;
        }

        /* ==================== INFO PANEL ==================== */
        .info-panel {
            background: white;
            border-radius: 20px;
            padding: 1.75rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            border: 2px solid rgba(8, 145, 178, 0.1);
        }

        .info-panel h5 {
            color: #0c4a6e;
            font-weight: 800;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* ==================== SOURCE BADGE ==================== */
        .source-badge {
            margin-top: 10px;
            padding: 8px 14px;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 3px solid #0891b2;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .source-badge i {
            color: #0891b2;
            font-size: 11px;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .source-badge span {
            font-size: 11px;
            color: #64748b;
            line-height: 1.5;
        }

        .source-badge strong {
            color: #0c4a6e;
        }

        /* ==================== TABS ==================== */
        .nav-tabs {
            background: white;
            border-radius: 15px 15px 0 0;
            padding: 0.5rem 1rem 0;
            border-bottom: 4px solid #e2e8f0;
        }

        .nav-tabs .nav-item {
            margin-bottom: -4px;
        }

        .nav-tabs .nav-link {
            color: white;
            background: linear-gradient(135deg, #2e3c3f, #4f676b) !important;
            border: 2px solid #e2e8f0;
            border-bottom: none;
            padding: 1rem 1.5rem;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s;
            margin-right: 0.5rem;
            border-radius: 10px 10px 0 0;
        }

        .nav-tabs .nav-link:hover {
            background: #19699e !important;
            border-color: #0891b2;
            transform: translateY(-3px);
        }

        .nav-tabs .nav-link.active {
            color: white !important;
            background: linear-gradient(135deg, #0891b2, #06b6d4) !important;
            border-color: #0891b2 !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .nav-tabs .nav-link i {
            margin-right: 0.5rem;
        }

        .tab-content {
            background: white;
            border-radius: 0 0 15px 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: 2px solid #e2e8f0;
            border-top: none;
        }

        .tab-pane h5 {
            color: #0c4a6e;
            font-weight: 800;
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
        }

        /* ==================== TABLE ==================== */
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
            padding: 1rem;
            font-weight: 700;
        }

        .table tbody tr {
            transition: all 0.3s;
        }

        .table tbody tr:hover {
            background: #f0f9ff;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }

        /* ==================== POPUP BUTTONS ==================== */
        .popup-btn-detail {
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: white;
            border: none;
            padding: 0.6rem 1rem;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .popup-btn-detail:hover {
            background: linear-gradient(135deg, #0e7490, #0891b2);
            transform: translateY(-2px);
        }

        .popup-btn-route {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            padding: 0.6rem 1rem;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .popup-btn-route:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-2px);
        }

        /* ==================== DETAIL MODAL ==================== */
        .detail-modal {
            display: none;
            position: fixed;
            z-index: 99998;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
        }

        .detail-modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .detail-modal-content {
            background: white;
            border-radius: 25px;
            width: 90%;
            max-width: 900px;
            max-height: 85vh;
            overflow-y: auto;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5);
            position: relative;
            animation: slideUp 0.3s ease;
        }

        .detail-modal-header {
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: white;
            padding: 2rem;
            border-radius: 25px 25px 0 0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .detail-modal-header h3 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 900;
        }

        .detail-modal-close {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.5);
            color: white;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.5rem;
            font-weight: bold;
            transition: all 0.3s;
        }

        .detail-modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        .detail-modal-body {
            padding: 2.5rem;
        }

        .detail-section {
            margin-bottom: 2rem;
        }

        .detail-section-title {
            color: #0c4a6e;
            font-weight: 800;
            font-size: 1.25rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 3px solid #e2e8f0;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .detail-item {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.25rem;
            border: 2px solid #e2e8f0;
        }

        .detail-item-label {
            color: #64748b;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .detail-item-value {
            color: #0f172a;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .detail-foto {
            width: 100%;
            max-width: 500px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            cursor: pointer;
            transition: all 0.3s;
        }

        .detail-foto:hover {
            transform: scale(1.05);
        }

        .badge-verified {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
            border: 2px solid #10b981;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ==================== MODAL IMAGE ==================== */
        .modal-image-viewer {
            display: none;
            position: fixed;
            z-index: 99999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.95);
            backdrop-filter: blur(8px);
        }

        .modal-image-content {
            position: relative;
            margin: auto;
            padding: 20px;
            width: 90%;
            max-width: 1200px;
            top: 50%;
            transform: translateY(-50%);
        }

        .modal-image-viewer img {
            width: 100%;
            height: auto;
            max-height: 85vh;
            object-fit: contain;
            border-radius: 15px;
        }

        .modal-close-btn {
            position: absolute;
            top: -15px;
            right: -15px;
            color: #fff;
            background: #ef4444;
            font-size: 32px;
            font-weight: bold;
            width: 55px;
            height: 55px;
            border-radius: 50%;
            border: 4px solid white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            z-index: 100000;
            line-height: 1;
        }

        .modal-close-btn:hover {
            background: #dc2626;
            transform: scale(1.15) rotate(90deg);
        }

        /* ==================== OVERRIDES ==================== */
        .leaflet-control-layers {
            display: none !important;
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 768px) {
            #map {
                height: 480px;
                border-radius: 15px;
            }

            .layer-panel {
                top: 10px;
                right: 10px;
                width: 200px;
            }

            .peta-hero h1 {
                font-size: 2.2rem;
            }

            .search-panel {
                width: 200px;
            }

            .map-toolbar {
                top: 10px;
                left: 10px;
            }
        }

        /* ===== MOBILE RESPONSIVE TAMBAHAN (tidak menghapus apapun di atas) ===== */
        @media (max-width: 767px) {

            /* Hero peta */
            .peta-hero h1 {
                font-size: 2rem;
            }

            /* Map lebih pendek */
            #map {
                height: 420px;
                border-radius: 14px;
            }

            /* Toolbar kiri: ukuran lebih kecil */
            .map-toolbar {
                top: 8px;
                left: 8px;
                gap: 6px;
            }

            .toolbar-btn {
                width: 36px;
                height: 36px;
                font-size: 13px;
            }

            /* KUNCI: Search panel dipindah ke kiri (bawah toolbar)
                                                                   agar tidak BERTABRAKAN dengan layer panel di kanan */
            .search-panel {
                top: 8px;
                left: 52px;
                transform: none;
                width: calc(100% - 175px);
                min-width: 130px;
            }

            .search-inner select {
                padding: 8px 7px;
                font-size: 11px;
            }

            .search-btn {
                padding: 8px 10px;
                font-size: 11px;
            }

            /* Layer panel kanan: dipersempit agar tidak tumpang tindih */
            .layer-panel {
                top: 8px;
                right: 8px;
                width: 150px;
            }

            .layer-panel-toggle {
                padding: 8px 10px;
            }

            .layer-panel-toggle span {
                font-size: 11px;
                gap: 5px;
            }

            .layer-panel-body.open {
                max-height: 360px;
            }

            .layer-panel-body-inner {
                padding: 10px;
                max-height: calc(100vh - 120px);
            }

            .layer-item {
                gap: 6px;
                padding: 5px 6px;
                margin-bottom: 2px;
            }

            .layer-item-label {
                font-size: 10px;
            }

            .legend-box {
                width: 12px;
                height: 12px;
            }

            .panel-section-hdr {
                padding: 6px 8px;
            }

            .panel-section-hdr .title {
                font-size: 10px;
            }

            .basemap-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 4px;
            }

            .basemap-icon {
                width: 27px;
                height: 21px;
            }

            .basemap-label {
                font-size: 8px;
            }

            /* Info panel */
            .info-panel {
                padding: 1rem;
                border-radius: 14px;
                margin-bottom: 1.25rem;
            }

            .info-panel h5 {
                font-size: 0.95rem;
            }

            .info-panel p {
                font-size: 0.84rem;
            }

            /* KUNCI: Tabs scroll horizontal — tidak bertumpuk / tidak terpotong */
            .nav-tabs {
                overflow-x: auto;
                flex-wrap: nowrap;
                -webkit-overflow-scrolling: touch;
                padding: 0.4rem 0.5rem 0;
            }

            .nav-tabs .nav-item {
                flex-shrink: 0;
            }

            .nav-tabs .nav-link {
                padding: 0.65rem 0.9rem;
                font-size: 0.82rem;
                white-space: nowrap;
                margin-right: 0.3rem;
            }

            .tab-content {
                padding: 1rem;
                overflow-x: auto;
            }

            /* Tabel */
            .table thead th {
                padding: 0.7rem 0.5rem;
                font-size: 0.79rem;
            }

            .table tbody td {
                padding: 0.7rem 0.5rem;
                font-size: 0.8rem;
            }

            /* Detail modal lebih kompak di HP */
            .detail-modal-content {
                width: 96%;
                border-radius: 16px;
            }

            .detail-modal-header {
                padding: 1.25rem 1.5rem;
                border-radius: 16px 16px 0 0;
            }

            .detail-modal-header h3 {
                font-size: 1.2rem;
            }

            .detail-modal-body {
                padding: 1.25rem;
            }

            .detail-grid {
                grid-template-columns: 1fr 1fr;
                gap: 0.75rem;
            }

            .detail-item {
                padding: 0.85rem;
            }
        }

        @media (max-width: 420px) {
            .search-panel {
                width: calc(100% - 165px);
                min-width: 110px;
            }

            .layer-panel {
                width: 132px;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div class="peta-hero">
        <div class="container text-center">
            <h1 class="mb-3"><i class="fas fa-map-marked-alt"></i> Peta Kerawanan Banjir</h1>
            <p class="lead mb-0">Peta Interaktif Zona Kerawanan &amp; Data Kejadian Banjir Kabupaten Bantul</p>
        </div>
    </div>

    <div class="container mt-4 mb-5">

        <!-- Info Panel -->
        <div class="info-panel">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-2"><i class="fas fa-info-circle"></i> Panduan Peta</h5>
                    <p class="mb-0 text-sm">
                        <strong>Layer:</strong> Klik <strong>🗂 Layer & Legenda</strong> di kanan atas peta.
                        <strong>Cari Kecamatan:</strong> Gunakan kotak pencarian di tengah atas peta.
                        <strong>Fullscreen:</strong> Klik <i class="fas fa-expand"></i> di kiri atas peta untuk tampilan
                        penuh.
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <span class="badge bg-success me-2"><i class="fas fa-check-circle"></i> {{ count($laporan) }} Laporan
                        Masyarakat</span>
                    <span class="badge bg-primary"><i class="fas fa-database"></i> {{ $totalHistoris }} Data Historis</span>
                </div>
            </div>
        </div>

        <!-- Map Container -->
        <div class="map-outer-wrapper" id="mapOuterWrapper">

            <!-- Loading Overlay -->
            <div id="loading-overlay" class="loading-overlay">
                <div class="text-center">
                    <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem;"></div>
                    <p class="text-muted">Memuat peta dan layer...</p>
                </div>
            </div>

            <!-- ── TOOLBAR KIRI ATAS ── -->
            <div class="map-toolbar">
                <button class="toolbar-btn enter-fs" onclick="toggleFullscreen()" title="Tampilan Fullscreen">
                    <i class="fas fa-expand"></i>
                </button>
                <button class="toolbar-btn exit-fs" onclick="toggleFullscreen()" title="Keluar Fullscreen">
                    <i class="fas fa-compress"></i>
                </button>
                <button class="toolbar-btn" onclick="zoomToExtent()" title="Zoom ke Semua Data">
                    <i class="fas fa-expand-arrows-alt"></i>
                </button>
            </div>

            <!-- ── SEARCH KECAMATAN (tengah atas) ── -->
            <div class="search-panel">
                <div class="search-inner">
                    <select id="searchKecamatan" onchange="doSearch()">
                        <option value="">🔍 Cari Kecamatan...</option>
                        @foreach (['Banguntapan', 'Bantul', 'Bambanglipuro', 'Dlingo', 'Imogiri', 'Jetis', 'Kasihan', 'Kretek', 'Pajangan', 'Pandak', 'Piyungan', 'Pleret', 'Pundong', 'Sanden', 'Sedayu', 'Sewon', 'Srandakan'] as $kec)
                            <option value="{{ $kec }}">{{ $kec }}</option>
                        @endforeach
                    </select>
                    <button class="search-clear" id="searchClearBtn" onclick="clearSearch()" title="Reset">
                        <i class="fas fa-times"></i>
                    </button>
                    <button class="search-btn" onclick="doSearch()">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="search-result-badge" id="searchResultBadge"></div>
            </div>

            <!-- ── LAYER PANEL KANAN ATAS ── -->
            <div class="layer-panel">
                <button class="layer-panel-toggle open" id="layerPanelToggle" onclick="toggleLayerPanel()">
                    <span><i class="fas fa-layer-group"></i> Layer &amp; Legenda</span>
                    <span class="arrow">▲</span>
                </button>
                <div class="layer-panel-body open" id="layerPanelBody">
                    <div class="layer-panel-body-inner">

                        <!-- Layer Section -->
                        <div class="panel-section-hdr open" id="layerSectionHdr"
                            onclick="toggleSection('layerSection','layerSectionHdr')">
                            <span class="title"><i class="fas fa-map"></i> Layer Peta</span>
                            <span class="arrow-sm">▲</span>
                        </div>
                        <div class="panel-section-body open" id="layerSection">
                            <hr class="panel-divider">
                            <label class="layer-item">
                                <input type="checkbox" id="chk-administrasi" checked
                                    onchange="toggleLayer('administrasi',this.checked)">
                                <div style="display:flex;align-items:center;gap:6px;flex:1;">
                                    <div
                                        style="width:16px;height:16px;border:2px dashed #1e3c72;border-radius:3px;flex-shrink:0;">
                                    </div>
                                    <span class="layer-item-label">Batas Administrasi</span>
                                </div>
                            </label>
                            <hr class="panel-divider">
                            <label class="layer-item">
                                <input type="checkbox" id="chk-laporan" checked
                                    onchange="toggleLayer('laporan',this.checked)">
                                <div style="display:flex;align-items:center;gap:6px;flex:1;">
                                    <img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png"
                                        style="width:13px;height:21px;object-fit:contain;flex-shrink:0;" alt="marker merah">
                                    <span class="layer-item-label">Laporan Masyarakat</span>
                                </div>
                            </label>
                            <label class="layer-item">
                                <input type="checkbox" id="chk-historis" checked
                                    onchange="toggleLayer('historis',this.checked)">
                                <div style="display:flex;align-items:center;gap:6px;flex:1;">
                                    <img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png"
                                        style="width:13px;height:21px;object-fit:contain;flex-shrink:0;"
                                        alt="marker biru">
                                    <span class="layer-item-label">Titik Historis BPBD</span>
                                </div>
                            </label>
                            <label class="layer-item">
                                <input type="checkbox" id="chk-stasiun" checked
                                    onchange="toggleLayer('stasiun',this.checked)">
                                <div style="display:flex;align-items:center;gap:6px;flex:1;">
                                    <img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png"
                                        style="width:13px;height:21px;object-fit:contain;flex-shrink:0;"
                                        alt="marker hijau">
                                    <span class="layer-item-label">Stasiun Pemantau Hujan</span>
                                </div>
                            </label>
                        </div>

                        <hr class="panel-divider" style="margin:3px 0;">

                        <!-- Peta Tematik Section -->
                        <div class="panel-section-hdr" id="tematikSectionHdr"
                            onclick="toggleSection('tematikSection','tematikSectionHdr')">
                            <span class="title"><i class="fas fa-layer-group"></i> Peta Tematik</span>
                            <span class="arrow-sm">▲</span>
                        </div>
                        <div class="panel-section-body" id="tematikSection">
                            <div style="font-size:10px;color:#94a3b8;margin-bottom:8px;padding:0 2px;">
                                <i class="fas fa-info-circle"></i> Klik nama layer untuk buka legenda
                            </div>

                            <!-- 1. Kerawanan banjir -->
                            <div class="tematik-item">
                                <div class="tematik-item-header" onclick="toggleTematikAccordion('acc-banjir',this)">
                                    <input type="checkbox" id="chk-banjir"
                                        onclick="event.stopPropagation();toggleTematik('banjir',this.checked)">
                                    <span class="tematik-item-title"><i class="fas fa-mountain"
                                            style="color:#0891b2;margin-right:4px;"></i>Kerawanan Banjir</span>
                                    <span class="tematik-item-arrow">▲</span>
                                </div>
                                <div class="tematik-item-body" id="acc-banjir">
                                    <div style="display:flex;flex-direction:column;gap:5px;padding:6px 0;">
                                        <div style="display:flex;align-items:center;gap:6px;">
                                            <span
                                                style="width:14px;height:14px;background:#10b981;border-radius:3px;flex-shrink:0;border:1px solid rgba(0,0,0,0.1);"></span>
                                            <span style="font-size:9px;color:#475569;font-weight:600;">Sangat Rendah</span>
                                        </div>
                                        <div style="display:flex;align-items:center;gap:6px;">
                                            <span
                                                style="width:14px;height:14px;background:#84cc16;border-radius:3px;flex-shrink:0;border:1px solid rgba(0,0,0,0.1);"></span>
                                            <span style="font-size:9px;color:#475569;font-weight:600;">Rendah</span>
                                        </div>
                                        <div style="display:flex;align-items:center;gap:6px;">
                                            <span
                                                style="width:14px;height:14px;background:#f4ad33;border-radius:3px;flex-shrink:0;border:1px solid rgba(0,0,0,0.1);"></span>
                                            <span style="font-size:9px;color:#475569;font-weight:600;">Sedang</span>
                                        </div>
                                        <div style="display:flex;align-items:center;gap:6px;">
                                            <span
                                                style="width:14px;height:14px;background:#ef4444;border-radius:3px;flex-shrink:0;border:1px solid rgba(0,0,0,0.1);"></span>
                                            <span style="font-size:9px;color:#475569;font-weight:600;">Tinggi</span>
                                        </div>
                                        <div style="display:flex;align-items:center;gap:6px;">
                                            <span
                                                style="width:14px;height:14px;background:#991b1b;border-radius:3px;flex-shrink:0;border:1px solid rgba(0,0,0,0.1);"></span>
                                            <span style="font-size:9px;color:#475569;font-weight:600;">Sangat Tinggi</span>
                                        </div>
                                        <div style="font-size:8px;color:#94a3b8;font-style:italic;margin-top:2px;">
                                            Field: Keterangan | Total: nilai skor
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Kemiringan Lereng -->
                            <div class="tematik-item">
                                <div class="tematik-item-header" onclick="toggleTematikAccordion('acc-slope',this)">
                                    <input type="checkbox" id="chk-slope"
                                        onclick="event.stopPropagation();toggleTematik('slope',this.checked)">
                                    <span class="tematik-item-title"><i class="fas fa-angle-double-up"
                                            style="color:#0891b2;margin-right:4px;"></i>Kemiringan Lereng</span>
                                    <span class="tematik-item-arrow">▲</span>
                                </div>
                                <div class="tematik-item-body" id="acc-slope">
                                    <div style="font-size:9px;font-weight:800;color:#10b981;margin-top:4px;">● Skor 1 —
                                        Sangat Rendah</div>
                                    <div style="padding-left:4px;margin-top:2px;margin-bottom:4px;">
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:10px;height:10px;background:#a8d5a2;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">&gt;40°</span></div>
                                    </div>
                                    <div style="font-size:9px;font-weight:800;color:#84cc16;">● Skor 2 — Rendah</div>
                                    <div style="padding-left:4px;margin-top:2px;margin-bottom:4px;">
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:10px;height:10px;background:#7dba5f;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">25–40°</span></div>
                                    </div>
                                    <div style="font-size:9px;font-weight:800;color:#f59e0b;">● Skor 3 — Sedang</div>
                                    <div style="padding-left:4px;margin-top:2px;margin-bottom:4px;">
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:10px;height:10px;background:#f59e0b;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">15–25°</span></div>
                                    </div>
                                    <div style="font-size:9px;font-weight:800;color:#ef4444;">● Skor 4 — Tinggi</div>
                                    <div style="padding-left:4px;margin-top:2px;margin-bottom:4px;">
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:10px;height:10px;background:#ef4444;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">2–15°</span></div>
                                    </div>
                                    <div style="font-size:9px;font-weight:800;color:#991b1b;">● Skor 5 — Sangat Tinggi
                                    </div>
                                    <div style="padding-left:4px;margin-top:2px;margin-bottom:4px;">
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:10px;height:10px;background:#991b1b;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">0–2° (Datar)</span></div>
                                    </div>
                                    <div style="font-size:8px;color:#94a3b8;font-style:italic;margin-top:2px;">Makin datar
                                        = makin rawan genangan</div>
                                </div>
                            </div>

                            <!-- 3. Curah Hujan -->
                            <div class="tematik-item">
                                <div class="tematik-item-header" onclick="toggleTematikAccordion('acc-rain',this)">
                                    <input type="checkbox" id="chk-rain"
                                        onclick="event.stopPropagation();toggleTematik('rain',this.checked)">
                                    <span class="tematik-item-title"><i class="fas fa-cloud-rain"
                                            style="color:#0891b2;margin-right:4px;"></i>Curah Hujan</span>
                                    <span class="tematik-item-arrow">▲</span>
                                </div>
                                <div class="tematik-item-body" id="acc-rain">
                                    <div style="display:flex;flex-direction:column;gap:4px;padding:4px 0;">
                                        <div style="font-size:8px;color:#94a3b8;font-style:italic;margin-bottom:2px;">Curah
                                            Hujan Tahunan (mm/thn)</div>
                                        <div style="display:flex;align-items:center;gap:4px;"><span
                                                style="width:12px;height:12px;background:#9ecae1;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:9px;color:#475569;">Rendah &nbsp;(1.000–1.500)</span>
                                        </div>
                                        <div style="display:flex;align-items:center;gap:4px;"><span
                                                style="width:12px;height:12px;background:#6baed6;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:9px;color:#475569;">Sedang (1.500–2.000)</span></div>
                                        <div style="display:flex;align-items:center;gap:4px;"><span
                                                style="width:12px;height:12px;background:#2171b5;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:9px;color:#475569;">Tinggi &nbsp;(2.000–2.500)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. Penggunaan Lahan -->
                            <div class="tematik-item">
                                <div class="tematik-item-header" onclick="toggleTematikAccordion('acc-landuse',this)">
                                    <input type="checkbox" id="chk-landuse"
                                        onclick="event.stopPropagation();toggleTematik('landuse',this.checked)">
                                    <span class="tematik-item-title"><i class="fas fa-map"
                                            style="color:#0891b2;margin-right:4px;"></i>Penggunaan Lahan</span>
                                    <span class="tematik-item-arrow">▲</span>
                                </div>
                                <div class="tematik-item-body" id="acc-landuse">
                                    <div style="font-size:9px;font-weight:800;color:#10b981;margin-top:4px;">● Skor 1 —
                                        Sangat Rendah</div>
                                    <div
                                        style="display:grid;grid-template-columns:1fr 1fr;gap:2px 4px;padding-left:4px;margin-top:2px;margin-bottom:4px;">
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:9px;height:9px;background:#1a6b1a;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Hutan Lebat</span></div>
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:9px;height:9px;background:#2d8a2d;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Hutan Sejenis</span></div>
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:9px;height:9px;background:#a8d5a2;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Emplasemen</span></div>
                                    </div>
                                    <div style="font-size:9px;font-weight:800;color:#84cc16;">● Skor 2 — Rendah</div>
                                    <div
                                        style="display:grid;grid-template-columns:1fr 1fr;gap:2px 4px;padding-left:4px;margin-top:2px;margin-bottom:4px;">
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:9px;height:9px;background:#7dba5f;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Padang Rumput</span></div>
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:9px;height:9px;background:#b5d96d;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Semak</span></div>
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:9px;height:9px;background:#d4c08a;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Tanah Tandus</span></div>
                                    </div>
                                    <div style="font-size:9px;font-weight:800;color:#f59e0b;">● Skor 3 — Sedang</div>
                                    <div
                                        style="display:grid;grid-template-columns:1fr 1fr;gap:2px 4px;padding-left:4px;margin-top:2px;margin-bottom:4px;">
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:9px;height:9px;background:#8db600;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Kebun Campuran</span></div>
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:9px;height:9px;background:#cddc39;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Tegalan/Ladang</span></div>
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:9px;height:9px;background:#9b59b6;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Industri Non P.</span></div>
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:9px;height:9px;background:#e67e22;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Industri Pertanian</span></div>
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:9px;height:9px;background:#7f8c8d;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Pertambangan</span></div>
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:9px;height:9px;background:#00bcd4;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Sarana OR</span></div>
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:9px;height:9px;background:#bcaaa4;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Tanah Pgn. Lain</span></div>
                                    </div>
                                    <div style="font-size:9px;font-weight:800;color:#ef4444;">● Skor 4 — Tinggi</div>
                                    <div
                                        style="display:grid;grid-template-columns:1fr 1fr;gap:2px 4px;padding-left:4px;margin-top:2px;margin-bottom:4px;">
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:9px;height:9px;background:#26a69a;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Sawah Irigasi</span></div>
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:9px;height:9px;background:#1565c0;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Sungai</span></div>
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:9px;height:9px;background:#0d47a1;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Tambak</span></div>
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:9px;height:9px;background:#64b5f6;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Kolam</span></div>
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:9px;height:9px;background:#4dd0e1;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Penggaraman</span></div>
                                    </div>
                                    <div style="font-size:9px;font-weight:800;color:#991b1b;">● Skor 5 — Sangat Tinggi
                                    </div>
                                    <div
                                        style="display:grid;grid-template-columns:1fr 1fr;gap:2px 4px;padding-left:4px;margin-top:2px;margin-bottom:4px;">
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:9px;height:9px;background:#ff7043;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Kampung</span></div>
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:9px;height:9px;background:#e53935;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Perumahan</span></div>
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:9px;height:9px;background:#8e44ad;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Kuburan/Makam</span></div>
                                    </div>
                                </div>
                            </div>

                            <!-- 5. Jenis Tanah -->
                            <div class="tematik-item">
                                <div class="tematik-item-header" onclick="toggleTematikAccordion('acc-soil',this)">
                                    <input type="checkbox" id="chk-soil"
                                        onclick="event.stopPropagation();toggleTematik('soil',this.checked)">
                                    <span class="tematik-item-title"><i class="fas fa-seedling"
                                            style="color:#0891b2;margin-right:4px;"></i>Jenis Tanah</span>
                                    <span class="tematik-item-arrow">▲</span>
                                </div>
                                <div class="tematik-item-body" id="acc-soil">
                                    <div style="font-size:9px;font-weight:800;color:#10b981;margin-top:4px;">● Skor 1 —
                                        Sangat Rendah</div>
                                    <div style="padding-left:4px;margin-top:2px;margin-bottom:4px;">
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:10px;height:10px;background:#a8d5a2;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Regosol</span></div>
                                    </div>
                                    <div style="font-size:9px;font-weight:800;color:#84cc16;">● Skor 2 — Rendah</div>
                                    <div
                                        style="display:grid;grid-template-columns:1fr 1fr;gap:2px 4px;padding-left:4px;margin-top:2px;margin-bottom:4px;">
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:10px;height:10px;background:#7dba5f;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Kambisol</span></div>
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:10px;height:10px;background:#b5d96d;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Grumusol</span></div>
                                    </div>
                                    <div style="font-size:9px;font-weight:800;color:#f59e0b;">● Skor 3 — Sedang</div>
                                    <div style="padding-left:4px;margin-top:2px;margin-bottom:4px;">
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:10px;height:10px;background:#f59e0b;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Mediterania</span></div>
                                    </div>
                                    <div style="font-size:9px;font-weight:800;color:#ef4444;">● Skor 4 — Tinggi</div>
                                    <div
                                        style="display:grid;grid-template-columns:1fr 1fr;gap:2px 4px;padding-left:4px;margin-top:2px;margin-bottom:4px;">
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:10px;height:10px;background:#fb923c;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Gleisol</span></div>
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:10px;height:10px;background:#ef4444;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Rendsina</span></div>
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:10px;height:10px;background:#dc2626;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Latosol</span></div>
                                    </div>
                                    <div style="font-size:9px;font-weight:800;color:#991b1b;">● Skor 5 — Sangat Tinggi
                                    </div>
                                    <div style="padding-left:4px;margin-top:2px;margin-bottom:4px;">
                                        <div style="display:flex;align-items:center;gap:3px;"><span
                                                style="width:10px;height:10px;background:#991b1b;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:8px;color:#475569;">Aluvial</span></div>
                                    </div>
                                </div>
                            </div>

                            <!-- 6. Jarak dari Sungai -->
                            <div class="tematik-item">
                                <div class="tematik-item-header" onclick="toggleTematikAccordion('acc-river',this)">
                                    <input type="checkbox" id="chk-river"
                                        onclick="event.stopPropagation();toggleTematik('river',this.checked)">
                                    <span class="tematik-item-title"><i class="fas fa-water"
                                            style="color:#0891b2;margin-right:4px;"></i>Jarak dari Sungai</span>
                                    <span class="tematik-item-arrow">▲</span>
                                </div>
                                <div class="tematik-item-body" id="acc-river">
                                    <div style="display:flex;flex-direction:column;gap:4px;padding:4px 0;">
                                        <div style="font-size:8px;color:#94a3b8;font-style:italic;margin-bottom:2px;">Jarak
                                            dari Sungai (Makin dekat = makin rawan)</div>
                                        <div style="display:flex;align-items:center;gap:4px;"><span
                                                style="width:12px;height:12px;background:#08519c;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:9px;color:#475569;">0 – 25 m &nbsp;&nbsp;&nbsp;(Skor
                                                5)</span></div>
                                        <div style="display:flex;align-items:center;gap:4px;"><span
                                                style="width:12px;height:12px;background:#2171b5;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:9px;color:#475569;">25 – 100 m &nbsp;(Skor 4)</span></div>
                                        <div style="display:flex;align-items:center;gap:4px;"><span
                                                style="width:12px;height:12px;background:#4292c6;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:9px;color:#475569;">100 – 250 m (Skor 3)</span></div>
                                        <div style="display:flex;align-items:center;gap:4px;"><span
                                                style="width:12px;height:12px;background:#6baed6;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:9px;color:#475569;">250 – 550 m (Skor 2)</span></div>
                                        <div style="display:flex;align-items:center;gap:4px;"><span
                                                style="width:12px;height:12px;background:#deebf7;border:1px solid #ccc;border-radius:2px;flex-shrink:0;"></span><span
                                                style="font-size:9px;color:#475569;">&gt; 550 m &nbsp;&nbsp;(Skor 1)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="panel-divider" style="margin:3px 0;">

                        <!-- Basemap Section -->
                        <div class="panel-section-hdr open" id="basemapSectionHdr"
                            onclick="toggleSection('basemapSection','basemapSectionHdr')">
                            <span class="title"><i class="fas fa-globe"></i> Basemap</span>
                            <span class="arrow-sm">▲</span>
                        </div>
                        <div class="panel-section-body open" id="basemapSection">
                            <div class="basemap-grid">
                                <button class="basemap-btn active" id="btn-streets" onclick="switchBasemap('streets')">
                                    <div class="basemap-icon"
                                        style="background-image:url('https://tile.openstreetmap.org/11/1620/1012.png');">
                                    </div>
                                    <span class="basemap-label">Streets</span>
                                </button>
                                <button class="basemap-btn" id="btn-satellite" onclick="switchBasemap('satellite')">
                                    <div class="basemap-icon"
                                        style="background-image:url('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/11/1012/1620');">
                                    </div>
                                    <span class="basemap-label">Satellite</span>
                                </button>
                                <button class="basemap-btn" id="btn-topo" onclick="switchBasemap('topo')">
                                    <div class="basemap-icon"
                                        style="background-image:url('https://tile.opentopomap.org/11/1620/1012.png');">
                                    </div>
                                    <span class="basemap-label">Topo</span>
                                </button>
                            </div>
                        </div>

                        <hr class="panel-divider" style="margin:3px 0;">

                    </div>
                </div>
            </div>

            <div id="map"></div>
        </div>

        <!-- Tabs Tabel -->
        <div class="info-panel mt-4">
            <ul class="nav nav-tabs mb-3" id="dataTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="laporan-tab" data-bs-toggle="tab"
                        data-bs-target="#laporan-content" type="button">
                        <i class="fas fa-users"></i> Laporan Masyarakat ({{ count($laporan) }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="historis-tab" data-bs-toggle="tab" data-bs-target="#historis-content"
                        type="button">
                        <i class="fas fa-database"></i> Data Historis BPBD ({{ $totalHistoris }})
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="dataTabsContent">

                <!-- Tab Laporan Masyarakat -->
                <div class="tab-pane fade show active" id="laporan-content" role="tabpanel">
                    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                        <h5 class="mb-0"><i class="fas fa-table"></i> Data Kejadian Banjir dari Laporan Masyarakat</h5>
                        <div class="d-flex align-items-center gap-2">
                            <label style="font-size:13px;font-weight:600;color:#64748b;">Tampilkan:</label>
                            <select id="laporanPerPage" onchange="renderLaporanPage(1)"
                                style="border:2px solid #e2e8f0;border-radius:8px;padding:4px 10px;font-size:13px;font-weight:700;color:#0c4a6e;cursor:pointer;">
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="999">Semua</option>
                            </select>
                            <span id="laporanInfo" style="font-size:12px;color:#94a3b8;"></span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Foto</th>
                                    <th>Waktu</th>
                                    <th>Lokasi</th>
                                    <th>Kedalaman</th>
                                    <th>Pelapor</th>
                                    <th>Deskripsi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="laporan-tbody">
                                @forelse($laporan as $index => $item)
                                    <tr class="laporan-row" data-index="{{ $index }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @php $fotos = array_values(array_filter([$item->foto, $item->foto2 ?? null, $item->foto3 ?? null])); @endphp
                                            @if (count($fotos) > 0)
                                                <div style="display:flex;gap:3px;flex-wrap:wrap;">
                                                    @foreach ($fotos as $fi => $f)
                                                        <div style="position:relative;">
                                                            @php $fotoUrl = str_starts_with($f, 'http') ? $f : asset('uploads/laporan/' . $f); @endphp
                                                            <img src="{{ $fotoUrl }}" alt="Foto {{ $fi + 1 }}"
                                                                style="width:44px;height:44px;object-fit:cover;border-radius:7px;cursor:pointer;border:2px solid #e2e8f0;"
                                                                onclick="openImageModal('{{ $fotoUrl }}')"
                                                                title="Foto {{ $fi + 1 }}"
                                                                onerror="this.style.display='none'">
                                                            @if ($fi === 0 && count($fotos) > 1)
                                                                <span
                                                                    style="position:absolute;bottom:1px;right:1px;background:rgba(8,145,178,0.85);color:white;font-size:8px;font-weight:900;padding:0 3px;border-radius:3px;">+{{ count($fotos) }}</span>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted" style="font-size:11px;">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->waktu_laporan->format('d/m/Y H:i') }}</td>
                                        <td><strong>{{ $item->kecamatan }}</strong><br><small
                                                class="text-muted">{{ $item->desa }}</small></td>
                                        <td>
                                            <span
                                                class="badge @if ($item->kedalaman_cm >= 70) bg-danger @elseif($item->kedalaman_cm >= 40) bg-warning @else bg-info @endif">
                                                {{ $item->kedalaman_cm }} cm
                                            </span>
                                        </td>
                                        <td>{{ $item->nama_pelapor }}</td>
                                        <td>{{ Str::limit($item->deskripsi, 50) }}</td>
                                        <td style="white-space:nowrap;">
                                            <button onclick="showDetail({{ $item->id }})"
                                                class="btn btn-sm btn-info me-1"><i class="fas fa-info-circle"></i>
                                                Detail</button>
                                            <button
                                                onclick="zoomToMarker({{ $item->latitude }},{{ $item->longitude }},'laporan')"
                                                class="btn btn-sm btn-outline-primary"><i
                                                    class="fas fa-search-location"></i> Lihat</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">Belum ada laporan
                                            terverifikasi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div id="laporanPagination" class="d-flex justify-content-center gap-2 mt-3 flex-wrap"></div>
                    <div class="source-badge">
                        <i class="fas fa-database"></i>
                        <span><strong>Sumber Data:</strong> Laporan masyarakat yang telah diverifikasi oleh Admin BPBD
                            Kabupaten Bantul melalui sistem WebGIS. Diperbarui secara real-time.</span>
                    </div>
                </div>

                <!-- Tab Data Historis -->
                <div class="tab-pane fade" id="historis-content" role="tabpanel">
                    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                        <h5 class="mb-0"><i class="fas fa-table"></i> Data Kejadian Banjir Historis dari BPBD</h5>
                        <div class="d-flex align-items-center gap-2">
                            <label style="font-size:13px;font-weight:600;color:#64748b;">Tampilkan:</label>
                            <select id="historisPerPage" onchange="renderHistorisPage(1)"
                                style="border:2px solid #e2e8f0;border-radius:8px;padding:4px 10px;font-size:13px;font-weight:700;color:#0c4a6e;cursor:pointer;">
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="999">Semua</option>
                            </select>
                            <span id="historisInfo" style="font-size:12px;color:#94a3b8;"></span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kecamatan</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Pemicu</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="historis-table-body">
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <div class="spinner-border spinner-border-sm me-2"></div>Memuat data historis...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="historisPagination" class="d-flex justify-content-center gap-2 mt-3 flex-wrap"></div>
                    <div class="source-badge">
                        <i class="fas fa-database"></i>
                        <span><strong>Sumber Data:</strong> Data kejadian banjir historis Kabupaten Bantul tahun 2020–2025.
                            Sumber: Badan Penanggulangan Bencana Daerah (BPBD) Kabupaten Bantul.</span>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- DETAIL MODAL -->
    <div id="detailModal" class="detail-modal" onclick="closeDetailModal()">
        <div class="detail-modal-content" onclick="event.stopPropagation()">
            <div class="detail-modal-header">
                <h3><i class="fas fa-file-alt"></i> Detail Laporan Banjir</h3>
                <button class="detail-modal-close" onclick="closeDetailModal()">×</button>
            </div>
            <div class="detail-modal-body">
                <div class="detail-section">
                    <h4 class="detail-section-title"><i class="fas fa-info-circle"></i> Informasi Laporan</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-item-label">ID Laporan</div>
                            <div class="detail-item-value" id="detail-id">-</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-item-label">Tanggal & Waktu</div>
                            <div class="detail-item-value" id="detail-waktu">-</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-item-label">Status</div>
                            <div class="detail-item-value" id="detail-status">-</div>
                        </div>
                    </div>
                </div>
                <div class="detail-section">
                    <h4 class="detail-section-title"><i class="fas fa-user"></i> Data Pelapor</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-item-label">Nama Pelapor</div>
                            <div class="detail-item-value" id="detail-pelapor">-</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-item-label">No. Telepon</div>
                            <div class="detail-item-value" id="detail-telp">-</div>
                        </div>
                    </div>
                </div>
                <div class="detail-section">
                    <h4 class="detail-section-title"><i class="fas fa-map-marker-alt"></i> Lokasi Banjir</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-item-label">Kecamatan</div>
                            <div class="detail-item-value" id="detail-kecamatan">-</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-item-label">Desa</div>
                            <div class="detail-item-value" id="detail-desa">-</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-item-label">Koordinat</div>
                            <div class="detail-item-value" id="detail-koordinat">-</div>
                        </div>
                    </div>
                </div>
                <div class="detail-section">
                    <h4 class="detail-section-title"><i class="fas fa-water"></i> Kondisi Banjir</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-item-label">Kedalaman Air</div>
                            <div class="detail-item-value" id="detail-kedalaman">-</div>
                        </div>
                    </div>
                    <div class="detail-item mt-3">
                        <div class="detail-item-label">Deskripsi</div>
                        <div class="detail-item-value" id="detail-deskripsi" style="font-weight:500;line-height:1.6;">-
                        </div>
                    </div>
                </div>
                <div class="detail-section" id="section-kebutuhan">
                    <h4 class="detail-section-title">
                        <i class="fas fa-hands-helping" style="color:#f59e0b;"></i> Kebutuhan / Bantuan yang Diperlukan
                    </h4>
                    <div class="detail-item">
                        <div class="detail-item-value" id="detail-kebutuhan"
                            style="font-weight:500;line-height:1.6;background:#fffbeb;border:1.5px solid #fde68a;border-radius:10px;padding:10px 14px;color:#92400e;">
                            -</div>
                    </div>
                </div>

                <div class="detail-section">
                    <h4 class="detail-section-title"><i class="fas fa-camera"></i> Dokumentasi Foto</h4>
                    <div id="detail-foto-container" style="display:flex;gap:12px;flex-wrap:wrap;justify-content:center;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- IMAGE MODAL -->
    <div id="imageModal" class="modal-image-viewer">
        <div class="modal-image-content">
            <span class="modal-close-btn" onclick="closeImageModal()">&times;</span>
            <img id="modalImage" src="" alt="Foto Laporan">
        </div>
    </div>
@endsection

@section('script')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Helper: handle Cloudinary URL atau local file
        function getFotoUrl(f) {
            if (!f) return '';
            return f.startsWith('http') ? f : '/uploads/laporan/' + f;
        }
        // ── DATA ───────────────────────────────────────────────────────────
        const laporanData = {!! json_encode($laporan->keyBy('id'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!};

        // Koordinat pusat tiap kecamatan Bantul
        const kecamatanCenter = {
            'Banguntapan': [-7.8283, 110.4167],
            'Bantul': [-7.8900, 110.3300],
            'Bambanglipuro': [-7.9667, 110.3167],
            'Dlingo': [-7.9500, 110.4667],
            'Imogiri': [-7.9333, 110.4000],
            'Jetis': [-7.8667, 110.3500],
            'Kasihan': [-7.8333, 110.3333],
            'Kretek': [-8.0000, 110.2833],
            'Pajangan': [-7.8833, 110.2833],
            'Pandak': [-7.9167, 110.3167],
            'Piyungan': [-7.8667, 110.4500],
            'Pleret': [-7.8833, 110.4000],
            'Pundong': [-7.9833, 110.3500],
            'Sanden': [-7.9833, 110.2833],
            'Sedayu': [-7.8167, 110.2833],
            'Sewon': [-7.8500, 110.3667],
            'Srandakan': [-7.9667, 110.2667],
        };

        // ── MAP VARS ───────────────────────────────────────────────────────
        let map, layerGroups = {},
            basemaps = {},
            currentBasemap = 'streets';
        let historisData = [],
            bantulGeoJSON = null,
            searchHighlightLayer = null;
        let isFullscreen = false;

        // ── FULLSCREEN ─────────────────────────────────────────────────────
        function toggleFullscreen() {
            const wrapper = document.getElementById('mapOuterWrapper');
            isFullscreen = !isFullscreen;
            if (isFullscreen) {
                wrapper.classList.add('is-fullscreen');
                document.body.style.overflow = 'hidden';
            } else {
                wrapper.classList.remove('is-fullscreen');
                document.body.style.overflow = '';
            }
            setTimeout(() => {
                if (map) map.invalidateSize();
            }, 350);
        }

        // ── SEARCH KECAMATAN ───────────────────────────────────────────────
        function pointInPolygon(lat, lng, feature) {
            const coords = feature.geometry.type === 'Polygon' ? [feature.geometry.coordinates] :
                feature.geometry.coordinates;
            for (const polygon of coords) {
                if (pip(lng, lat, polygon[0])) return true;
            }
            return false;
        }

        function pip(x, y, vs) {
            let inside = false;
            for (let i = 0, j = vs.length - 1; i < vs.length; j = i++) {
                const xi = vs[i][0],
                    yi = vs[i][1];
                const xj = vs[j][0],
                    yj = vs[j][1];
                if (((yi > y) !== (yj > y)) && (x < (xj - xi) * (y - yi) / (yj - yi) + xi)) {
                    inside = !inside;
                }
            }
            return inside;
        }

        function doSearch() {
            const val = document.getElementById('searchKecamatan').value;
            const badge = document.getElementById('searchResultBadge');
            const clearBtn = document.getElementById('searchClearBtn');

            if (searchHighlightLayer) {
                map.removeLayer(searchHighlightLayer);
                searchHighlightLayer = null;
            }

            if (!val) {
                badge.classList.remove('visible');
                clearBtn.classList.remove('visible');
                map.flyTo([-7.8700, 110.3300], 11, {
                    duration: 1
                });
                return;
            }

            clearBtn.classList.add('visible');

            let kecFeature = null;
            if (bantulGeoJSON) {
                kecFeature = bantulGeoJSON.features.find(f =>
                    (f.properties.WADMC || f.properties.WADMKC || '') === val
                );
            }

            if (kecFeature) {
                const layer = L.geoJSON(kecFeature);
                map.flyToBounds(layer.getBounds(), {
                    padding: [30, 30],
                    duration: 1.2
                });
            } else {
                const center = kecamatanCenter[val];
                if (center) map.flyTo(center, 13, {
                    duration: 1.2
                });
            }

            let jmlLaporan = 0;
            let jmlHistoris = 0;

            if (kecFeature) {
                Object.values(laporanData).forEach(l => {
                    if (pointInPolygon(parseFloat(l.latitude), parseFloat(l.longitude), kecFeature))
                        jmlLaporan++;
                });
                historisData.forEach(f => {
                    const c = f.geometry.coordinates;
                    if (pointInPolygon(c[1], c[0], kecFeature)) jmlHistoris++;
                });
            } else {
                jmlLaporan = Object.values(laporanData).filter(l => l.kecamatan === val).length;
                jmlHistoris = historisData.filter(f => (f.properties.Kecamatan || '') === val).length;
            }

            badge.textContent = `📍 ${val}: ${jmlLaporan} laporan • ${jmlHistoris} historis`;
            badge.classList.add('visible');

            if (kecFeature) {
                searchHighlightLayer = L.geoJSON(kecFeature, {
                    style: {
                        fillColor: '#fbbf24',
                        fillOpacity: 0.35,
                        color: '#f59e0b',
                        weight: 3,
                        opacity: 1
                    }
                }).addTo(map);
            }
        }

        function clearSearch() {
            document.getElementById('searchKecamatan').value = '';
            doSearch();
        }

        // ── ZOOM TO EXTENT ─────────────────────────────────────────────────
        function zoomToExtent() {
            const bounds = [];
            Object.values(laporanData).forEach(l => {
                if (l.latitude && l.longitude)
                    bounds.push([parseFloat(l.latitude), parseFloat(l.longitude)]);
            });
            historisData.forEach(f => {
                const c = f.geometry.coordinates;
                if (c && c[0] && c[1]) bounds.push([c[1], c[0]]);
            });
            if (bounds.length === 0) {
                map.flyTo([-7.8700, 110.3300], 11, {
                    duration: 1.2
                });
                return;
            }
            map.flyToBounds(L.latLngBounds(bounds), {
                padding: [50, 50],
                duration: 1.2,
                maxZoom: 14
            });
        }

        // ── PANEL TOGGLE ───────────────────────────────────────────────────
        function toggleLayerPanel() {
            document.getElementById('layerPanelBody').classList.toggle('open');
            document.getElementById('layerPanelToggle').classList.toggle('open');
        }

        function toggleSection(sId, hId) {
            document.getElementById(sId).classList.toggle('open');
            document.getElementById(hId).classList.toggle('open');
        }

        // ── TEMATIK ACCORDION ──────────────────────────────────────────
        function toggleTematikAccordion(bodyId, headerEl) {
            const body = document.getElementById(bodyId);
            const arrow = headerEl.querySelector('.tematik-item-arrow');
            body.classList.toggle('open');
            arrow.classList.toggle('open');
        }

        // ── IMAGE MODAL ────────────────────────────────────────────────────
        function openImageModal(src) {
            if (event) {
                event.stopPropagation();
                event.preventDefault();
            }
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            document.getElementById('imageModal').style.display = 'none';
            if (!isFullscreen) document.body.style.overflow = '';
        }

        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) closeImageModal();
        });

        // ── DETAIL MODAL ───────────────────────────────────────────────────
        function showDetail(laporanId) {
            const laporan = laporanData[laporanId];
            if (!laporan) {
                alert('Data laporan tidak ditemukan');
                return;
            }

            document.getElementById('detail-id').textContent = laporan.id;
            document.getElementById('detail-waktu').textContent = formatDateTime(laporan.waktu_laporan);
            document.getElementById('detail-status').innerHTML =
                '<span class="badge badge-verified"><i class="fas fa-check-circle"></i> Verified</span>';
            document.getElementById('detail-pelapor').textContent = laporan.nama_pelapor || '-';
            document.getElementById('detail-telp').textContent = laporan.no_telp || '-';
            document.getElementById('detail-kecamatan').textContent = laporan.kecamatan || '-';
            document.getElementById('detail-desa').textContent = laporan.desa || '-';
            document.getElementById('detail-koordinat').textContent = `${laporan.latitude}, ${laporan.longitude}`;
            document.getElementById('detail-kedalaman').textContent = `${laporan.kedalaman_cm || 0} cm`;
            document.getElementById('detail-deskripsi').textContent = laporan.deskripsi || 'Tidak ada deskripsi';
            // Kebutuhan / Bantuan
            const kebutuhanEl = document.getElementById('detail-kebutuhan');
            const kebutuhanSection = document.getElementById('section-kebutuhan');
            if (laporan.kebutuhan_bantuan && laporan.kebutuhan_bantuan.trim()) {
                kebutuhanEl.textContent = laporan.kebutuhan_bantuan;
                kebutuhanSection.style.display = 'block';
            } else {
                kebutuhanSection.style.display = 'none';
            }

            const fotoContainer = document.getElementById('detail-foto-container');
            const fotoFields = [laporan.foto, laporan.foto2, laporan.foto3].filter(Boolean);
            if (fotoFields.length > 0) {
                fotoContainer.innerHTML = fotoFields.map((f, i) => `
                    <div style="text-align:center;">
                        <img src="${getFotoUrl(f)}" alt="Foto ${i + 1}" class="detail-foto"
                             onclick="openImageModal('${getFotoUrl(f)}')"
                             style="width:160px;height:160px;object-fit:cover;border-radius:14px;
                                    cursor:pointer;border:3px solid #e2e8f0;
                                    box-shadow:0 6px 20px rgba(0,0,0,0.1);">
                        <div style="font-size:11px;font-weight:700;color:#64748b;margin-top:6px;">Foto ${i + 1}</div>
                    </div>
                `).join('');
            } else {
                fotoContainer.innerHTML =
                    '<p class="text-muted" style="width:100%;text-align:center;padding:1.5rem 0;">' +
                    '<i class="fas fa-image fa-3x mb-3 d-block"></i>Tidak ada foto</p>';
            }

            document.getElementById('detailModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.remove('show');
            if (!isFullscreen) document.body.style.overflow = '';
        }

        function formatDateTime(ds) {
            return new Date(ds).toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                if (isFullscreen) toggleFullscreen();
                closeImageModal();
                closeDetailModal();
            }
        });

        // ── INIT MAP ───────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', initMap);

        function initMap() {
            map = L.map('map', {
                center: [-7.8700, 110.3300],
                zoom: 11,
                zoomControl: false
            });
            L.control.zoom({
                position: 'bottomright'
            }).addTo(map);

            basemaps.streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap',
                maxZoom: 19
            });
            basemaps.satellite = L.tileLayer(
                'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    attribution: '© Esri',
                    maxZoom: 19
                }
            );
            basemaps.topo = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenTopoMap',
                maxZoom: 17
            });
            basemaps.streets.addTo(map);

            layerGroups.administrasi = L.layerGroup().addTo(map);
            layerGroups.laporan = L.layerGroup().addTo(map);
            layerGroups.historis = L.layerGroup().addTo(map);
            layerGroups.stasiun = L.layerGroup().addTo(map);

            Promise.all([
                loadBatasAdministrasi(),
                loadLaporanMasyarakat(),
                loadTitikHistoris(),
                loadStasiunPemantau()
            ]).then(() => {
                setTimeout(() => {
                    document.getElementById('loading-overlay').style.display = 'none';
                    focusLaporanFromHome();
                }, 500);
            });
        }

        function toggleLayer(name, visible) {
            if (visible) {
                if (!map.hasLayer(layerGroups[name])) layerGroups[name].addTo(map);
            } else {
                if (map.hasLayer(layerGroups[name])) map.removeLayer(layerGroups[name]);
            }
        }

        function switchBasemap(type) {
            map.removeLayer(basemaps[currentBasemap]);
            basemaps[type].addTo(map);
            currentBasemap = type;
            document.querySelectorAll('.basemap-btn').forEach(b => b.classList.remove('active'));
            document.getElementById(`btn-${type}`).classList.add('active');
        }

        // ── LOAD LAYERS ──────────────────────────────────────────────────
        function loadBatasAdministrasi() {
            return fetch('/geojson/bantul.geojson')
                .then(r => r.json())
                .then(data => {
                    bantulGeoJSON = data;
                    L.geoJSON(data, {
                        style: {
                            fillColor: 'transparent',
                            color: '#1e3c72',
                            weight: 2,
                            opacity: 0.8,
                            dashArray: '5,5'
                        },
                        onEachFeature: (feature, layer) => {
                            const props = feature.properties;
                            layer.bindPopup(`
                                <div style="min-width:150px;">
                                    <h6><strong>Batas Administrasi</strong></h6>
                                    <p class="mb-0">Kecamatan ${props.WADMC || props.WADMKC || 'Kab. Bantul'}</p>
                                </div>`);
                            layerGroups.administrasi.addLayer(layer);
                        }
                    });
                }).catch(err => console.error('Error batas admin:', err));
        }

        function loadLaporanMasyarakat() {
            const laporanArr = Object.values(laporanData);
            const laporanIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34]
            });

            laporanArr.forEach(laporan => {
                const marker = L.marker([laporan.latitude, laporan.longitude], {
                    icon: laporanIcon
                });
                marker._laporanId = laporan.id;

                let html = `<div style="min-width:250px;max-width:300px;">
                    <h6 class="mb-2" style="font-weight:bold;font-size:14px;">
                        Laporan Masyarakat #${laporan.id}
                    </h6>`;

                const fotos = [laporan.foto, laporan.foto2, laporan.foto3].filter(Boolean);
                if (fotos.length > 0) {
                    html += `<div style="display:flex;gap:4px;margin-bottom:10px;flex-wrap:wrap;">`;
                    fotos.forEach((f, i) => {
                        const w = fotos.length === 1 ? '100%' :
                            fotos.length === 2 ? 'calc(50% - 2px)' :
                            'calc(33.3% - 3px)';
                        html += `<img src="${getFotoUrl(f)}"
                            style="width:${w};height:80px;object-fit:cover;border-radius:8px;
                                   cursor:pointer;border:2px solid #e2e8f0;"
                            onclick="openImageModal('${getFotoUrl(f)}')">`;
                    });
                    html += `</div>`;
                    if (fotos.length > 1) {
                        html += `<p style="font-size:10px;color:#64748b;margin-bottom:8px;text-align:center;">
                            ${fotos.length} foto — klik untuk memperbesar</p>`;
                    }
                }

                const kd = laporan.kedalaman_cm >= 70 ?
                    'background:#fee2e2;color:#991b1b;' :
                    laporan.kedalaman_cm >= 40 ?
                    'background:#fef3c7;color:#92400e;' :
                    'background:#dbeafe;color:#1e40af;';

                html += `
                    <p style="margin-bottom:6px;"><strong>Lokasi:</strong> ${laporan.kecamatan}, ${laporan.desa}</p>
                    <p style="margin-bottom:6px;"><strong>Kedalaman:</strong>
                        <span style="display:inline-block;padding:2px 8px;border-radius:4px;
                                     font-size:12px;font-weight:bold;${kd}">${laporan.kedalaman_cm} cm</span></p>
                    <p style="margin-bottom:6px;"><strong>Pelapor:</strong> ${laporan.nama_pelapor}</p>
                    <p style="margin-bottom:12px;"><strong>Waktu:</strong>
                        ${new Date(laporan.waktu_laporan).toLocaleString('id-ID')}</p>
                    <div style="display:flex;gap:8px;">
                        <button class="popup-btn-detail" onclick="showDetail(${laporan.id})" style="flex:1;">
                            <i class="fas fa-info-circle"></i> Detail
                        </button>
                        <button class="popup-btn-route"
                            onclick="goToRoute(${laporan.latitude},${laporan.longitude},'Laporan #${laporan.id}')"
                            style="flex:1;">
                            <i class="fas fa-route"></i> Rute
                        </button>
                    </div>
                </div>`;

                marker.bindPopup(html, {
                    maxWidth: 320
                });
                layerGroups.laporan.addLayer(marker);
            });
            return Promise.resolve();
        }

        function focusLaporanFromHome() {
            const targetId = sessionStorage.getItem('bantara_focus_laporan');
            if (!targetId) return;
            let targetMarker = null;
            layerGroups.laporan.eachLayer(function(marker) {
                if (String(marker._laporanId) === String(targetId)) targetMarker = marker;
            });
            if (targetMarker) {
                map.setView(targetMarker.getLatLng(), 16);
                setTimeout(() => targetMarker.openPopup(), 300);
            }
            sessionStorage.removeItem('bantara_focus_laporan');
        }

        // ── FIX 1: loadTitikHistoris memanggil renderHistorisPage(1) ──────
        function loadTitikHistoris() {
            return fetch('/geojson/titikbanjir.geojson')
                .then(r => r.json())
                .then(data => {
                    historisData = data.features;
                    const historisIcon = L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34]
                    });
                    L.geoJSON(data, {
                        pointToLayer: (feature, latlng) => L.marker(latlng, {
                            icon: historisIcon
                        }),
                        onEachFeature: (feature, layer) => {
                            const props = feature.properties;
                            const coords = feature.geometry.coordinates;
                            layer.bindPopup(`
                                <div style="min-width:250px;max-width:300px;">
                                    <h6 class="mb-2" style="font-weight:bold;font-size:14px;color:#0c4a6e;">
                                        <i class="fas fa-database"></i> Data Historis BPBD #${props.No || '-'}
                                    </h6>
                                    <div style="background:#f0f9ff;padding:10px;border-radius:8px;margin-bottom:10px;">
                                        <p style="margin:4px 0;"><strong>📍 Kecamatan:</strong> ${props.Kecamatan || '-'}</p>
                                        <p style="margin:4px 0;"><strong>📅 Tanggal:</strong> ${props.Tanggal || '-'}</p>
                                        <p style="margin:4px 0;"><strong>⚠️ Jenis:</strong> ${props.Penyebab || '-'}</p>
                                        <p style="margin:4px 0 0;"><strong>💧 Pemicu:</strong> ${props.p || '-'}</p>
                                    </div>
                                    <button class="popup-btn-route"
                                        onclick="goToRoute(${coords[1]},${coords[0]},'Historis ${props.Kecamatan}')"
                                        style="width:100%;">
                                        <i class="fas fa-route"></i> Lihat Rute ke Lokasi
                                    </button>
                                </div>`);
                            layerGroups.historis.addLayer(layer);
                        }
                    });
                    // FIX 1: Ganti populateHistorisTable() → renderHistorisPage(1)
                    // agar tabel langsung tampil 25 baris pertama saat data siap
                    renderHistorisPage(1);
                })
                .catch(err => {
                    console.error('Error historis:', err);
                    document.getElementById('historis-table-body').innerHTML =
                        '<tr><td colspan="6" class="text-center text-danger">' +
                        '<i class="fas fa-exclamation-triangle"></i> Gagal memuat data historis</td></tr>';
                });
        }

        // ── LOAD STASIUN PEMANTAU HUJAN ───────────────────────────────────
        function loadStasiunPemantau() {
            return fetch('/geojson/stasiunpemantau.geojson')
                .then(r => r.json())
                .then(data => {
                    const stasiunIcon = L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34]
                    });
                    L.geoJSON(data, {
                        pointToLayer: (feature, latlng) => L.marker(latlng, {
                            icon: stasiunIcon
                        }),
                        onEachFeature: (feature, layer) => {
                            const p = feature.properties;
                            const nama = p.Nama_Stasi || '-';
                            const ch = p.CH !== undefined ? parseFloat(p.CH).toFixed(2) : '-';
                            layer.bindPopup(`
                                <div style="min-width:220px;font-family:inherit;">
                                    <div style="background:linear-gradient(135deg,#16a34a,#22c55e);
                                                color:white;padding:10px 14px;border-radius:10px 10px 0 0;
                                                margin:-1px -1px 0 -1px;">
                                        <h6 style="margin:0;font-weight:800;font-size:13px;">
                                            <i class="fas fa-tint"></i> Stasiun Pemantau Hujan
                                        </h6>
                                    </div>
                                    <div style="padding:12px;background:#f0fdf4;border-radius:0 0 10px 10px;">
                                        <p style="margin:4px 0;font-size:13px;">
                                            <strong style="color:#14532d;">📍 Nama Stasiun:</strong>
                                            <span style="color:#0f172a;font-weight:700;">${nama}</span>
                                        </p>
                                        <p style="margin:4px 0;font-size:13px;">
                                            <strong style="color:#14532d;">🌧️ Curah Hujan:</strong>
                                            <span style="background:#16a34a;color:white;padding:2px 10px;
                                                         border-radius:12px;font-size:12px;font-weight:700;">
                                                ${ch} mm/thn
                                            </span>
                                        </p>
                                    </div>
                                </div>`);
                            layer.bindTooltip(`<strong>${nama}</strong><br>${ch} mm/thn`, {
                                sticky: false,
                                className: 'leaflet-tooltip'
                            });
                            layerGroups.stasiun.addLayer(layer);
                        }
                    });
                })
                .catch(err => console.warn('Stasiun pemantau tidak ditemukan:', err));
        }

        function zoomToMarker(lat, lon, type) {
            map.flyTo([lat, lon], 16, {
                duration: 1.5
            });
            document.getElementById('map').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            setTimeout(() => {
                const target = type === 'laporan' ? layerGroups.laporan : layerGroups.historis;
                target.eachLayer(layer => {
                    if (layer.getLatLng &&
                        layer.getLatLng().lat === lat &&
                        layer.getLatLng().lng === lon)
                        layer.openPopup();
                });
            }, 1600);
        }

        function goToRoute(lat, lng, title) {
            window.location.href = `/peta/rute?lat=${lat}&lng=${lng}&title=${encodeURIComponent(title)}`;
        }

        // ── HISTORIS PAGINATION ────────────────────────────────────────────
        let historisCurrentPage = 1;

        // FIX 1 (lanjutan): Fungsi ini menggantikan populateHistorisTable() sepenuhnya
        function renderHistorisPage(page) {
            historisCurrentPage = page;
            const perPage = parseInt(document.getElementById('historisPerPage')?.value || 25);
            const tbody = document.getElementById('historis-table-body');
            const pagDiv = document.getElementById('historisPagination');
            const info = document.getElementById('historisInfo');

            if (!historisData.length) {
                tbody.innerHTML =
                    '<tr><td colspan="6" class="text-center text-muted">Tidak ada data historis</td></tr>';
                return;
            }

            const total = historisData.length;
            const totalPages = perPage >= 999 ? 1 : Math.ceil(total / perPage);
            const start = (page - 1) * perPage;
            const end = Math.min(start + perPage, total);
            const slice = historisData.slice(start, end);

            if (info) info.textContent = `${start + 1}–${end} dari ${total}`;

            tbody.innerHTML = slice.map((feature, i) => {
                const p = feature.properties;
                const c = feature.geometry.coordinates;
                return `<tr>
                    <td>${start + i + 1}</td>
                    <td><strong>${p.Kecamatan || '-'}</strong></td>
                    <td>${p.Tanggal || '-'}</td>
                    <td>${p.Penyebab || '-'}</td>
                    <td>${p.p || '-'}</td>
                    <td>
                        <button onclick="zoomToMarker(${c[1]},${c[0]},'historis')"
                            class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-search-location"></i> Lihat
                        </button>
                    </td>
                </tr>`;
            }).join('');

            renderPagBtns(pagDiv, page, totalPages, 'renderHistorisPage');
        }

        // ── LAPORAN PAGINATION ─────────────────────────────────────────────
        let laporanCurrentPage = 1;

        function renderLaporanPage(page) {
            laporanCurrentPage = page;
            const perPage = parseInt(document.getElementById('laporanPerPage')?.value || 25);
            const rows = Array.from(document.querySelectorAll('.laporan-row'));
            const total = rows.length;
            const pagDiv = document.getElementById('laporanPagination');
            const info = document.getElementById('laporanInfo');
            const totalPages = perPage >= 999 ? 1 : Math.ceil(total / perPage);
            const start = (page - 1) * perPage;
            const end = Math.min(start + perPage, total);

            rows.forEach((r, i) => {
                r.style.display = (i >= start && i < end) ? '' : 'none';
            });

            if (info) info.textContent = total ? `${start + 1}–${end} dari ${total}` : '';
            renderPagBtns(pagDiv, page, totalPages, 'renderLaporanPage');
        }

        function renderPagBtns(container, page, totalPages, fn) {
            if (!container) return;
            if (totalPages <= 1) {
                container.innerHTML = '';
                return;
            }

            const base = 'padding:4px 12px;border-radius:8px;border:2px solid #e2e8f0;' +
                'background:white;font-weight:700;font-size:13px;cursor:pointer;margin:2px;';
            const act = 'padding:4px 12px;border-radius:8px;border:2px solid #0891b2;' +
                'background:linear-gradient(135deg,#0891b2,#06b6d4);color:white;' +
                'font-weight:700;font-size:13px;cursor:pointer;margin:2px;';

            let html = '';
            if (page > 1)
                html += `<button style="${base}" onclick="${fn}(${page - 1})">‹</button>`;
            for (let p = Math.max(1, page - 2); p <= Math.min(totalPages, page + 2); p++) {
                html += `<button style="${p === page ? act : base}" onclick="${fn}(${p})">${p}</button>`;
            }
            if (page < totalPages)
                html += `<button style="${base}" onclick="${fn}(${page + 1})">›</button>`;

            container.innerHTML = html;
        }

        // ── TEMATIK LAYERS ─────────────────────────────────────────────────
        const tematikLayers = {};
        const tematikConfig = {
            banjir: {
                file: '/geojson/kerawanan.geojson',
                key: 'Keterangan',
                skorField: 'TOTAL',
                colors: {
                    'Sangat Rendah': '#10b981',
                    'Rendah': '#84cc16',
                    'Sedang': '#f59e0b',
                    'Tinggi': '#ef4444',
                    'Sangat Tinggi': '#991b1b'
                },
                skorLabels: {
                    1: 'Sangat Rendah',
                    2: 'Rendah',
                    3: 'Sedang',
                    4: 'Tinggi',
                    5: 'Sangat Tinggi'
                },
                def: '#94a3b8'
            },
            slope: {
                file: '/geojson/klkl.geojson',
                key: 'kemiringan',
                skorLabels: {
                    1: 'Sangat Rendah',
                    2: 'Rendah',
                    3: 'Sedang',
                    4: 'Tinggi',
                    5: 'Sangat Tinggi'
                },
                colors: {
                    // Skor 1 - Sangat Rendah (lereng >40°, sangat curam)
                    '>40': '#a8d5a2',
                    // Skor 2 - Rendah (lereng 25-40°, curam)
                    '25-40': '#7dba5f',
                    // Skor 3 - Sedang (lereng 15-25°)
                    '15-25': '#f59e0b',
                    // Skor 4 - Tinggi (lereng 2-15°, agak landai)
                    '2-15': '#ef4444',
                    // Skor 5 - Sangat Tinggi (lereng 0-2°, hampir datar = rawan genangan)
                    '0-2': '#991b1b',
                },
                def: '#d9d9d9'
            },
            rain: {
                file: '/geojson/chch.geojson',
                key: 'keterangan',
                skorLabels: {
                    2: 'Rendah',
                    3: 'Sedang',
                    4: 'Tinggi'
                },
                colors: {
                    '1.000 - 1.500 mm/thn': '#9ecae1',
                    '1.500 - 2.000 mm/thn': '#6baed6',
                    '2.000 - 2.500 mm/thn': '#2171b5',
                },
                def: '#deebf7'
            },
            landuse: {
                file: '/geojson/plpl.geojson',
                key: 'Peng_Tanah',
                skorLabels: {
                    1: 'Sangat Rendah',
                    2: 'Rendah',
                    3: 'Sedang',
                    4: 'Tinggi',
                    5: 'Sangat Tinggi'
                },
                colors: {
                    // Skor 1 - Sangat Rendah
                    'Hutan Lebat': '#1a6b1a',
                    'Hutan Sejenis': '#2d8a2d',
                    'Emplasemen': '#a8d5a2',
                    // Skor 2 - Rendah
                    'Padang Rumput': '#7dba5f',
                    'Semak': '#b5d96d',
                    'Tanah Tandus': '#d4c08a',
                    // Skor 3 - Sedang
                    'Industri Non Pertanian': '#9b59b6',
                    'Industri Pertanian': '#e67e22',
                    'Kebun Campuran': '#8db600',
                    'Pertambangan': '#7f8c8d',
                    'Sarana Olah Raga': '#00bcd4',
                    'Tanah penggunaan lain': '#bcaaa4',
                    'Tegalan/Ladang': '#cddc39',
                    // Skor 4 - Tinggi
                    'Kolam': '#64b5f6',
                    'Penggaraman': '#4dd0e1',
                    'Sawah Irigasi': '#26a69a',
                    'Sungai': '#1565c0',
                    'Tambak': '#0d47a1',
                    // Skor 5 - Sangat Tinggi
                    'Kampung': '#ff7043',
                    'Kuburan/Makam': '#8e44ad',
                    'Perumahan': '#e53935',
                },
                def: '#d9d9d9'
            },
            soil: {
                file: '/geojson/jtjt.geojson',
                key: 'KETERANGAN',
                skorLabels: {
                    1: 'Sangat Rendah',
                    2: 'Rendah',
                    3: 'Sedang',
                    4: 'Tinggi',
                    5: 'Sangat Tinggi'
                },
                colors: {
                    // Skor 1 - Sangat Rendah
                    'Regosol': '#a8d5a2',
                    // Skor 2 - Rendah
                    'Kambisol': '#7dba5f',
                    'Grumusol': '#b5d96d',
                    // Skor 3 - Sedang
                    'Mediterania': '#f59e0b',
                    // Skor 4 - Tinggi
                    'Gleisol': '#fb923c',
                    'Rendsina': '#ef4444',
                    'Latosol': '#dc2626',
                    // Skor 5 - Sangat Tinggi
                    'Aluvial': '#991b1b',
                },
                def: '#d9d9d9'
            },
            river: {
                file: '/geojson/jsjs.geojson',
                key: 'Keterangan',
                skorLabels: {
                    1: 'Sangat Rendah',
                    2: 'Rendah',
                    3: 'Sedang',
                    4: 'Tinggi',
                    5: 'Sangat Tinggi'
                },
                colors: {
                    '0 - 25 m': '#08519c',
                    '25 - 100 m': '#2171b5',
                    '100 - 250 m': '#4292c6',
                    '250 - 550 m': '#6baed6',
                    '> 550 m': '#deebf7',
                },
                def: '#deebf7'
            },
        };

        function getSkorColor(skor) {
            const map = {
                1: '#10b981',
                2: '#84cc16',
                3: '#f59e0b',
                4: '#ef4444',
                5: '#991b1b'
            };
            return map[skor] || '#64748b';
        }

        function toggleTematik(name, visible) {
            if (!visible) {
                if (tematikLayers[name] && map.hasLayer(tematikLayers[name]))
                    map.removeLayer(tematikLayers[name]);
                return;
            }
            if (tematikLayers[name]) {
                tematikLayers[name].addTo(map);
                return;
            }
            const cfg = tematikConfig[name];
            fetch(cfg.file)
                .then(r => {
                    if (!r.ok) throw new Error('not found');
                    return r.json();
                })
                .then(data => {
                    tematikLayers[name] = L.geoJSON(data, {
                        style: f => {
                            const val = f.properties[cfg.key] || '';
                            const fillCol = cfg.colors[val] || cfg.def;
                            return {
                                fillColor: fillCol,
                                fillOpacity: 0.7,
                                color: fillCol,
                                weight: 0.5,
                                opacity: 0.6
                            };
                        },
                        onEachFeature: (f, layer) => {
                            const val = f.properties[cfg.key] || '-';
                            const skorRaw = cfg.skorField ? f.properties[cfg.skorField] : f.properties.skor;
                            const skor = skorRaw !== undefined ? Math.round(skorRaw) : undefined;
                            const warna = cfg.colors[val] || cfg.def;

                            // Tooltip ringan saat hover
                            layer.bindTooltip(
                                `<strong>${val}</strong>` + (skor !== undefined && cfg.skorLabels && cfg.skorLabels[skor] ?
                                    ` &nbsp;|&nbsp; ${cfg.skorLabels[skor]}` :
                                    ''), {
                                    sticky: true,
                                    className: 'leaflet-tooltip'
                                }
                            );

                            // Popup interaktif saat klik (khusus landuse tampil lengkap)
                            if (cfg.skorLabels && skor !== undefined) {
                                const skorLabel = cfg.skorLabels[skor] || '-';
                                const luas = f.properties.luas ?
                                    parseFloat(f.properties.luas).toFixed(2) + ' ha' :
                                    '-';
                                layer.bindPopup(`
                            <div style="min-width:230px;font-family:inherit;">
                                <div style="background:linear-gradient(135deg,#0891b2,#06b6d4);
                                            color:white;padding:10px 14px;border-radius:10px 10px 0 0;
                                            margin:-1px -1px 0 -1px;">
                                    <h6 style="margin:0;font-weight:800;font-size:13px;">
                                        <i class="fas fa-map"></i> ${name === 'banjir' ? 'Kerawanan Banjir' : name === 'landuse' ? 'Penggunaan Lahan' : name === 'soil' ? 'Jenis Tanah' : name === 'slope' ? 'Kemiringan Lereng' : name === 'rain' ? 'Curah Hujan' : name === 'river' ? 'Jarak dari Sungai' : name.charAt(0).toUpperCase() + name.slice(1)}
                                    </h6>
                                </div>
                                <div style="padding:12px;background:#f8fafc;border-radius:0 0 10px 10px;">
                                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
                                        <span style="width:18px;height:18px;background:${warna};
                                                     border-radius:4px;flex-shrink:0;
                                                     border:2px solid rgba(0,0,0,0.15);
                                                     box-shadow:0 2px 4px rgba(0,0,0,0.1);"></span>
                                        <strong style="color:#0f172a;font-size:14px;">${val}</strong>
                                    </div>
                                    <table style="width:100%;font-size:12px;border-collapse:collapse;">
                                        <tr>
                                            <td style="color:#64748b;padding:3px 0;width:55%;">🎯 Skor Kerawanan</td>
                                            <td style="font-weight:700;color:#0f172a;">${skor}</td>
                                        </tr>
                                        <tr>
                                            <td style="color:#64748b;padding:3px 0;">📊 Kategori</td>
                                            <td>
                                                <span style="background:${getSkorColor(skor)};color:white;
                                                             padding:2px 8px;border-radius:20px;
                                                             font-size:11px;font-weight:700;">
                                                    ${skorLabel}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="color:#64748b;padding:3px 0;">📐 Luas</td>
                                            <td style="font-weight:600;color:#0f172a;">${luas}</td>
                                        </tr>
                                    </table>
                                    <div style="margin-top:10px;padding:7px 10px;
                                                background:white;border-radius:8px;
                                                border:1px solid #e2e8f0;font-size:10px;color:#64748b;">
                                        <strong style="color:#0c4a6e;">Keterangan Skor:</strong><br>
                                        <span style="color:#10b981;">1</span> = Sangat Rendah &nbsp;
                                        <span style="color:#84cc16;">2</span> = Rendah &nbsp;
                                        <span style="color:#f59e0b;">3</span> = Sedang<br>
                                        <span style="color:#ef4444;">4</span> = Tinggi &nbsp;
                                        <span style="color:#991b1b;">5</span> = Sangat Tinggi
                                    </div>
                                </div>
                            </div>
                        `, {
                                    maxWidth: 280
                                });
                            } else {
                                layer.bindPopup(`
                            <div style="min-width:180px;padding:6px;">
                                <h6 style="color:#0c4a6e;font-weight:700;margin-bottom:6px;">
                                    ${name.charAt(0).toUpperCase() + name.slice(1)}
                                </h6>
                                <p style="margin:0;"><strong>${cfg.key}:</strong> ${val}</p>
                            </div>`);
                            }
                        }
                    }).addTo(map);
                })
                .catch(() => {
                    const chk = document.getElementById('chk-' + name);
                    if (chk) chk.checked = false;
                    alert('⚠️ File GeoJSON "' + cfg.file +
                        '" belum tersedia.\nSiapkan file di /geojson/ terlebih dahulu.');
                });
        }

        // ── FIX 2: DOMContentLoaded — render tabel laporan tanpa delay ────
        document.addEventListener('DOMContentLoaded', () => {
            // Langsung panggil tanpa setTimeout agar 25 baris tampil segera
            renderLaporanPage(1);
        });

        // ── FOCUS FROM URL PARAMS ──────────────────────────────────────────
        window.onload = function() {
            const params = new URLSearchParams(window.location.search);
            const lat = params.get('lat');
            const lng = params.get('lng');
            if (lat && lng) {
                const lokasi = [parseFloat(lat), parseFloat(lng)];
                map.setView(lokasi, 16);
                L.marker(lokasi).addTo(map).bindPopup('Lokasi Laporan Banjir').openPopup();
            }
        };
    </script>
@endsection
