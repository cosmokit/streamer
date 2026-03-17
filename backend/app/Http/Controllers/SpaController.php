<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpaController extends Controller
{
    public function index()
    {
        $html = file_get_contents(public_path("app/index.html"));

        $csrfToken = csrf_token();
        $csrfMeta = "<meta name=\"csrf-token\" content=\"" . $csrfToken . "\">";

        $authScript = "";
        if (Auth::check()) {
            $authScript = "\n    <script>localStorage.setItem(\"isAuthenticated\",\"true\");</script>";
        }

        $html = str_replace(
            "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\" />",
            "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\" />\n    " . $csrfMeta . $authScript,
            $html
        );

        return response($html)->header("Content-Type", "text/html");
    }
}
