<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proxy;
use App\Models\User;
use Illuminate\Http\Request;

class ProxyController extends Controller
{
    public function index(Request $request)
    {
        $query = Proxy::with('user');

        if ($request->filled('username')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('username') . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $proxies = $query->orderBy('created_at', 'desc')->paginate(50)->appends($request->query());

        return view('admin.proxies.index', compact('proxies'));
    }

    public function activate(Proxy $proxy)
    {
        $proxy->update([
            'status' => 'active',
            'is_active' => true,
        ]);

        return back()->with('success', 'Прокси активирован');
    }

    public function deactivate(Proxy $proxy)
    {
        $proxy->update([
            'status' => 'pending',
            'is_active' => false,
        ]);

        return back()->with('success', 'Прокси деактивирован');
    }

    public function updateStatus(Request $request, Proxy $proxy)
    {
        $status = $request->input('status');
        
        $proxy->update([
            'status' => $status,
            'is_active' => in_array($status, ['active', 'online', 'offline']),
        ]);

        return back()->with('success', 'Статус обновлён');
    }

    public function destroy(Proxy $proxy)
    {
        $proxy->delete();
        return back()->with('success', 'Прокси удалён');
    }

    public function activateAll(Request $request)
    {
        $userId = $request->input('user_id');
        
        $updated = Proxy::where('user_id', $userId)
            ->where('status', 'pending')
            ->update([
                'status' => 'active',
                'is_active' => true,
            ]);

        return back()->with('success', "Активировано {$updated} прокси");
    }

    public function showGenerate()
    {
        $users = User::where('is_admin', false)->orderBy('name')->get();
        return view('admin.proxies.generate', compact('users'));
    }

    public function generateProxies(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'count' => 'required|integer|min:1|max:200',
            'status' => 'required|in:pending,active,failed',
        ]);

        $userId = $request->input('user_id');
        $count = $request->input('count');
        $status = $request->input('status');

        $generated = 0;
        for ($i = 0; $i < $count; $i++) {
            $host = implode('.', [rand(1, 255), rand(1, 255), rand(1, 255), rand(1, 255)]);
            $port = rand(8000, 9999);
            $username = 'user_' . strtolower(\Illuminate\Support\Str::random(6));
            $password = \Illuminate\Support\Str::random(10);
            
            $lineRaw = "{$host}:{$port}:{$username}:{$password}";

            Proxy::create([
                'user_id' => $userId,
                'line_raw' => $lineRaw,
                'host' => $host,
                'port' => $port,
                'username' => $username,
                'password' => $password,
                'status' => $status,
                'is_active' => $status === 'active',
            ]);
            $generated++;
        }

        return back()->with('success', "Сгенерировано {$generated} прокси для пользователя");
    }
}
