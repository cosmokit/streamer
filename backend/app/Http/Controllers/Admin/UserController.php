<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telegram', 'like', "%{$search}%")
                  ->orWhere('twitch', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'telegram' => 'nullable|string|max:255',
            'twitch' => 'nullable|string|max:255',
            'is_admin' => 'boolean',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Hash::make($request->password),
            'telegram' => $request->telegram,
            'twitch' => $request->twitch,
            'is_admin' => $request->boolean('is_admin'),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Пользователь создан');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'is_banned' => 'boolean',
            'banned_reason' => 'nullable|string|max:500',
            'telegram' => 'nullable|string|max:255',
            'twitch' => 'nullable|string|max:255',
        ]);

        $user->update($request->only(['name', 'email', 'is_banned', 'banned_reason', 'telegram', 'twitch']));

        return back()->with('success', 'Пользователь обновлён');
    }

    public function progress(User $user)
    {
        $learningProgress = $user->learningProgress()
            ->with('learningStep')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.users.progress', compact('user', 'learningProgress'));
    }

    public function confirmProgress(Request $request, User $user, $progressId)
    {
        $progress = $user->learningProgress()->findOrFail($progressId);
        $progress->update(['status' => 'completed', 'completed_at' => now()]);
        
        return back()->with('success', 'Прогресс подтвержден');
    }

    public function toggleBan(User $user)
    {
        $user->update([
            'is_banned' => !$user->is_banned,
        ]);

        return back()->with('success', $user->is_banned ? 'Пользователь заблокирован' : 'Пользователь разблокирован');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Пользователь удалён');
    }

    public function proxies(User $user)
    {
        $proxies = $user->proxies()->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users.proxies', compact('user', 'proxies'));
    }

    public function activateAllProxies(User $user)
    {
        $pendingCount = $user->proxies()->where('status', 'pending')->count();
        
        if ($pendingCount === 0) {
            return back()->with('warning', 'Нет прокси для активации');
        }
        
        $user->proxies()->where('status', 'pending')->update(['status' => 'active']);
        
        return back()->with('success', "Активировано прокси: {$pendingCount}");
    }

    public function deactivateAllProxies(User $user)
    {
        $activeCount = $user->proxies()->where('status', 'active')->count();
        
        if ($activeCount === 0) {
            return back()->with('warning', 'Нет активных прокси');
        }
        
        $user->proxies()->where('status', 'active')->update(['status' => 'inactive']);
        
        return back()->with('success', "Деактивировано прокси: {$activeCount}");
    }

    public function deleteAllProxies(User $user)
    {
        $count = $user->proxies()->count();
        
        if ($count === 0) {
            return back()->with('warning', 'Нет прокси для удаления');
        }
        
        $user->proxies()->delete();
        
        return back()->with('success', "Удалено прокси: {$count}");
    }

    public function generateProxies(Request $request, User $user)
    {
        $request->validate([
            'format' => 'required|in:txt,json,csv',
            'count' => 'nullable|integer|min:1|max:100',
        ]);

        $format = $request->format;
        $count = $request->count ?? 11;
        $proxies = $this->generateRealisticProxies($count);

        $timestamp = date('Ymd_His');
        $filename = "proxies_user_{$user->id}_{$timestamp}.{$format}";
        
        $content = '';
        $contentType = 'text/plain';

        if ($format === 'txt') {
            $content = implode("\n", array_map(fn($p) => "{$p['host']}:{$p['port']}:{$p['username']}:{$p['password']}", $proxies));
            $contentType = 'text/plain';
        } elseif ($format === 'json') {
            $content = json_encode($proxies, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            $contentType = 'application/json';
        } elseif ($format === 'csv') {
            $content = "host,port,username,password\n";
            $content .= implode("\n", array_map(fn($p) => "{$p['host']},{$p['port']},{$p['username']},{$p['password']}", $proxies));
            $contentType = 'text/csv';
        }

        return response($content)
            ->header('Content-Type', $contentType)
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    private function generateRealisticProxies($count = 11)
    {
        $ips = [
            '45.138.74.100', '91.203.15.224', '185.223.95.162', '178.62.85.45',
            '195.85.59.191', '45.9.74.71', '109.248.6.115', '188.40.181.236',
            '91.239.207.126', '195.244.43.134', '62.122.184.54'
        ];
        
        $ports = [8080, 3128, 8888, 1080, 8000];
        $users = ['proxynet', 'streamhub', 'vipzone', 'eliteproxy', 'netstream'];
        $passes = ['secure_pass_123', 'elite_key_456', 'premium_789', 'vip_access_101', 'stream_key_202'];

        $proxies = [];
        for ($i = 0; $i < min($count, count($ips)); $i++) {
            $proxies[] = [
                'host' => $ips[$i],
                'port' => $ports[$i % count($ports)],
                'username' => $users[$i % count($users)],
                'password' => $passes[$i % count($passes)],
            ];
        }

        return $proxies;
    }
}
