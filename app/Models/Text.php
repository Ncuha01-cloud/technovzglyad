<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable(['heading', 'text', 'category_id', 'user_id', 'is_published', 'is_blocked'])]
class Text extends Model
{
    use HasFactory;

    /**
     * Приводим флаги к булевому типу при чтении из базы.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'is_blocked' => 'boolean',
        ];
    }

    /**
     * Категория статьи (связь многие-к-одному).
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Автор статьи. Внешний ключ называется user_id, поэтому указываем его явно.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Локальная область запроса: только опубликованные и не заблокированные статьи.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->where('is_blocked', false);
    }

    /**
     * Видна ли статья обычным посетителям сайта.
     */
    public function isVisible(): bool
    {
        return $this->is_published && ! $this->is_blocked;
    }

    /**
     * Короткий анонс для карточки статьи.
     */
    public function getExcerptAttribute(): string
    {
        $plainText = preg_replace('/\s+/u', ' ', strip_tags($this->text));

        return Str::limit(trim($plainText), 180);
    }
}
