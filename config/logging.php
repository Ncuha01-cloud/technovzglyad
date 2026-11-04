<?php

/*
|--------------------------------------------------------------------------
| Настройки логирования (config/logging.php)
|--------------------------------------------------------------------------
| Laravel записывает ошибки и отладочные сообщения в файлы.
| По умолчанию используется канал stack, который пишет в single-файл.
*/

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Processor\PsrLogMessageProcessor;

return [

    // Канал логирования по умолчанию.
    'default' => env('LOG_CHANNEL', 'stack'),

    // Куда писать предупреждения об устаревших возможностях PHP.
    'deprecations' => [
        'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'), // Канал (null — отключено).
        'trace' => env('LOG_DEPRECATIONS_TRACE', false), // Добавлять стек вызовов.
    ],

    // Список каналов логирования.
    'channels' => [

        // Объединяет несколько каналов в один.
        'stack' => [
            'driver' => 'stack', // Драйвер «стек».
            'channels' => explode(',', (string) env('LOG_STACK', 'single')), // Список каналов через запятую.
            'ignore_exceptions' => false, // Не игнорировать ошибки каналов.
        ],

        // Один общий файл laravel.log.
        'single' => [
            'driver' => 'single', // Драйвер «один файл».
            'path' => storage_path('logs/laravel.log'), // Путь к файлу.
            'level' => env('LOG_LEVEL', 'debug'), // Минимальный уровень сообщений.
            'replace_placeholders' => true, // Подставлять значения в сообщения.
        ],

        // Новый файл на каждый день.
        'daily' => [
            'driver' => 'daily', // Драйвер «по дням».
            'path' => storage_path('logs/laravel.log'), // Базовый путь.
            'level' => env('LOG_LEVEL', 'debug'), // Минимальный уровень.
            'max_files' => env('LOG_DAILY_DAYS', 14), // Сколько дней хранить.
            'replace_placeholders' => true, // Подставлять значения.
        ],

        // Новый файл на каждый месяц.
        'monthly' => [
            'driver' => 'monthly', // Драйвер «по месяцам».
            'path' => storage_path('logs/laravel.log'), // Базовый путь.
            'level' => env('LOG_LEVEL', 'debug'), // Минимальный уровень.
            'max_files' => 3, // Сколько месяцев хранить.
            'replace_placeholders' => true, // Подставлять значения.
        ],

        // Отправка логов в Slack.
        'slack' => [
            'driver' => 'slack', // Драйвер Slack.
            'url' => env('LOG_SLACK_WEBHOOK_URL'), // Адрес вебхука.
            'username' => env('LOG_SLACK_USERNAME', env('APP_NAME', 'Laravel')), // Имя бота.
            'emoji' => env('LOG_SLACK_EMOJI', ':boom:'), // Эмодзи сообщения.
            'level' => env('LOG_LEVEL', 'critical'), // Минимальный уровень.
            'replace_placeholders' => true, // Подставлять значения.
        ],

        // Отправка логов в Papertrail.
        'papertrail' => [
            'driver' => 'monolog', // Используем Monolog напрямую.
            'level' => env('LOG_LEVEL', 'debug'), // Минимальный уровень.
            'handler' => env('LOG_PAPERTRAIL_HANDLER', SyslogUdpHandler::class), // Обработчик.
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'), // Адрес сервера.
                'port' => env('PAPERTRAIL_PORT'), // Порт.
                'connectionString' => 'tls://'.env('PAPERTRAIL_URL').':'.env('PAPERTRAIL_PORT'), // Строка подключения.
            ],
            'processors' => [PsrLogMessageProcessor::class], // Обработчик сообщений.
        ],

        // Вывод логов в стандартный поток ошибок.
        'stderr' => [
            'driver' => 'monolog', // Используем Monolog.
            'level' => env('LOG_LEVEL', 'debug'), // Минимальный уровень.
            'handler' => StreamHandler::class, // Потоковый обработчик.
            'handler_with' => [
                'stream' => 'php://stderr', // Поток ошибок.
            ],
            'formatter' => env('LOG_STDERR_FORMATTER'), // Формат сообщений.
            'processors' => [PsrLogMessageProcessor::class], // Обработчик сообщений.
        ],

        // Системный журнал операционной системы.
        'syslog' => [
            'driver' => 'syslog', // Драйвер syslog.
            'level' => env('LOG_LEVEL', 'debug'), // Минимальный уровень.
            'facility' => env('LOG_SYSLOG_FACILITY', LOG_USER), // Категория syslog.
            'replace_placeholders' => true, // Подставлять значения.
        ],

        // Журнал ошибок PHP.
        'errorlog' => [
            'driver' => 'errorlog', // Драйвер errorlog.
            'level' => env('LOG_LEVEL', 'debug'), // Минимальный уровень.
            'replace_placeholders' => true, // Подставлять значения.
        ],

        // Канал, который ничего не записывает.
        'null' => [
            'driver' => 'monolog', // Используем Monolog.
            'handler' => NullHandler::class, // Пустой обработчик.
        ],

        // Аварийный канал для критических ошибок.
        'emergency' => [
            'path' => storage_path('logs/laravel.log'), // Путь к файлу.
        ],

    ],

];
