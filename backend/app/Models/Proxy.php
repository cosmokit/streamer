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
        'status',
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
        
        if (empty($line) || str_starts_with($line, '#')) {
            return [];
        }
        
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
            return ['host' => $host, 'port' => $port, 'username' => null, 'password' => null];
        }
        
        return [];
    }

    public static function parseFromFile(string $content, string $format): array
    {
        $proxies = [];
        
        if ($format === 'json') {
            $data = json_decode($content, true);
            if (!is_array($data)) {
                return [];
            }
            
            foreach ($data as $item) {
                if (isset($item['ip']) && isset($item['port'])) {
                    $proxies[] = [
                        'host' => $item['ip'],
                        'port' => $item['port'],
                        'username' => $item['username'] ?? null,
                        'password' => $item['password'] ?? null,
                    ];
                }
            }
        } elseif ($format === 'csv') {
            $lines = explode("\n", $content);
            $header = null;
            
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;
                
                $cols = str_getcsv($line);
                
                if ($header === null) {
                    $header = array_map('strtolower', $cols);
                    continue;
                }
                
                $row = array_combine($header, $cols);
                if (isset($row['ip']) && isset($row['port'])) {
                    $proxies[] = [
                        'host' => $row['ip'],
                        'port' => $row['port'],
                        'username' => $row['username'] ?? null,
                        'password' => $row['password'] ?? null,
                    ];
                }
            }
        } else {
            $lines = explode("\n", $content);
            foreach ($lines as $line) {
                $parsed = self::parseProxyLine($line);
                if (!empty($parsed)) {
                    $proxies[] = $parsed;
                }
            }
        }
        
        return $proxies;
    }
}
