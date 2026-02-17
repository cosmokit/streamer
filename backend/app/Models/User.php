<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_banned',
        'banned_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_banned' => 'boolean',
        ];
    }

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

    public function progressSteps()
    {
        return $this->hasMany(ProgressStep::class);
    }
}
