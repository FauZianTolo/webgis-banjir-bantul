<?php

namespace App\Services;

use App\Models\LaporanBanjir;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    public function isConfigured(): bool
    {
        return (bool) config('services.whatsapp.enabled')
            && filled(config('services.whatsapp.phone_number_id'))
            && filled(config('services.whatsapp.token'));
    }

    public function normalizePhone(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $phone);

        if (!$digits) {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        } elseif (str_starts_with($digits, '8')) {
            $digits = '62' . $digits;
        }

        if (!preg_match('/^62[0-9]{8,15}$/', $digits)) {
            return null;
        }

        return $digits;
    }

    public function sendLaporanDiterima(LaporanBanjir $laporan): bool
    {
        $statusUrl = route('laporan.status', ['q' => $laporan->id], true);
        $adminPhone = config('services.whatsapp.admin_phone', '6287834755177');

        $body = "Halo {$laporan->nama_pelapor},\n\n"
            . "Laporan kejadian banjir Anda telah diterima oleh sistem WebGIS Banjir Bantul.\n\n"
            . "ID Laporan: #{$laporan->id}\n"
            . "Lokasi: {$laporan->desa}, {$laporan->kecamatan}\n"
            . "Status: MENUNGGU VERIFIKASI ADMIN\n\n"
            . "Silakan pantau status laporan melalui tautan berikut:\n{$statusUrl}\n\n"
            . "Nomor layanan/admin: {$adminPhone}\n"
            . "Terima kasih atas partisipasi Anda.";

        return $this->sendSmartMessage('laporan_diterima', $laporan->no_telp, $body, [
            $laporan->nama_pelapor ?: 'Pelapor',
            '#' . $laporan->id,
            trim(($laporan->desa ? $laporan->desa . ', ' : '') . $laporan->kecamatan),
            $statusUrl,
        ]);
    }

    public function sendLaporanDiverifikasi(LaporanBanjir $laporan): bool
    {
        $statusUrl = route('laporan.status', ['q' => $laporan->id], true);
        $adminPhone = config('services.whatsapp.admin_phone', '6287834755177');

        $body = "Halo {$laporan->nama_pelapor},\n\n"
            . "Laporan kejadian banjir Anda telah DIVERIFIKASI oleh admin WebGIS Banjir Bantul.\n\n"
            . "ID Laporan: #{$laporan->id}\n"
            . "Lokasi: {$laporan->desa}, {$laporan->kecamatan}\n"
            . "Status: TERVERIFIKASI\n\n"
            . "Cek status laporan:\n{$statusUrl}\n\n"
            . "Nomor layanan/admin: {$adminPhone}";

        return $this->sendSmartMessage('laporan_diverifikasi', $laporan->no_telp, $body, [
            $laporan->nama_pelapor ?: 'Pelapor',
            '#' . $laporan->id,
            trim(($laporan->desa ? $laporan->desa . ', ' : '') . $laporan->kecamatan),
            $statusUrl,
        ]);
    }

    public function sendLaporanDitolak(LaporanBanjir $laporan): bool
    {
        $statusUrl = route('laporan.status', ['q' => $laporan->id], true);
        $adminPhone = config('services.whatsapp.admin_phone', '6287834755177');

        $body = "Halo {$laporan->nama_pelapor},\n\n"
            . "Mohon maaf, laporan kejadian banjir Anda belum dapat diverifikasi oleh admin WebGIS Banjir Bantul.\n\n"
            . "ID Laporan: #{$laporan->id}\n"
            . "Lokasi: {$laporan->desa}, {$laporan->kecamatan}\n"
            . "Status: DITOLAK / BELUM MEMENUHI SYARAT VERIFIKASI\n\n"
            . "Silakan cek status laporan:\n{$statusUrl}\n\n"
            . "Nomor layanan/admin: {$adminPhone}";

        return $this->sendSmartMessage('laporan_ditolak', $laporan->no_telp, $body, [
            $laporan->nama_pelapor ?: 'Pelapor',
            '#' . $laporan->id,
            trim(($laporan->desa ? $laporan->desa . ', ' : '') . $laporan->kecamatan),
            $statusUrl,
        ]);
    }

    private function sendSmartMessage(string $templateKey, ?string $recipientPhone, string $fallbackText, array $templateParams = []): bool
    {
        if ((bool) config('services.whatsapp.use_template')) {
            $templateName = config("services.whatsapp.templates.{$templateKey}");
            if ($templateName) {
                return $this->sendTemplateMessage($recipientPhone, $templateName, $templateParams);
            }
        }

        return $this->sendTextMessage($recipientPhone, $fallbackText);
    }

    public function sendTextMessage(?string $recipientPhone, string $message): bool
    {
        $to = $this->normalizePhone($recipientPhone);

        if (!$to) {
            Log::warning('WhatsApp tidak dikirim: nomor tujuan tidak valid.', ['recipient_phone' => $recipientPhone]);
            return false;
        }

        if (!$this->isConfigured()) {
            Log::warning('WhatsApp tidak dikirim: konfigurasi WhatsApp Cloud API belum lengkap.', ['to' => $to]);
            return false;
        }

        try {
            $apiVersion = config('services.whatsapp.api_version', 'v20.0');
            $phoneNumberId = config('services.whatsapp.phone_number_id');
            $token = config('services.whatsapp.token');

            $response = Http::withToken($token)
                ->acceptJson()
                ->timeout(20)
                ->post("https://graph.facebook.com/{$apiVersion}/{$phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'recipient_type' => 'individual',
                    'to' => $to,
                    'type' => 'text',
                    'text' => [
                        'preview_url' => true,
                        'body' => $message,
                    ],
                ]);

            if (!$response->successful()) {
                Log::error('Gagal mengirim WhatsApp text message.', [
                    'to' => $to,
                    'status' => $response->status(),
                    'response' => $response->json() ?: $response->body(),
                ]);
                return false;
            }

            Log::info('WhatsApp text message berhasil dikirim.', [
                'to' => $to,
                'response' => $response->json(),
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error('Exception saat mengirim WhatsApp text message.', [
                'to' => $to,
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function sendTemplateMessage(?string $recipientPhone, string $templateName, array $bodyParams = []): bool
    {
        $to = $this->normalizePhone($recipientPhone);

        if (!$to) {
            Log::warning('WhatsApp template tidak dikirim: nomor tujuan tidak valid.', ['recipient_phone' => $recipientPhone]);
            return false;
        }

        if (!$this->isConfigured()) {
            Log::warning('WhatsApp template tidak dikirim: konfigurasi WhatsApp Cloud API belum lengkap.', [
                'to' => $to,
                'template' => $templateName,
            ]);
            return false;
        }

        try {
            $apiVersion = config('services.whatsapp.api_version', 'v20.0');
            $phoneNumberId = config('services.whatsapp.phone_number_id');
            $token = config('services.whatsapp.token');
            $languageCode = config('services.whatsapp.template_language', 'id');

            $components = [];
            if (!empty($bodyParams)) {
                $components[] = [
                    'type' => 'body',
                    'parameters' => array_map(fn ($value) => [
                        'type' => 'text',
                        'text' => (string) $value,
                    ], $bodyParams),
                ];
            }

            $payload = [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $to,
                'type' => 'template',
                'template' => [
                    'name' => $templateName,
                    'language' => [
                        'code' => $languageCode,
                    ],
                ],
            ];

            if (!empty($components)) {
                $payload['template']['components'] = $components;
            }

            $response = Http::withToken($token)
                ->acceptJson()
                ->timeout(20)
                ->post("https://graph.facebook.com/{$apiVersion}/{$phoneNumberId}/messages", $payload);

            if (!$response->successful()) {
                Log::error('Gagal mengirim WhatsApp template message.', [
                    'to' => $to,
                    'template' => $templateName,
                    'status' => $response->status(),
                    'response' => $response->json() ?: $response->body(),
                ]);
                return false;
            }

            Log::info('WhatsApp template message berhasil dikirim.', [
                'to' => $to,
                'template' => $templateName,
                'response' => $response->json(),
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error('Exception saat mengirim WhatsApp template message.', [
                'to' => $to,
                'template' => $templateName,
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
