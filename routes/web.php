<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Member;
use App\Http\Controllers\Public;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (No auth required)
|--------------------------------------------------------------------------
*/
Route::get('/', [Public\HomeController::class, 'index'])->name('home');
Route::get('/berita', [Public\NewsController::class, 'index'])->name('news.index');
Route::get('/berita/{slug}', [Public\NewsController::class, 'show'])->name('news.show');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Member Routes (masyarakat)
|--------------------------------------------------------------------------
*/
Route::prefix('member')
     ->name('member.')
     ->middleware(['auth', 'role:masyarakat'])
     ->group(function () {
         Route::get('/dashboard', [Member\DashboardController::class, 'index'])->name('dashboard');

         // Reports (AJAX-based)
         Route::get('/laporan', [Member\ReportController::class, 'index'])->name('reports.index');
         Route::post('/laporan', [Member\ReportController::class, 'store'])->name('reports.store');
         Route::get('/laporan/{report}', [Member\ReportController::class, 'show'])->name('reports.show');
         Route::delete('/laporan/{report}', [Member\ReportController::class, 'destroy'])->name('reports.destroy');
         Route::get('/notifikasi', [\App\Http\Controllers\Member\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifikasi/{id}/read', [\App\Http\Controllers\Member\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifikasi/read-all', [\App\Http\Controllers\Member\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::view('/panduan', 'member.panduan')->name('panduan');
    Route::get('/profil', [\App\Http\Controllers\Member\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profil', [\App\Http\Controllers\Member\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/password', [\App\Http\Controllers\Member\ProfileController::class, 'updatePassword'])->name('profile.password');
     });

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
     ->name('admin.')
     ->middleware(['auth', 'role:admin'])
     ->group(function () {
         Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

         // Reports
         Route::get('/laporan', [Admin\ReportController::class, 'index'])->name('reports.index');
         Route::get('/laporan/{report}', [Admin\ReportController::class, 'show'])->name('reports.show');
         Route::patch('/laporan/{report}/status', [Admin\ReportController::class, 'updateStatus'])->name('reports.status');
         Route::post('/laporan/{report}/response', [Admin\ReportController::class, 'addResponse'])->name('reports.response');
         Route::delete('/laporan/{report}', [Admin\ReportController::class, 'destroy'])->name('reports.destroy');

         // Budgets
         Route::get('/anggaran', [Admin\BudgetController::class, 'index'])->name('budgets.index');
         Route::post('/anggaran/{report}', [Admin\BudgetController::class, 'store'])->name('budgets.store');

         // Users
         Route::get('/pengguna', [Admin\UserController::class, 'index'])->name('users.index');
         Route::post('/pengguna', [Admin\UserController::class, 'store'])->name('users.store');
         Route::get('/pengguna/{user}', [Admin\UserController::class, 'show'])->name('users.show');
         Route::put('/pengguna/{user}', [Admin\UserController::class, 'update'])->name('users.update');
         Route::delete('/pengguna/{user}', [Admin\UserController::class, 'destroy'])->name('users.destroy');

         // Categories
         Route::get('/kategori', [Admin\CategoryController::class, 'index'])->name('categories.index');
         Route::post('/kategori', [Admin\CategoryController::class, 'store'])->name('categories.store');
         Route::get('/kategori/{category}', [Admin\CategoryController::class, 'show'])->name('categories.show');
         Route::put('/kategori/{category}', [Admin\CategoryController::class, 'update'])->name('categories.update');
         Route::delete('/kategori/{category}', [Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

         // News
         Route::get('/berita', [Admin\NewsController::class, 'index'])->name('news.index');
         Route::post('/berita', [Admin\NewsController::class, 'store'])->name('news.store');
         Route::get('/berita/{news}', [Admin\NewsController::class, 'show'])->name('news.show');
         Route::post('/berita/{news}', [Admin\NewsController::class, 'update'])->name('news.update');
         Route::delete('/berita/{news}', [Admin\NewsController::class, 'destroy'])->name('news.destroy');

         // Export Reports
         Route::get('/export/reports-pdf', [\App\Http\Controllers\Admin\ExportController::class, 'reportsPdf'])->name('export.reports-pdf');

         // Settings
         Route::get('/pengaturan', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
         Route::put('/pengaturan', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

         Route::get('/notifikasi', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifikasi/{id}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifikasi/read-all', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
     });
