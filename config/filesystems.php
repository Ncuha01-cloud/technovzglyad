<?php

/*
|--------------------------------------------------------------------------
| Настройки файловых систем (config/filesystems.php)
|--------------------------------------------------------------------------
| Определяет, куда сохранять загруженные файлы.
| В проекте загрузка файлов не используется, но стандартные диски оставлены.
*/

return [

    // Диск по умолчанию для работы с файлами.
    'default' => env('FILESYSTEM_DISK', 'local'),

    // Список доступных дисков.
    'disks' => [

        // Приватный локальный диск: файлы недоступны напрямую из браузера.
        'local' => [
            'driver' => 'local', // Локальный диск.
            'root' => storage_path('app/private'), // Папка хранения.
            'serve' => true, // Разрешить отдачу файлов через Laravel.
            'throw' => false, // Не выбрасывать исключение при ошибке.
            'report' => false, // Не писать ошибку в лог.
        ],

        // Публичный локальный диск: файлы доступны по ссылке /storage.
        'public' => [
            'driver' => 'local', // Локальный диск.
            'root' => storage_path('app/public'), // Папка хранения.
            'url' => rtrim(env('APP_URL', 'http://localhost'), '/').'/storage', // Публичный адрес.
            'visibility' => 'public', // Файлы видны всем.
            'throw' => false, // Не выбрасывать исключение.
            'report' => false, // Не писать в лог.
        ],

        // Облачное хранилище Amazon S3 (в проекте не используется).
        's3' => [
            'driver' => 's3', // Драйвер S3.
            'key' => env('AWS_ACCESS_KEY_ID'), // Ключ доступа.
            'secret' => env('AWS_SECRET_ACCESS_KEY'), // Секретный ключ.
            'region' => env('AWS_DEFAULT_REGION'), // Регион.
            'bucket' => env('AWS_BUCKET'), // Имя бакета.
            'url' => env('AWS_URL'), // Публичный URL.
            'endpoint' => env('AWS_ENDPOINT'), // Альтернативный адрес сервиса.
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false), // Стиль адреса.
            'throw' => false, // Не выбрасывать исключение.
            'report' => false, // Не писать в лог.
        ],

    ],

    // Символические ссылки, создаваемые командой storage:link.
    'links' => [
        public_path('storage') => storage_path('app/public'), // public/storage → storage/app/public.
    ],

];
