<?php

namespace App\Http\Controllers;

use App\Models\LaporanBanjir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PublicController extends Controller
{
    public function home()
    {
        $totalLaporan = LaporanBanjir::where('status', 'verified')->count();
        $laporanTerbaru = LaporanBanjir::where('status', 'verified')
            ->orderBy('waktu_laporan', 'desc')
            ->take(3)
            ->get();

        return view('public.home', [
            'title' => 'Home',
            'totalLaporan' => $totalLaporan,
            'laporanTerbaru' => $laporanTerbaru
        ]);
    }

    public function peta()
    {
        // Cache data peta selama 10 menit
        $laporan = Cache::remember('laporan_verified', 600, function() {
            return LaporanBanjir::where('status', 'verified')
                ->orderBy('waktu_laporan', 'desc')
                ->get();
        });

        // Hitung total historis dari GeoJSON
        $geojsonPath = public_path('geojson/titikbanjir.geojson');
        $totalHistoris = 0;

        if (file_exists($geojsonPath)) {
            $geojsonContent = file_get_contents($geojsonPath);
            $geojsonData = json_decode($geojsonContent, true);
            $totalHistoris = count($geojsonData['features'] ?? []);
        }

        return view('public.peta', [
            'title' => 'Peta Kerawanan Banjir',
            'laporan' => $laporan,
            'totalHistoris' => $totalHistoris
        ]);
    }

    /**
 * Display route navigation page
 */
public function route(Request $request)
{
    // Validasi parameter
    $lat = $request->query('lat');
    $lng = $request->query('lng');
    $title = $request->query('title', 'Lokasi Tujuan');

    if (!$lat || !$lng) {
        return redirect()->route('peta')->with('error', 'Koordinat tidak valid');
    }

    return view('public.route', [
        'title' => 'Navigasi Rute',
        'targetLat' => $lat,
        'targetLng' => $lng,
        'targetTitle' => $title
    ]);
}

    public function laporan()
    {
        return view('public.laporan', [
            'title' => 'Lapor Banjir'
        ]);
    }

     public function submitLaporan(Request $request)
    {
        $validated = $request->validate([
            'nama_pelapor' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'kecamatan' => 'required|string|max:100',
            'desa' => 'nullable|string|max:100',
            'deskripsi' => 'required|string',
            'kedalaman_cm' => 'nullable|numeric',
            'fotos' => 'nullable|array|max:3',
            'fotos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle multi-foto upload (max 3)
        $fotoKeys = ['foto', 'foto2', 'foto3'];
        if ($request->hasFile('fotos')) {
            $files = $request->file('fotos');
            foreach (array_slice($files, 0, 3) as $i => $file) {
    try {
        $uploaded = cloudinary()->upload($file->getRealPath(), [
            'folder' => 'webgis-banjir/laporan',
            'resource_type' => 'image',
        ]);
        $validated[$fotoKeys[$i]] = $uploaded->getSecurePath();
    } catch (\Exception $e) {
        $fotoName = time() . '_' . ($i + 1) . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/laporan'), $fotoName);
        $validated[$fotoKeys[$i]] = asset('uploads/laporan/' . $fotoName);
    }
}
        }

        // Remove 'fotos' from validated (not a db column)
        unset($validated['fotos']);

        $validated['waktu_laporan'] = now();
        $validated['status'] = 'pending';
        $validated['user_id'] = auth()->id();

        $laporan = LaporanBanjir::create($validated);

        // Clear cache
        Cache::forget('laporan_verified');
        Cache::forget('dashboard_stats');

        // Buat notifikasi
        NotificationController::createLaporanNotification($laporan);

        return redirect()->route('laporan')->with('success', 'Laporan berhasil dikirim! Terima kasih atas partisipasinya.');
    }

    public function statistik()
{
    try {
        // ========== LOAD DATA HISTORIS DARI GEOJSON ==========
        $titikBanjirPath = public_path('geojson/titikbanjir.geojson');
        $batasAdminPath = public_path('geojson/bantul.geojson');

        $historisFeatures = [];
        $kecamatanPolygons = [];

        // Load GeoJSON Batas Admin
        if (file_exists($batasAdminPath)) {
            $adminContent = file_get_contents($batasAdminPath);
            $adminData = json_decode($adminContent, true);
            $adminFeatures = $adminData['features'] ?? [];

            foreach ($adminFeatures as $admin) {
                $props = $admin['properties'] ?? [];
                $geometry = $admin['geometry'] ?? [];

                $kecamatanName = $props['WADMKC'] ?? $props['KECAMATAN'] ?? $props['Kecamatan'] ?? $props['NAME'] ?? null;

                if (!empty($kecamatanName) && isset($geometry['coordinates'])) {
                    $kecamatanPolygons[] = [
                        'name' => trim($kecamatanName),
                        'geometry' => $geometry
                    ];
                }
            }
        }

        // Load GeoJSON Titik Historis
        if (file_exists($titikBanjirPath)) {
            $titikContent = file_get_contents($titikBanjirPath);
            $titikData = json_decode($titikContent, true);
            $historisFeatures = $titikData['features'] ?? [];
        }

        // ========== LOAD DATA LAPORAN MASYARAKAT VERIFIED ==========
        $laporanVerified = LaporanBanjir::where('status', 'verified')
            ->get();

        Log::info('Data loaded - Historis: ' . count($historisFeatures) . ', Laporan Verified: ' . count($laporanVerified) . ', Kecamatan Polygons: ' . count($kecamatanPolygons));

        // ========== GABUNGKAN & PROSES DATA ==========
        $kecamatanCount = [];
        $yearlyData = [];
        $unmatchedCount = 0;

        // 1. PROSES DATA HISTORIS (dari GeoJSON)
        foreach ($historisFeatures as $titik) {
            $props = $titik['properties'] ?? [];
            $geometry = $titik['geometry'] ?? [];
            $coords = $geometry['coordinates'] ?? null;
            $tanggal = $props['Tanggal'] ?? '';

            if (!$coords || count($coords) < 2) {
                continue;
            }

            $lon = $coords[0];
            $lat = $coords[1];

            // Spatial join - cari kecamatan
            $kecamatanName = $this->findKecamatanByPoint($lat, $lon, $kecamatanPolygons);

            if (!$kecamatanName) {
                $unmatchedCount++;
                continue;
            }

            // Count per kecamatan
            if (!isset($kecamatanCount[$kecamatanName])) {
                $kecamatanCount[$kecamatanName] = 0;
            }
            $kecamatanCount[$kecamatanName]++;

            // Count per tahun-bulan
            if (!empty($tanggal)) {
                $parsedDate = $this->parseIndonesianDate($tanggal);

                if ($parsedDate) {
                    $year = $parsedDate['year'];
                    $month = $parsedDate['month'];

                    if (!isset($yearlyData[$year])) {
                        $yearlyData[$year] = array_fill(1, 12, 0);
                    }

                    if ($month >= 1 && $month <= 12) {
                        $yearlyData[$year][$month]++;
                    }
                }
            }
        }

        // 2. PROSES DATA LAPORAN MASYARAKAT VERIFIED
        foreach ($laporanVerified as $laporan) {
            $kecamatanName = trim($laporan->kecamatan);
            $waktu = $laporan->waktu_laporan;

            // Count per kecamatan
            if (!empty($kecamatanName)) {
                if (!isset($kecamatanCount[$kecamatanName])) {
                    $kecamatanCount[$kecamatanName] = 0;
                }
                $kecamatanCount[$kecamatanName]++;
            }

            // Count per tahun-bulan
            if ($waktu) {
                $year = $waktu->year;
                $month = $waktu->month;

                if (!isset($yearlyData[$year])) {
                    $yearlyData[$year] = array_fill(1, 12, 0);
                }

                if ($month >= 1 && $month <= 12) {
                    $yearlyData[$year][$month]++;
                }
            }
        }

        Log::info('Processing complete - Total kecamatan: ' . count($kecamatanCount) . ', Unmatched: ' . $unmatchedCount);

        // ========== SORT & FORMAT ==========
        // Sort kecamatan by count (descending) dan ambil top 10
        arsort($kecamatanCount);
        $top10Kecamatan = array_slice($kecamatanCount, 0, 10, true);

        $kecamatanStats = [];
        foreach ($top10Kecamatan as $kecamatan => $count) {
            $kecamatanStats[] = [
                'kecamatan' => $kecamatan,
                'total' => $count
            ];
        }

        // Available years (sorted descending)
        $availableYears = array_keys($yearlyData);
        rsort($availableYears);

        // Selected year (default: tahun terbaru)
        $selectedYear = request('year', $availableYears[0] ?? date('Y'));

        // Data bulanan untuk tahun yang dipilih
        $laporanPerBulan = [];
        if (isset($yearlyData[$selectedYear])) {
            for ($i = 1; $i <= 12; $i++) {
                $laporanPerBulan[] = [
                    'bulan' => $i,
                    'total' => $yearlyData[$selectedYear][$i] ?? 0
                ];
            }
        } else {
            for ($i = 1; $i <= 12; $i++) {
                $laporanPerBulan[] = ['bulan' => $i, 'total' => 0];
            }
        }

        // Total stats
        $totalLaporan = array_sum($kecamatanCount);
        $totalVerified = count($laporanVerified);

        return view('public.statistik', [
            'title' => 'Statistik Banjir',
            'totalLaporan' => $totalLaporan,
            'totalVerified' => $totalVerified,
            'kecamatanStats' => $kecamatanStats,
            'laporanPerBulan' => $laporanPerBulan,
            'yearlyData' => $yearlyData, // ⭐ BARU: untuk AJAX
            'availableYears' => $availableYears,
            'selectedYear' => $selectedYear
        ]);

    } catch (\Exception $e) {
        Log::error('Error loading statistik: ' . $e->getMessage());
        Log::error($e->getTraceAsString());

        return view('public.statistik', [
            'title' => 'Statistik Banjir',
            'totalLaporan' => 0,
            'totalVerified' => 0,
            'kecamatanStats' => [],
            'laporanPerBulan' => [],
            'yearlyData' => [],
            'availableYears' => [],
            'selectedYear' => date('Y')
        ]);
    }
}

/**
 * Find which kecamatan polygon contains the given point
 *
 * @param float $lat Latitude
 * @param float $lon Longitude
 * @param array $kecamatanPolygons Array of kecamatan polygons
 * @return string|null Kecamatan name or null
 */
private function findKecamatanByPoint($lat, $lon, $kecamatanPolygons)
{
    foreach ($kecamatanPolygons as $kecamatan) {
        $geometry = $kecamatan['geometry'];
        $type = $geometry['type'] ?? '';
        $coordinates = $geometry['coordinates'] ?? [];

        if ($type === 'Polygon') {
            // Polygon memiliki array of rings (outer ring + holes)
            // Kita cek outer ring (index 0)
            if (isset($coordinates[0])) {
                if ($this->pointInPolygon($lat, $lon, $coordinates[0])) {
                    return $kecamatan['name'];
                }
            }
        } elseif ($type === 'MultiPolygon') {
            // MultiPolygon memiliki array of polygons
            foreach ($coordinates as $polygon) {
                // Cek outer ring dari setiap polygon
                if (isset($polygon[0])) {
                    if ($this->pointInPolygon($lat, $lon, $polygon[0])) {
                        return $kecamatan['name'];
                    }
                }
            }
        }
    }

    return null; // Tidak ditemukan di polygon manapun
}

/**
 * Ray Casting Algorithm - Check if point is inside polygon
 *
 * @param float $lat Point latitude
 * @param float $lon Point longitude
 * @param array $polygon Array of [lon, lat] coordinates
 * @return bool True if point is inside polygon
 */
private function pointInPolygon($lat, $lon, $polygon)
{
    $inside = false;
    $count = count($polygon);

    for ($i = 0, $j = $count - 1; $i < $count; $j = $i++) {
        // Polygon coordinates dalam format [longitude, latitude]
        $xi = $polygon[$i][0]; // longitude i
        $yi = $polygon[$i][1]; // latitude i
        $xj = $polygon[$j][0]; // longitude j
        $yj = $polygon[$j][1]; // latitude j

        // Ray casting algorithm
        $intersect = (($yi > $lat) != ($yj > $lat))
            && ($lon < ($xj - $xi) * ($lat - $yi) / ($yj - $yi) + $xi);

        if ($intersect) {
            $inside = !$inside;
        }
    }

    return $inside;
}

/**
 * Parse Indonesian date format to year and month
 */
private function parseIndonesianDate($dateString)
{
    $monthNames = [
        'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4,
        'mei' => 5, 'juni' => 6, 'juli' => 7, 'agustus' => 8,
        'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12
    ];

    $dateString = strtolower(trim($dateString));

    // Try Indonesian format: "12 Januari 2020"
    foreach ($monthNames as $monthName => $monthNum) {
        if (strpos($dateString, $monthName) !== false) {
            preg_match('/(\d+)\s+' . $monthName . '\s+(\d{4})/i', $dateString, $matches);
            if (count($matches) === 3) {
                return ['year' => (int)$matches[2], 'month' => $monthNum];
            }
        }
    }

    // Try ISO format: "2020-01-12"
    if (preg_match('/(\d{4})-(\d{2})-(\d{2})/', $dateString, $matches)) {
        return ['year' => (int)$matches[1], 'month' => (int)$matches[2]];
    }

    // Try format: "12-01-2020" or "12/01/2020"
    if (preg_match('/(\d{2})[-\/](\d{2})[-\/](\d{4})/', $dateString, $matches)) {
        return ['year' => (int)$matches[3], 'month' => (int)$matches[2]];
    }

    return null;
}

    public function berita()
{
    // Ambil data cuaca real dari WeatherController (default: Bantul)
    $weatherController = new WeatherController();
    $weatherPayload = $weatherController->getServerWeather(-7.8877, 110.3302);

    $beritaList = [
        [
            'kategori' => 'Peringatan Dini',
            'kategori_color' => '#ef4444',
            'image' => 'https://images.unsplash.com/photo-1527482797697-8795b05a13fe?w=800&h=500&fit=crop&q=80',
            'tanggal' => '8 Maret 2026',
            'judul' => 'BMKG Prediksi Hujan Lebat di Bantul Pekan Ini',
            'excerpt' => 'Badan Meteorologi memprediksi hujan lebat dengan intensitas tinggi di wilayah Kabupaten Bantul. Masyarakat diimbau untuk waspada terhadap potensi banjir dan genangan air.',
            'link' => 'https://www.bmkg.go.id',
            'icon' => 'fa-cloud-showers-heavy'
        ],
        [
            'kategori' => 'Mitigasi',
            'kategori_color' => '#10b981',
            'image' => 'https://images.unsplash.com/photo-1609220136736-443140cffec6?w=800&h=500&fit=crop&q=80',
            'tanggal' => '5 Maret 2026',
            'judul' => 'BPBD Gelar Simulasi Tanggap Darurat Banjir',
            'excerpt' => 'BPBD Kabupaten Bantul menggelar simulasi tanggap darurat bencana banjir yang melibatkan seluruh elemen masyarakat dan relawan untuk meningkatkan kesiapsiagaan.',
            'link' => '#',
            'icon' => 'fa-users-cog'
        ],
        [
            'kategori' => 'Infrastruktur',
            'kategori_color' => '#f59e0b',
            'image' => 'https://images.unsplash.com/photo-1590508526196-0e9a8d6fddbb?w=800&h=500&fit=crop&q=80',
            'tanggal' => '1 Maret 2026',
            'judul' => 'Normalisasi Sungai di 5 Kecamatan Rawan Banjir',
            'excerpt' => 'Pemerintah Kabupaten Bantul melakukan normalisasi sungai di 5 kecamatan yang teridentifikasi sebagai daerah rawan banjir untuk mengurangi risiko bencana.',
            'link' => '#',
            'icon' => 'fa-water'
        ],
        [
            'kategori' => 'Edukasi',
            'kategori_color' => '#3b82f6',
            'image' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=800&h=500&fit=crop&q=80',
            'tanggal' => '25 Februari 2026',
            'judul' => 'Sosialisasi Penggunaan WebGIS Banjir ke Masyarakat',
            'excerpt' => 'BPBD Bantul mengadakan sosialisasi sistem WebGIS Kerawanan Banjir yang memudahkan masyarakat melaporkan kejadian banjir secara real-time melalui platform digital.',
            'link' => '#',
            'icon' => 'fa-chalkboard-teacher'
        ],
        [
            'kategori' => 'Teknologi',
            'kategori_color' => '#8b5cf6',
            'image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=800&h=500&fit=crop&q=80',
            'tanggal' => '20 Februari 2026',
            'judul' => 'Sistem Monitoring Cuaca 24/7 di Bantul Aktif',
            'excerpt' => 'BPBD meluncurkan sistem monitoring cuaca 24 jam untuk memberikan informasi real-time kepada masyarakat terkait potensi bencana banjir dengan sensor otomatis.',
            'link' => '#',
            'icon' => 'fa-satellite-dish'
        ],
        [
            'kategori' => 'Evakuasi',
            'kategori_color' => '#ec4899',
            'image' => 'https://images.unsplash.com/photo-1547036967-23d11aacaee0?w=800&h=500&fit=crop&q=80',
            'tanggal' => '15 Februari 2026',
            'judul' => 'Pemetaan 45 Titik Evakuasi Banjir di Bantul',
            'excerpt' => 'BPBD berhasil memetakan 45 titik evakuasi banjir yang tersebar di 17 kecamatan untuk antisipasi bencana dan memastikan evakuasi cepat dan terorganisir.',
            'link' => '#',
            'icon' => 'fa-map-marked-alt'
        ]
    ];

    return view('public.berita', [
        'title' => 'Berita & Informasi',
        'cuacaAPI' => $weatherPayload,
        'weatherPayload' => $weatherPayload,
        'beritaList' => $beritaList
    ]);
}

    public function kontak()
    {
        return view('public.kontak', [
            'title' => 'Kontak BPBD'
        ]);
    }


}
