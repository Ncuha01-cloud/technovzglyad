<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Показывает форму регистрации.
     */
    public function create(): View
    {
        return view('auth.register'); // Открываем шаблон регистрации.
    }

    /**
     * Создаёт читателя и сразу авторизует его.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([ // Проверяем данные формы.
            'login' => [
                'required', // Поле обязательно.
                'string', // Значение должно быть строкой.
                'min:3', // Минимум 3 символа.
                'max:50', // Максимум 50 символов.
                'regex:/^[A-Za-z0-9_]+$/', // Только латиница, цифры и подчёркивание.
                'unique:users,login', // Логин не должен повторяться.
            ],
            'password' => [
                'required', // Пароль обязателен.
                'string', // Значение должно быть строкой.
                'min:8', // Минимум 8 символов.
                'confirmed', // Пароль должен совпасть с подтверждением.
            ],
        ], [
            'login.required' => 'Придумайте логин.', // Русские сообщения об ошибках.
            'login.min' => 'Логин должен содержать минимум 3 символа.',
            'login.max' => 'Логин не должен превышать 50 символов.',
            'login.regex' => 'Логин может содержать только латинские буквы, цифры и подчёркивание.',
            'login.unique' => 'Такой логин уже занят.',
            'password.required' => 'Придумайте пароль.',
            'password.min' => 'Пароль должен содержать минимум 8 символов.',
            'password.confirmed' => 'Пароли не совпадают.',
        ]);

        $readerRole = Role::query()->firstOrCreate([ // Ищем роль читателя.
            'name' => Role::USER,
        ]);

        $user = User::query()->create([ // Создаём нового пользователя.
            'login' => $data['login'], // Логин из формы.
            'password' => $data['password'], // Пароль (модель User его хеширует).
            'role_id' => $readerRole->id, // Назначаем роль читателя.
        ]);

        Auth::login($user); // Сразу входим под новым пользователем.
        $request->session()->regenerate(); // Обновляем сессию.

        return redirect() // Переходим на главную.
            ->route('home')
            ->with('success', 'Регистрация прошла успешно. Добро пожаловать!'); // Показываем сообщение.
    }
}
