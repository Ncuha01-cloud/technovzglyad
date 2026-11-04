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
        return view('auth.register');
    }

    /**
     * Создаёт пользователя с ролью «Читатель» и сразу авторизует его.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'login' => [
                'required', // Поле обязательно.
                'string', // Должно быть строкой.
                'min:3', // Минимум 3 символа.
                'max:50', // Максимум 50 символов.
                'regex:/^[A-Za-z0-9_]+$/', // Только латиница, цифры и подчёркивание.
                'unique:users,login', // Логин должен быть уникальным.
            ],
            'password' => [
                'required', // Пароль обязателен.
                'string', // Должен быть строкой.
                'min:8', // Минимум 8 символов.
                'confirmed', // Должен совпадать с password_confirmation.
            ],
        ], [
            'login.required' => 'Придумайте логин.',
            'login.min' => 'Логин должен содержать минимум 3 символа.',
            'login.max' => 'Логин не должен превышать 50 символов.',
            'login.regex' => 'Логин может содержать только латинские буквы, цифры и подчёркивание.',
            'login.unique' => 'Такой логин уже занят.',
            'password.required' => 'Придумайте пароль.',
            'password.min' => 'Пароль должен содержать минимум 8 символов.',
            'password.confirmed' => 'Пароли не совпадают.',
        ]);

        // Новые пользователи всегда получают роль обычного читателя.
        $readerRole = Role::query()->firstOrCreate(['name' => Role::USER]);

        $user = User::query()->create([
            'login' => $data['login'], // Логин из формы.
            'password' => $data['password'], // Пароль хешируется кастом модели User.
            'role_id' => $readerRole->id, // Назначаем роль «Читатель».
        ]);

        Auth::login($user); // Автоматический вход после регистрации.
        $request->session()->regenerate(); // Защита от фиксации сессии.

        return redirect()
            ->route('home')
            ->with('success', 'Регистрация прошла успешно. Добро пожаловать!');
    }
}
