<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view("admin.auth.login");
    }

    public function login(Request $request)
    {
        $request->validate([
            "username" => "required|string",
            "password" => "required",
        ]);

        if (Auth::attempt(['name' => $request->username, 'password' => $request->password], $request->boolean("remember"))) {
            $request->session()->regenerate();

            if (Auth::user()->is_admin) {
                return redirect()->intended(route("admin.dashboard"));
            }

            Auth::logout();
            throw ValidationException::withMessages([
                "username" => "У вас нет прав доступа к админ-панели.",
            ]);
        }

        throw ValidationException::withMessages([
            "username" => "Неверный логин или пароль.",
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route("admin.login");
    }
}
