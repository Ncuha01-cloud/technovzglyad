<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Кешируем хеш пароля, чтобы не хешировать его для каждого пользователя.
     */
    protected static ?string $password;

    /**
     * Значения по умолчанию для тестового пользователя.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'login' => fake()->unique()->userName(), // Уникальный логин.
            'password' => static::$password ??= Hash::make('password'), // Пароль по умолчанию.
            'role_id' => Role::query()->firstOrCreate(['name' => Role::USER])->id, // Роль читателя.
            'remember_token' => Str::random(10), // Случайный токен.
        ];
    }
}
