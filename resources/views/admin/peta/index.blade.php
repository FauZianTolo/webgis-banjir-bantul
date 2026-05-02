@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // ─────────────────────────────────────────────
    // DATA LAPORAN
    // ─────────────────────────────────────────────
    const laporanData = @json($laporan->keyBy('id'));

    // ─────────────────────────────────────────────
    // VARIABEL GLOBAL MAP
    // ─────────────────────────────────────────────
    let map;
    let layerGroups = {};
    let basemaps = {};
    let currentBasemap = 'streets';
    let isFullscreen = false;
    let allBounds = null;

    let bantulGeoJSON = null;
    let highlightLayer = null;
    let desaList = [];

    // ─────────────────────────────────────────────
    // HELPER UMUM
    // ─────────────────────────────────────────────
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
            'WADMKC',
            'KECAMATAN',
            'Kecamatan',
            'NAMKEC',
            'NAME_2'
        ]);
    }

    function getDesaName(feature) {
        return getPropertyValue(feature.properties || {}, [
            'WADMKD',
            'DESA',
            'Desa',
            'NAMOBJ',
            'NAMDES',
            'NAME_3'
        ]);
    }

    // ─────────────────────────────────────────────
    // FULLSCREEN
    // ─────────────────────────────────────────────
    function toggleFullscreen() {
        const wrapper = document.getElementById('mapOuterWrapper');

        isFullscreen = !isFullscreen;

        wrapper.classList.toggle('is-fullscreen', isFullscreen);
        document.body.style.overflow = isFullscreen ? 'hidden' : '';

        setTimeout(function () {
            if (map) {
                map.invalidateSize();
            }
        }, 350);
    }

    // ─────────────────────────────────────────────
    // ZOOM KE SEMUA DATA
    // ─────────────────────────────────────────────
    function zoomToExtent() {
        if (allBounds && allBounds.isValid()) {
            map.fitBounds(allBounds, {
                padding: [50, 50],
                maxZoom: 14
            });
        } else {
            map.setView([-7.8700, 110.3300], 11);
        }
    }

    // ─────────────────────────────────────────────
    // TOGGLE LAYER
    // ─────────────────────────────────────────────
    function toggleLayer(name, visible) {
        if (!layerGroups[name]) return;

        if (visible) {
            layerGroups[name].addTo(map);
        } else {
            map.removeLayer(layerGroups[name]);
        }
    }

    // ─────────────────────────────────────────────
    // PANEL KETERANGAN PETA
    // ─────────────────────────────────────────────
    function toggleLayerPanel() {
        document.getElementById('layerPanelBody').classList.toggle('open');
        document.getElementById('layerPanelToggle').classList.toggle('open');
    }

    function toggleSection(bodyId, hdrId) {
        document.getElementById(bodyId).classList.toggle('open');
        document.getElementById(hdrId).classList.toggle('open');
    }

    // ─────────────────────────────────────────────
    // BASEMAP
    // ─────────────────────────────────────────────
    function switchBasemap(name) {
        if (currentBasemap === name) return;

        if (basemaps[currentBasemap]) {
            map.removeLayer(basemaps[currentBasemap]);
        }

        if (basemaps[name]) {
            basemaps[name].addTo(map);
        }

        document.querySelectorAll('.basemap-btn').forEach(function (btn) {
            btn.classList.remove('active');
        });

        const activeButton = document.getElementById('btn-' + name);

        if (activeButton) {
            activeButton.classList.add('active');
        }

        currentBasemap = name;
    }

    // ─────────────────────────────────────────────
    // HIGHLIGHT POLYGON
    // ─────────────────────────────────────────────
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

        const featureCollection = {
            type: 'FeatureCollection',
            features: features
        };

        highlightLayer = L.geoJSON(featureCollection, {
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
            map.fitBounds(bounds, {
                padding: [45, 45],
                maxZoom: 15
            });
        }

        showSearchBadge(label);
    }

    // ─────────────────────────────────────────────
    // PENCARIAN KECAMATAN DAN DESA
    // ─────────────────────────────────────────────
    function prepareWilayahSearch(data) {
        bantulGeoJSON = data;

        desaList = data.features
            .map(function (feature) {
                return {
                    kecamatan: getKecamatanName(feature),
                    desa: getDesaName(feature),
                    feature: feature
                };
            })
            .filter(function (item) {
                return item.kecamatan && item.desa;
            })
            .sort(function (a, b) {
                if (a.kecamatan === b.kecamatan) {
                    return a.desa.localeCompare(b.desa);
                }

                return a.kecamatan.localeCompare(b.kecamatan);
            });

        const kecamatanList = [...new Set(desaList.map(function (item) {
            return item.kecamatan;
        }))].sort(function (a, b) {
            return a.localeCompare(b);
        });

        const kecamatanSelect = document.getElementById('searchKecamatan');

        if (!kecamatanSelect) return;

        kecamatanSelect.innerHTML = '<option value="">🔍 Pilih Kecamatan...</option>';

        kecamatanList.forEach(function (kecamatan) {
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

        const desaFiltered = desaList.filter(function (item) {
            return item.kecamatan === kecamatan;
        });

        desaSelect.disabled = false;
        desaSelect.innerHTML = '<option value="">🏠 Pilih Desa / Kelurahan...</option>';

        desaFiltered.forEach(function (item) {
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

        if (!kecamatan || !bantulGeoJSON) {
            return;
        }

        const matchedFeatures = bantulGeoJSON.features.filter(function (feature) {
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

        if (!kecamatan || !desa) {
            return;
        }

        const selected = desaList.find(function (item) {
            return item.kecamatan === kecamatan && item.desa === desa;
        });

        if (!selected) {
            return;
        }

        highlightFeatures(
            [selected.feature],
            '🏠 ' + selected.desa + ' - Kecamatan ' + selected.kecamatan
        );
    }

    function clearWilayahSearch() {
        const kecamatanSelect = document.getElementById('searchKecamatan');

        if (kecamatanSelect) {
            kecamatanSelect.value = '';
        }

        updateDesaOptions('');
        clearHighlight();
        hideSearchBadge();

        if (allBounds && allBounds.isValid()) {
            map.fitBounds(allBounds, {
                padding: [50, 50],
                maxZoom: 14
            });
        } else {
            map.setView([-7.8700, 110.3300], 11);
        }
    }

    // ─────────────────────────────────────────────
    // MODAL FOTO
    // ─────────────────────────────────────────────
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
        document.getElementById('imageModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    // ─────────────────────────────────────────────
    // MODAL DETAIL LAPORAN
    // ─────────────────────────────────────────────
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
            fotoContainer.innerHTML = fotoFields.map(function (f, i) {
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
        document.getElementById('detailModal').classList.remove('show');
        document.body.style.overflow = '';
    }

    // ─────────────────────────────────────────────
    // INISIALISASI MAP
    // ─────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        basemaps.streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        });

        basemaps.satellite = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
            {
                attribution: 'Tiles &copy; Esri',
                maxZoom: 19
            }
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
            const icon =
                item.status === 'pending'
                    ? pendingIcon
                    : item.status === 'verified'
                        ? verifiedIcon
                        : rejectedIcon;

            const targetLayer =
                item.status === 'pending'
                    ? layerGroups.pending
                    : item.status === 'verified'
                        ? layerGroups.verified
                        : layerGroups.rejected;

            const statusColor =
                item.status === 'pending'
                    ? '#fef3c7'
                    : item.status === 'verified'
                        ? '#d1fae5'
                        : '#fee2e2';

            const statusTxt =
                item.status === 'pending'
                    ? '#92400e'
                    : item.status === 'verified'
                        ? '#065f46'
                        : '#991b1b';

            const statusLabel =
                item.status === 'pending'
                    ? 'PENDING'
                    : item.status === 'verified'
                        ? 'VERIFIED'
                        : 'REJECTED';

            const popupFotos = [item.foto, item.foto2, item.foto3].filter(Boolean);

            let fotoHtml = '';

            if (popupFotos.length > 0) {
                fotoHtml += '<div style="display:flex;gap:4px;margin-bottom:10px;">';

                popupFotos.forEach(function (f, i) {
                    const url = getFotoUrl(f);
                    const width =
                        popupFotos.length === 1
                            ? '100%'
                            : popupFotos.length === 2
                                ? 'calc(50% - 2px)'
                                : 'calc(33.3% - 3px)';

                    fotoHtml += '' +
                        '<img src="' + url + '" alt="Foto ' + (i + 1) + '" ' +
                        'style="width:' + width + ';height:75px;object-fit:cover;border-radius:7px;cursor:pointer;border:2px solid #e2e8f0;" ' +
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

            const marker = L.marker([item.latitude, item.longitude], {
                icon: icon
            });

            marker.bindPopup(popupContent, {
                maxWidth: 340
            });

            marker.addTo(targetLayer);
        });

        layerGroups.verified.addTo(map);
        layerGroups.pending.addTo(map);
        layerGroups.rejected.addTo(map);
        layerGroups.batas.addTo(map);

        fetch('/geojson/bantuldesa.geojson')
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('File GeoJSON tidak ditemukan');
                }

                return response.json();
            })
            .then(function (data) {
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
                            this.setStyle({
                                fillColor: '#1e3c72',
                                fillOpacity: 0.08
                            });
                        });

                        layer.on('mouseout', function () {
                            this.setStyle({
                                fillColor: 'transparent',
                                fillOpacity: 0
                            });
                        });
                    }
                }).addTo(layerGroups.batas);
            })
            .catch(function (error) {
                console.error('Gagal memuat GeoJSON bantuldesa:', error);
                showSearchBadge('GeoJSON wilayah belum berhasil dimuat.');
            });

        if (laporanArray.length > 0) {
            allBounds = L.latLngBounds();

            laporanArray.forEach(function (item) {
                allBounds.extend([item.latitude, item.longitude]);
            });

            if (allBounds.isValid()) {
                map.fitBounds(allBounds, {
                    padding: [50, 50],
                    maxZoom: 14
                });
            }
        }

        const loadingOverlay = document.getElementById('loading-overlay');

        if (loadingOverlay) {
            loadingOverlay.style.display = 'none';
        }

        const imageModal = document.getElementById('imageModal');

        if (imageModal) {
            imageModal.addEventListener('click', function (e) {
                if (e.target === this) {
                    closeImageModal();
                }
            });
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                if (isFullscreen) {
                    toggleFullscreen();
                }

                closeImageModal();
                closeDetailModal();
            }
        });

        console.log('Admin Peta loaded successfully');
    });
</script>
@endpush
