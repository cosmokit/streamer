<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StreamRun;
use App\Models\Notification;
use App\Models\Setting;
use App\Services\NezhaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StreamRunController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $streamRuns = StreamRun::where('user_id', $user->id)
            ->orderBy('day_index', 'desc')
            ->get();
        
        $currentDay = $streamRuns->count();
        $lastRun = $streamRuns->first();
        
        // Check if stream is running (created within last 12 hours)
        $isRunning = false;
        if ($lastRun && $lastRun->created_at->diffInHours(now()) < 12) {
            $isRunning = true;
        }
        
        return response()->json([
            'current_day' => $currentDay,
            'twitch_url' => $lastRun ? $lastRun->twitch_url : null,
            'is_running' => $isRunning,
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

        // Before 4th stream, check if user has >= 65 active proxies
        if ($currentDay == 4) {
            $activeProxiesCount = $user->proxies()->where('status', 'active')->count();
            
            if ($activeProxiesCount < 65) {
                return response()->json([
                    'message' => 'Для начала следующего стрима Вам необходимо предоставить 65 резидентных прокси IPS USA. Для помощи в данном вопросе обратитесь к @chillkiller_v',
                    'error_type' => 'insufficient_proxies',
                    'required_proxies' => 65,
                    'current_proxies' => $activeProxiesCount,
                ], 400);
            }
        }

        $twitchChannel = $user->twitch;
        
        if (!$twitchChannel) {
            return response()->json([
                'message' => 'Twitch канал не указан. Обратитесь к администратору для добавления канала.',
                'error_type' => 'no_twitch',
            ], 400);
        }

        // Call Nezhna service to purchase
        try {
            $nezhaService = new NezhaService();
            $nezhaService->purchaseService(
                serviceId: 1, // Default service ID
                count: 1,
                username: $user->name,
                email: $user->email
            );
        } catch (\Exception $e) {
            Log::error('Nezhna purchase error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Не удалось запустить трафик. Обратитесь в поддержку.',
                'error_type' => 'nezhna_error',
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

    public function stop(Request $request)
    {
        $user = Auth::user();
        $lastRun = StreamRun::where('user_id', $user->id)
            ->latest()
            ->first();
        
        if (!$lastRun) {
            return response()->json([
                'message' => 'Нет активного стрима',
            ], 400);
        }

        Notification::create([
            'user_id' => $user->id,
            'message' => "Трансляция завершена (День {$lastRun->day_index})",
        ]);

        return response()->json([
            'message' => 'Трансляция завершена',
        ]);
    }
}
