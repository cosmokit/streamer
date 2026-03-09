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

        if ($request->has('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        $proxies = $query->orderBy('created_at', 'desc')->paginate(50);
        $users = User::all();

        return view('admin.proxies.index', compact('proxies', 'users'));
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
}
