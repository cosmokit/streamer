<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $nezhnaApiKey = Setting::get('nezhna_api_key', '');
        return view('admin.settings.index', compact('nezhnaApiKey'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nezhna_api_key' => 'nullable|string|max:500',
        ]);

        Setting::set('nezhna_api_key', $request->input('nezhna_api_key'));

        return back()->with('success', 'Настройки сохранены');
    }
}
