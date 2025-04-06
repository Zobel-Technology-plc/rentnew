<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\Admin\CompanySettingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Redirect root to login if not authenticated
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->isSuperAdmin()) {
            return redirect()->route('superadmin.dashboard');
        }
        if (Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Regular user dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin routes
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::get('/settings/company', [CompanySettingController::class, 'edit'])->name('settings.company');
        Route::post('/settings/company', [CompanySettingController::class, 'update'])->name('settings.company.update');
    });

    // SuperAdmin routes
    Route::prefix('superadmin')
        ->middleware(['auth', 'verified', 'superadmin'])
        ->group(function () {
            Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('superadmin.dashboard');
            Route::get('/users', [UserController::class, 'index'])->name('superadmin.users');
            Route::get('/analytics', [AnalyticsController::class, 'index'])->name('superadmin.analytics');
            Route::get('/settings', [SettingsController::class, 'index'])->name('superadmin.settings');
        });
});

require __DIR__.'/auth.php';
