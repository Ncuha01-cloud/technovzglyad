<?php

/*
|--------------------------------------------------------------------------
| Настройки почты (config/mail.php)
|--------------------------------------------------------------------------
| В учебном проекте письма не отправляются: MAIL_MAILER=log.
| Все сообщения просто попадают в лог, а не уходят реальным адресатам.
*/

return [

    // Почтовый транспорт по умолчанию (log — запись в лог).
    'default' => env('MAIL_MAILER', 'log'),

    // Список доступных транспортов.
    'mailers' => [

        // Обычная отправка через SMTP-сервер.
        'smtp' => [
            'transport' => 'smtp', // Тип транспорта.
            'scheme' => env('MAIL_SCHEME'), // Схема подключения.
            'url' => env('MAIL_URL'), // Готовый URL SMTP.
            'host' => env('MAIL_HOST', '127.0.0.1'), // Адрес SMTP-сервера.
            'port' => env('MAIL_PORT', 2525), // Порт.
            'username' => env('MAIL_USERNAME'), // Логин.
            'password' => env('MAIL_PASSWORD'), // Пароль.
            'timeout' => null, // Таймаут подключения.
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST)), // Домен отправителя.
        ],

        // Amazon SES.
        'ses' => [
            'transport' => 'ses', // Тип транспорта.
        ],

        // Postmark.
        'postmark' => [
            'transport' => 'postmark', // Тип транспорта.
            // 'message_stream_id' => env('POSTMARK_MESSAGE_STREAM_ID'), // Поток сообщений.
            // 'client' => [
            //     'timeout' => 5, // Таймаут клиента.
            // ],
        ],

        // Resend.
        'resend' => [
            'transport' => 'resend', // Тип транспорта.
        ],

        // Локальная программа sendmail.
        'sendmail' => [
            'transport' => 'sendmail', // Тип транспорта.
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'), // Путь к sendmail.
        ],

        // Запись писем в лог (используется в этом проекте).
        'log' => [
            'transport' => 'log', // Тип транспорта.
            'channel' => env('MAIL_LOG_CHANNEL'), // Канал лога.
        ],

        // Письма складываются в массив (удобно для тестов).
        'array' => [
            'transport' => 'array', // Тип транспорта.
        ],

        // Если основной транспорт не сработал — пробуем следующий.
        'failover' => [
            'transport' => 'failover', // Тип транспорта.
            'mailers' => [
                'smtp', // Сначала SMTP.
                'log', // Затем лог.
            ],
            'retry_after' => 60, // Повтор через 60 секунд.
        ],

        // По очереди распределяет письма между транспортами.
        'roundrobin' => [
            'transport' => 'roundrobin', // Тип транспорта.
            'mailers' => [
                'ses', // Первый транспорт.
                'postmark', // Второй транспорт.
            ],
            'retry_after' => 60, // Повтор через 60 секунд.
        ],

    ],

    // Адрес и имя отправителя по умолчанию.
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'), // Почтовый адрес.
        'name' => env('MAIL_FROM_NAME', env('APP_NAME', 'Laravel')), // Имя отправителя.
    ],

];
