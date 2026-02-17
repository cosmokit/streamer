<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Video;
use App\Models\Template;
use App\Models\StreamRun;
use App\Models\Proxy;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users_count' => User::count(),
            'active_users' => User::where('is_banned', false)->count(),
            'videos_count' => Video::count(),
            'templates_count' => Template::count(),
            'streams_count' => StreamRun::count(),
            'active_proxies' => Proxy::where('is_active', true)->count(),
        ];

        $recent_users = User::latest()->take(5)->get();
        $recent_streams = StreamRun::with('user')->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recent_users', 'recent_streams'));
    }
}
