@extends('layouts.app') {{-- Используем общий каркас сайта. --}}

@section('title', 'Вход') {{-- Заголовок вкладки. --}}

@section('content')
    <h1>Вход</h1> {{-- Заголовок формы. --}}

    <form class="form" action="{{ route('login.store') }}" method="POST"> {{-- Отправляем данные на проверку. --}}
        @csrf {{-- Защита формы от подделки. --}}

        <label for="login">Логин</label> {{-- Подпись поля. --}}
        <input id="login" name="login" type="text" value="{{ old('login') }}" required> {{-- Поле логина. --}}
        @error('login') {{-- Проверяем ошибку поля. --}}
            <p class="error">{{ $message }}</p> {{-- Выводим текст ошибки. --}}
        @enderror

        <label for="password">Пароль</label> {{-- Подпись поля. --}}
        <input id="password" name="password" type="password" required> {{-- Поле пароля. --}}
        @error('password') {{-- Проверяем ошибку поля. --}}
            <p class="error">{{ $message }}</p> {{-- Выводим текст ошибки. --}}
        @enderror

        <button class="btn btn-primary" type="submit">Войти</button> {{-- Кнопка отправки формы. --}}
    </form>

    <p>Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a></p> {{-- Ссылка на регистрацию. --}}
    <p class="hint">Демо-аккаунт: admin / admin12345</p> {{-- Подсказка для проверки. --}}
@endsection
