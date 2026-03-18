<?php
use App\Http\Controllers\SpaController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\HelpArticleController;
use App\Http\Controllers\Admin\TemplateController as AdminTemplateController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\Admin\AdminAuthController;

// User auth API routes (используем веб-роуты для сессий!)
Route::post('/api/login', [\App\Http\Controllers\Api\AuthController::class, 'login'])->name('api.login');
Route::post('/api/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware('api.auth')->name('api.logout');
Route::get('/api/me', [\App\Http\Controllers\Api\AuthController::class, 'me'])->middleware('api.auth')->name('api.me');

// Protected API routes
Route::middleware('api.auth')->group(function () {
    Route::get('/api/videos', [\App\Http\Controllers\Api\VideoController::class, 'index']);
    Route::get('/api/proxies', [\App\Http\Controllers\Api\ProxyController::class, 'index']);
    Route::post('/api/proxies/upload', [\App\Http\Controllers\Api\ProxyController::class, 'upload']);
    Route::get('/api/stream-runs', [\App\Http\Controllers\Api\StreamRunController::class, 'index']);
    Route::post('/api/stream-runs', [\App\Http\Controllers\Api\StreamRunController::class, 'store']);
    Route::get('/api/notifications', [\App\Http\Controllers\Api\NotificationController::class, 'index']);
    Route::post('/api/notifications/{notification}/read', [\App\Http\Controllers\Api\NotificationController::class, 'markAsRead']);
    Route::get('/api/social-links', [\App\Http\Controllers\Api\SocialLinkController::class, 'index']);
    Route::post('/api/social-links', [\App\Http\Controllers\Api\SocialLinkController::class, 'store']);
    Route::get('/api/templates', [\App\Http\Controllers\Api\TemplateController::class, 'index']);
    Route::get('/api/help', [\App\Http\Controllers\Api\HelpArticleController::class, 'index']);
    Route::get('/api/progress', [\App\Http\Controllers\Api\ProgressController::class, 'index']);
    Route::post('/api/progress/{step}/start', [\App\Http\Controllers\Api\ProgressController::class, 'start']);
    Route::post('/api/progress/{step}/complete', [\App\Http\Controllers\Api\ProgressController::class, 'complete']);
});

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
    Route::post('users/generate', [UserController::class, 'generate'])->name('users.generate');
    Route::post('users/{user}/toggle-ban', [UserController::class, 'toggleBan'])->name('users.toggle-ban');
    Route::post('users/{user}/generate-proxies', [UserController::class, 'generateProxies'])->name('users.generate-proxies');
    Route::get('users/{user}/proxies', [UserController::class, 'proxies'])->name('users.proxies');
    Route::post('users/{user}/proxies/activate-all', [UserController::class, 'activateAllProxies'])->name('users.activate-all-proxies');
    Route::post('users/{user}/proxies/deactivate-all', [UserController::class, 'deactivateAllProxies'])->name('users.deactivate-all-proxies');
    Route::delete('users/{user}/proxies', [UserController::class, 'deleteAllProxies'])->name('users.delete-all-proxies');
    Route::get('users/{user}/progress', [UserController::class, 'progress'])->name('users.progress');
    Route::post('users/{user}/progress/{userProgress}/confirm', [UserController::class, 'confirmProgress'])->name('users.progress.confirm');
    Route::get('users/{user}/proxy-file/download', [UserController::class, 'downloadProxyFile'])->name('users.download-proxy-file');
    Route::post('users/{user}/impersonate', [UserController::class, 'impersonate'])->name('users.impersonate');
    
    Route::resource('help', HelpArticleController::class);
    Route::resource('templates', AdminTemplateController::class);
    Route::resource('videos', AdminVideoController::class);
    Route::resource('learning-steps', \App\Http\Controllers\Admin\LearningStepController::class);
    
    Route::get('proxies', [\App\Http\Controllers\Admin\ProxyController::class, 'index'])->name('proxies.index');
    Route::get('proxies/generate', [\App\Http\Controllers\Admin\ProxyController::class, 'showGenerate'])->name('proxies.generate');
    Route::post('proxies/generate', [\App\Http\Controllers\Admin\ProxyController::class, 'generateProxies'])->name('proxies.generate.post');
    Route::post('proxies/{proxy}/activate', [\App\Http\Controllers\Admin\ProxyController::class, 'activate'])->name('proxies.activate');
    Route::post('proxies/{proxy}/deactivate', [\App\Http\Controllers\Admin\ProxyController::class, 'deactivate'])->name('proxies.deactivate');
    Route::post('proxies/{proxy}/update-status', [\App\Http\Controllers\Admin\ProxyController::class, 'updateStatus'])->name('proxies.update-status');
    Route::delete('proxies/{proxy}', [\App\Http\Controllers\Admin\ProxyController::class, 'destroy'])->name('proxies.destroy');
    Route::post('proxies/activate-all', [\App\Http\Controllers\Admin\ProxyController::class, 'activateAll'])->name('proxies.activate-all');
    
    Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
});

// API routes - using session-based auth via web middleware
Route::prefix('api')->group(function () {
    Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware('auth');
    Route::get('/me', [\App\Http\Controllers\Api\AuthController::class, 'me'])->middleware('auth');

    Route::middleware('auth')->group(function () {
        Route::get('/proxies', [\App\Http\Controllers\Api\ProxyController::class, 'index']);
        Route::post('/proxies/upload', [\App\Http\Controllers\Api\ProxyController::class, 'upload']);

        Route::get('/stream-runs', [\App\Http\Controllers\Api\StreamRunController::class, 'index']);
        Route::post('/stream-runs/start', [\App\Http\Controllers\Api\StreamRunController::class, 'start']);
        Route::post('/stream-runs/stop', [\App\Http\Controllers\Api\StreamRunController::class, 'stop']);

        Route::get('/notifications', [\App\Http\Controllers\Api\NotificationController::class, 'index']);
        Route::post('/notifications/{notification}/read', [\App\Http\Controllers\Api\NotificationController::class, 'markAsRead']);

        Route::get('/social-links', [\App\Http\Controllers\Api\SocialLinkController::class, 'index']);
        Route::post('/social-links', [\App\Http\Controllers\Api\SocialLinkController::class, 'store']);

        Route::get('/videos', [\App\Http\Controllers\Api\VideoController::class, 'index']);

        Route::get('/templates', [\App\Http\Controllers\Api\TemplateController::class, 'index']);

        Route::get('/help', [\App\Http\Controllers\Api\HelpArticleController::class, 'index']);

        Route::get('/progress', [\App\Http\Controllers\Api\ProgressController::class, 'index']);
        Route::post('/progress/{step}/start', [\App\Http\Controllers\Api\ProgressController::class, 'start']);
        Route::post('/progress/{step}/complete', [\App\Http\Controllers\Api\ProgressController::class, 'complete']);
    });
});

// SPA fallback - только для dashboard маршрутов
// SPA routes - добавляем все маршруты фронтенда
Route::get('/login', [SpaController::class, 'index']);
Route::get('/dashboard/{any?}', [SpaController::class, 'index'])->where('any', '.*');

// Stop impersonation (доступно для импорсонированных пользователей)
Route::post('admin/stop-impersonate', [App\Http\Controllers\Admin\UserController::class, 'stopImpersonate'])->middleware('auth')->name('admin.stop-impersonate');
Route::get('/', [SpaController::class, 'index']);
