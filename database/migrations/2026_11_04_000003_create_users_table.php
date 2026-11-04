<?php

/*
|--------------------------------------------------------------------------
| Миграция пользователей (2026_11_04_000003_create_users_table.php)
|--------------------------------------------------------------------------
| Создаёт таблицы users и sessions.
| Изменено: вместо name/email — уникальный login, добавлен role_id.
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Создаёт таблицу пользователей и таблицу сессий.
     * Регистрация выполняется только по логину и паролю.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Первичный ключ пользователя.
            $table->string('login')->unique(); // Уникальный логин для входа.
            $table->string('password'); // Хеш пароля (plain-text никогда не хранится).
            $table->foreignId('role_id') // Внешний ключ на справочник ролей.
                ->constrained('roles') // Ссылается на таблицу roles.
                ->restrictOnDelete(); // Роль нельзя удалить, пока она назначена пользователю.
            $table->rememberToken(); // Токен для функции «запомнить меня».
            $table->timestamps(); // Поля created_at и updated_at.
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // Идентификатор сессии.
            $table->foreignId('user_id')->nullable()->index(); // Пользователь сессии (может быть гостем).
            $table->string('ip_address', 45)->nullable(); // IP-адрес клиента.
            $table->text('user_agent')->nullable(); // User-Agent браузера.
            $table->longText('payload'); // Сериализованные данные сессии.
            $table->integer('last_activity')->index(); // Время последней активности (Unix timestamp).
        });
    }

    /**
     * Откатывает миграцию.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions'); // Сначала зависимую таблицу сессий.
        Schema::dropIfExists('users'); // Затем пользователей.
    }
};
