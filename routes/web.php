<?php

/*
|--------------------------------------------------------------------------
| Маршруты сайта (routes/web.php)
|--------------------------------------------------------------------------
| Публичные страницы, вход/регистрация, кабинет журналиста и админка.
| Изменено: убраны маршруты поиска и фильтрации статей.
| Доступ к закрытым разделам проверяют middleware auth и role.
*/

use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Journalist\ArticleController as JournalistArticleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Публичные разделы
|--------------------------------------------------------------------------
| Главная и категории доступны всем посетителям, включая гостей.
| Роли ограничивают только закрытые разделы «Журналист» и «Админ».
*/

Route::get('/', HomeController::class)->name('home'); // Главная лента статей.
Route::get('/articles/{text}', [ArticleController::class, 'show'])->name('articles.show'); // Полный текст статьи.
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index'); // Каталог категорий.
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show'); // Статьи категории.

/*
|--------------------------------------------------------------------------
| Авторизация и регистрация
|--------------------------------------------------------------------------
| Гостям доступны формы входа и регистрации, авторизованным — выход.
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login'); // Форма входа.
    Route::post('/login', [LoginController::class, 'store'])->name('login.store'); // Проверка логина и пароля.
    Route::get('/register', [RegisterController::class, 'create'])->name('register'); // Форма регистрации.
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store'); // Создание аккаунта.
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth') // Выйти может только авторизованный пользователь.
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Раздел «Журналист»
|--------------------------------------------------------------------------
| Доступен журналистам и администраторам (middleware role).
*/

Route::middleware(['auth', 'role:admin,journalist'])
    ->prefix('journalist')
    ->name('journalist.')
    ->group(function () {
        Route::get('/', [JournalistArticleController::class, 'index'])->name('index'); // Список статей.
        Route::get('/articles/create', [JournalistArticleController::class, 'create'])->name('articles.create'); // Новая статья.
        Route::post('/articles', [JournalistArticleController::class, 'store'])->name('articles.store'); // Сохранение.
        Route::get('/articles/{text}/edit', [JournalistArticleController::class, 'edit'])->name('articles.edit'); // Редактирование.
        Route::put('/articles/{text}', [JournalistArticleController::class, 'update'])->name('articles.update'); // Обновление.
        Route::delete('/articles/{text}', [JournalistArticleController::class, 'destroy'])->name('articles.destroy'); // Удаление.
    });

/*
|--------------------------------------------------------------------------
| Раздел «Админ»
|--------------------------------------------------------------------------
| Доступен только администраторам.
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', AdminDashboardController::class)->name('dashboard'); // Сводка.
        Route::get('/articles', [AdminArticleController::class, 'index'])->name('articles.index'); // Все статьи.
        Route::get('/articles/{text}/edit', [AdminArticleController::class, 'edit'])->name('articles.edit'); // Редактирование.
        Route::put('/articles/{text}', [AdminArticleController::class, 'update'])->name('articles.update'); // Сохранение.
        Route::delete('/articles/{text}', [AdminArticleController::class, 'destroy'])->name('articles.destroy'); // Удаление.
        Route::post('/articles/{text}/block', [AdminArticleController::class, 'toggleBlock'])->name('articles.block'); // Блокировка.
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index'); // Пользователи.
        Route::put('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.role'); // Смена роли.
    });
