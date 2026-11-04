<?php

/*
|--------------------------------------------------------------------------
| Вход и выход (app/Http/Controllers/Auth/LoginController.php)
|--------------------------------------------------------------------------
| Авторизация выполняется по уникальному логину и паролю.
| Изменено: вход по login вместо стандартного email.
*/

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Показывает форму входа.
     */
    public function create(): View
    {
        return view('auth.login'); // Открываем шаблон формы входа.
    }

    /**
     * Проверяет логин и пароль и авторизует пользователя.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([ // Проверяем поля формы.
            'login' => ['required', 'string'], // Логин обязателен.
            'password' => ['required', 'string'], // Пароль обязателен.
        ], [
            'login.required' => 'Введите логин.', // Русское сообщение об ошибке.
            'password.required' => 'Введите пароль.', // Русское сообщение об ошибке.
        ]);

        if (! Auth::attempt($credentials)) { // Пытаемся войти с этими данными.
            throw ValidationException::withMessages([ // Если не получилось...
                'login' => 'Неверный логин или пароль.', // ...показываем ошибку.
            ]);
        }

        $request->session()->regenerate(); // Обновляем сессию после входа.

        return redirect() // Переходим на главную страницу.
            ->intended(route('home'))
            ->with('success', 'Вы успешно вошли в аккаунт.'); // Показываем сообщение.
    }

    /**
     * Выполняет выход из аккаунта.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout(); // Убираем авторизацию.
        $request->session()->invalidate(); // Удаляем старые данные сессии.
        $request->session()->regenerateToken(); // Обновляем CSRF-токен.

        return redirect() // Возвращаемся на главную.
            ->route('home')
            ->with('success', 'Вы вышли из аккаунта.'); // Показываем сообщение.
    }
}
