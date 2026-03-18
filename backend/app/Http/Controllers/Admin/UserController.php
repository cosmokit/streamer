<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->withCount(['proxies', 'streamRuns']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('telegram', 'like', "%{$search}%")
                  ->orWhere('twitch', 'like', "%{$search}%");
            });
        }

        if ($request->has('stream_days') && $request->stream_days !== '') {
            $streamDays = (int) $request->stream_days;
            
            if ($streamDays === 0) {
                $query->whereDoesntHave('streamRuns');
            } elseif ($streamDays >= 1 && $streamDays <= 3) {
                $query->whereHas('streamRuns', function($q) use ($streamDays) {
                    $q->select('user_id')
                      ->groupBy('user_id')
                      ->havingRaw('COUNT(*) = ?', [$streamDays]);
                });
            } elseif ($streamDays === 4) {
                $query->whereHas('streamRuns', function($q) {
                    $q->select('user_id')
                      ->groupBy('user_id')
                      ->havingRaw('COUNT(*) >= 4');
                });
            }
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.generate');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'telegram' => 'nullable|string|max:255',
            'twitch' => 'nullable|string|max:255',
            'is_admin' => 'boolean',
        ]);

        // Generate random username
        $username = 'user_' . rand(10000, 99999);
        
        // Generate random password (8 characters, letters + numbers)
        $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
        
        // Generate random email
        $email = $username . '@example.com';

        $user = User::create([
            'name' => $username,
            'email' => $email,
            'password' => \Hash::make($password),
            'telegram' => $request->telegram,
            'twitch' => $request->twitch,
            'is_admin' => $request->boolean('is_admin'),
        ]);

        return redirect()->route('admin.users.edit', $user)
            ->with('success', 'Пользователь создан')
            ->with('generated_credentials', [
                'username' => $username,
                'password' => $password,
                'telegram' => $request->telegram,
                'twitch' => $request->twitch,
            ]);
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
        
        $user->proxies()->where('status', 'active')->update(['status' => 'offline']);
        
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
            'count' => 'required|integer|min:1|max:100',
            'status' => 'required|in:pending,active',
        ]);

        $count = $request->count;
        $status = $request->status;
        $proxies = $this->generateRealisticProxies($count);

        foreach ($proxies as $proxy) {
            $lineRaw = "{$proxy['host']}:{$proxy['port']}:{$proxy['username']}:{$proxy['password']}";
            $user->proxies()->create([
                'host' => $proxy['host'],
                'port' => $proxy['port'],
                'username' => $proxy['username'],
                'password' => $proxy['password'],
                'line_raw' => $lineRaw,
                'status' => $status,
            ]);
        }

        return back()->with('success', "Создано прокси: {$count}");
    }

    private function generateRealisticProxies($count = 10)
    {
        $ports = [8080, 3128, 8888, 1080, 8000, 9090, 4145, 1081];
        $prefixes = ['proxy', 'stream', 'vip', 'elite', 'net', 'fast', 'ultra'];

        $proxies = [];
        for ($i = 0; $i < $count; $i++) {
            $host = rand(1, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(1, 254);
            $username = $prefixes[array_rand($prefixes)] . '_' . strtolower(\Illuminate\Support\Str::random(5));
            $password = \Illuminate\Support\Str::random(12);
            $proxies[] = [
                'host' => $host,
                'port' => $ports[array_rand($ports)],
                'username' => $username,
                'password' => $password,
            ];
        }

        return $proxies;
    }

    public function downloadProxyFile(User $user)
    {
        $filePath = storage_path("app/proxy_uploads/{$user->id}/proxies.txt");
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'Файл прокси не найден');
        }
        
        return response()->download($filePath, "proxies_user_{$user->id}.txt");
    }

    public function impersonate(User $user)
    {
        if ($user->is_admin) {
            return back()->with('error', 'Нельзя войти под администратором');
        }
        
        session(['impersonate_admin_id' => auth()->id()]);
        auth()->login($user);
        
        return redirect('/dashboard')->with('success', "Вы вошли как пользователь: {$user->name}");
    }

    public function stopImpersonate()
    {
        $adminId = session('impersonate_admin_id');
        
        if (!$adminId) {
            return redirect('/dashboard');
        }
        
        session()->forget('impersonate_admin_id');
        auth()->loginUsingId($adminId);
        
        return redirect()->route('admin.users.index')->with('success', 'Вы вернулись в админку');
    }
}
