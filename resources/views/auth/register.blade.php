{{-- Форма регистрации нового читателя. --}}
{{-- Изменено: только логин, пароль и подтверждение пароля. --}}
@extends('layouts.app') {{-- Используем общий каркас сайта. --}}

@section('title', 'Регистрация') {{-- Заголовок вкладки. --}}

@section('content')
    <h1>Регистрация</h1> {{-- Заголовок формы. --}}
    <p>Новый пользователь получает роль читателя.</p> {{-- Пояснение о роли. --}}

    <form class="form" action="{{ route('register.store') }}" method="POST"> {{-- Отправляем данные на проверку. --}}
        @csrf {{-- Защита формы от подделки. --}}

        <label for="login">Логин</label> {{-- Подпись поля. --}}
        <input id="login" name="login" type="text" value="{{ old('login') }}" required> {{-- Поле логина. --}}
        <p class="hint">От 3 до 50 символов: латиница, цифры и подчёркивание.</p> {{-- Подсказка. --}}
        @error('login') {{-- Проверяем ошибку поля. --}}
            <p class="error">{{ $message }}</p> {{-- Выводим текст ошибки. --}}
        @enderror

        <label for="password">Пароль</label> {{-- Подпись поля. --}}
        <input id="password" name="password" type="password" required> {{-- Поле пароля. --}}
        <p class="hint">Минимум 8 символов.</p> {{-- Подсказка. --}}
        @error('password') {{-- Проверяем ошибку поля. --}}
            <p class="error">{{ $message }}</p> {{-- Выводим текст ошибки. --}}
        @enderror

        <label for="password_confirmation">Подтверждение пароля</label> {{-- Подпись поля. --}}
        <input id="password_confirmation" name="password_confirmation" type="password" required> {{-- Повтор пароля. --}}

        <button class="btn btn-primary" type="submit">Создать аккаунт</button> {{-- Кнопка отправки формы. --}}
    </form>

    <p>Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a></p> {{-- Ссылка на вход. --}}
@endsection
