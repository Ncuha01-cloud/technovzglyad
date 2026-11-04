<?php

/*
|--------------------------------------------------------------------------
| Настройки базы данных (config/database.php)
|--------------------------------------------------------------------------
| Проект использует PostgreSQL, поэтому основное подключение — pgsql.
| Остальные подключения (sqlite, mysql, mariadb, sqlsrv) оставлены
| как стандартные примеры Laravel, но в проекте не используются.
| Изменено: подключение по умолчанию переведено с sqlite на pgsql.
*/

use Illuminate\Support\Str;
use Pdo\Mysql;

return [

    // Какое подключение использовать по умолчанию (pgsql для PostgreSQL).
    'default' => env('DB_CONNECTION', 'pgsql'),

    // Список всех доступных подключений к базам данных.
    'connections' => [

        // SQLite — файловая база, в этом проекте не используется.
        'sqlite' => [
            'driver' => 'sqlite', // Тип драйвера.
            'url' => env('DB_URL'), // Полный URL подключения (необязательно).
            'database' => env('DB_DATABASE', database_path('database.sqlite')), // Путь к файлу базы.
            'prefix' => '', // Префикс имён таблиц.
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true), // Проверять внешние ключи.
            'busy_timeout' => null, // Сколько ждать освобождения базы.
            'journal_mode' => null, // Режим журнала SQLite.
            'synchronous' => null, // Режим синхронизации записи.
            'transaction_mode' => 'DEFERRED', // Когда начинать транзакцию.
        ],

        // MySQL — стандартный пример, в проекте не используется.
        'mysql' => [
            'driver' => 'mysql', // Тип драйвера.
            'url' => env('DB_URL'), // URL подключения.
            'host' => env('DB_HOST', '127.0.0.1'), // Адрес сервера.
            'port' => env('DB_PORT', '3306'), // Порт MySQL.
            'database' => env('DB_DATABASE', 'laravel'), // Имя базы.
            'username' => env('DB_USERNAME', 'root'), // Пользователь.
            'password' => env('DB_PASSWORD', ''), // Пароль.
            'unix_socket' => env('DB_SOCKET', ''), // Unix-сокет вместо host:port.
            'charset' => env('DB_CHARSET', 'utf8mb4'), // Кодировка соединения.
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'), // Правила сравнения строк.
            'prefix' => '', // Префикс таблиц.
            'prefix_indexes' => true, // Учитывать префикс в индексах.
            'strict' => true, // Строгий режим SQL.
            'engine' => null, // Движок таблиц (по умолчанию InnoDB).
            'options' => extension_loaded('pdo_mysql') ? array_filter([ // Дополнительные опции PDO.
                Mysql::ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'), // Путь к SSL-сертификату.
            ]) : [],
        ],

        // MariaDB — стандартный пример, в проекте не используется.
        'mariadb' => [
            'driver' => 'mariadb', // Тип драйвера.
            'url' => env('DB_URL'), // URL подключения.
            'host' => env('DB_HOST', '127.0.0.1'), // Адрес сервера.
            'port' => env('DB_PORT', '3306'), // Порт MariaDB.
            'database' => env('DB_DATABASE', 'laravel'), // Имя базы.
            'username' => env('DB_USERNAME', 'root'), // Пользователь.
            'password' => env('DB_PASSWORD', ''), // Пароль.
            'unix_socket' => env('DB_SOCKET', ''), // Unix-сокет.
            'charset' => env('DB_CHARSET', 'utf8mb4'), // Кодировка.
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'), // Сравнение строк.
            'prefix' => '', // Префикс таблиц.
            'prefix_indexes' => true, // Учитывать префикс в индексах.
            'strict' => true, // Строгий режим SQL.
            'engine' => null, // Движок таблиц.
            'options' => extension_loaded('pdo_mysql') ? array_filter([ // Опции PDO.
                Mysql::ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'), // SSL-сертификат.
            ]) : [],
        ],

        // PostgreSQL — основное подключение этого проекта.
        'pgsql' => [
            'driver' => 'pgsql', // Тип драйвера PostgreSQL.
            'url' => env('DB_URL'), // URL подключения (можно не задавать).
            'host' => env('DB_HOST', '127.0.0.1'), // Адрес сервера PostgreSQL.
            'port' => env('DB_PORT', '5432'), // Стандартный порт PostgreSQL.
            'database' => env('DB_DATABASE', 'laravel'), // Имя базы (news_site).
            'username' => env('DB_USERNAME', 'root'), // Пользователь (postgres).
            'password' => env('DB_PASSWORD', ''), // Пароль (postgres).
            'charset' => env('DB_CHARSET', 'utf8'), // Кодировка соединения.
            'prefix' => '', // Префикс таблиц.
            'prefix_indexes' => true, // Учитывать префикс в индексах.
            'search_path' => 'public', // Схема PostgreSQL по умолчанию.
            'sslmode' => env('DB_SSLMODE', 'prefer'), // Режим SSL-соединения.
        ],

        // Microsoft SQL Server — стандартный пример, не используется.
        'sqlsrv' => [
            'driver' => 'sqlsrv', // Тип драйвера.
            'url' => env('DB_URL'), // URL подключения.
            'host' => env('DB_HOST', 'localhost'), // Адрес сервера.
            'port' => env('DB_PORT', '1433'), // Порт SQL Server.
            'database' => env('DB_DATABASE', 'laravel'), // Имя базы.
            'username' => env('DB_USERNAME', 'root'), // Пользователь.
            'password' => env('DB_PASSWORD', ''), // Пароль.
            'charset' => env('DB_CHARSET', 'utf8'), // Кодировка.
            'prefix' => '', // Префикс таблиц.
            'prefix_indexes' => true, // Учитывать префикс в индексах.
            // 'encrypt' => env('DB_ENCRYPT', 'yes'), // Шифрование (закомментировано).
            // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
        ],

    ],

    // Таблица, в которой Laravel хранит список выполненных миграций.
    'migrations' => [
        'table' => 'migrations', // Имя таблицы миграций.
        'update_date_on_publish' => true, // Обновлять дату при публикации миграции.
    ],

    // Настройки Redis. В этом учебном проекте Redis не используется.
    'redis' => [

        // PHP-клиент для работы с Redis.
        'client' => env('REDIS_CLIENT', 'phpredis'),

        // Общие параметры всех Redis-подключений.
        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'), // Режим кластера.
            'prefix' => env('REDIS_PREFIX', Str::slug((string) env('APP_NAME', 'laravel')).'-database-'), // Префикс ключей.
            'persistent' => env('REDIS_PERSISTENT', false), // Постоянное соединение.
        ],

        // Основная база Redis.
        'default' => [
            'url' => env('REDIS_URL'), // URL подключения.
            'host' => env('REDIS_HOST', '127.0.0.1'), // Адрес сервера.
            'username' => env('REDIS_USERNAME'), // Логин.
            'password' => env('REDIS_PASSWORD'), // Пароль.
            'port' => env('REDIS_PORT', '6379'), // Порт.
            'database' => env('REDIS_DB', '0'), // Номер базы.
            'max_retries' => env('REDIS_MAX_RETRIES', 3), // Число повторов.
            'backoff_algorithm' => env('REDIS_BACKOFF_ALGORITHM', 'decorrelated_jitter'), // Алгоритм паузы.
            'backoff_base' => env('REDIS_BACKOFF_BASE', 100), // Базовая пауза, мс.
            'backoff_cap' => env('REDIS_BACKOFF_CAP', 1000), // Максимальная пауза, мс.
        ],

        // Отдельная база Redis для кеша.
        'cache' => [
            'url' => env('REDIS_URL'), // URL подключения.
            'host' => env('REDIS_HOST', '127.0.0.1'), // Адрес сервера.
            'username' => env('REDIS_USERNAME'), // Логин.
            'password' => env('REDIS_PASSWORD'), // Пароль.
            'port' => env('REDIS_PORT', '6379'), // Порт.
            'database' => env('REDIS_CACHE_DB', '1'), // Номер базы для кеша.
            'max_retries' => env('REDIS_MAX_RETRIES', 3), // Число повторов.
            'backoff_algorithm' => env('REDIS_BACKOFF_ALGORITHM', 'decorrelated_jitter'), // Алгоритм паузы.
            'backoff_base' => env('REDIS_BACKOFF_BASE', 100), // Базовая пауза, мс.
            'backoff_cap' => env('REDIS_BACKOFF_CAP', 1000), // Максимальная пауза, мс.
        ],

    ],

];
