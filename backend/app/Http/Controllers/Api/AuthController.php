<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function me()
    {
        // Демо: всегда возвращаем user1
        $user = \App\Models\User::find(2);
        return response()->json([
            'data' => $user,
        ]);
    }
}
