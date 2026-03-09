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

        $videoData = [
            ['title' => 'DYNASTY WARRIORS ORIGINS', 'url' => 'https://www.youtube.com/watch?v=GoJsl7HmWnc', 'duration' => '18:17:36'],
            ['title' => 'CLAIR OBSCUR EXPEDITION 33', 'url' => 'https://www.youtube.com/watch?v=xaZrrh11WtE', 'duration' => '13:45:41'],
            ['title' => 'KINGDOM COME DELIVERANCE 2', 'url' => 'https://www.youtube.com/watch?v=Q2zGTrkapjw', 'duration' => '1:02:14:27'],
            ['title' => 'SILENT HILL F', 'url' => 'https://www.youtube.com/watch?v=7dYwYiivlu4', 'duration' => '08:51:19'],
            ['title' => 'DELTARUNE CHAPTER 4', 'url' => 'https://www.youtube.com/watch?v=eA-RBpfcGgQ', 'duration' => '3:38:28'],
            ['title' => 'MAFIA THE OLD COUNTRY', 'url' => 'https://www.youtube.com/watch?v=l9CkJVxwf_k', 'duration' => '8:37:06'],
            ['title' => 'LITTLE NIGHTMARES 3', 'url' => 'https://www.youtube.com/watch?v=WeP9hMJzuKA', 'duration' => '2:48:13'],
            ['title' => 'METAL GEAR SOLID DELTA SNAKE EATER', 'url' => 'https://www.youtube.com/watch?v=s9Wok1qAX0E', 'duration' => '9:22:15'],
            ['title' => 'SNIPER ELITE RESISTANCE', 'url' => 'https://www.youtube.com/watch?v=pEy-JVYof1c', 'duration' => '3:09:21'],
            ['title' => 'ATOMFALL', 'url' => 'https://www.youtube.com/watch?v=WR8j3nUiinA', 'duration' => '6:33:45'],
            ['title' => 'DYING LIGHT THE BEAST', 'url' => 'https://www.youtube.com/watch?v=u24z_TvT82A', 'duration' => '8:55:49'],
            ['title' => 'CIVILIZATION 7', 'url' => 'https://www.youtube.com/watch?v=nx4tzuK63VQ', 'duration' => '10:29:22'],
            ['title' => 'DOOM THE DARK AGES', 'url' => 'https://www.youtube.com/watch?v=glelvXmzzoY', 'duration' => '9:27:18'],
            ['title' => 'HOLLOW KNIGHT SILKSONG', 'url' => 'https://www.youtube.com/watch?v=xAv4bhSwK0g', 'duration' => '11:44:34'],
            ['title' => 'AI LIMIT', 'url' => 'https://www.youtube.com/watch?v=uPISl8qMZBk', 'duration' => '9:13:06'],
            ['title' => 'LOST SOUL ASIDE', 'url' => 'https://www.youtube.com/watch?v=eBTRAb8LJzM', 'duration' => '11:42:31'],
            ['title' => 'THE FIRST BERSERKER KHAZAN', 'url' => 'https://www.youtube.com/watch?v=gkLEqlzUkto', 'duration' => '16:54:32'],
            ['title' => 'BACK TO THE FUTURE', 'url' => 'https://www.youtube.com/watch?v=klXuO7D_bSc', 'duration' => '6:59:55'],
            ['title' => 'FAR CRY PRIMAL', 'url' => 'https://www.youtube.com/watch?v=O_lpHz_1_HA', 'duration' => '9:55:24'],
            ['title' => 'SPLIT FICTION', 'url' => 'https://www.youtube.com/watch?v=1122GLflOSo', 'duration' => '11:30:02'],
            ['title' => 'CASTLEVANIA', 'url' => 'https://www.youtube.com/watch?v=4ESoNzwiBvc', 'duration' => '5:11:33'],
            ['title' => 'WUCHANG FALLEN FEATHERS', 'url' => 'https://www.youtube.com/watch?v=W7FnwcWoE9s', 'duration' => '17:41:24'],
            ['title' => 'CRONOS THE NEW DAWN', 'url' => 'https://www.youtube.com/watch?v=K2r0HmMVjTs', 'duration' => '11:03:14'],
            ['title' => 'BORDERLANDS 4', 'url' => 'https://www.youtube.com/watch?v=T8SlS1r9Xag', 'duration' => '13:02:41'],
            ['title' => 'BERSERK', 'url' => 'https://www.youtube.com/watch?v=RBPOVlitdzs', 'duration' => '6:48:51'],
            ['title' => 'BLOODBORNE', 'url' => 'https://www.youtube.com/watch?v=zEHwAaJOWXI', 'duration' => '8:05:28'],
            ['title' => 'NINJA GAIDEN RAGEBOUND', 'url' => 'https://www.youtube.com/watch?v=Ql0c9GYS0dg', 'duration' => '3:43:59'],
            ['title' => 'DEMON SLAYER THE HINOKAMI CHRONICLES 2', 'url' => 'https://www.youtube.com/watch?v=uGPlvHOVpmA', 'duration' => '6:07:06'],
            ['title' => 'DONKEY KONG BANANZA', 'url' => 'https://www.youtube.com/watch?v=EXUFqZuJLj4', 'duration' => '7:11:11'],
            ['title' => 'THE ROGUE PRINCE OF PERSIA', 'url' => 'https://www.youtube.com/watch?v=KKG_CnxBvgU', 'duration' => '8:07:27'],
            ['title' => 'SHINOBI ART OF VENGEANCE', 'url' => 'https://www.youtube.com/watch?v=OxomGfE4Ax0', 'duration' => '6:13:31'],
            ['title' => 'AVOWED', 'url' => 'https://www.youtube.com/watch?v=pxVhXJXAQOg', 'duration' => '11:38:31'],
            ['title' => 'ZELDA BREATH OF THE WILD', 'url' => 'https://www.youtube.com/watch?v=Vf5qecc1Uw0', 'duration' => '13:29:25'],
            ['title' => 'ELDEN RING NIGHTREIGN', 'url' => 'https://www.youtube.com/watch?v=HqAVICf6QWI', 'duration' => '12:13:31'],
            ['title' => 'RESIDENT EVIL 5', 'url' => 'https://www.youtube.com/watch?v=aeoLKrOfJ58', 'duration' => '8:08:30'],
            ['title' => 'HELL IS US', 'url' => 'https://www.youtube.com/watch?v=QjuWyZtiYD8', 'duration' => '12:02:39'],
            ['title' => 'THE ELDER SCROLLS 3 MORROWIND', 'url' => 'https://www.youtube.com/watch?v=lVFyDmd9RsA', 'duration' => '10:12:24'],
            ['title' => 'ELDEN RING NIGHTREIGN', 'url' => 'https://www.youtube.com/watch?v=4OqgOdQvADA', 'duration' => '7:08:48'],
            ['title' => 'FATAL FURY CITY OF THE WOLVES', 'url' => 'https://www.youtube.com/watch?v=fSJtBGnxa2g', 'duration' => '9:55:47'],
            ['title' => 'TOMB RAIDER 4 THE LAST REVELATION REMASTERED', 'url' => 'https://www.youtube.com/watch?v=Z74e_b1LDwY', 'duration' => '8:18:46'],
            ['title' => 'NINJA GAIDEN 4', 'url' => 'https://www.youtube.com/watch?v=z3DHlXbQDiA', 'duration' => '8:17:24'],
            ['title' => 'FAIRY TAIL 2', 'url' => 'https://www.youtube.com/watch?v=S_RW-wya950', 'duration' => '12:49:16'],
            ['title' => 'SYSTEM SHOCK 2 REMASTER', 'url' => 'https://www.youtube.com/watch?v=XEK9ysGtThc', 'duration' => '7:55:13'],
            ['title' => "ASSASSIN'S CREED SYNDICATE", 'url' => 'https://www.youtube.com/watch?v=NLinFoxrmwY', 'duration' => '12:26:11'],
            ['title' => 'MAFIA 2', 'url' => 'https://www.youtube.com/watch?v=SEH8OwapgK4', 'duration' => '7:49:00'],
            ['title' => 'BENDY LONE WOLF', 'url' => 'https://www.youtube.com/watch?v=edWfEKTm9xs', 'duration' => '8:20:22'],
            ['title' => 'SHADOW OF ROME', 'url' => 'https://www.youtube.com/watch?v=a4AQFDDCvmg', 'duration' => '9:05:26'],
            ['title' => 'RAIDOU REMASTERED', 'url' => 'https://www.youtube.com/watch?v=lj78aOeUCX4', 'duration' => '12:19:06'],
            ['title' => 'MASS EFFECT', 'url' => 'https://www.youtube.com/watch?v=c9x1dIQTdYI', 'duration' => '8:27:03'],
            ['title' => 'MAFIA 3', 'url' => 'https://www.youtube.com/watch?v=maNmPOB_pQ4', 'duration' => '12:53:18'],
            ['title' => 'VAMPIRE THE MASQUERADE BLOODLINES 2', 'url' => 'https://www.youtube.com/watch?v=9xJxhWj9Z_4', 'duration' => '12:43:03'],
            ['title' => 'TORMENTED SOULS 2', 'url' => 'https://www.youtube.com/watch?v=wGoFjk7E8-k', 'duration' => '8:05:48'],
            ['title' => 'SILENT HILL F', 'url' => 'https://www.youtube.com/watch?v=-RHFIApW3Io', 'duration' => '8:41:54'],
            ['title' => 'Fast Food Simulator', 'url' => 'https://www.youtube.com/watch?v=NW9fLkLEjDA', 'duration' => '7:23:24'],
            ['title' => 'Resident Evil 4', 'url' => 'https://www.youtube.com/watch?v=utzWbncA8TU', 'duration' => '7:28:00'],
            ['title' => 'Dead Rising Deluxe Remaster', 'url' => 'https://www.youtube.com/watch?v=QNQLduNnETw', 'duration' => '7:21:47'],
            ['title' => 'RESIDENT EVIL HD Remaster Chris & Jill', 'url' => 'https://www.youtube.com/watch?v=saPMbm-UL-E', 'duration' => '8:12:39'],
            ['title' => 'Silent Hill 4: The Room', 'url' => 'https://www.youtube.com/watch?v=YNNfRI2YARM', 'duration' => '7:46:35'],
            ['title' => 'Beyond Hanwell', 'url' => 'https://www.youtube.com/watch?v=UW9INRoO7Ac', 'duration' => '7:35:34'],
            ['title' => 'HEAVY RAIN', 'url' => 'https://www.youtube.com/watch?v=vS8SKt5acas', 'duration' => '7:33:21'],
            ['title' => 'RESIDENT EVIL 4 Remake Professional Mode', 'url' => 'https://www.youtube.com/watch?v=IYaHTRlkJVY', 'duration' => '8:34:58'],
            ['title' => 'Dead Space Remake', 'url' => 'https://www.youtube.com/watch?v=qUl3wVEh2oU', 'duration' => '7:56:54'],
            ['title' => 'Resident Evil 8 Village', 'url' => 'https://www.youtube.com/watch?v=4tnq1ERXc5M', 'duration' => '7:29:16'],
            ['title' => 'FOBIA St. Dinfna Hotel', 'url' => 'https://www.youtube.com/watch?v=_tMPyXXw0rM', 'duration' => '6:13:21'],
            ['title' => 'Dying Light 2 : Part 2', 'url' => 'https://www.youtube.com/watch?v=lrH_3g-dEB8', 'duration' => '7:44:04'],
            ['title' => 'Fatal Frame 4/Project Zero 4:Mask of the Lunar Eclipse', 'url' => 'https://www.youtube.com/watch?v=dhyXmBzUZhA', 'duration' => '10:18:56'],
            ['title' => 'Alan Wake Remastered', 'url' => 'https://www.youtube.com/watch?v=Q2inULBRmKI', 'duration' => '9:09:55'],
            ['title' => 'Resident Evil 5', 'url' => 'https://www.youtube.com/watch?v=0TiOSVTufhw', 'duration' => '7:19:03'],
            ['title' => 'Resident Evil 5', 'url' => 'https://www.youtube.com/watch?v=GmDTT-wYqgE', 'duration' => '7:19:03'],
        ];

        $sevenDaysAgo = now()->subDays(7);
        
        foreach ($videoData as $video) {
            $parts = explode(':', $video['duration']);
            
            if (count($parts) === 4) {
                $minutes = (int)$parts[0] * 24 * 60 + (int)$parts[1] * 60 + (int)$parts[2];
            } elseif (count($parts) === 3) {
                $minutes = (int)$parts[0] * 60 + (int)$parts[1];
            } else {
                $minutes = (int)$parts[0];
            }

            Video::create([
                'title' => $video['title'],
                'url' => $video['url'],
                'duration' => $video['duration'],
                'duration_minutes' => $minutes,
                'created_at' => $sevenDaysAgo,
                'updated_at' => $sevenDaysAgo,
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
            'body' => 'Свяжитесь с технической поддержкой для получения помощи.',
            'sort_order' => 6,
        ]);

    }
}
