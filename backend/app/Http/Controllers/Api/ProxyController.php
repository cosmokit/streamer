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
        // Демо: всегда user_id=2 (user1)
        $userId = 2;
        $proxies = Proxy::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'data' => $proxies,
            'active_count' => $proxies->where('is_active', true)->count(),
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:txt|max:10240',
        ]);

        $file = $request->file('file');
        $content = file_get_contents($file->getRealPath());
        $lines = explode("\n", $content);
        
        $created = 0;
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }
            
            $parsed = Proxy::parseProxyLine($line);
            
            Proxy::create([
                'user_id' => 2, // Демо: user1
                'line_raw' => $line,
                'protocol' => $parsed['protocol'] ?? null,
                'host' => $parsed['host'] ?? null,
                'port' => $parsed['port'] ?? null,
                'username' => $parsed['username'] ?? null,
                'password' => $parsed['password'] ?? null,
                'is_active' => false,
            ]);
            
            $created++;
        }
        
        return response()->json([
            'message' => "Загружено {$created} прокси",
            'created' => $created,
        ]);
    }

    public function activate()
    {
        $updated = Proxy::where('user_id', 2)
            ->whereNotNull('host')
            ->whereNotNull('port')
            ->update(['is_active' => true]);
        
        return response()->json([
            'message' => "Активировано {$updated} прокси",
            'activated' => $updated,
        ]);
    }
}
