<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Proxy extends Model
{
    protected $fillable = [
        'user_id',
        'line_raw',
        'protocol',
        'host',
        'port',
        'username',
        'password',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function parseProxyLine(string $line): array
    {
        $line = trim($line);
        
        // Format: host:port:user:pass
        if (substr_count($line, ':') === 3) {
            [$host, $port, $username, $password] = explode(':', $line, 4);
            return compact('host', 'port', 'username', 'password');
        }
        
        // Format: user:pass@host:port
        if (strpos($line, '@') !== false) {
            [$auth, $hostPort] = explode('@', $line, 2);
            [$username, $password] = explode(':', $auth, 2);
            [$host, $port] = explode(':', $hostPort, 2);
            return compact('host', 'port', 'username', 'password');
        }
        
        // Format: host:port
        if (substr_count($line, ':') === 1) {
            [$host, $port] = explode(':', $line, 2);
            return compact('host', 'port');
        }
        
        return [];
    }
}
