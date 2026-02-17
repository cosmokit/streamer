<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::latest()->paginate(20);
        return view('admin.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'download_url' => 'required|url',
        ]);

        Template::create($validated);

        return redirect()->route('admin.templates.index')->with('success', 'Шаблон создан');
    }

    public function edit(Template $template)
    {
        return view('admin.templates.edit', compact('template'));
    }

    public function update(Request $request, Template $template)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'download_url' => 'required|url',
        ]);

        $template->update($validated);

        return redirect()->route('admin.templates.index')->with('success', 'Шаблон обновлен');
    }

    public function destroy(Template $template)
    {
        $template->delete();
        return redirect()->route('admin.templates.index')->with('success', 'Шаблон удален');
    }
}
