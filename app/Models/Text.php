<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Text extends Model
{
    use HasFactory; // Подключаем фабрики для тестов.

    protected $fillable = ['heading', 'text', 'category_id', 'user_id', 'is_blocked']; // Поля статьи.

    protected $casts = [
        'is_blocked' => 'boolean', // Флаг блокировки читаем как true/false.
    ];

    /**
     * Категория статьи.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class); // У статьи одна категория.
    }

    /**
     * Автор статьи.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id'); // Автор определяется по user_id.
    }

    /**
     * Оставляет только видимые статьи.
     */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_blocked', false); // Скрываем заблокированные статьи.
    }

    /**
     * Проверяет, видна ли статья читателю.
     */
    public function isVisible(): bool
    {
        return ! $this->is_blocked; // Видна, если не заблокирована.
    }

    /**
     * Короткий анонс для карточки.
     */
    public function getExcerptAttribute(): string
    {
        $plainText = strip_tags($this->text); // Убираем HTML-теги.

        return Str::limit($plainText, 150); // Оставляем 150 символов.
    }
}
