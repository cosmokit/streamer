<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpArticle extends Model
{
    protected $fillable = [
        'tag',
        'title',
        'body',
        'sort_order',
    ];
}
