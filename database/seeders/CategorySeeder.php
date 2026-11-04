<?php

/*
|--------------------------------------------------------------------------
| Сидер категорий (database/seeders/CategorySeeder.php)
|--------------------------------------------------------------------------
| Создаёт пять тематических категорий сайта.
| Изменено: добавлены slug для красивых адресов.
*/

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Создаёт справочник категорий новостного сайта.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Технологии', 'slug' => 'technologies'],
            ['name' => 'Космос', 'slug' => 'space'],
            ['name' => 'Медицина', 'slug' => 'medicine'],
            ['name' => 'Наука', 'slug' => 'science'],
            ['name' => 'Искусственный интеллект', 'slug' => 'ai'],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(
                ['slug' => $category['slug']], // Ищем по slug.
                ['name' => $category['name']] // Обновляем название.
            );
        }
    }
}
