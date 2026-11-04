<?php

/*
|--------------------------------------------------------------------------
| Точка входа сайта (public/index.php)
|--------------------------------------------------------------------------
| Сюда попадают все HTTP-запросы. Файл подключает Composer-автозагрузку
| и передаёт запрос приложению Laravel.
*/

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Запоминаем время старта — нужно для замера скорости выполнения.
define('LARAVEL_START', microtime(true));

// Если сайт включил режим обслуживания, показываем заглушку и выходим.
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance; // Подключаем файл режима обслуживания.
}

// Подключаем автозагрузку классов Composer.
require __DIR__.'/../vendor/autoload.php';

// Создаём приложение Laravel из bootstrap/app.php.
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

// Передаём входящий запрос приложению и отправляем ответ браузеру.
$app->handleRequest(Request::capture());
