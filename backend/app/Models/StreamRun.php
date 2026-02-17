<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StreamRun extends Model
{
    protected $fillable = [
        'user_id',
        'twitch_url',
        'day_index',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
