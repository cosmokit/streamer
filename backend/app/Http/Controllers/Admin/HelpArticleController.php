<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HelpArticle;
use Illuminate\Http\Request;

class HelpArticleController extends Controller
{
    public function index()
    {
        $articles = HelpArticle::orderBy('sort_order')->paginate(20);
        return view('admin.help.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.help.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tag' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        HelpArticle::create($validated);

        return redirect()->route('admin.help.index')->with('success', 'Статья создана');
    }

    public function edit(HelpArticle $help)
    {
        return view('admin.help.edit', compact('help'));
    }

    public function update(Request $request, HelpArticle $help)
    {
        $validated = $request->validate([
            'tag' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        $help->update($validated);

        return redirect()->route('admin.help.index')->with('success', 'Статья обновлена');
    }

    public function destroy(HelpArticle $help)
    {
        $help->delete();
        return redirect()->route('admin.help.index')->with('success', 'Статья удалена');
    }
}
