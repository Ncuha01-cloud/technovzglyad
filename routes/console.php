<?php

/*
|--------------------------------------------------------------------------
| Консольные команды (routes/console.php)
|--------------------------------------------------------------------------
| Здесь регистрируются собственные команды Artisan.
| Оставлена только стандартная демонстрационная команда inspire.
*/

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// Команда php artisan inspire выводит случайную мотивирующую цитату.
Artisan::command('inspire', function () {
    // Выводим цитату в консоль.
    $this->comment(Inspiring::quote());
})->purpose('Показать вдохновляющую цитату'); // Описание команды в списке artisan.
