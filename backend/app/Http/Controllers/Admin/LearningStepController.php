<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LearningStep;
use Illuminate\Http\Request;

class LearningStepController extends Controller
{
    public function index()
    {
        $steps = LearningStep::orderBy('order')->paginate(20);
        return view('admin.learning-steps.index', compact('steps'));
    }

    public function create()
    {
        return view('admin.learning-steps.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'external_url' => 'required|url|max:500',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        LearningStep::create($validated);

        return redirect()->route('admin.learning-steps.index')->with('success', 'Шаг создан');
    }

    public function edit(LearningStep $learningStep)
    {
        return view('admin.learning-steps.edit', compact('learningStep'));
    }

    public function update(Request $request, LearningStep $learningStep)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'external_url' => 'required|url|max:500',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $learningStep->update($validated);

        return redirect()->route('admin.learning-steps.index')->with('success', 'Шаг обновлён');
    }

    public function destroy(LearningStep $learningStep)
    {
        $learningStep->delete();
        return redirect()->route('admin.learning-steps.index')->with('success', 'Шаг удалён');
    }
}
