@extends('layouts.app')

@section('title', 'Вход')

@section('content')
    <div class="auth-wrap">
        <div class="auth-card">
            <span class="eyebrow">Аккаунт</span>
            <h1 class="auth-card__title">Вход на сайт</h1>
            <p class="auth-card__lead">Введите логин и пароль, чтобы продолжить работу.</p>

            <form class="form" action="{{ route('login.store') }}" method="POST">
                @csrf

                <div class="form__group">
                    <label class="form__label" for="login">Логин</label>
                    <input
                        class="form__input @error('login') is-invalid @enderror"
                        id="login"
                        name="login"
                        type="text"
                        value="{{ old('login') }}"
                        autocomplete="username"
                        autofocus
                        required
                    >
                    @error('login')
                        <p class="form__error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form__group">
                    <label class="form__label" for="password">Пароль</label>
                    <input
                        class="form__input @error('password') is-invalid @enderror"
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="current-password"
                        required
                    >
                    @error('password')
                        <p class="form__error">{{ $message }}</p>
                    @enderror
                </div>

                <label class="checkbox">
                    <input type="checkbox" name="remember" value="1">
                    <span>Запомнить меня</span>
                </label>

                <button class="btn btn--primary btn--full" type="submit">Войти</button>
            </form>

            <p class="auth-card__footer">
                Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a>
            </p>

            <div class="demo-accounts">
                <span class="demo-accounts__title">Демо-аккаунты</span>
                <code>admin / admin12345</code>
                <code>journalist / journalist123</code>
                <code>reader / reader12345</code>
            </div>
        </div>
    </div>
@endsection
