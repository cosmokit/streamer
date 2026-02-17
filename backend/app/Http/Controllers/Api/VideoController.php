<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::where('user_id', 2)
            ->orderBy('added_at', 'desc')
            ->get();
        
        return response()->json([
            'data' => $videos,
        ]);
    }

    public function summary()
    {
        $videos = Video::where('user_id', 2);
        
        $totalVideos = $videos->count();
        $totalDuration = $videos->sum('duration_seconds');
        $totalMinutes = (int)($totalDuration / 60);
        
        $weekAgo = Carbon::now()->subWeek();
        $thisWeek = $videos->where('added_at', '>=', $weekAgo)->count();
        
        return response()->json([
            'total_videos' => $totalVideos,
            'total_duration_minutes' => $totalMinutes,
            'this_week' => $thisWeek,
        ]);
    }
}
