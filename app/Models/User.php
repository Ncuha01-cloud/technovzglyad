<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable; // Фабрики и уведомления.

    protected $fillable = ['login', 'password', 'role_id']; // Поля, которые можно заполнять.

    protected $hidden = ['password', 'remember_token']; // Скрываем пароль при выводе.

    protected $casts = [
        'password' => 'hashed', // Пароль автоматически хешируется.
    ];

    /**
     * Роль пользователя.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class); // У пользователя одна роль.
    }

    /**
     * Статьи пользователя.
     */
    public function texts(): HasMany
    {
        return $this->hasMany(Text::class); // У пользователя много статей.
    }

    /**
     * Проверяет, является ли пользователь администратором.
     */
    public function isAdmin(): bool
    {
        return $this->role?->name === Role::ADMIN; // Сравниваем название роли.
    }

    /**
     * Проверяет, является ли пользователь журналистом.
     */
    public function isJournalist(): bool
    {
        return $this->role?->name === Role::JOURNALIST; // Сравниваем название роли.
    }

    /**
     * Проверяет, является ли пользователь читателем.
     */
    public function isReader(): bool
    {
        return $this->role?->name === Role::USER; // Сравниваем название роли.
    }

    /**
     * Проверяет, входит ли роль в список разрешённых.
     *
     * @param  array<int, string>  $roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role?->name, $roles, true); // Ищем роль в массиве.
    }
}
