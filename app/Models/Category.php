<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory; // Подключаем фабрики для тестов.

    protected $fillable = ['name', 'slug']; // Поля, которые можно заполнять.

    /**
     * Статьи этой категории.
     */
    public function texts(): HasMany
    {
        return $this->hasMany(Text::class); // У одной категории много статей.
    }

    /**
     * В адресе используем slug вместо числового id.
     */
    public function getRouteKeyName(): string
    {
        return 'slug'; // Например, /categories/space.
    }
}
