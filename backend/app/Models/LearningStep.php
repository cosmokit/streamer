<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LearningStep extends Model
{
    protected $fillable = [
        'title',
        'description',
        'external_url',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function userProgress(): HasMany
    {
        return $this->hasMany(UserProgress::class, 'learning_step_id');
    }
}
