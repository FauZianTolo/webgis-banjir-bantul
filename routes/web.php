<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MapAdminController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\ApprovalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES - Accessible without authentication
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/peta', [PublicController::class, 'peta'])->name('peta');
Route::get('/laporan', [PublicController::class, 'laporan'])->name('laporan');
Route::post('/laporan/submit', [PublicController::class, 'submitLaporan'])->name('laporan.submit');
Route::get('/statistik', [PublicController::class, 'statistik'])->name('statistik');
Route::get('/berita', [PublicController::class, 'berita'])->name('berita');
Route::get('/kontak', [PublicController::class, 'kontak'])->name('kontak');
// Route untuk halaman rute/navigasi
Route::get('/peta/rute', [PublicController::class, 'route'])->name('peta.route');
Route::get('/about', function () {
    return view('about', ['title' => 'Tentang Kami']);
})->name('about');

// Weather API (public)
Route::get('/api/weather', [WeatherController::class, 'getWeather'])->name('api.weather');

// Approval Pending Page (public - untuk user yang baru register)
Route::get('/approval-pending', [ApprovalController::class, 'pending'])->name('approval.pending');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES - Require login only
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // ========== NOTIFICATION API (butuh login tapi tidak perlu approved) ==========
    Route::prefix('api/notifications')->group(function () {
        Route::get('/latest', [NotificationController::class, 'getLatestNotifications']);
        Route::get('/count', [NotificationController::class, 'getUnreadCount']);
        Route::get('/', [NotificationController::class, 'getNotifications']);
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead']);
    });

    // Dashboard stats refresh API
    Route::get('/api/dashboard/refresh', [DashboardController::class, 'refreshStats']);

});

/*
|--------------------------------------------------------------------------
| PROFILE ROUTES - Require authentication only
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES - Require authentication, verification, AND approval
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'approved'])->prefix('admin')->group(function () {

    // Check approval status (untuk auto-refresh halaman pending)
Route::get('/check-approval-status', function() {
    if (Auth::check()) {
        return response()->json([
            'approved' => Auth::user()->is_approved
        ]);
    }
    return response()->json(['approved' => false]);
});

    // ========== DASHBOARD ==========
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ========== LAPORAN BANJIR (Verifikasi) ==========
    Route::prefix('laporan')->name('admin.laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/export-pdf', [LaporanController::class, 'exportPdf'])->name('exportPdf');
        Route::post('/{id}/verify', [LaporanController::class, 'verify'])->name('verify');
        Route::post('/{id}/reject', [LaporanController::class, 'reject'])->name('reject');
        Route::delete('/{id}/delete-rejected', [LaporanController::class, 'destroyRejected'])->name('destroyRejected');
    });

    // ========== DATA POINTS (CRUD) ==========
    Route::prefix('points')->name('admin.points.')->group(function () {
        Route::get('/', [PointController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [PointController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PointController::class, 'update'])->name('update');
        Route::delete('/{id}', [PointController::class, 'destroy'])->name('destroy');
    });

    // ========== PETA MONITORING ==========
Route::get('/peta', [MapAdminController::class, 'index'])->name('admin.peta');
Route::get('/peta/rute', [MapAdminController::class, 'route'])->name('admin.peta.route'); // ⭐ TAMBAH INI

    // ========== USER MANAGEMENT ==========
    Route::prefix('users')->name('admin.users.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::post('/{id}/approve', [UserManagementController::class, 'approve'])->name('approve');
        Route::delete('/{id}/reject', [UserManagementController::class, 'reject'])->name('reject');
    });

});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES - From Laravel Breeze
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
