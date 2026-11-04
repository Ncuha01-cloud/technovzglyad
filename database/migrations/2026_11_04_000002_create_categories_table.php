<?php

/*
|--------------------------------------------------------------------------
| Миграция категорий (2026_11_04_000002_create_categories_table.php)
|--------------------------------------------------------------------------
| Создаёт справочник категорий статей.
| Изменено: добавлен уникальный slug для адресов вида /categories/space.
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Создаёт справочник категорий статей.
     * Таблица является родительской для texts: одна категория содержит много статей.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Первичный ключ категории.
            $table->string('name')->unique(); // Название категории, выводится на сайте.
            $table->string('slug')->unique(); // Человекопонятный идентификатор для URL.
            $table->timestamps(); // Поля created_at и updated_at.
        });
    }

    /**
     * Откатывает миграцию.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
