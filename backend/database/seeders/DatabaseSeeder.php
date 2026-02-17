<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\StreamRun;
use App\Models\Notification;
use App\Models\Video;
use App\Models\Template;
use App\Models\HelpArticle;
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
        ]);

        $user2 = User::create([
            'name' => 'User 2',
            'email' => 'user2@streamer.local',
            'password' => Hash::make('user12345'),
        ]);

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

        // Create videos (70 total)
        $videoTitles = [
            'DYNASTY WARRIORS ORIGINS',
            'CLAIR OBSCUR EXPEDITION 33',
            'KINGDOM COME DELIVERANCE 2',
            'SILENT HILL F',
            'DELTARUNE CHAPTER 4',
            'MAFIA THE OLD COUNTRY',
            'LITTLE NIGHTMARES 3',
            'METAL GEAR SOLID DELTA SNAKE EATER',
            'SNIPER ELITE RESISTANCE',
            'ATOMFALL',
            'DYING LIGHT THE BEAST',
            'CIVILIZATION 7',
            'DOOM THE DARK AGES',
            'HOLLOW KNIGHT SILKSONG',
            'AI LIMIT',
            'LOST SOUL ASIDE',
            'BLOODBORNE',
            'ELDEN RING NIGHTREIGN',
            'RESIDENT EVIL 5',
            'BORDERLANDS 4',
        ];

        $durations = [
            65856, 49541, 222867, 31879, 13108, 31026, 10093, 33735,
            11361, 23625, 32149, 37762, 34038, 42274, 33186, 42151,
            29128, 43811, 29310, 46961
        ];

        for ($i = 0; $i < 70; $i++) {
            $titleIndex = $i % count($videoTitles);
            $durationIndex = $i % count($durations);
            
            Video::create([
                'user_id' => $user1->id,
                'title' => $videoTitles[$titleIndex] . ($i >= 20 ? ' #' . ($i - 19) : ''),
                'url' => "https://youtube.com/watch?v=example{$i}",
                'duration_seconds' => $durations[$durationIndex],
                'added_at' => now()->subDays(rand(30, 60)),
            ]);
        }

        // Create templates
        $templates = [
            ['category' => 'Gaming', 'name' => 'Boy&Girls', 'description' => 'Stylish gaming setup for mixed streamers'],
            ['category' => 'Gaming', 'name' => 'Wizard', 'description' => 'Mystical wizard themed overlay'],
            ['category' => 'Gaming', 'name' => 'Knight', 'description' => 'Medieval knight themed design'],
            ['category' => 'Gaming', 'name' => 'Girl', 'description' => 'Feminine and elegant streaming template'],
            ['category' => 'Gaming', 'name' => 'Gollum', 'description' => 'Dark and mysterious theme'],
            ['category' => 'Gaming', 'name' => 'Cowboy', 'description' => 'Wild west cowboy template'],
            ['category' => 'Gaming', 'name' => 'Panda', 'description' => 'Cute panda themed overlay'],
            ['category' => 'Gaming', 'name' => 'Wizard 2', 'description' => 'Enhanced wizard theme with effects'],
            ['category' => 'Gaming', 'name' => 'Mustang', 'description' => 'Racing and speed themed design'],
            ['category' => 'Gaming', 'name' => 'Robot', 'description' => 'Futuristic robot themed overlay'],
            ['category' => 'Gaming', 'name' => 'Mario', 'description' => 'Classic gaming character inspired'],
            ['category' => 'Gaming', 'name' => 'Crow', 'description' => 'Dark crow themed design'],
            ['category' => 'Gaming', 'name' => 'Cat', 'description' => 'Cute cat themed overlay'],
            ['category' => 'Gaming', 'name' => 'Sword', 'description' => 'Epic sword and shield theme'],
            ['category' => 'Gaming', 'name' => 'Knight with rose', 'description' => 'Romantic knight themed design'],
            ['category' => 'Gaming', 'name' => 'Harry P', 'description' => 'Magic school themed overlay'],
            ['category' => 'Gaming', 'name' => 'Son Jin', 'description' => 'Asian warrior themed design'],
            ['category' => 'Gaming', 'name' => 'Horror', 'description' => 'Spooky horror themed template'],
            ['category' => 'Gaming', 'name' => 'Gta', 'description' => 'Urban street style theme'],
        ];

        foreach ($templates as $template) {
            Template::create([
                'category' => $template['category'],
                'name' => $template['name'],
                'description' => $template['description'],
                'download_url' => '#',
            ]);
        }

        // Create help articles
        HelpArticle::create([
            'tag' => 'step',
            'title' => 'Шаг 1 - Ознакомление',
            'body' => 'Изучите краткое сведение, чтобы получить представление о предстоящей деятельности.',
            'sort_order' => 1,
        ]);

        HelpArticle::create([
            'tag' => 'step',
            'title' => 'Шаг 2 - Настройка и оформление канала Twitch',
            'body' => 'Инструкция как зарегистрировать канал на Twitch для новичков.',
            'sort_order' => 2,
        ]);

        HelpArticle::create([
            'tag' => 'step',
            'title' => 'Шаг 3 - Настройка OBS studio',
            'body' => 'Инструкция для новичков от скачивания игровых записей до настройки OBS.',
            'sort_order' => 3,
        ]);

        HelpArticle::create([
            'tag' => 'step',
            'title' => 'Шаг 4 - Ответы на вопросы',
            'body' => 'В случае возникновения затруднений или вопросов воспользуйтесь разделом «Помощь».',
            'sort_order' => 4,
        ]);

        HelpArticle::create([
            'tag' => 'useful',
            'title' => 'Полезная информация',
            'body' => 'Дополнительные материалы и рекомендации для успешного стриминга.',
            'sort_order' => 5,
        ]);

        HelpArticle::create([
            'tag' => 'main',
            'title' => 'Поддержка в Telegram',
            'body' => 'Свяжитесь с @vladis1av_esipenko для получения помощи.',
            'sort_order' => 6,
        ]);
    }
}
