<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProgressStep;
use App\Models\StreamRun;
use App\Models\Proxy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    public function index()
    {
        $userId = 2; // Демо: user1
        
        $runsCount = StreamRun::where('user_id', $userId)->count();
        $activeProxiesCount = Proxy::where('user_id', $userId)
            ->where('is_active', true)
            ->count();
        
        $monetizationLocked = $runsCount < 4 || $activeProxiesCount === 0;
        $reason = [];
        
        if ($runsCount < 4) {
            $reason[] = "Нужно {$runsCount}/4 запуска стрима";
        }
        if ($activeProxiesCount === 0) {
            $reason[] = 'Нет активных прокси';
        }
        
        return response()->json([
            'runs_count' => $runsCount,
            'day_index' => min($runsCount, 4),
            'active_proxies_count' => $activeProxiesCount,
            'monetization_locked' => $monetizationLocked,
            'reason' => implode(', ', $reason),
        ]);
    }

    public function steps()
    {
        $steps = ProgressStep::where('user_id', 2)
            ->orderBy('step_key')
            ->get()
            ->keyBy('step_key');
        
        return response()->json([
            'data' => $steps,
        ]);
    }

    public function completeStep(string $stepKey)
    {
        $step = ProgressStep::updateOrCreate(
            [
                'user_id' => 2,
                'step_key' => $stepKey,
            ],
            [
                'is_completed' => true,
                'completed_at' => now(),
            ]
        );

        return response()->json([
            'message' => 'Шаг отмечен как выполненный',
            'data' => $step,
        ]);
    }
}
