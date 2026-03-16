<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\StreamRun;
use App\Models\Notification;
use App\Models\Video;
use App\Models\Template;
use App\Models\HelpArticle;
use App\Models\Proxy;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@streamer.local',
            'password' => Hash::make('admin12345'),
            'is_admin' => true,
        ]);

        // Create demo users
        $user1 = User::create([
            'name' => 'User 1',
            'email' => 'user1@streamer.local',
            'password' => Hash::make('user12345'),
            'telegram' => '@user1_stream',
            'twitch' => 'user1_twitch',
        ]);

        $user2 = User::create([
            'name' => 'User 2',
            'email' => 'user2@streamer.local',
            'password' => Hash::make('user12345'),
            'telegram' => '@user2_stream',
            'twitch' => 'user2_twitch',
        ]);

        // Create proxies for user1
        $proxyData = [
            ['host' => '45.138.74.100', 'port' => 8080, 'username' => 'proxynet', 'password' => 'secure_pass_123'],
            ['host' => '91.203.15.224', 'port' => 3128, 'username' => 'streamhub', 'password' => 'elite_key_456'],
            ['host' => '185.223.95.162', 'port' => 8888, 'username' => 'vipzone', 'password' => 'premium_789'],
            ['host' => '178.62.85.45', 'port' => 1080, 'username' => 'eliteproxy', 'password' => 'vip_access_101'],
            ['host' => '195.85.59.191', 'port' => 8000, 'username' => 'netstream', 'password' => 'stream_key_202'],
        ];

        foreach ($proxyData as $index => $proxy) {
            $lineRaw = "{$proxy['username']}:{$proxy['password']}@{$proxy['host']}:{$proxy['port']}";
            Proxy::create([
                'user_id' => $user1->id,
                'line_raw' => $lineRaw,
                'host' => $proxy['host'],
                'port' => $proxy['port'],
                'username' => $proxy['username'],
                'password' => $proxy['password'],
                'status' => $index < 2 ? 'active' : 'pending',
            ]);
        }

        // Create stream runs for user1 (day 3)
        for ($i = 1; $i <= 3; $i++) {
            StreamRun::create([
                'user_id' => $user1->id,
                'twitch_url' => 'https://www.twitch.tv/user1',
                'day_index' => $i,
                'created_at' => now()->subDays(4 - $i),
            ]);

            Notification::create([
                'user_id' => $user1->id,
                'message' => "Трансляция успешно запущена (День {$i})",
                'created_at' => now()->subDays(4 - $i),
            ]);
        }

        // Seed videos, templates, help articles
        $this->call([
            VideoSeeder::class,
            TemplateSeeder::class,
            HelpArticleSeeder::class,
            LearningStepSeeder::class,
        ]);
    }
}
