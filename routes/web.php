<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\WaiverController;
use App\Http\Middleware\AdminAuth;
use Illuminate\Support\Facades\Route;

// Public waiver routes
Route::get('/', [WaiverController::class, 'landing'])->name('landing');
Route::get('/waiver', [WaiverController::class, 'create'])->name('waiver.create');
Route::post('/waiver', [WaiverController::class, 'store'])->name('waiver.store');
Route::get('/waiver/confirm', [WaiverController::class, 'confirm'])->name('waiver.confirm');
Route::get('/waiver/download', [WaiverController::class, 'downloadPdf'])->name('waiver.download');

// Admin routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'loginForm'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login']);
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::middleware(AdminAuth::class)->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/waiver/{waiver}', [AdminController::class, 'show'])->name('admin.waiver.show');
        Route::get('/waiver/{waiver}/pdf', [AdminController::class, 'downloadPdf'])->name('admin.waiver.pdf');
        Route::post('/waiver/{waiver}/resend-email', [AdminController::class, 'resendEmail'])->name('admin.waiver.resend-email');

        // Waiver Text Management
        Route::get('/waiver-texts', [AdminController::class, 'waiverTexts'])->name('admin.waiver-texts.index');
        Route::get('/waiver-texts/create', [AdminController::class, 'createWaiverText'])->name('admin.waiver-texts.create');
        Route::post('/waiver-texts', [AdminController::class, 'storeWaiverText'])->name('admin.waiver-texts.store');
        Route::get('/waiver-texts/{waiverText}/edit', [AdminController::class, 'editWaiverText'])->name('admin.waiver-texts.edit');
        Route::put('/waiver-texts/{waiverText}', [AdminController::class, 'updateWaiverText'])->name('admin.waiver-texts.update');
        Route::post('/waiver-texts/{waiverText}/activate', [AdminController::class, 'activateWaiverText'])->name('admin.waiver-texts.activate');
    });
});
