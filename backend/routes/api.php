<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProxyController;
use App\Http\Controllers\Api\StreamRunController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\SocialLinkController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\TemplateController;
use App\Http\Controllers\Api\HelpArticleController;
use App\Http\Controllers\Api\ProgressController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth');

// Protected routes - require authentication
Route::middleware('auth')->group(function () {
    Route::get('/proxies', [ProxyController::class, 'index']);
    Route::post('/proxies/upload', [ProxyController::class, 'upload']);
    Route::post('/proxies/activate', [ProxyController::class, 'activate']);

    Route::post('/stream-runs/start', [StreamRunController::class, 'start']);

    Route::get('/notifications', [NotificationController::class, 'index']);

    Route::get('/social-links', [SocialLinkController::class, 'index']);
    Route::post('/social-links', [SocialLinkController::class, 'store']);

    Route::get('/videos/summary', [VideoController::class, 'summary']);
    Route::get('/videos', [VideoController::class, 'index']);

    Route::get('/templates', [TemplateController::class, 'index']);

    Route::get('/help', [HelpArticleController::class, 'index']);

    Route::get('/progress', [ProgressController::class, 'index']);
    Route::get('/progress/steps', [ProgressController::class, 'steps']);
    Route::post('/progress/steps/{stepKey}/complete', [ProgressController::class, 'completeStep']);
});
