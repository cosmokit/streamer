<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'telegram',
        'twitch',
        'is_admin',
        'is_banned',
        'banned_reason',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'is_banned' => 'boolean',
    ];

    public function proxies()
    {
        return $this->hasMany(Proxy::class);
    }

    public function streamRuns()
    {
        return $this->hasMany(StreamRun::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function socialLink()
    {
        return $this->hasOne(SocialLink::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }


    public function learningProgress()
    {
        return $this->hasMany(UserProgress::class);
    }
}
