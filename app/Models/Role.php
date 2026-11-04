<?php

/*
|--------------------------------------------------------------------------
| Модель роли (app/Models/Role.php)
|--------------------------------------------------------------------------
| Роли: admin, journalist, user. У одной роли много пользователей.
| Изменено: вместо атрибута #[Fillable] используется обычное свойство
| $fillable — так понятнее студенту.
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory; // Подключаем фабрики для тестов.

    protected $fillable = ['name']; // Поля, которые можно заполнять.

    public const ADMIN = 'admin'; // Роль администратора.
    public const JOURNALIST = 'journalist'; // Роль журналиста.
    public const USER = 'user'; // Роль читателя.

    /**
     * Пользователи с этой ролью.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class); // У одной роли много пользователей.
    }

    /**
     * Русское название роли.
     */
    public function getLabelAttribute(): string
    {
        return match ($this->name) { // Выбираем название по коду роли.
            self::ADMIN => 'Администратор', // Для admin.
            self::JOURNALIST => 'Журналист', // Для journalist.
            self::USER => 'Читатель', // Для user.
            default => $this->name, // Если роль неизвестна.
        };
    }
}
