<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name'])]
class Role extends Model
{
    use HasFactory;

    public const ADMIN = 'admin'; // Константа для роли администратора.
    public const JOURNALIST = 'journalist'; // Константа для роли журналиста.
    public const USER = 'user'; // Константа для роли обычного читателя.

    /**
     * Пользователи, которым назначена эта роль.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Человекопонятное название роли для интерфейса.
     */
    public function getLabelAttribute(): string
    {
        return match ($this->name) {
            self::ADMIN => 'Администратор',
            self::JOURNALIST => 'Журналист',
            self::USER => 'Читатель',
            default => $this->name,
        };
    }
}
