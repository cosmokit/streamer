<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => explode('@', $request->email)[0],
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json([
            'user' => $user,
            'message' => 'Регистрация успешна'
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        if (Auth::attempt($request->only("email", "password"))) {
            $user = Auth::user();
            
            if ($user->is_admin) {
                Auth::logout();
                return response()->json([
                    "message" => "Используйте /admin/login для входа в админ-панель"
                ], 403);
            }

            if ($user->is_banned) {
                Auth::logout();
                return response()->json([
                    "message" => $user->banned_reason ?: "Ваш аккаунт заблокирован"
                ], 403);
            }

            $request->session()->regenerate();

            return response()->json([
                "user" => $user,
                "message" => "Успешная авторизация"
            ]);
        }

        return response()->json([
            "message" => "Неверный email или пароль"
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            "message" => "Выход выполнен"
        ]);
    }

    public function me(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                "message" => "Не авторизован"
            ], 401);
        }

        $user = Auth::user();
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'telegram' => $user->telegram,
            'twitch' => $user->twitch,
            'is_admin' => $user->is_admin,
            'is_banned' => $user->is_banned,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'is_impersonating' => $request->session()->has('impersonate_admin_id'),
        ];

        return response()->json([
            "data" => $userData,
        ]);
    }
}
