<?php

/*
|--------------------------------------------------------------------------
| Запуск приложения (bootstrap/app.php)
|--------------------------------------------------------------------------
| Этот файл создаёт экземпляр Laravel и подключает маршруты, middleware
| и обработку исключений. Изменено: добавлен псевдоним middleware role,
| чтобы в маршрутах можно было писать ->middleware('role:admin').
*/

use App\Http\Middleware\EnsureUserHasRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

// Создаём приложение, указывая корневую папку проекта.
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php', // Подключаем веб-маршруты.
        commands: __DIR__.'/../routes/console.php', // Подключаем консольные команды.
        health: '/up', // Адрес проверки работоспособности сайта.
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Регистрируем короткое имя role для проверки ролей в маршрутах.
        $middleware->alias([
            'role' => EnsureUserHasRole::class, // role:admin,journalist.
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Если запрос ждёт JSON, ошибки тоже отдаём в формате JSON.
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create(); // Завершаем настройку и возвращаем приложение.
