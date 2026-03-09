<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.videos.index', compact('videos'));
    }

    public function create()
    {
        return view('admin.videos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'duration' => 'required|string',
        ]);

        $validated['duration_minutes'] = $this->parseDurationToMinutes($validated['duration']);

        Video::create($validated);

        return redirect()->route('admin.videos.index')
            ->with('success', 'Видео успешно добавлено');
    }

    public function edit(Video $video)
    {
        return view('admin.videos.edit', compact('video'));
    }

    public function update(Request $request, Video $video)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'duration' => 'required|string',
        ]);

        $validated['duration_minutes'] = $this->parseDurationToMinutes($validated['duration']);

        $video->update($validated);

        return redirect()->route('admin.videos.index')
            ->with('success', 'Видео успешно обновлено');
    }

    public function destroy(Video $video)
    {
        $video->delete();

        return redirect()->route('admin.videos.index')
            ->with('success', 'Видео успешно удалено');
    }

    private function parseDurationToMinutes(string $duration): int
    {
        $parts = array_map('intval', explode(':', $duration));
        
        if (count($parts) === 4) {
            return (int)$parts[0] * 24 * 60 + (int)$parts[1] * 60 + (int)$parts[2];
        } elseif (count($parts) === 3) {
            return (int)$parts[0] * 60 + (int)$parts[1];
        } elseif (count($parts) === 2) {
            return (int)$parts[0];
        }
        
        return 0;
    }
}
