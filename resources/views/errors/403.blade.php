@extends('layouts.app')

@section('title', 'Доступ запрещён')

@section('content')
    <div class="empty-state empty-state--error">
        <span class="empty-state__code">403</span>
        <h1>Доступ запрещён</h1>
        <p>У вашей роли нет прав для этого раздела. Вернитесь на главную или войдите под другой учётной записью.</p>
        <div class="button-row">
            <a class="btn btn--primary" href="{{ route('home') }}">На главную</a>
            <a class="btn btn--ghost" href="{{ route('login') }}">Сменить аккаунт</a>
        </div>
    </div>
@endsection
