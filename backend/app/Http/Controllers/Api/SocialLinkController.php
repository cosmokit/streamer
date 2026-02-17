<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SocialLinkController extends Controller
{
    public function index()
    {
        $socialLink = SocialLink::where('user_id', 2)->first();
        
        return response()->json([
            'data' => $socialLink ?? ['x_url' => null],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'x_url' => 'nullable|url',
        ]);

        $socialLink = SocialLink::updateOrCreate(
            ['user_id' => 2],
            ['x_url' => $request->x_url]
        );

        return response()->json([
            'message' => 'Ссылка сохранена',
            'data' => $socialLink,
        ]);
    }
}
