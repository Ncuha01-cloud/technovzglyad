<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Создаёт основную рабочую таблицу статей.
     * Каждая статья принадлежит одному автору и одной категории.
     */
    public function up(): void
    {
        Schema::create('texts', function (Blueprint $table) {
            $table->id(); // Первичный ключ статьи.
            $table->string('heading'); // Заголовок статьи.
            $table->longText('text'); // Полный текст статьи.
            $table->foreignId('category_id') // Внешний ключ на категорию.
                ->constrained('categories') // Ссылается на таблицу categories.
                ->restrictOnDelete(); // Категорию с статьями удалить нельзя.
            $table->foreignId('user_id') // Внешний ключ на автора.
                ->constrained('users') // Ссылается на таблицу users.
                ->cascadeOnDelete(); // При удалении автора его статьи удаляются.
            $table->boolean('is_published')->default(true); // Опубликована ли статья.
            $table->boolean('is_blocked')->default(false); // Заблокирована ли статья администратором.
            $table->timestamps(); // Поля created_at и updated_at.

            // Составной индекс ускоряет выборку ленты: фильтр по статусам + сортировка по дате.
            $table->index(['is_published', 'is_blocked', 'created_at']);
        });
    }

    /**
     * Откатывает миграцию.
     */
    public function down(): void
    {
        Schema::dropIfExists('texts');
    }
};
