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

    public function confirmProgress(User $user, $stepId)
    {
        $progress = $user->learningProgress()->where('learning_step_id', $stepId)->first();
        
        if (!$progress) {
            return back()->with('error', 'Прогресс не найден');
        }
        
        if ($progress->status !== 'awaiting_confirmation') {
            return back()->with('error', 'Этот шаг не требует подтверждения');
        }
        
        $progress->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
        
        return back()->with('success', 'Шаг подтверждён');
    }

    public function generateProxies(Request $request, User $user)
    {
        $format = $request->input('format', 'txt');
        $proxies = $this->generateRealisticProxies(11);
        
        $content = '';
        $filename = '';
        
        if ($format === 'json') {
            $content = json_encode($proxies, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            $filename = "proxies_user_{$user->id}_" . date('Y-m-d_His') . ".json";
            $contentType = 'application/json';
        } elseif ($format === 'csv') {
            $content = "ip,port,username,password\n";
            foreach ($proxies as $proxy) {
                $content .= "{$proxy['ip']},{$proxy['port']},{$proxy['username']},{$proxy['password']}\n";
            }
            $filename = "proxies_user_{$user->id}_" . date('Y-m-d_His') . ".csv";
            $contentType = 'text/csv';
        } else {
            foreach ($proxies as $proxy) {
                $content .= "{$proxy['ip']}:{$proxy['port']}:{$proxy['username']}:{$proxy['password']}\n";
            }
            $filename = "proxies_user_{$user->id}_" . date('Y-m-d_His') . ".txt";
            $contentType = 'text/plain';
        }
        
        return response($content)
            ->header('Content-Type', $contentType)
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    public function proxies(User $user)
    {
        $proxies = $user->proxies()->orderBy('created_at', 'desc')->paginate(50);
        return view('admin.users.proxies', compact('user', 'proxies'));
    }

    public function activateAllProxies(User $user)
    {
        $count = $user->proxies()->whereIn('status', ['pending', 'offline'])->count();
        
        if ($count === 0) {
            return back()->with('warning', 'Нет прокси для активации');
        }
        
        $user->proxies()->whereIn('status', ['pending', 'offline'])->update(['status' => 'active']);
        
        return back()->with('success', "Активировано прокси: {$count}");
    }

    public function deactivateAllProxies(User $user)
    {
        $count = $user->proxies()->whereIn('status', ['active', 'online'])->count();
        
        if ($count === 0) {
            return back()->with('warning', 'Нет активных прокси для деактивации');
        }
        
        $user->proxies()->whereIn('status', ['active', 'online'])->update(['status' => 'offline']);
        
        return back()->with('success', "Деактивировано прокси: {$count}");
    }

    public function deleteAllProxies(User $user)
    {
        $count = $user->proxies()->count();
        $user->proxies()->delete();
        
        return back()->with('success', "Удалено прокси: {$count}");
    }

    private function generateRealisticProxies(int $count): array
    {
        $proxies = [];
        $usedIps = [];
        
        $ipRanges = [
            ['45.138.74', 1, 255], ['91.203.15', 1, 255], ['185.223.95', 1, 255],
            ['203.142.75', 1, 255], ['212.83.164', 1, 255], ['94.130.244', 1, 255],
            ['178.128.117', 1, 255], ['167.71.200', 1, 255], ['139.59.176', 1, 255],
            ['159.89.195', 1, 255], ['104.248.143', 1, 255], ['188.166.234', 1, 255],
            ['165.227.84', 1, 255], ['46.101.203', 1, 255], ['157.245.109', 1, 255],
            ['134.209.156', 1, 255], ['161.35.219', 1, 255], ['68.183.205', 1, 255],
        ];
        
        $ports = [8080, 3128, 1080, 8888, 9090, 8000, 3129, 8081, 9091, 1081, 10000];
        
        $firstParts = [
            'proxy', 'user', 'stream', 'elite', 'premium', 'vip', 'fast', 'secure',
            'private', 'dedicated', 'residential', 'mobile', 'datacenter', 'business',
            'pro', 'super', 'mega', 'ultra', 'hyper', 'quantum', 'turbo', 'speed',
            'dash', 'flex', 'nova', 'apex', 'prime', 'omega', 'alpha', 'beta',
        ];
        
        $midParts = [
            'px', 'net', 'link', 'hub', 'zone', 'node', 'core', 'edge', 'mesh',
            'grid', 'cloud', 'wave', 'flow', 'sync', 'bolt', 'spark', 'lite',
        ];
        
        $suffixes = ['', 'x', 'pro', 'plus', 'max', 'ultra', 'v2', 'new'];
        
        for ($i = 0; $i < $count; $i++) {
            do {
                $range = $ipRanges[array_rand($ipRanges)];
                $lastOctet = rand($range[1], $range[2]);
                $ip = $range[0] . '.' . $lastOctet;
            } while (in_array($ip, $usedIps));
            
            $usedIps[] = $ip;
            $port = $ports[array_rand($ports)];
            
            $usernameStyle = rand(1, 6);
            
            switch ($usernameStyle) {
                case 1:
                    $username = $firstParts[array_rand($firstParts)] . rand(100, 9999);
                    break;
                case 2:
                    $username = $firstParts[array_rand($firstParts)] . '_' . $midParts[array_rand($midParts)] . rand(10, 999);
                    break;
                case 3:
                    $username = $midParts[array_rand($midParts)] . rand(1000, 9999) . $suffixes[array_rand($suffixes)];
                    break;
                case 4:
                    $username = $firstParts[array_rand($firstParts)] . rand(10, 99) . $midParts[array_rand($midParts)];
                    break;
                case 5:
                    $username = strtolower($firstParts[array_rand($firstParts)]) . strtoupper($midParts[array_rand($midParts)]) . rand(100, 999);
                    break;
                default:
                    $username = $firstParts[array_rand($firstParts)] . $firstParts[array_rand($firstParts)] . rand(10, 99);
                    break;
            }
            
            $password = $this->generateRealisticPassword();
            
            $proxies[] = [
                'ip' => $ip,
                'port' => $port,
                'username' => $username,
                'password' => $password,
            ];
        }
        
        return $proxies;
    }

    private function generateRealisticPassword(): string
    {
        $patterns = [
            function() {
                $chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789';
                $pass = '';
                for ($j = 0; $j < 8; $j++) {
                    $pass .= $chars[rand(0, strlen($chars) - 1)];
                }
                $sym = ['!', '@', '#', '$', '%', '&', '*'][array_rand(['!', '@', '#', '$', '%', '&', '*'])];
                return $pass . $sym . rand(10, 99);
            },
            function() {
                $word = ['Stream', 'Proxy', 'Elite', 'Premium', 'Fast', 'Secure', 'Data', 'Access', 'Ultra', 'Mega'][array_rand(['Stream', 'Proxy', 'Elite', 'Premium', 'Fast', 'Secure', 'Data', 'Access', 'Ultra', 'Mega'])];
                return $word . rand(2020, 2026) . ['!', '@', '#', '$'][array_rand(['!', '@', '#', '$'])] . chr(rand(65, 90)) . chr(rand(97, 122));
            },
            function() {
                $word1 = ['Ultra', 'Super', 'Mega', 'Hyper', 'Fast', 'Quick'][array_rand(['Ultra', 'Super', 'Mega', 'Hyper', 'Fast', 'Quick'])];
                $word2 = ['Pass', 'Proxy', 'Access', 'Secure'][array_rand(['Pass', 'Proxy', 'Access', 'Secure'])];
                $nums = rand(1000, 9999);
                $sym = ['!', '@', '#', '$'][array_rand(['!', '@', '#', '$'])];
                return $word1 . $nums . $sym . $word2;
            },
            function() {
                $prefix = ['Px', 'Pr', 'Acc', 'Str', 'Dt', 'Us', 'Vp', 'El', 'Pm'][array_rand(['Px', 'Pr', 'Acc', 'Str', 'Dt', 'Us', 'Vp', 'El', 'Pm'])];
                $mid = rand(100, 999);
                $suffix = ['#pX', '@sT', '!aK', '$mN', '%rQ', '&vB', '*hL'][array_rand(['#pX', '@sT', '!aK', '$mN', '%rQ', '&vB', '*hL'])];
                $end = rand(10, 99);
                return $prefix . $mid . $suffix . $end;
            },
            function() {
                $upper = chr(rand(65, 90));
                $lower = chr(rand(97, 122));
                $num = rand(0, 9);
                $sym = ['!', '@', '#', '$', '%', '&', '*'][array_rand(['!', '@', '#', '$', '%', '&', '*'])];
                $word = ['Secure', 'Access', 'Proxy', 'Pass', 'Stream', 'Data', 'Elite', 'Vip'][array_rand(['Secure', 'Access', 'Proxy', 'Pass', 'Stream', 'Data', 'Elite', 'Vip'])];
                return $upper . $lower . $num . $sym . $word . rand(100, 999);
            },
            function() {
                $chars1 = 'ABCDEFGHJKMNPQRSTUVWXYZ';
                $chars2 = 'abcdefghjkmnpqrstuvwxyz';
                $pass = '';
                for ($j = 0; $j < 3; $j++) {
                    $pass .= $chars1[rand(0, strlen($chars1) - 1)];
                    $pass .= $chars2[rand(0, strlen($chars2) - 1)];
                    $pass .= rand(0, 9);
                }
                $sym = ['!', '@', '#', '$', '%'][array_rand(['!', '@', '#', '$', '%'])];
                return $pass . $sym;
            },
        ];
        
        return $patterns[array_rand($patterns)]();
    }

}
