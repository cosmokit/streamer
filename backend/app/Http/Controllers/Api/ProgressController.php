<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LearningStep;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $steps = LearningStep::where('is_active', true)
            ->orderBy('order')
            ->get();
        
        $userProgress = UserProgress::where('user_id', $user->id)
            ->get()
            ->keyBy('learning_step_id');
        
        $stepsWithProgress = $steps->map(function ($step) use ($userProgress) {
            $progress = $userProgress->get($step->id);
            
            return [
                'id' => $step->id,
                'title' => $step->title,
                'description' => $step->description,
                'external_url' => $step->external_url,
                'order' => $step->order,
                'status' => $progress ? $progress->status : 'new',
                'started_at' => $progress ? $progress->started_at : null,
                'completed_at' => $progress ? $progress->completed_at : null,
            ];
        });
        
        $completedCount = $stepsWithProgress->where('status', 'completed')->count();
        $totalCount = $stepsWithProgress->count();
        $progressPercentage = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
        
        return response()->json([
            'data' => $stepsWithProgress,
            'summary' => [
                'total' => $totalCount,
                'completed' => $completedCount,
                'progress_percentage' => $progressPercentage,
            ]
        ]);
    }

    public function start(Request $request, LearningStep $step)
    {
        $user = Auth::user();
        
        $progress = UserProgress::firstOrCreate(
            [
                'user_id' => $user->id,
                'learning_step_id' => $step->id,
            ],
            [
                'status' => 'in_progress',
                'started_at' => now(),
            ]
        );
        
        if ($progress->wasRecentlyCreated || $progress->status === 'new') {
            $progress->update([
                'status' => 'in_progress',
                'started_at' => now(),
            ]);
        }
        
        return response()->json([
            'message' => 'Обучение начато',
            'data' => $progress
        ]);
    }

    public function complete(Request $request, LearningStep $step)
    {
        $user = Auth::user();
        
        $progress = UserProgress::where('user_id', $user->id)
            ->where('learning_step_id', $step->id)
            ->firstOrFail();
        
        $progress->update([
            'status' => 'awaiting_confirmation',
        ]);
        
        return response()->json([
            'message' => 'Отправлено на подтверждение',
            'data' => $progress
        ]);
    }
}
