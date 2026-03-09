<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'data' => $videos,
            'summary' => [
                'total_videos' => $videos->count(),
                'total_duration_minutes' => $videos->sum('duration_minutes'),
            ]
        ]);
    }
}
