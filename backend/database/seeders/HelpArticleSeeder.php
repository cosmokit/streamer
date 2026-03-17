<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class HelpArticleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('help_articles')->insert([
            ['tag' => 'main', 'title' => 'Начало работы', 'body' => 'Добро пожаловать!', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tag' => 'step', 'title' => 'Шаг 1', 'body' => 'Инструкция по первому шагу.', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tag' => 'useful', 'title' => 'Советы', 'body' => 'Полезные советы по использованию.', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
