<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['login', 'password', 'role_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Приведение типов: пароль автоматически хешируется при сохранении.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Роль пользователя (связь многие-к-одному).
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Статьи, написанные пользователем.
     */
    public function texts(): HasMany
    {
        return $this->hasMany(Text::class);
    }

    /**
     * Является ли пользователь администратором.
     */
    public function isAdmin(): bool
    {
        return $this->role?->name === Role::ADMIN;
    }

    /**
     * Является ли пользователь журналистом.
     */
    public function isJournalist(): bool
    {
        return $this->role?->name === Role::JOURNALIST;
    }

    /**
     * Является ли пользователь обычным читателем.
     */
    public function isReader(): bool
    {
        return $this->role?->name === Role::USER;
    }

    /**
     * Проверяет, входит ли роль пользователя в переданный список.
     *
     * @param  array<int, string>  $roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role?->name, $roles, true);
    }
}
