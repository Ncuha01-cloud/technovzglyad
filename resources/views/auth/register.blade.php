@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
    <div class="auth-wrap">
        <div class="auth-card">
            <span class="eyebrow">Аккаунт</span>
            <h1 class="auth-card__title">Регистрация</h1>
            <p class="auth-card__lead">
                Создайте аккаунт читателя. Роль журналиста или администратора назначает администратор.
            </p>

            <form class="form" action="{{ route('register.store') }}" method="POST">
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
                        minlength="3"
                        maxlength="50"
                        pattern="[A-Za-z0-9_]+"
                        title="Только латинские буквы, цифры и подчёркивание"
                        autofocus
                        required
                    >
                    <p class="form__hint">От 3 до 50 символов: латиница, цифры и подчёркивание.</p>
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
                        autocomplete="new-password"
                        minlength="8"
                        required
                    >
                    <p class="form__hint">Минимум 8 символов.</p>
                    @error('password')
                        <p class="form__error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form__group">
                    <label class="form__label" for="password_confirmation">Подтверждение пароля</label>
                    <input
                        class="form__input"
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        autocomplete="new-password"
                        minlength="8"
                        required
                    >
                </div>

                <button class="btn btn--primary btn--full" type="submit">Создать аккаунт</button>
            </form>

            <p class="auth-card__footer">
                Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a>
            </p>
        </div>
    </div>
@endsection
