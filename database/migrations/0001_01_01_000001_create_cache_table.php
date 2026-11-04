<?php

/*
|--------------------------------------------------------------------------
| Миграция служебных таблиц кеша (0001_01_01_000001_create_cache_table.php)
|--------------------------------------------------------------------------
| Стандартная миграция Laravel. В проекте кеш хранится в базе данных,
| поэтому таблицы cache и cache_locks нужны для работы CACHE_STORE=database.
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Создаёт таблицы для кеша и блокировок.
     */
    public function up(): void
    {
        // Таблица с парами «ключ — значение» и временем жизни.
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary(); // Ключ кеша — первичный ключ.
            $table->mediumText('value'); // Сериализованное значение.
            $table->bigInteger('expiration')->index(); // Время истечения (Unix timestamp).
        });

        // Таблица блокировок, чтобы задачи не выполнялись дважды.
        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary(); // Ключ блокировки.
            $table->string('owner'); // Владелец блокировки.
            $table->bigInteger('expiration')->index(); // Время истечения блокировки.
        });
    }

    /**
     * Удаляет таблицы кеша при откате миграции.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache'); // Удаляем таблицу кеша.
        Schema::dropIfExists('cache_locks'); // Удаляем таблицу блокировок.
    }
};
