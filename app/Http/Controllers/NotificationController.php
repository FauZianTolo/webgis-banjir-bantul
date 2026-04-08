<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\LaporanBanjir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    // Get unread count (untuk badge)
    public function getUnreadCount()
    {
        try {
            $count = Notification::where('is_read', false)->count();

            return response()->json([
                'count' => $count,
                'success' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting notification count: ' . $e->getMessage());
            return response()->json([
                'count' => 0,
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // Get all notifications (untuk dropdown list)
    public function getNotifications()
    {
        try {
            $notifications = Notification::with('laporan')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            return response()->json([
                'notifications' => $notifications,
                'success' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting notifications: ' . $e->getMessage());
            return response()->json([
                'notifications' => [],
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // Get latest notifications (untuk toast popup real-time)
    public function getLatestNotifications(Request $request)
    {
        try {
            $afterId = $request->input('after', 0);

            // Ambil notifikasi baru yang belum dibaca
            $notifications = Notification::with('laporan')
                ->where('id', '>', $afterId)
                ->where('is_read', false)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            Log::info('Latest notifications check: found ' . $notifications->count() . ' new notifications after ID ' . $afterId);

            return response()->json([
                'notifications' => $notifications,
                'success' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting latest notifications: ' . $e->getMessage());
            return response()->json([
                'notifications' => [],
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // Mark as read
    public function markAsRead($id)
    {
        try {
            $notification = Notification::findOrFail($id);
            $notification->is_read = true;
            $notification->save();

            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Error marking notification as read: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // Mark all as read
    public function markAllAsRead()
    {
        try {
            Notification::where('is_read', false)->update(['is_read' => true]);

            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Error marking all as read: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // Create notification (dipanggil saat submit laporan)
    public static function createLaporanNotification($laporan)
    {
        try {
            // Buat notifikasi untuk SEMUA admin
            $users = \App\Models\User::all();

            foreach ($users as $user) {
                $notif = Notification::create([
                    'user_id' => $user->id,
                    'laporan_id' => $laporan->id,
                    'type' => 'new_laporan',
                    'title' => '🚨 Laporan Banjir Baru!',
                    'message' => 'Laporan banjir baru dari ' . $laporan->nama_pelapor . ' di ' . $laporan->kecamatan . ', ' . $laporan->desa,
                    'is_read' => false
                ]);

                Log::info('Created notification ID: ' . $notif->id . ' for user: ' . $user->id);
            }
        } catch (\Exception $e) {
            Log::error('Error creating notification: ' . $e->getMessage());
        }
    }

    // Create notification saat verify
    public static function createVerifiedNotification($laporan)
    {
        try {
            if ($laporan->user_id) {
                Notification::create([
                    'user_id' => $laporan->user_id,
                    'laporan_id' => $laporan->id,
                    'type' => 'verified',
                    'title' => '✅ Laporan Disetujui',
                    'message' => 'Laporan Anda di ' . $laporan->kecamatan . ' telah diverifikasi oleh admin BPBD.',
                    'is_read' => false
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error creating verified notification: ' . $e->getMessage());
        }
    }

    // Create notification saat reject
    public static function createRejectedNotification($laporan)
    {
        try {
            if ($laporan->user_id) {
                Notification::create([
                    'user_id' => $laporan->user_id,
                    'laporan_id' => $laporan->id,
                    'type' => 'rejected',
                    'title' => '❌ Laporan Ditolak',
                    'message' => 'Laporan Anda di ' . $laporan->kecamatan . ' ditolak. Hubungi admin untuk info lebih lanjut.',
                    'is_read' => false
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error creating rejected notification: ' . $e->getMessage());
        }
    }
}
