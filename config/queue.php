<?php

/*
|--------------------------------------------------------------------------
| Настройки очередей (config/queue.php)
|--------------------------------------------------------------------------
| Очереди позволяют выполнять долгие задачи в фоне.
| В учебном проекте фоновые задачи не используются, но стандартные
| настройки оставлены для совместимости с Laravel.
*/

return [

    // Подключение очереди по умолчанию.
    'default' => env('QUEUE_CONNECTION', 'database'),

    // Список доступных подключений очереди.
    'connections' => [

        // Выполнение задачи сразу, без очереди.
        'sync' => [
            'driver' => 'sync', // Синхронный драйвер.
        ],

        // Очередь в таблице базы данных.
        'database' => [
            'driver' => 'database', // Драйвер «база данных».
            'connection' => env('DB_QUEUE_CONNECTION'), // Подключение к базе.
            'table' => env('DB_QUEUE_TABLE', 'jobs'), // Таблица задач.
            'queue' => env('DB_QUEUE', 'default'), // Имя очереди.
            'retry_after' => (int) env('DB_QUEUE_RETRY_AFTER', 90), // Повтор через 90 секунд.
            'after_commit' => false, // Запускать после подтверждения транзакции.
        ],

        // Очередь Beanstalkd.
        'beanstalkd' => [
            'driver' => 'beanstalkd', // Драйвер Beanstalkd.
            'host' => env('BEANSTALKD_QUEUE_HOST', 'localhost'), // Адрес сервера.
            'queue' => env('BEANSTALKD_QUEUE', 'default'), // Имя очереди.
            'retry_after' => (int) env('BEANSTALKD_QUEUE_RETRY_AFTER', 90), // Повтор через 90 секунд.
            'block_for' => 0, // Сколько ждать задачу.
            'after_commit' => false, // Запускать после транзакции.
        ],

        // Очередь Amazon SQS.
        'sqs' => [
            'driver' => 'sqs', // Драйвер SQS.
            'key' => env('AWS_ACCESS_KEY_ID'), // Ключ доступа.
            'secret' => env('AWS_SECRET_ACCESS_KEY'), // Секретный ключ.
            'prefix' => env('SQS_PREFIX', 'https://sqs.us-east-1.amazonaws.com/your-account-id'), // Адрес очереди.
            'queue' => env('SQS_QUEUE', 'default'), // Имя очереди.
            'suffix' => env('SQS_SUFFIX'), // Суффикс имени.
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'), // Регион.
            'after_commit' => false, // Запускать после транзакции.
        ],

        // Очередь Redis.
        'redis' => [
            'driver' => 'redis', // Драйвер Redis.
            'connection' => env('REDIS_QUEUE_CONNECTION', 'default'), // Подключение Redis.
            'queue' => env('REDIS_QUEUE', 'default'), // Имя очереди.
            'retry_after' => (int) env('REDIS_QUEUE_RETRY_AFTER', 90), // Повтор через 90 секунд.
            'block_for' => null, // Сколько ждать задачу.
            'after_commit' => false, // Запускать после транзакции.
        ],

        // Отложенное выполнение.
        'deferred' => [
            'driver' => 'deferred', // Драйвер отложенных задач.
        ],

        // Выполнение в фоне.
        'background' => [
            'driver' => 'background', // Фоновый драйвер.
        ],

        // Резервная очередь при недоступности основной.
        'failover' => [
            'driver' => 'failover', // Драйвер переключения.
            'connections' => [
                'database', // Основная очередь.
                'deferred', // Резервная очередь.
            ],
        ],

    ],

    // Настройки пакетной обработки задач.
    'batching' => [
        'database' => env('DB_CONNECTION', 'sqlite'), // База для пакетов.
        'table' => 'job_batches', // Таблица пакетов.
    ],

    // Настройки неудачно выполненных задач.
    'failed' => [
        'driver' => env('QUEUE_FAILED_DRIVER', 'database-uuids'), // Куда сохранять ошибки.
        'database' => env('DB_CONNECTION', 'sqlite'), // База данных.
        'table' => 'failed_jobs', // Таблица неудачных задач.
    ],

];
