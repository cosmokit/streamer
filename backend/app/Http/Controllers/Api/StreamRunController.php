<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StreamRun;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StreamRunController extends Controller
{
    public function start(Request $request)
    {
        $request->validate([
            'twitch_url' => 'required|url',
        ]);

        $userId = 2; // Демо: user1
        $currentDay = StreamRun::where('user_id', $userId)->count() + 1;
        
        if ($currentDay > 4) {
            return response()->json([
                'message' => 'Вы уже достигли максимального количества дней (4)',
            ], 400);
        }

        $streamRun = StreamRun::create([
            'user_id' => $userId,
            'twitch_url' => $request->twitch_url,
            'day_index' => $currentDay,
        ]);

        Notification::create([
            'user_id' => $userId,
            'message' => "Трансляция успешно запущена (День {$currentDay})",
        ]);

        return response()->json([
            'message' => 'Трансляция успешно запущена',
            'day_index' => $currentDay,
            'data' => $streamRun,
        ]);
    }
}
