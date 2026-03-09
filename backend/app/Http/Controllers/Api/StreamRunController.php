<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StreamRun;
use App\Models\Notification;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StreamRunController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currentDay = StreamRun::where('user_id', $user->id)->count();
        $lastRun = StreamRun::where('user_id', $user->id)
            ->latest()
            ->first();
        
        return response()->json([
            'current_day' => $currentDay,
            'twitch_url' => $lastRun ? $lastRun->twitch_url : null,
        ]);
    }

    public function start(Request $request)
    {
        $request->validate([
            'twitch_url' => 'required|url',
        ]);

        $user = Auth::user();
        $currentDay = StreamRun::where('user_id', $user->id)->count() + 1;
        
        if ($currentDay > 4) {
            return response()->json([
                'message' => 'Вы уже достигли максимального количества дней (4)',
            ], 400);
        }

        $nezhnaApiKey = Setting::get('nezhna_api_key');
        
        if (!$nezhnaApiKey) {
            return response()->json([
                'message' => 'Сервис временно недоступен. Пожалуйста, обратитесь в поддержку.',
                'error_type' => 'no_api_key',
            ], 503);
        }

        $twitchChannel = $user->twitch;
        
        if (!$twitchChannel) {
            return response()->json([
                'message' => 'Twitch канал не указан. Обратитесь к администратору для добавления канала.',
                'error_type' => 'no_twitch',
            ], 400);
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $nezhnaApiKey,
                    'Accept' => 'application/json',
                ])
                ->post('https://nezhna.com/api/v1/traffic/start', [
                    'twitch_channel' => $twitchChannel,
                    'twitch_url' => $request->twitch_url,
                    'user_id' => $user->id,
                ]);

            if (!$response->successful()) {
                Log::error('Nezhna API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                
                return response()->json([
                    'message' => 'Не удалось запустить трафик. Обратитесь в поддержку.',
                    'error_type' => 'nezhna_error',
                ], 503);
            }
        } catch (\Exception $e) {
            Log::error('Nezhna API exception', [
                'message' => $e->getMessage(),
            ]);
            
            return response()->json([
                'message' => 'Не удалось подключиться к сервису. Обратитесь в поддержку.',
                'error_type' => 'connection_error',
            ], 503);
        }

        $streamRun = StreamRun::create([
            'user_id' => $user->id,
            'twitch_url' => $request->twitch_url,
            'day_index' => $currentDay,
        ]);

        Notification::create([
            'user_id' => $user->id,
            'message' => "Трансляция успешно запущена (День {$currentDay})",
        ]);

        return response()->json([
            'message' => 'Трансляция успешно запущена',
            'day_index' => $currentDay,
            'data' => $streamRun,
        ]);
    }
}
