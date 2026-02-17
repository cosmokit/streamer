<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->withCount(['videos', 'proxies', 'streamRuns'])
                       ->latest()
                       ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'is_admin' => 'boolean',
            'is_banned' => 'boolean',
            'banned_reason' => 'nullable|string',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Пользователь обновлен');
    }

    public function destroy(User $user)
    {
        if ($user->is_admin) {
            return back()->with('error', 'Нельзя удалить администратора');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Пользователь удален');
    }

    public function toggleBan(User $user)
    {
        $user->update([
            'is_banned' => !$user->is_banned,
            'banned_reason' => $user->is_banned ? null : 'Заблокирован администратором',
        ]);

        $status = $user->is_banned ? 'заблокирован' : 'разблокирован';
        return back()->with('success', "Пользователь {$status}");
    }
}
