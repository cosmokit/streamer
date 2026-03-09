<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Proxy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProxyController extends Controller
{
    public function index()
    {
        $proxies = Auth::user()->proxies()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $proxies,
            'summary' => [
                'total' => $proxies->count(),
                'pending' => $proxies->where('status', 'pending')->count(),
                'active' => $proxies->where('status', 'active')->count(),
                'online' => $proxies->where('status', 'online')->count(),
                'offline' => $proxies->where('status', 'offline')->count(),
            ]
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:txt,json,csv|max:2048',
        ]);

        $file = $request->file('file');
        $content = file_get_contents($file->getRealPath());
        $extension = $file->getClientOriginalExtension();

        $format = match($extension) {
            'json' => 'json',
            'csv' => 'csv',
            default => 'txt',
        };

        $parsedProxies = Proxy::parseFromFile($content, $format);

        if (empty($parsedProxies)) {
            return response()->json([
                'message' => 'Не удалось распарсить файл или он пустой'
            ], 422);
        }

        $user = Auth::user();
        $addedCount = 0;
        $skippedCount = 0;

        foreach ($parsedProxies as $proxyData) {
            $exists = $user->proxies()->where('host', $proxyData['host'])->exists();
            
            if ($exists) {
                $skippedCount++;
                continue;
            }

            $user->proxies()->create([
                'host' => $proxyData['host'],
                'port' => $proxyData['port'],
                'username' => $proxyData['username'] ?? null,
                'password' => $proxyData['password'] ?? null,
                'status' => 'pending',
            ]);

            $addedCount++;
        }

        return response()->json([
            'message' => "Добавлено: {$addedCount}, пропущено дубликатов: {$skippedCount}",
            'added' => $addedCount,
            'skipped' => $skippedCount,
        ]);
    }

    public function activate()
    {
        $user = Auth::user();
        
        $pendingCount = $user->proxies()->where('status', 'pending')->count();
        
        if ($pendingCount === 0) {
            return response()->json([
                'message' => 'Нет прокси для активации'
            ], 400);
        }

        $user->proxies()->where('status', 'pending')->update([
            'status' => 'active'
        ]);

        return response()->json([
            'message' => "Активировано прокси: {$pendingCount}",
            'activated' => $pendingCount,
        ]);
    }
}
