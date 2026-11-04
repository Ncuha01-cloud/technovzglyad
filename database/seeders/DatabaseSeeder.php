<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Запускает сидеры в правильном порядке: сначала справочники, затем пользователи и статьи.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class, // Роли.
            CategorySeeder::class, // Категории.
            UserSeeder::class, // Демо-пользователи.
            TextSeeder::class, // Демо-статьи.
        ]);
    }
}
