<?php

/*
|--------------------------------------------------------------------------
| Настройки сторонних сервисов (config/services.php)
|--------------------------------------------------------------------------
| Здесь хранятся ключи доступа к внешним сервисам.
| В учебном проекте внешние сервисы не подключены, поэтому значения пустые.
*/

return [

    // Сервис отправки почты Postmark.
    'postmark' => [
        'key' => env('POSTMARK_API_KEY'), // API-ключ Postmark.
    ],

    // Сервис отправки почты Resend.
    'resend' => [
        'key' => env('RESEND_API_KEY'), // API-ключ Resend.
    ],

    // Почтовый сервис Amazon SES.
    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'), // Ключ доступа AWS.
        'secret' => env('AWS_SECRET_ACCESS_KEY'), // Секретный ключ AWS.
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'), // Регион AWS.
    ],

    // Уведомления в Slack.
    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'), // Токен бота.
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'), // Канал по умолчанию.
        ],
    ],

];
