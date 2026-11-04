<?php

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
        return view('auth.login');
    }

    /**
     * Проверяет логин и пароль и авторизует пользователя.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'], // Логин обязателен.
            'password' => ['required', 'string'], // Пароль обязателен.
        ], [
            'login.required' => 'Введите логин.',
            'password.required' => 'Введите пароль.',
        ]);

        // Второй аргумент — галочка «запомнить меня».
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'login' => 'Неверный логин или пароль.',
            ]);
        }

        // Защита от фиксации сессии: создаём новый идентификатор после входа.
        $request->session()->regenerate();

        return redirect()
            ->intended(route('home'))
            ->with('success', 'Вы успешно вошли в аккаунт.');
    }

    /**
     * Выполняет выход и очищает сессию.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout(); // Убираем авторизацию.
        $request->session()->invalidate(); // Уничтожаем старые данные сессии.
        $request->session()->regenerateToken(); // Обновляем CSRF-токен.

        return redirect()
            ->route('home')
            ->with('success', 'Вы вышли из аккаунта.');
    }
}
