<?php

/*
|--------------------------------------------------------------------------
| Миграция ролей (2026_11_04_000001_create_roles_table.php)
|--------------------------------------------------------------------------
| Создаёт справочник ролей: admin, journalist, user.
| Изменено: добавлено уникальное ограничение на имя роли.
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Создаёт справочник ролей.
     * Таблица является родительской для users: одна роль может быть у многих пользователей.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id(); // Первичный ключ роли.
            $table->string('name')->unique(); // Машинное имя роли (admin, journalist, user), уникальное.
            $table->timestamps(); // Поля created_at и updated_at.
        });
    }

    /**
     * Откатывает миграцию.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
