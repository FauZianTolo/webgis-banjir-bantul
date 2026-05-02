<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 style="color: #0c4a6e; font-weight: 900; font-size: 2rem; margin: 0;">
                    <i class="fas fa-map-marked-alt"></i> Peta Monitoring - Semua Laporan
                </h2>
                <p style="color: #64748b; margin: 0.5rem 0 0 0; font-weight: 600;">
                    Kelola dan verifikasi laporan banjir dari masyarakat
                </p>
            </div>
        </div>
    </x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    @push('styles')
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

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
            top: 0; left: 0; right: 0; bottom: 0;
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
            top: 16px; right: 16px;
        }
        .map-outer-wrapper.is-fullscreen .map-toolbar {
            top: 16px; left: 16px;
        }
        .map-outer-wrapper.is-fullscreen .search-panel {
            top: 16px; left: 50%; transform: translateX(-50%);
        }

        /* ==================== TOOLBAR (kiri atas peta) ==================== */
        .map-toolbar {
            position: absolute;
            top: 20px; left: 20px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .toolbar-btn {
            width: 42px; height: 42px;
            border-radius: 10px;
            border: 2px solid white;
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: white;
            display: flex; align-items: center; justify-content: center;
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
        .is-fullscreen .toolbar-btn.exit-fs { display: flex; }
        .is-fullscreen .toolbar-btn.enter-fs { display: none; }

        /* ==================== SEARCH PANEL (tengah atas) ==================== */
        .search-panel {
            position: absolute;
            top: 20px; left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            width: 320px;
        }

        /* Tab switcher */
        .search-tabs {
            display: flex;
            background: white;
            border-radius: 12px 12px 0 0;
            box-shadow: 0 -2px 8px rgba(0,0,0,0.08);
            border: 2px solid rgba(8,145,178,0.25);
            border-bottom: none;
            overflow: hidden;
        }
        .search-tab-btn {
            flex: 1; border: none; outline: none; cursor: pointer;
            padding: 8px 6px; font-size: 11px; font-weight: 700;
            color: #64748b; background: #f8fafc;
            transition: all 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 4px;
        }
        .search-tab-btn:first-child { border-right: 1px solid #e2e8f0; }
        .search-tab-btn.active {
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: white;
        }
        .search-tab-btn:hover:not(.active) { background: #e0f2fe; color: #0891b2; }

        /* Search input row */
        .search-inner {
            display: flex;
            background: white;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.22);
            border: 2px solid rgba(8, 145, 178, 0.25);
            border-top: none;
            overflow: hidden;
        }
        .search-inner select,
        .search-inner input[type="text"] {
            flex: 1; border: none; outline: none;
            padding: 10px 12px;
            font-size: 13px; font-weight: 600;
            color: #0c4a6e; background: transparent; cursor: pointer;
            font-family: inherit;
        }
        .search-inner input[type="text"]::placeholder { color: #94a3b8; font-weight: 500; }
        .search-inner select option { color: #334155; font-weight: 600; }
        .search-btn {
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: white; border: none;
            padding: 10px 14px; font-size: 13px;
            cursor: pointer; transition: all 0.2s;
            display: flex; align-items: center; gap: 5px; font-weight: 700;
        }
        .search-btn:hover { background: linear-gradient(135deg, #0e7490, #0891b2); }
        .search-clear {
            background: #f1f5f9; color: #64748b; border: none;
            padding: 10px 10px; font-size: 13px; cursor: pointer;
            border-left: 1px solid #e2e8f0;
            display: none; align-items: center; transition: all 0.2s;
        }
        .search-clear:hover { background: #fee2e2; color: #ef4444; }
        .search-clear.visible { display: flex; }
        .search-result-badge {
            margin-top: 7px;
            background: rgba(8, 145, 178, 0.9);
            color: white; border-radius: 8px;
            padding: 5px 12px; font-size: 11px; font-weight: 700;
            text-align: center; display: none;
            backdrop-filter: blur(6px);
        }
        .search-result-badge.visible { display: block; }

        /* ==================== SEARCH WILAYAH BARU ==================== */
        .search-panel {
            width: 520px;
        }
        .search-inner.wilayah-search {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            border-radius: 0 0 12px 12px;
        }
        .search-inner.wilayah-search select {
            width: 100%;
            border-right: 1px solid #e2e8f0;
        }
        .search-inner.wilayah-search select:disabled {
            background: #f1f5f9;
            color: #94a3b8;
            cursor: not-allowed;
        }
        .search-inner.wilayah-search .search-clear {
            display: flex;
            border-left: none;
            align-items: center;
            justify-content: center;
        }
        .leaflet-control-zoom {
            border: none !important;
            box-shadow: 0 6px 20px rgba(8, 145, 178, 0.35) !important;
        }
        .leaflet-control-zoom a {
            background: white !important;
            color: #0c4a6e !important;
            font-weight: 900 !important;
        }
        .leaflet-control-zoom a:hover {
            background: #e0f2fe !important;
            color: #0891b2 !important;
        }


        /* Autocomplete dropdown desa */
        .desa-dropdown {
            position: absolute;
            top: 100%; left: 0; right: 0;
            background: white;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            border: 2px solid rgba(8,145,178,0.25);
            border-top: none;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1100;
            display: none;
            scrollbar-width: thin;
            scrollbar-color: #0891b2 #f0f9ff;
        }
        .desa-dropdown.open { display: block; }
        .desa-option {
            padding: 9px 14px; font-size: 12px; font-weight: 600;
            color: #334155; cursor: pointer; border-bottom: 1px solid #f1f5f9;
            transition: background 0.15s;
        }
        .desa-option:hover, .desa-option.active { background: #e0f2fe; color: #0891b2; }
        .desa-option:last-child { border-bottom: none; }
        .desa-option small { display: block; font-size: 10px; color: #94a3b8; font-weight: 500; }
        .search-pane { display: none; }
        .search-pane.active { display: contents; }

        /* ==================== LAYER PANEL (kanan atas) ==================== */
        .layer-panel {
            position: absolute;
            top: 20px; right: 20px;
            z-index: 1000;
            width: 230px;
            font-size: 13px;
        }
        .layer-panel-toggle {
            display: flex; align-items: center; justify-content: space-between;
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: white; padding: 10px 14px; border-radius: 12px;
            cursor: pointer; user-select: none;
            box-shadow: 0 6px 20px rgba(8, 145, 178, 0.4);
            transition: all 0.2s; border: none; width: 100%;
        }
        .layer-panel-toggle:hover { background: linear-gradient(135deg, #0e7490, #0891b2); }
        .layer-panel-toggle span { font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 7px; }
        .layer-panel-toggle .arrow { transition: transform 0.3s; font-size: 11px; }
        .layer-panel-toggle.open .arrow { transform: rotate(180deg); }
        .layer-panel-body {
            background: white;
            border-radius: 0 0 14px 14px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.18);
            border: 2px solid rgba(8, 145, 178, 0.15);
            border-top: none; overflow: hidden;
            max-height: 0; transition: max-height 0.4s;
        }
        .layer-panel-body.open { max-height: 550px; }
        .layer-panel-body-inner {
            padding: 14px;
            max-height: calc(100vh - 160px);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #0891b2 #f0f9ff;
        }
        .layer-panel-body-inner::-webkit-scrollbar { width: 5px; }
        .layer-panel-body-inner::-webkit-scrollbar-track { background: #f0f9ff; border-radius: 3px; }
        .layer-panel-body-inner::-webkit-scrollbar-thumb { background: #0891b2; border-radius: 3px; }

        .panel-section-hdr {
            display: flex; align-items: center; justify-content: space-between;
            cursor: pointer; padding: 8px 10px; border-radius: 8px;
            background: #f0f9ff; margin-bottom: 10px;
            border: 1px solid #bae6fd; user-select: none; transition: background 0.2s;
        }
        .panel-section-hdr:hover { background: #e0f2fe; }
        .panel-section-hdr .title {
            font-weight: 700; color: #0c4a6e; font-size: 12px;
            display: flex; align-items: center; gap: 6px;
        }
        .panel-section-hdr .arrow-sm { font-size: 10px; color: #0891b2; transition: transform 0.25s; }
        .panel-section-hdr.open .arrow-sm { transform: rotate(180deg); }
        .panel-section-body { overflow: hidden; max-height: 0; transition: max-height 0.3s; }
        .panel-section-body.open { max-height: 400px; }
        .panel-divider { border: none; border-top: 1px solid #e2e8f0; margin: 10px 0; }

        .layer-item {
            display: flex; align-items: center; gap: 9px;
            padding: 7px 8px; border-radius: 8px;
            cursor: pointer; transition: background 0.2s; margin-bottom: 4px;
        }
        .layer-item:hover { background: #f0f9ff; }
        .layer-item input[type="checkbox"] {
            width: 15px; height: 15px;
            accent-color: #0891b2; cursor: pointer; flex-shrink: 0;
        }
        .layer-item-label { font-weight: 600; color: #334155; font-size: 12px; line-height: 1.3; }
        .legend-box {
            width: 16px; height: 16px; border-radius: 4px;
            flex-shrink: 0; border: 1px solid rgba(0,0,0,0.1);
        }
        .legend-marker-pin {
            width: 13px; height: 21px;
            object-fit: contain; flex-shrink: 0;
        }

        /* ==================== BASEMAP GRID ==================== */
        .basemap-grid {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 7px; padding-top: 2px;
        }
        .basemap-btn {
            display: flex; flex-direction: column;
            align-items: center; gap: 4px;
            padding: 8px 4px; border-radius: 9px;
            border: 2px solid #e2e8f0; background: white;
            cursor: pointer; transition: all 0.2s;
        }
        .basemap-btn:hover { border-color: #0891b2; background: #f0f9ff; transform: translateY(-2px); }
        .basemap-btn.active {
            border-color: #0891b2;
            background: linear-gradient(135deg, #e0f2fe, #f0f9ff);
            box-shadow: 0 4px 12px rgba(8, 145, 178, 0.25);
        }
        .basemap-icon {
            width: 36px; height: 28px;
            border-radius: 5px; background-size: cover;
            background-position: center; border: 1px solid #e2e8f0;
        }
        .basemap-label { font-size: 10px; font-weight: 700; color: #475569; }
        .basemap-btn.active .basemap-label { color: #0891b2; }

        /* ==================== LOADING OVERLAY ==================== */
        .loading-overlay {
            position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255,255,255,0.98);
            display: flex; align-items: center; justify-content: center;
            z-index: 2000; border-radius: 20px;
        }

        /* ==================== INFO BOX ==================== */
        .info-box {
            background: white; padding: 1.5rem; margin-bottom: 1.25rem;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            border: 2px solid rgba(8,145,178,0.1);
        }
        .info-box h4 { color: #0c4a6e; font-weight: 900; font-size: 1.5rem; margin-bottom: 1rem; }

        .status-indicator {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.5rem 1rem; border-radius: 10px; font-weight: 700;
            margin-right: 1rem; transition: all 0.3s ease;
        }
        .status-indicator:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.15); }
        .status-pending  { background: linear-gradient(135deg,#fef3c7,#fde68a); color:#92400e; border:2px solid #f59e0b; }
        .status-pending i { color: #f59e0b; }
        .status-verified { background: linear-gradient(135deg,#d1fae5,#a7f3d0); color:#065f46; border:2px solid #10b981; }
        .status-verified i { color: #10b981; }
        .status-rejected { background: linear-gradient(135deg,#fee2e2,#fecaca); color:#991b1b; border:2px solid #ef4444; }
        .status-rejected i { color: #ef4444; }

        .btn-kelola {
            background: linear-gradient(135deg,#0891b2,#06b6d4);
            color: white; border: none; padding: 0.75rem 1.5rem;
            border-radius: 12px; font-weight: 700; transition: all 0.3s ease;
            text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;
        }
        .btn-kelola:hover {
            background: linear-gradient(135deg,#0e7490,#0891b2);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(8,145,178,0.3); color: white;
        }

        /* ==================== DETAIL MODAL ==================== */
        .detail-modal {
            display: none; position: fixed; z-index: 99998;
            left: 0; top: 0; width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.8); backdrop-filter: blur(5px);
        }
        .detail-modal.show { display: flex; align-items: center; justify-content: center; }
        .detail-modal-content {
            background: white; border-radius: 25px;
            width: 90%; max-width: 900px; max-height: 85vh; overflow-y: auto;
            box-shadow: 0 25px 80px rgba(0,0,0,0.5); position: relative;
            animation: slideUp 0.3s ease;
        }
        .detail-modal-header {
            background: linear-gradient(135deg,#0891b2,#06b6d4); color: white;
            padding: 2rem; border-radius: 25px 25px 0 0;
            position: sticky; top: 0; z-index: 10;
        }
        .detail-modal-header h3 { margin:0; font-size:1.75rem; font-weight:900; }
        .detail-modal-close {
            position: absolute; top: 1.5rem; right: 1.5rem;
            background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.5);
            color: white; width: 45px; height: 45px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 1.5rem; font-weight: bold; transition: all 0.3s;
        }
        .detail-modal-close:hover { background: rgba(255,255,255,0.3); transform: rotate(90deg); }
        .detail-modal-body { padding: 2.5rem; }
        .detail-section { margin-bottom: 2rem; }
        .detail-section-title {
            color: #0c4a6e; font-weight: 800; font-size: 1.25rem; margin-bottom: 1.25rem;
            display: flex; align-items: center; gap: 0.75rem;
            padding-bottom: 0.75rem; border-bottom: 3px solid #e2e8f0;
        }
        .detail-grid { display: grid; grid-template-columns: repeat(auto-fit,minmax(250px,1fr)); gap: 1.5rem; }
        .detail-item { background: #f8fafc; border-radius: 12px; padding: 1.25rem; border: 2px solid #e2e8f0; }
        .detail-item-label { color:#64748b; font-size:0.85rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:0.5rem; }
        .detail-item-value { color:#0f172a; font-size:1.1rem; font-weight:700; }
        .badge-status { padding:0.5rem 1rem; border-radius:10px; font-weight:700; font-size:0.85rem; display:inline-block; }
        .badge-pending  { background:linear-gradient(135deg,#fef3c7,#fde68a); color:#92400e; border:2px solid #f59e0b; }
        .badge-verified { background:linear-gradient(135deg,#d1fae5,#a7f3d0); color:#065f46; border:2px solid #10b981; }
        .badge-rejected { background:linear-gradient(135deg,#fee2e2,#fecaca); color:#991b1b; border:2px solid #ef4444; }

        @keyframes slideUp { from{opacity:0;transform:translateY(50px);} to{opacity:1;transform:translateY(0);} }

        /* ==================== POPUP BUTTONS ==================== */
        .popup-btn-detail {
            background: linear-gradient(135deg,#0891b2,#06b6d4); color:white; border:none;
            padding:0.6rem 1rem; border-radius:8px; font-weight:700; cursor:pointer;
            transition:all 0.3s; display:flex; align-items:center; justify-content:center;
            gap:0.5rem; font-size:0.9rem;
        }
        .popup-btn-detail:hover { background:linear-gradient(135deg,#0e7490,#0891b2); transform:translateY(-2px); }
        .popup-btn-route {
            background: linear-gradient(135deg,#10b981,#059669); color:white; border:none;
            padding:0.6rem 1rem; border-radius:8px; font-weight:700; cursor:pointer;
            transition:all 0.3s; display:flex; align-items:center; justify-content:center;
            gap:0.5rem; font-size:0.9rem;
        }
        .popup-btn-route:hover { background:linear-gradient(135deg,#059669,#047857); transform:translateY(-2px); }

        /* ==================== IMAGE MODAL ==================== */
        .modal-image-viewer {
            display:none; position:fixed; z-index:99999;
            left:0; top:0; width:100%; height:100%;
            background-color:rgba(0,0,0,0.95); backdrop-filter:blur(8px);
        }
        .modal-image-content {
            position:relative; margin:auto; padding:20px;
            width:90%; max-width:1200px; top:50%; transform:translateY(-50%);
        }
        .modal-image-viewer img { width:100%; height:auto; max-height:85vh; object-fit:contain; border-radius:15px; }
        .modal-close-btn {
            position:absolute; top:-15px; right:-15px; color:#fff; background:#ef4444;
            font-size:32px; font-weight:bold; width:55px; height:55px; border-radius:50%;
            border:4px solid white; cursor:pointer; display:flex;
            align-items:center; justify-content:center; transition:all 0.3s; z-index:100000;
        }
        .modal-close-btn:hover { background:#dc2626; transform:scale(1.15) rotate(90deg); }

        /* ==================== HIDE DEFAULT LEAFLET LAYER CONTROL ==================== */
        .leaflet-control-layers { display: none !important; }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 768px) {
            #map { height: 480px; border-radius: 15px; }
            .layer-panel { top:10px; right:10px; width:190px; }
            .search-panel { width:220px; }
            .map-toolbar { top:10px; left:10px; }
        }
        @media (max-width: 767px) {
            #map { height: 420px; border-radius: 14px; }
            .map-toolbar { top:8px; left:8px; gap:6px; }
            .toolbar-btn { width:36px; height:36px; font-size:13px; }
            .search-panel { top:8px; left:52px; transform:none; width:calc(100% - 180px); min-width:140px; }
            .search-tab-btn { padding:6px 4px; font-size:10px; }
            .search-inner select, .search-inner input[type="text"] { padding:8px 7px; font-size:11px; }
            .search-btn { padding:8px 10px; font-size:11px; }
            .layer-panel { top:8px; right:8px; width:148px; }
            .layer-panel-toggle { padding:8px 10px; }
            .layer-panel-toggle span { font-size:11px; gap:5px; }
            .layer-panel-body.open { max-height:340px; }
            .layer-panel-body-inner { padding:10px; max-height:calc(100vh - 120px); }
            .layer-item { gap:6px; padding:5px 6px; }
            .layer-item-label { font-size:10px; }
            .legend-box { width:12px; height:12px; }

            .search-inner.wilayah-search {
                grid-template-columns: 1fr;
            }
            .search-inner.wilayah-search select {
                border-right: none;
                border-bottom: 1px solid #e2e8f0;
                padding: 8px 9px;
                font-size: 11px;
            }
            .search-inner.wilayah-search .search-clear {
                padding: 8px;
                justify-content: center;
            }
            .detail-modal-content { width:96%; border-radius:16px; }
            .detail-modal-header { padding:1.25rem; border-radius:16px 16px 0 0; }
            .detail-modal-header h3 { font-size:1.2rem; }
            .detail-modal-body { padding:1.25rem; }
            .detail-grid { grid-template-columns:1fr 1fr; gap:0.75rem; }
            .info-box { padding:1rem; border-radius:12px; margin-bottom:1rem; }
        }
        @media (max-width: 420px) {
            .search-panel { width:calc(100% - 170px); min-width:130px; }
            .layer-panel { width:130px; }
        }
    </style>
    @endpush

    <div class="container-fluid px-0 pb-5">

        <!-- Info Box -->
        <div class="info-box">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="mb-3"><i class="fas fa-layer-group"></i> Status Laporan</h4>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="status-indicator status-pending">
                            <i class="fas fa-circle"></i>
                            Pending: {{ $laporan->where('status', 'pending')->count() }}
                        </span>
                        <span class="status-indicator status-verified">
                            <i class="fas fa-circle"></i>
                            Verified: {{ $laporan->where('status', 'verified')->count() }}
                        </span>
                        <span class="status-indicator status-rejected">
                            <i class="fas fa-circle"></i>
                            Rejected: {{ $laporan->where('status', 'rejected')->count() }}
                        </span>
                    </div>
                </div>
                <div>
                    <a href="{{ route('admin.laporan.index') }}" class="btn-kelola">
                        <i class="fas fa-clipboard-check"></i> Kelola Laporan
                    </a>
                </div>
            </div>
        </div>

        <!-- Map Container -->
        <div class="bg-white overflow-hidden shadow-xl rounded-3" style="border-radius:20px;padding:1rem;">
            <div class="map-outer-wrapper" id="mapOuterWrapper">

                <!-- Loading Overlay -->
                <div id="loading-overlay" class="loading-overlay">
                    <div class="text-center">
                        <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem;"></div>
                        <p class="text-muted">Memuat peta...</p>
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

                <!-- ── SEARCH PANEL (tengah atas) ── -->
                <div class="search-panel" id="searchPanel">
                    <div class="search-tabs">
                        <button class="search-tab-btn active" type="button">
                            <i class="fas fa-search-location"></i> Cari Kecamatan dan Desa
                        </button>
                    </div>

                    <div class="search-inner wilayah-search">
                        <select id="searchKecamatan" onchange="onKecamatanChange()">
                            <option value="">🔍 Pilih Kecamatan...</option>
                        </select>

                        <select id="searchDesa" onchange="onDesaChange()" disabled>
                            <option value="">Pilih kecamatan dulu...</option>
                        </select>

                        <button class="search-clear visible" id="clearWilayah" onclick="clearWilayahSearch()" title="Reset">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="search-result-badge" id="searchResultBadge"></div>
                </div>

                <!-- ── LAYER PANEL KANAN ATAS ── -->
                <div class="layer-panel">
                    <button class="layer-panel-toggle open" id="layerPanelToggle" onclick="toggleLayerPanel()">
                        <span><i class="fas fa-layer-group"></i>Keterangan Peta</span>
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

                                <!-- Pending -->
                                <label class="layer-item">
                                    <input type="checkbox" id="chk-pending" checked
                                        onchange="toggleLayer('pending', this.checked)">
                                    <div style="display:flex;align-items:center;gap:6px;flex:1;">
                                        <img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-yellow.png"
                                            class="legend-marker-pin" alt="marker kuning">
                                        <span class="layer-item-label">
                                            Pending
                                            <span style="display:block;font-size:9px;font-weight:600;color:#f59e0b;">● Menunggu verifikasi</span>
                                        </span>
                                    </div>
                                </label>

                                <!-- Verified -->
                                <label class="layer-item">
                                    <input type="checkbox" id="chk-verified" checked
                                        onchange="toggleLayer('verified', this.checked)">
                                    <div style="display:flex;align-items:center;gap:6px;flex:1;">
                                        <img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png"
                                            class="legend-marker-pin" alt="marker hijau">
                                        <span class="layer-item-label">
                                            Verified
                                            <span style="display:block;font-size:9px;font-weight:600;color:#10b981;">● Sudah diverifikasi</span>
                                        </span>
                                    </div>
                                </label>

                                <!-- Rejected -->
                                <label class="layer-item">
                                    <input type="checkbox" id="chk-rejected" checked
                                        onchange="toggleLayer('rejected', this.checked)">
                                    <div style="display:flex;align-items:center;gap:6px;flex:1;">
                                        <img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png"
                                            class="legend-marker-pin" alt="marker merah">
                                        <span class="layer-item-label">
                                            Rejected
                                            <span style="display:block;font-size:9px;font-weight:600;color:#ef4444;">● Ditolak/tidak valid</span>
                                        </span>
                                    </div>
                                </label>

                                <!-- Batas Kecamatan -->
                                <label class="layer-item">
                                    <input type="checkbox" id="chk-batas" checked
                                        onchange="toggleLayer('batas', this.checked)">
                                    <div style="display:flex;align-items:center;gap:6px;flex:1;">
                                        <div style="width:16px;height:16px;border:2px dashed #1e3c72;border-radius:3px;flex-shrink:0;"></div>
                                        <span class="layer-item-label">Batas Kecamatan</span>
                                    </div>
                                </label>
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
                                            style="background-image:url('https://tile.openstreetmap.org/11/1620/1012.png');"></div>
                                        <span class="basemap-label">Streets</span>
                                    </button>
                                    <button class="basemap-btn" id="btn-satellite" onclick="switchBasemap('satellite')">
                                        <div class="basemap-icon"
                                            style="background-image:url('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/11/1012/1620');"></div>
                                        <span class="basemap-label">Satellite</span>
                                    </button>
                                    <button class="basemap-btn" id="btn-topo" onclick="switchBasemap('topo')">
                                        <div class="basemap-icon"
                                            style="background-image:url('https://tile.opentopomap.org/11/1620/1012.png');"></div>
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
        </div>

    </div>

    <!-- Detail Modal -->
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
                        <div class="detail-item"><div class="detail-item-label">ID Laporan</div><div class="detail-item-value" id="detail-id">-</div></div>
                        <div class="detail-item"><div class="detail-item-label">Tanggal & Waktu</div><div class="detail-item-value" id="detail-waktu">-</div></div>
                        <div class="detail-item"><div class="detail-item-label">Status</div><div class="detail-item-value" id="detail-status">-</div></div>
                    </div>
                </div>
                <div class="detail-section">
                    <h4 class="detail-section-title"><i class="fas fa-user"></i> Data Pelapor</h4>
                    <div class="detail-grid">
                        <div class="detail-item"><div class="detail-item-label">Nama Pelapor</div><div class="detail-item-value" id="detail-pelapor">-</div></div>
                        <div class="detail-item"><div class="detail-item-label">No. Telepon</div><div class="detail-item-value" id="detail-telp">-</div></div>
                    </div>
                </div>
                <div class="detail-section">
                    <h4 class="detail-section-title"><i class="fas fa-map-marker-alt"></i> Lokasi Banjir</h4>
                    <div class="detail-grid">
                        <div class="detail-item"><div class="detail-item-label">Kecamatan</div><div class="detail-item-value" id="detail-kecamatan">-</div></div>
                        <div class="detail-item"><div class="detail-item-label">Desa</div><div class="detail-item-value" id="detail-desa">-</div></div>
                        <div class="detail-item"><div class="detail-item-label">Koordinat</div><div class="detail-item-value" id="detail-koordinat">-</div></div>
                    </div>
                </div>
                <div class="detail-section">
                    <h4 class="detail-section-title"><i class="fas fa-water"></i> Kondisi Banjir</h4>
                    <div class="detail-grid">
                        <div class="detail-item"><div class="detail-item-label">Kedalaman Air</div><div class="detail-item-value" id="detail-kedalaman">-</div></div>
                    </div>
                    <div class="detail-item mt-3">
                        <div class="detail-item-label">Deskripsi</div>
                        <div class="detail-item-value" id="detail-deskripsi" style="font-weight:500;line-height:1.6;">-</div>
                    </div>
                </div>
                <div class="detail-section" id="section-kebutuhan">
                    <h4 class="detail-section-title">
                        <i class="fas fa-hands-helping" style="color:#f59e0b;"></i> Kebutuhan / Bantuan yang Diperlukan
                    </h4>
                    <div class="detail-item">
                        <div class="detail-item-value" id="detail-kebutuhan"
                            style="font-weight:500;line-height:1.6;background:#fffbeb;border:1.5px solid #fde68a;border-radius:10px;padding:10px 14px;color:#92400e;">-</div>
                    </div>
                </div>
                <div class="detail-section">
                    <h4 class="detail-section-title"><i class="fas fa-camera"></i> Dokumentasi Foto</h4>
                    <div id="detail-foto-container" style="display:flex;gap:12px;flex-wrap:wrap;justify-content:center;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="modal-image-viewer">
        <div class="modal-image-content">
            <span class="modal-close-btn" onclick="closeImageModal()">&times;</span>
            <img id="modalImage" src="" alt="Foto Laporan">
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // ── DATA LAPORAN ──────────────────────────────────────────────────
        const laporanData = @json($laporan->keyBy('id'));

        // ── MAP VARS ───────────────────────────────────────────────────────
        let map;
        let layerGroups = {};
        let basemaps = {};
        let currentBasemap = 'streets';
        let isFullscreen = false;
        let allBounds = null;
        let bantulGeoJSON = null;
        let highlightLayer = null;
        let desaList = [];

        // ── HELPERS ───────────────────────────────────────────────────────
        function getFotoUrl(f) {
            if (!f) return '';
            return f.startsWith('http') ? f : '/uploads/laporan/' + f;
        }

        function formatDateTime(dateString) {
            const d = new Date(dateString);
            return d.toLocaleString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function goToRoute(lat, lng, title) {
            window.location.href = '/admin/peta/rute?lat=' + lat + '&lng=' + lng + '&title=' + encodeURIComponent(title);
        }

        function getPropertyValue(properties, keys) {
            for (const key of keys) {
                if (properties && properties[key]) {
                    return String(properties[key]).trim();
                }
            }
            return '';
        }

        function getKecamatanName(feature) {
            return getPropertyValue(feature.properties || {}, [
                'WADMKC', 'KECAMATAN', 'Kecamatan', 'NAMKEC', 'NAME_2'
            ]);
        }

        function getDesaName(feature) {
            return getPropertyValue(feature.properties || {}, [
                'WADMKD', 'DESA', 'Desa', 'NAMOBJ', 'NAMDES', 'NAME_3'
            ]);
        }

        // ── FULLSCREEN ────────────────────────────────────────────────────
        function toggleFullscreen() {
            const wrapper = document.getElementById('mapOuterWrapper');
            isFullscreen = !isFullscreen;
            wrapper.classList.toggle('is-fullscreen', isFullscreen);
            document.body.style.overflow = isFullscreen ? 'hidden' : '';
            setTimeout(() => {
                if (map) map.invalidateSize();
            }, 350);
        }

        // ── ZOOM TO EXTENT ────────────────────────────────────────────────
        function zoomToExtent() {
            if (allBounds && allBounds.isValid()) {
                map.fitBounds(allBounds, { padding: [50, 50], maxZoom: 14 });
            } else {
                map.setView([-7.8700, 110.3300], 11);
            }
        }

        // ── LAYER TOGGLE ──────────────────────────────────────────────────
        function toggleLayer(name, visible) {
            if (!layerGroups[name]) return;
            visible ? layerGroups[name].addTo(map) : map.removeLayer(layerGroups[name]);
        }

        // ── LAYER PANEL ───────────────────────────────────────────────────
        function toggleLayerPanel() {
            document.getElementById('layerPanelBody').classList.toggle('open');
            document.getElementById('layerPanelToggle').classList.toggle('open');
        }

        function toggleSection(bodyId, hdrId) {
            document.getElementById(bodyId).classList.toggle('open');
            document.getElementById(hdrId).classList.toggle('open');
        }

        // ── BASEMAP SWITCH ────────────────────────────────────────────────
        function switchBasemap(name) {
            if (currentBasemap === name) return;

            if (basemaps[currentBasemap]) map.removeLayer(basemaps[currentBasemap]);
            if (basemaps[name]) basemaps[name].addTo(map);

            document.querySelectorAll('.basemap-btn').forEach(btn => btn.classList.remove('active'));
            const btn = document.getElementById('btn-' + name);
            if (btn) btn.classList.add('active');

            currentBasemap = name;
        }

        // ── HIGHLIGHT POLYGON ─────────────────────────────────────────────
        function clearHighlight() {
            if (highlightLayer) {
                map.removeLayer(highlightLayer);
                highlightLayer = null;
            }
        }

        function showSearchBadge(text) {
            const badge = document.getElementById('searchResultBadge');
            if (!badge) return;
            badge.textContent = text;
            badge.classList.add('visible');
        }

        function hideSearchBadge() {
            const badge = document.getElementById('searchResultBadge');
            if (!badge) return;
            badge.textContent = '';
            badge.classList.remove('visible');
        }

        function highlightFeatures(features, label) {
            clearHighlight();

            if (!features || features.length === 0) {
                hideSearchBadge();
                return;
            }

            highlightLayer = L.geoJSON({
                type: 'FeatureCollection',
                features: features
            }, {
                style: {
                    fillColor: '#facc15',
                    fillOpacity: 0.38,
                    color: '#dc2626',
                    weight: 4,
                    opacity: 1
                }
            }).addTo(map);

            const bounds = highlightLayer.getBounds();
            if (bounds && bounds.isValid()) {
                map.fitBounds(bounds, { padding: [45, 45], maxZoom: 15 });
            }

            showSearchBadge(label);
        }

        // ── SEARCH KECAMATAN DAN DESA ─────────────────────────────────────
        function prepareWilayahSearch(data) {
            bantulGeoJSON = data;

            desaList = data.features
                .map(feature => ({
                    kecamatan: getKecamatanName(feature),
                    desa: getDesaName(feature),
                    feature: feature
                }))
                .filter(item => item.kecamatan && item.desa)
                .sort((a, b) => {
                    if (a.kecamatan === b.kecamatan) return a.desa.localeCompare(b.desa);
                    return a.kecamatan.localeCompare(b.kecamatan);
                });

            const kecamatanList = [...new Set(desaList.map(item => item.kecamatan))]
                .sort((a, b) => a.localeCompare(b));

            const kecamatanSelect = document.getElementById('searchKecamatan');
            if (!kecamatanSelect) return;

            kecamatanSelect.innerHTML = '<option value="">🔍 Pilih Kecamatan...</option>';

            kecamatanList.forEach(kecamatan => {
                const option = document.createElement('option');
                option.value = kecamatan;
                option.textContent = kecamatan;
                kecamatanSelect.appendChild(option);
            });

            updateDesaOptions('');
        }

        function updateDesaOptions(kecamatan) {
            const desaSelect = document.getElementById('searchDesa');
            if (!desaSelect) return;

            desaSelect.innerHTML = '';

            if (!kecamatan) {
                desaSelect.disabled = true;
                desaSelect.innerHTML = '<option value="">Pilih kecamatan dulu...</option>';
                return;
            }

            const desaFiltered = desaList.filter(item => item.kecamatan === kecamatan);

            desaSelect.disabled = false;
            desaSelect.innerHTML = '<option value="">🏠 Pilih Desa / Kelurahan...</option>';

            desaFiltered.forEach(item => {
                const option = document.createElement('option');
                option.value = item.desa;
                option.textContent = item.desa;
                desaSelect.appendChild(option);
            });
        }

        function onKecamatanChange() {
            const kecamatan = document.getElementById('searchKecamatan').value;

            updateDesaOptions(kecamatan);
            clearHighlight();
            hideSearchBadge();

            if (!kecamatan || !bantulGeoJSON) return;

            const matchedFeatures = bantulGeoJSON.features.filter(feature => {
                return getKecamatanName(feature).toLowerCase() === kecamatan.toLowerCase();
            });

            highlightFeatures(
                matchedFeatures,
                '📍 Kecamatan ' + kecamatan + ' dipilih. Silakan pilih desa atau kelurahan.'
            );
        }

        function onDesaChange() {
            const kecamatan = document.getElementById('searchKecamatan').value;
            const desa = document.getElementById('searchDesa').value;

            clearHighlight();
            hideSearchBadge();

            if (!kecamatan || !desa) return;

            const selected = desaList.find(item => item.kecamatan === kecamatan && item.desa === desa);
            if (!selected) return;

            highlightFeatures(
                [selected.feature],
                '🏠 ' + selected.desa + ' - Kecamatan ' + selected.kecamatan
            );
        }

        function clearWilayahSearch() {
            const kecamatanSelect = document.getElementById('searchKecamatan');
            if (kecamatanSelect) kecamatanSelect.value = '';

            updateDesaOptions('');
            clearHighlight();
            hideSearchBadge();

            if (allBounds && allBounds.isValid()) {
                map.fitBounds(allBounds, { padding: [50, 50], maxZoom: 14 });
            } else {
                map.setView([-7.8700, 110.3300], 11);
            }
        }

        // ── IMAGE MODAL ───────────────────────────────────────────────────
        function openImageModal(src) {
            if (window.event) {
                window.event.stopPropagation();
                window.event.preventDefault();
            }

            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const imageModal = document.getElementById('imageModal');
            if (imageModal) imageModal.style.display = 'none';
            document.body.style.overflow = '';
        }

        // ── DETAIL MODAL ──────────────────────────────────────────────────
        function showDetail(laporanId) {
            const laporan = laporanData[laporanId];
            if (!laporan) {
                alert('Data laporan tidak ditemukan');
                return;
            }

            document.getElementById('detail-id').textContent = laporan.id;
            document.getElementById('detail-waktu').textContent = formatDateTime(laporan.waktu_laporan);

            let statusBadge = '';
            if (laporan.status === 'pending') {
                statusBadge = '<span class="badge-status badge-pending"><i class="fas fa-clock"></i> Pending</span>';
            } else if (laporan.status === 'verified') {
                statusBadge = '<span class="badge-status badge-verified"><i class="fas fa-check-circle"></i> Verified</span>';
            } else {
                statusBadge = '<span class="badge-status badge-rejected"><i class="fas fa-times-circle"></i> Rejected</span>';
            }

            document.getElementById('detail-status').innerHTML = statusBadge;
            document.getElementById('detail-pelapor').textContent = laporan.nama_pelapor || '-';
            document.getElementById('detail-telp').textContent = laporan.no_telp || '-';
            document.getElementById('detail-kecamatan').textContent = laporan.kecamatan || '-';
            document.getElementById('detail-desa').textContent = laporan.desa || '-';
            document.getElementById('detail-koordinat').textContent = laporan.latitude + ', ' + laporan.longitude;
            document.getElementById('detail-kedalaman').textContent = (laporan.kedalaman_cm || 0) + ' cm';
            document.getElementById('detail-deskripsi').textContent = laporan.deskripsi || 'Tidak ada deskripsi';

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
                fotoContainer.innerHTML = fotoFields.map((f, i) => {
                    const url = getFotoUrl(f);
                    return '' +
                        '<div style="text-align:center;">' +
                            '<img src="' + url + '" alt="Foto ' + (i + 1) + '" ' +
                            'onclick="openImageModal(\'' + url + '\')" ' +
                            'style="width:160px;height:160px;object-fit:cover;border-radius:14px;cursor:pointer;border:3px solid #e2e8f0;box-shadow:0 6px 20px rgba(0,0,0,0.1);">' +
                            '<div style="text-align:center;margin-top:6px;font-size:11px;font-weight:700;color:#64748b;">Foto ' + (i + 1) + '</div>' +
                        '</div>';
                }).join('');
            } else {
                fotoContainer.innerHTML = '' +
                    '<p class="text-muted" style="width:100%;text-align:center;padding:2rem 0;">' +
                        '<i class="fas fa-image fa-3x mb-3 d-block"></i>' +
                        'Tidak ada foto' +
                    '</p>';
            }

            document.getElementById('detailModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeDetailModal() {
            const modal = document.getElementById('detailModal');
            if (modal) modal.classList.remove('show');
            document.body.style.overflow = '';
        }

        // ══════════════════════════════════════════════════════════════════
        // MAP INITIALIZATION
        // ══════════════════════════════════════════════════════════════════
        document.addEventListener('DOMContentLoaded', function () {
            basemaps.streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19
            });

            basemaps.satellite = L.tileLayer(
                'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
                { attribution: 'Tiles &copy; Esri', maxZoom: 19 }
            );

            basemaps.topo = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenTopoMap contributors',
                maxZoom: 17
            });

            map = L.map('map', {
                center: [-7.8700, 110.3300],
                zoom: 11,
                layers: [basemaps.streets],
                zoomControl: false
            });

            L.control.zoom({
                position: 'bottomleft'
            }).addTo(map);

            layerGroups.pending = L.layerGroup();
            layerGroups.verified = L.layerGroup();
            layerGroups.rejected = L.layerGroup();
            layerGroups.batas = L.layerGroup();

            const shadowUrl = 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png';

            const pendingIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-yellow.png',
                shadowUrl: shadowUrl,
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34]
            });

            const verifiedIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                shadowUrl: shadowUrl,
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34]
            });

            const rejectedIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                shadowUrl: shadowUrl,
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34]
            });

            const laporanArray = Object.values(laporanData);

            laporanArray.forEach(function (item) {
                const icon = item.status === 'pending' ? pendingIcon :
                    item.status === 'verified' ? verifiedIcon : rejectedIcon;

                const targetLayer = item.status === 'pending' ? layerGroups.pending :
                    item.status === 'verified' ? layerGroups.verified : layerGroups.rejected;

                const statusColor = item.status === 'pending' ? '#fef3c7' :
                    item.status === 'verified' ? '#d1fae5' : '#fee2e2';

                const statusTxt = item.status === 'pending' ? '#92400e' :
                    item.status === 'verified' ? '#065f46' : '#991b1b';

                const statusLabel = item.status === 'pending' ? 'PENDING' :
                    item.status === 'verified' ? 'VERIFIED' : 'REJECTED';

                const popupFotos = [item.foto, item.foto2, item.foto3].filter(Boolean);
                let fotoHtml = '';

                if (popupFotos.length > 0) {
                    fotoHtml += '<div style="display:flex;gap:4px;margin-bottom:10px;">';

                    popupFotos.forEach(function (f, i) {
                        const url = getFotoUrl(f);
                        const w = popupFotos.length === 1 ? '100%' :
                            popupFotos.length === 2 ? 'calc(50% - 2px)' : 'calc(33.3% - 3px)';

                        fotoHtml += '<img src="' + url + '" alt="Foto ' + (i + 1) + '" ' +
                            'style="width:' + w + ';height:75px;object-fit:cover;border-radius:7px;cursor:pointer;border:2px solid #e2e8f0;" ' +
                            'onclick="openImageModal(\'' + url + '\')">';
                    });

                    fotoHtml += '</div>';

                    if (popupFotos.length > 1) {
                        fotoHtml += '<p style="font-size:10px;color:#64748b;margin-bottom:6px;text-align:center;">' + popupFotos.length + ' foto tersedia</p>';
                    }
                }

                const popupContent =
                    '<div style="min-width:260px;max-width:320px;">' +
                        '<h6 style="font-weight:bold;margin-bottom:10px;font-size:14px;">Laporan #' + item.id + '</h6>' +
                        fotoHtml +
                        '<p style="margin:6px 0;"><strong>Status:</strong> <span style="background:' + statusColor + ';color:' + statusTxt + ';padding:2px 8px;border-radius:4px;font-size:11px;font-weight:bold;">' + statusLabel + '</span></p>' +
                        '<p style="margin:6px 0;"><strong>Lokasi:</strong> ' + item.kecamatan + ', ' + item.desa + '</p>' +
                        '<p style="margin:6px 0;"><strong>Kedalaman:</strong> <strong>' + (item.kedalaman_cm || 0) + ' cm</strong></p>' +
                        '<p style="margin:6px 0;"><strong>Pelapor:</strong> ' + item.nama_pelapor + '</p>' +
                        '<p style="margin:6px 0 12px 0;"><strong>Waktu:</strong> ' + new Date(item.waktu_laporan).toLocaleString('id-ID') + '</p>' +
                        '<div style="display:flex;gap:8px;">' +
                            '<button class="popup-btn-detail" onclick="showDetail(' + item.id + ')" style="flex:1;"><i class="fas fa-info-circle"></i> Detail</button>' +
                            '<button class="popup-btn-route" onclick="goToRoute(' + item.latitude + ',' + item.longitude + ',\'Laporan #' + item.id + '\')" style="flex:1;"><i class="fas fa-route"></i> Rute</button>' +
                        '</div>' +
                    '</div>';

                const marker = L.marker([item.latitude, item.longitude], { icon: icon });
                marker.bindPopup(popupContent, { maxWidth: 340 });
                marker.addTo(targetLayer);
            });

            layerGroups.verified.addTo(map);
            layerGroups.pending.addTo(map);
            layerGroups.rejected.addTo(map);
            layerGroups.batas.addTo(map);

            fetch('/geojson/bantuldesa.geojson')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('File GeoJSON tidak ditemukan');
                    }
                    return response.json();
                })
                .then(data => {
                    prepareWilayahSearch(data);

                    L.geoJSON(data, {
                        style: {
                            fillColor: 'transparent',
                            color: '#1e3c72',
                            weight: 2,
                            opacity: 0.85,
                            dashArray: '6, 4'
                        },
                        onEachFeature: function (feature, layer) {
                            const kecamatan = getKecamatanName(feature) || 'Kab. Bantul';
                            const desa = getDesaName(feature);

                            layer.bindPopup(
                                '<div style="min-width:160px;">' +
                                    '<h6 style="margin:0 0 4px 0;font-weight:800;color:#1e3c72;">Batas Administrasi</h6>' +
                                    (desa ? '<p style="margin:0;font-size:13px;"><strong>Desa:</strong> ' + desa + '</p>' : '') +
                                    '<p style="margin:0;font-size:13px;"><strong>Kecamatan:</strong> ' + kecamatan + '</p>' +
                                '</div>'
                            );

                            layer.on('mouseover', function () {
                                this.setStyle({ fillColor: '#1e3c72', fillOpacity: 0.08 });
                            });

                            layer.on('mouseout', function () {
                                this.setStyle({ fillColor: 'transparent', fillOpacity: 0 });
                            });
                        }
                    }).addTo(layerGroups.batas);
                })
                .catch(error => {
                    console.error('Gagal memuat GeoJSON bantuldesa:', error);
                    showSearchBadge('GeoJSON wilayah belum berhasil dimuat. Cek lokasi file /geojson/bantuldesa.geojson');
                });

            if (laporanArray.length > 0) {
                allBounds = L.latLngBounds();
                laporanArray.forEach(item => allBounds.extend([item.latitude, item.longitude]));

                if (allBounds.isValid()) {
                    map.fitBounds(allBounds, { padding: [50, 50], maxZoom: 14 });
                }
            }

            const loadingOverlay = document.getElementById('loading-overlay');
            if (loadingOverlay) loadingOverlay.style.display = 'none';

            const imageModal = document.getElementById('imageModal');
            if (imageModal) {
                imageModal.addEventListener('click', function (e) {
                    if (e.target === this) closeImageModal();
                });
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    if (isFullscreen) toggleFullscreen();
                    closeImageModal();
                    closeDetailModal();
                }
            });

            console.log('Admin Peta loaded successfully');
        });
    </script>
@endpush
</x-app-layout>
