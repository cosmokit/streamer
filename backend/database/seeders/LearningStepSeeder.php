<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LearningStep;

class LearningStepSeeder extends Seeder
{
    public function run(): void
    {
        $steps = [
            [
                'title' => 'Шаг 1: Регистрация и настройка профиля',
                'description' => 'Создайте аккаунт и заполните основную информацию о себе. Укажите свои социальные сети для лучшей связи.',
                'external_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Шаг 2: Изучение основ стриминга',
                'description' => 'Посмотрите обучающее видео о том, как начать стримить на Twitch. Узнайте про настройки OBS и качество трансляции.',
                'external_url' => 'https://www.youtube.com/watch?v=example-streaming-basics',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Шаг 3: Загрузка и настройка прокси',
                'description' => 'Научитесь загружать и активировать прокси-серверы для стабильной работы. Важный этап для продвинутых пользователей.',
                'external_url' => 'https://www.youtube.com/watch?v=example-proxy-setup',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Шаг 4: Первый тестовый стрим',
                'description' => 'Проведите свой первый тестовый стрим и получите обратную связь от куратора. Минимальная длительность — 30 минут.',
                'external_url' => 'https://www.youtube.com/watch?v=example-first-stream',
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($steps as $step) {
            LearningStep::create($step);
        }
    }
}
