<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Template;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::orderBy('id')->get();
        
        return response()->json([
            'data' => $templates,
        ]);
    }
}
