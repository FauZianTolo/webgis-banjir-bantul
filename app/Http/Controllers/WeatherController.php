<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    // Endpoint API: /api/weather?lat=...&lon=...
    public function getWeather(Request $request)
    {
        $validated = $request->validate([
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lon' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $lat = round((float) $validated['lat'], 4);
        $lon = round((float) $validated['lon'], 4);

        $payload = $this->buildWeatherPayload($lat, $lon);

        return response()->json($payload)
            ->header('Cache-Control', 'no-store');
    }

    // Dipakai jika ingin render data cuaca server-side
    public function getServerWeather(float $lat = -7.8877, float $lon = 110.3302): array
    {
        return $this->buildWeatherPayload($lat, $lon);
    }

    private function buildWeatherPayload(float $lat, float $lon): array
    {
        $cacheKey = "weather_v3:{$lat}:{$lon}";

        $cached = Cache::get($cacheKey);
        if ($cached) {
            $cached['updated'] = now()->setTimezone('Asia/Jakarta')->format('H:i \W\I\B');
            $cached['from_cache'] = true;
            return $cached;
        }

        try {
            $location = $this->resolveLocationName($lat, $lon);
            $omData = $this->fetchOpenMeteo($lat, $lon);

            if (empty($omData)) {
                throw new \RuntimeException('Open-Meteo kosong');
            }

            $payload = $this->transformPayload($omData, $location, $lat, $lon);

            Cache::put($cacheKey, $payload, now()->addMinutes(15));

            return $payload;
        } catch (\Throwable) {
            return $this->buildFallback($lat, $lon);
        }
    }

    private function fetchOpenMeteo(float $lat, float $lon): ?array
    {
        $response = Http::timeout(15)
            ->acceptJson()
            ->get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => $lat,
                'longitude' => $lon,
                'current' => implode(',', [
                    'temperature_2m',
                    'relative_humidity_2m',
                    'apparent_temperature',
                    'wind_speed_10m',
                    'wind_gusts_10m',
                    'precipitation_probability',
                    'weather_code',
                    'visibility',
                    'surface_pressure',
                    'uv_index',
                ]),
                'daily' => implode(',', [
                    'weather_code',
                    'temperature_2m_max',
                    'temperature_2m_min',
                    'precipitation_probability_max',
                    'uv_index_max',
                ]),
                'forecast_days' => 7,
                'timezone' => 'Asia/Jakarta',
                'wind_speed_unit' => 'kmh',
            ]);

        if (! $response->successful()) {
            return null;
        }

        $json = $response->json();

        return is_array($json) && isset($json['current']) ? $json : null;
    }

    private function resolveLocationName(float $lat, float $lon): string
    {
        $cacheKey = "weather_loc:{$lat}:{$lon}";

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($lat, $lon) {
            try {
                $response = Http::timeout(8)
                    ->withHeaders(['User-Agent' => 'BANTARA-BanjirBantul/1.0'])
                    ->acceptJson()
                    ->get('https://nominatim.openstreetmap.org/reverse', [
                        'lat' => $lat,
                        'lon' => $lon,
                        'format' => 'jsonv2',
                        'addressdetails' => 1,
                        'zoom' => 10,
                        'accept-language' => 'id',
                    ]);

                if (! $response->successful()) {
                    return 'Kabupaten Bantul, D.I. Yogyakarta';
                }

                $addr = data_get($response->json(), 'address', []);

                $city = $addr['city']
                    ?? $addr['town']
                    ?? $addr['village']
                    ?? $addr['municipality']
                    ?? $addr['county']
                    ?? null;

                $state = $addr['state'] ?? null;

                $parts = array_filter([$city, $state]);

                return $parts ? implode(', ', $parts) : 'Kabupaten Bantul, D.I. Yogyakarta';
            } catch (\Throwable) {
                return 'Kabupaten Bantul, D.I. Yogyakarta';
            }
        });
    }

    private function transformPayload(array $om, string $location, float $lat, float $lon): array
    {
        $cur = $om['current'] ?? [];
        $daily = $om['daily'] ?? [];

        $wcode = (int) ($cur['weather_code'] ?? 0);
        $mapped = $this->mapWeatherCode($wcode);

        $current = [
            'temp' => $this->round($cur['temperature_2m'] ?? 29),
            'feels_like' => $this->round($cur['apparent_temperature'] ?? 29),
            'humidity' => $this->round($cur['relative_humidity_2m'] ?? 82),
            'wind_speed' => $this->round($cur['wind_speed_10m'] ?? 10),
            'wind_gust' => $this->round($cur['wind_gusts_10m'] ?? 15),
            'rain_prob' => $this->round($cur['precipitation_probability'] ?? 0),
            'visibility' => round(($cur['visibility'] ?? 10000) / 1000, 1),
            'uv_index' => $this->round($cur['uv_index'] ?? 0),
            'pressure' => $this->round($cur['surface_pressure'] ?? 1010),
            'weather' => $mapped['label'],
            'icon' => $mapped['icon'],
            'weather_code' => $wcode,
        ];

        $dates = $daily['time'] ?? [];
        $wCodes = $daily['weather_code'] ?? [];
        $tMax = $daily['temperature_2m_max'] ?? [];
        $tMin = $daily['temperature_2m_min'] ?? [];
        $rainProb = $daily['precipitation_probability_max'] ?? [];
        $uvMax = $daily['uv_index_max'] ?? [];

        $today = Carbon::today('Asia/Jakarta');
        $forecast = [];

        foreach ($dates as $i => $dateStr) {
            $date = Carbon::parse($dateStr)->setTimezone('Asia/Jakarta');
            if ($date->lt($today)) {
                continue;
            }

            $fcMapped = $this->mapWeatherCode((int) ($wCodes[$i] ?? 0));
            $diff = $today->diffInDays($date);

            $label = match (true) {
                $diff === 0 => 'Hari Ini',
                $diff === 1 => 'Besok',
                default => $date->locale('id')->isoFormat('ddd, D MMM'),
            };

            $forecast[] = [
                'date' => $label,
                'temp_max' => $this->round($tMax[$i] ?? 32),
                'temp_min' => $this->round($tMin[$i] ?? 24),
                'rain_prob' => $this->round($rainProb[$i] ?? 0),
                'uv_index' => $this->round($uvMax[$i] ?? 0),
                'weather_text' => $fcMapped['label'],
                'icon' => $fcMapped['icon'],
                'weather_code' => (int) ($wCodes[$i] ?? 0),
            ];

            if (count($forecast) >= 7) {
                break;
            }
        }

        return [
            'location' => $location,
            'coordinates' => ['lat' => $lat, 'lon' => $lon],
            'current' => $current,
            'forecast' => $forecast,
            'updated' => now()->setTimezone('Asia/Jakarta')->format('H:i \W\I\B'),
            'source' => 'open-meteo',
            'from_cache' => false,
        ];
    }

    private function buildFallback(float $lat, float $lon): array
    {
        $today = Carbon::today('Asia/Jakarta');

        $forecastFallback = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $today->copy()->addDays($i);
            $label = match ($i) {
                0 => 'Hari Ini',
                1 => 'Besok',
                default => $date->locale('id')->isoFormat('ddd, D MMM'),
            };

            $forecastFallback[] = [
                'date' => $label,
                'temp_max' => rand(31, 34),
                'temp_min' => rand(23, 26),
                'rain_prob' => rand(30, 60),
                'uv_index' => 5,
                'weather_text' => 'Berawan Sebagian',
                'icon' => 'fa-cloud-sun',
                'weather_code' => 1,
            ];
        }

        return [
            'location' => 'Kabupaten Bantul, D.I. Yogyakarta',
            'coordinates' => ['lat' => $lat, 'lon' => $lon],
            'current' => [
                'temp' => 29,
                'feels_like' => 31,
                'humidity' => 82,
                'wind_speed' => 12,
                'wind_gust' => 18,
                'rain_prob' => 45,
                'visibility' => 10.0,
                'uv_index' => 5,
                'pressure' => 1010,
                'weather' => 'Berawan Sebagian',
                'icon' => 'fa-cloud-sun',
                'weather_code' => 1,
            ],
            'forecast' => $forecastFallback,
            'updated' => now()->setTimezone('Asia/Jakarta')->format('H:i \W\I\B'),
            'source' => 'fallback',
            'from_cache' => false,
        ];
    }

    private function mapWeatherCode(int $code): array
    {
        return match (true) {
            $code === 0 => ['label' => 'Cerah', 'icon' => 'fa-sun'],
            in_array($code, [1, 2], true) => ['label' => 'Cerah Berawan', 'icon' => 'fa-cloud-sun'],
            $code === 3 => ['label' => 'Berawan', 'icon' => 'fa-cloud'],
            in_array($code, [45, 48], true) => ['label' => 'Berkabut', 'icon' => 'fa-smog'],
            in_array($code, [51, 53, 55], true) => ['label' => 'Gerimis', 'icon' => 'fa-cloud-drizzle'],
            in_array($code, [56, 57], true) => ['label' => 'Gerimis Beku', 'icon' => 'fa-cloud-drizzle'],
            in_array($code, [61, 63], true) => ['label' => 'Hujan Ringan', 'icon' => 'fa-cloud-rain'],
            $code === 65 => ['label' => 'Hujan Lebat', 'icon' => 'fa-cloud-showers-heavy'],
            in_array($code, [66, 67], true) => ['label' => 'Hujan Beku', 'icon' => 'fa-cloud-rain'],
            in_array($code, [71, 73, 75, 77], true) => ['label' => 'Salju', 'icon' => 'fa-snowflake'],
            in_array($code, [80, 81], true) => ['label' => 'Hujan Lokal', 'icon' => 'fa-cloud-rain'],
            $code === 82 => ['label' => 'Hujan Deras', 'icon' => 'fa-cloud-showers-heavy'],
            in_array($code, [85, 86], true) => ['label' => 'Hujan Salju', 'icon' => 'fa-snowflake'],
            $code === 95 => ['label' => 'Badai Petir', 'icon' => 'fa-bolt'],
            in_array($code, [96, 99], true) => ['label' => 'Badai Petir Lebat', 'icon' => 'fa-bolt'],
            default => ['label' => 'Cerah Berawan', 'icon' => 'fa-cloud-sun'],
        };
    }

    private function round($value): int
    {
        return (int) round((float) ($value ?? 0));
    }
}
