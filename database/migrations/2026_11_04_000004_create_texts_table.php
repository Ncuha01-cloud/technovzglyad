<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Создаёт таблицу статей.
     * Каждая статья принадлежит одной категории и одному автору.
     */
    public function up(): void
    {
        Schema::create('texts', function (Blueprint $table) {
            $table->id(); // Уникальный номер статьи.
            $table->string('heading'); // Заголовок статьи.
            $table->longText('text'); // Полный текст статьи.
            $table->foreignId('category_id') // Номер категории статьи.
                ->constrained('categories') // Ссылка на таблицу categories.
                ->restrictOnDelete(); // Категорию со статьями удалить нельзя.
            $table->foreignId('user_id') // Номер автора статьи.
                ->constrained('users') // Ссылка на таблицу users.
                ->cascadeOnDelete(); // Вместе с автором удаляются его статьи.
            $table->boolean('is_blocked')->default(false); // Признак блокировки администратором.
            $table->timestamps(); // Даты создания и обновления записи.

            $table->index(['is_blocked', 'created_at']); // Индекс ускоряет вывод ленты.
        });
    }

    /**
     * Удаляет таблицу статей при откате миграции.
     */
    public function down(): void
    {
        Schema::dropIfExists('texts'); // Удаляем таблицу texts.
    }
};
