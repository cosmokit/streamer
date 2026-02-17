<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\HelpArticleController;
use App\Http\Controllers\Admin\TemplateController as AdminTemplateController;

// Breeze auth routes
require __DIR__.'/auth.php';

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle-ban', [UserController::class, 'toggleBan'])->name('users.toggle-ban');
    
    Route::resource('help', HelpArticleController::class);
    Route::resource('templates', AdminTemplateController::class);
});

// SPA fallback - отдаём frontend для всех маршрутов кроме /api, /admin, /login, /register
Route::get('/{any}', function () {
    return file_get_contents(public_path('app/index.html'));
})->where('any', '^(?!api|admin|login|register|logout|password).*$');
