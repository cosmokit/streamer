<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HelpArticle;

class HelpArticleController extends Controller
{
    public function index()
    {
        $articles = HelpArticle::orderBy('sort_order')->get();
        
        return response()->json([
            'data' => $articles,
        ]);
    }
}
