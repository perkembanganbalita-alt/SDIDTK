<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PemeriksaanController;
use App\Http\Controllers\RiwayatController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/otp', [AuthController::class, 'showOtpForm'])->name('otp.form');
Route::post('/otp/verify', [AuthController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/otp/resend', [AuthController::class, 'resendOtp'])->name('otp.resend');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('orangtua')->name('orangtua.')->group(function () {
        Route::get('/profile', [\App\Http\Controllers\OrangtuaController::class, 'profile'])->name('profile');
        Route::post('/profile', [\App\Http\Controllers\OrangtuaController::class, 'updateProfile'])->name('profile.update');
    });

    Route::prefix('perkembangan')->name('perkembangan.')->group(function () {
        Route::get('/', [\App\Http\Controllers\PerkembanganController::class, 'index'])->name('index');
    });
    
    Route::prefix('redflag')->name('redflag.')->group(function () {
        Route::get('/', [\App\Http\Controllers\RedFlagController::class, 'index'])->name('index');
    });

    Route::get('/bayi/export', [\App\Http\Controllers\BayiController::class, 'export'])->name('bayi.export');
    Route::resource('bayi', \App\Http\Controllers\BayiController::class)->except(['show']);

    Route::prefix('pemeriksaan/{jenis}')->name('pemeriksaan.')->where(['jenis' => 'kpsp|tdd'])->group(function () {
        Route::get('/', [PemeriksaanController::class, 'index'])->name('index');
        Route::post('/store-bayi', [PemeriksaanController::class, 'storeBayi'])->name('storeBayi');
        Route::get('/kuesioner/{pemeriksaan}', [PemeriksaanController::class, 'kuesioner'])->name('kuesioner');
        Route::post('/kuesioner/{pemeriksaan}/submit', [PemeriksaanController::class, 'submitKuesioner'])->name('submitKuesioner');
        Route::get('/hasil/{pemeriksaan}', [PemeriksaanController::class, 'hasil'])->name('hasil');
    });

    Route::prefix('riwayat')->name('riwayat.')->group(function () {
        Route::get('/', [RiwayatController::class, 'index'])->name('index');
        Route::get('/export', [RiwayatController::class, 'exportAll'])->name('export');
        Route::get('/pdf/{pemeriksaan}', [RiwayatController::class, 'downloadPdf'])->name('pdf');
    });

    Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
        Route::get('/', [\App\Http\Controllers\PengaturanController::class, 'index'])->name('index');
        Route::post('/password', [\App\Http\Controllers\PengaturanController::class, 'updatePassword'])->name('password');

        // Admin only nakes management
        Route::post('/nakes', [\App\Http\Controllers\PengaturanController::class, 'storeNakes'])->name('nakes.store');
        Route::put('/nakes/{user}', [\App\Http\Controllers\PengaturanController::class, 'updateNakes'])->name('nakes.update');
        Route::delete('/nakes/{user}', [\App\Http\Controllers\PengaturanController::class, 'destroyNakes'])->name('nakes.destroy');

        // Admin only orangtua management
        Route::post('/orangtua', [\App\Http\Controllers\PengaturanController::class, 'storeOrangtua'])->name('orangtua.store');
        Route::put('/orangtua/{user}', [\App\Http\Controllers\PengaturanController::class, 'updateOrangtua'])->name('orangtua.update');
        Route::delete('/orangtua/{user}', [\App\Http\Controllers\PengaturanController::class, 'destroyOrangtua'])->name('orangtua.destroy');
        
        // Admin backup
        Route::get('/backup', [\App\Http\Controllers\PengaturanController::class, 'backupDatabase'])->name('backup');
    });
});
