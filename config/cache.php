<?php

/*
|--------------------------------------------------------------------------
| Настройки кеша (config/cache.php)
|--------------------------------------------------------------------------
| Кеш ускоряет повторяющиеся вычисления и запросы.
| В учебном проекте используется драйвер database (таблица cache).
*/

use Illuminate\Support\Str;

return [

    // Хранилище кеша по умолчанию.
    'default' => env('CACHE_STORE', 'database'),

    // Список доступных хранилищ кеша.
    'stores' => [

        // Кеш в памяти — живёт только во время одного запроса.
        'array' => [
            'driver' => 'array', // Драйвер «массив».
            'serialize' => false, // Не сериализовать значения.
        ],

        // Кеш в таблице базы данных.
        'database' => [
            'driver' => 'database', // Драйвер «база данных».
            'connection' => env('DB_CACHE_CONNECTION'), // Подключение к базе.
            'table' => env('DB_CACHE_TABLE', 'cache'), // Таблица кеша.
            'lock_connection' => env('DB_CACHE_LOCK_CONNECTION'), // Подключение для блокировок.
            'lock_table' => env('DB_CACHE_LOCK_TABLE'), // Таблица блокировок.
        ],

        // Кеш в файлах на диске.
        'file' => [
            'driver' => 'file', // Драйвер «файлы».
            'path' => storage_path('framework/cache/data'), // Папка кеша.
            'lock_path' => storage_path('framework/cache/locks'), // Папка блокировок.
        ],

        // Кеш через файловую систему Laravel.
        'storage' => [
            'driver' => 'storage', // Драйвер «storage».
            'disk' => env('CACHE_STORAGE_DISK'), // Диск хранения.
            'path' => env('CACHE_STORAGE_PATH', 'framework/cache/data'), // Путь на диске.
        ],

        // Кеш в Memcached (в проекте не используется).
        'memcached' => [
            'driver' => 'memcached', // Драйвер Memcached.
            'persistent_id' => env('MEMCACHED_PERSISTENT_ID'), // Постоянный идентификатор.
            'sasl' => [
                env('MEMCACHED_USERNAME'), // Логин SASL.
                env('MEMCACHED_PASSWORD'), // Пароль SASL.
            ],
            'options' => [
                // Memcached::OPT_CONNECT_TIMEOUT => 2000, // Таймаут подключения.
            ],
            'servers' => [
                [
                    'host' => env('MEMCACHED_HOST', '127.0.0.1'), // Адрес сервера.
                    'port' => env('MEMCACHED_PORT', 11211), // Порт.
                    'weight' => 100, // Вес сервера.
                ],
            ],
        ],

        // Кеш в Redis (в проекте не используется).
        'redis' => [
            'driver' => 'redis', // Драйвер Redis.
            'connection' => env('REDIS_CACHE_CONNECTION', 'cache'), // Подключение для кеша.
            'lock_connection' => env('REDIS_CACHE_LOCK_CONNECTION', 'default'), // Подключение для блокировок.
        ],

        // Кеш в AWS DynamoDB (в проекте не используется).
        'dynamodb' => [
            'driver' => 'dynamodb', // Драйвер DynamoDB.
            'key' => env('AWS_ACCESS_KEY_ID'), // Ключ доступа AWS.
            'secret' => env('AWS_SECRET_ACCESS_KEY'), // Секретный ключ AWS.
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'), // Регион.
            'table' => env('DYNAMODB_CACHE_TABLE', 'cache'), // Таблица.
            'endpoint' => env('DYNAMODB_ENDPOINT'), // Адрес сервиса.
        ],

        // Кеш для Laravel Octane.
        'octane' => [
            'driver' => 'octane', // Драйвер Octane.
        ],

        // Резервный кеш: если основное хранилище недоступно, используется array.
        'failover' => [
            'driver' => 'failover', // Драйвер переключения.
            'stores' => [
                'database', // Основное хранилище.
                'array', // Резервное хранилище.
            ],
        ],

    ],

    // Префикс ключей кеша, чтобы не пересекаться с другими приложениями.
    'prefix' => env('CACHE_PREFIX', Str::slug((string) env('APP_NAME', 'laravel')).'-cache-'),

    // Запрет на восстановление PHP-классов из кеша (защита от атак).
    'serializable_classes' => false,

];
