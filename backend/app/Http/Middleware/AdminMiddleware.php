<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Необходима авторизация');
        }

        if (!Auth::user()->is_admin) {
            abort(403, 'Доступ запрещен. Только для администраторов.');
        }

        return $next($request);
    }
}
