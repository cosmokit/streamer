<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\HelpArticleController;
use App\Http\Controllers\Admin\TemplateController as AdminTemplateController;
use App\Http\Controllers\Admin\AdminAuthController;

// User auth API routes (используем веб-роуты для сессий!)
Route::post('/api/login', [\App\Http\Controllers\Api\AuthController::class, 'login'])->name('api.login');
Route::post('/api/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware('auth')->name('api.logout');

// Admin auth routes (отдельные от юзерских!)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// Admin panel routes (защищены auth + admin middleware)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle-ban', [UserController::class, 'toggleBan'])->name('users.toggle-ban');
    
    Route::resource('help', HelpArticleController::class);
    Route::resource('templates', AdminTemplateController::class);
});

// SPA fallback - отдаём React frontend для всех остальных маршрутов
Route::get('/{any}', function () {
    return file_get_contents(public_path('app/index.html'));
})->where('any', '^(?!api|admin).*$');
