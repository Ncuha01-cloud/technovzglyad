<?php

/*
|--------------------------------------------------------------------------
| Сидер пользователей (database/seeders/UserSeeder.php)
|--------------------------------------------------------------------------
| Создаёт три демо-аккаунта для проверки ролей.
| Изменено: вход по логину, пароли хешируются моделью User.
*/

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Создаёт демонстрационных пользователей для каждой роли.
     */
    public function run(): void
    {
        $roles = Role::query()->pluck('id', 'name'); // Получаем id ролей по их именам.

        $users = [
            [
                'login' => 'admin',
                'password' => 'admin12345',
                'role' => Role::ADMIN,
            ],
            [
                'login' => 'journalist',
                'password' => 'journalist123',
                'role' => Role::JOURNALIST,
            ],
            [
                'login' => 'reader',
                'password' => 'reader12345',
                'role' => Role::USER,
            ],
        ];

        foreach ($users as $user) {
            User::query()->updateOrCreate(
                ['login' => $user['login']], // Логин — уникальный ключ.
                [
                    'password' => $user['password'], // Модель User хеширует пароль автоматически.
                    'role_id' => $roles[$user['role']], // Назначаем роль.
                ]
            );
        }
    }
}
