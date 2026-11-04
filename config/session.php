<?php

/*
|--------------------------------------------------------------------------
| Настройки сессий (config/session.php)
|--------------------------------------------------------------------------
| Сессия хранит данные входа пользователя между запросами.
| Изменено: используется драйвер database и таблица sessions,
| которая создаётся миграцией create_users_table.
*/

use Illuminate\Support\Str;

return [

    // Где хранить сессии: file, cookie, database, redis и другие.
    'driver' => env('SESSION_DRIVER', 'database'),

    // Сколько минут сессия живёт без активности.
    'lifetime' => (int) env('SESSION_LIFETIME', 120),

    // Завершать сессию сразу после закрытия браузера.
    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    // Шифровать данные сессии перед сохранением.
    'encrypt' => env('SESSION_ENCRYPT', false),

    // Папка для файловых сессий (если драйвер file).
    'files' => storage_path('framework/sessions'),

    // Отдельное подключение к базе для сессий (если нужно).
    'connection' => env('SESSION_CONNECTION'),

    // Таблица для хранения сессий в базе данных.
    'table' => env('SESSION_TABLE', 'sessions'),

    // Хранилище кеша для сессий (для redis/memcached).
    'store' => env('SESSION_STORE'),

    // Вероятность автоматической очистки старых сессий: 2 из 100 запросов.
    'lottery' => [2, 100],

    // Имя cookie с идентификатором сессии.
    'cookie' => env('SESSION_COOKIE', Str::slug((string) env('APP_NAME', 'laravel')).'-session'),

    // Путь, для которого действует cookie.
    'path' => env('SESSION_PATH', '/'),

    // Домен cookie (null — текущий домен).
    'domain' => env('SESSION_DOMAIN'),

    // Отправлять cookie только по HTTPS.
    'secure' => env('SESSION_SECURE_COOKIE'),

    // Запретить доступ к cookie из JavaScript.
    'http_only' => env('SESSION_HTTP_ONLY', true),

    // Поведение cookie при переходах между сайтами (защита от CSRF).
    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    // Разделяемые cookie для встраивания сайта в iframe.
    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

    // Формат сериализации данных сессии: json или php.
    'serialization' => 'json',

];
