<?php

/*
|--------------------------------------------------------------------------
| Миграция таблиц очередей (0001_01_01_000002_create_jobs_table.php)
|--------------------------------------------------------------------------
| Стандартная миграция Laravel. Очереди в проекте не используются,
| но таблицы нужны, чтобы стандартные настройки QUEUE_CONNECTION=database
| не вызывали ошибок.
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Создаёт таблицы задач, пакетов и неудачных задач.
     */
    public function up(): void
    {
        // Очередь задач.
        Schema::create('jobs', function (Blueprint $table) {
            $table->id(); // Номер задачи.
            $table->string('queue')->index(); // Имя очереди.
            $table->longText('payload'); // Данные задачи.
            $table->unsignedSmallInteger('attempts'); // Сколько раз пытались выполнить.
            $table->unsignedInteger('reserved_at')->nullable(); // Когда задача взята в работу.
            $table->unsignedInteger('available_at'); // Когда задачу можно выполнять.
            $table->unsignedInteger('created_at'); // Когда задача создана.
        });

        // Пакеты задач (несколько задач как одна группа).
        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary(); // Идентификатор пакета.
            $table->string('name'); // Название пакета.
            $table->integer('total_jobs'); // Всего задач.
            $table->integer('pending_jobs'); // Ожидают выполнения.
            $table->integer('failed_jobs'); // Завершились ошибкой.
            $table->longText('failed_job_ids'); // Идентификаторы неудачных задач.
            $table->mediumText('options')->nullable(); // Дополнительные настройки.
            $table->integer('cancelled_at')->nullable(); // Когда пакет отменён.
            $table->integer('created_at'); // Когда пакет создан.
            $table->integer('finished_at')->nullable(); // Когда пакет завершён.
        });

        // Неудачно выполненные задачи.
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id(); // Номер записи.
            $table->string('uuid')->unique(); // Уникальный идентификатор задачи.
            $table->string('connection'); // Подключение очереди.
            $table->string('queue'); // Имя очереди.
            $table->longText('payload'); // Данные задачи.
            $table->longText('exception'); // Текст ошибки.
            $table->timestamp('failed_at')->useCurrent(); // Время ошибки.

            $table->index(['connection', 'queue', 'failed_at']); // Индекс для поиска ошибок.
        });
    }

    /**
     * Удаляет таблицы очередей при откате миграции.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs'); // Удаляем задачи.
        Schema::dropIfExists('job_batches'); // Удаляем пакеты.
        Schema::dropIfExists('failed_jobs'); // Удаляем неудачные задачи.
    }
};
