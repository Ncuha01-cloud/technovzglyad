{{-- Главная страница админ-панели. --}}
{{-- Изменено: статистика сокращена, добавлены простые таблицы последних записей. --}}
@extends('layouts.app') {{-- Используем общий каркас сайта. --}}

@section('title', 'Админ-панель') {{-- Заголовок вкладки. --}}

@section('content')
    <h1>Админ-панель</h1> {{-- Заголовок страницы. --}}

    <p> {{-- Быстрые ссылки. --}}
        <a class="btn" href="{{ route('admin.articles.index') }}">Статьи</a> {{-- Переход к статьям. --}}
        <a class="btn" href="{{ route('admin.users.index') }}">Пользователи</a> {{-- Переход к пользователям. --}}
    </p>

    <div class="stats"> {{-- Блок со статистикой. --}}
        <div class="stat"><strong>{{ $stats['users'] }}</strong><span>пользователей</span></div> {{-- Всего пользователей. --}}
        <div class="stat"><strong>{{ $stats['texts'] }}</strong><span>статей</span></div> {{-- Всего статей. --}}
        <div class="stat"><strong>{{ $stats['blocked'] }}</strong><span>заблокировано</span></div> {{-- Заблокированных статей. --}}
    </div>

    <h2>Последние статьи</h2> {{-- Подзаголовок. --}}
    <div class="table-wrap"> {{-- Обёртка для таблицы. --}}
        <table class="table"> {{-- Таблица последних статей. --}}
            <thead>
                <tr>
                    <th>Заголовок</th> {{-- Название столбца. --}}
                    <th>Автор</th> {{-- Название столбца. --}}
                    <th>Статус</th> {{-- Название столбца. --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($latestTexts as $text) {{-- Перебираем статьи. --}}
                    <tr>
                        <td><a href="{{ route('admin.articles.edit', $text) }}">{{ $text->heading }}</a></td> {{-- Ссылка на редактирование. --}}
                        <td>{{ $text->author->login }}</td> {{-- Логин автора. --}}
                        <td>{{ $text->is_blocked ? 'Заблокирована' : 'Опубликована' }}</td> {{-- Статус статьи. --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h2>Новые пользователи</h2> {{-- Подзаголовок. --}}
    <div class="table-wrap"> {{-- Обёртка для таблицы. --}}
        <table class="table"> {{-- Таблица пользователей. --}}
            <thead>
                <tr>
                    <th>Логин</th> {{-- Название столбца. --}}
                    <th>Роль</th> {{-- Название столбца. --}}
                    <th>Дата</th> {{-- Название столбца. --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($latestUsers as $user) {{-- Перебираем пользователей. --}}
                    <tr>
                        <td>{{ $user->login }}</td> {{-- Логин. --}}
                        <td>{{ $user->role?->label }}</td> {{-- Название роли. --}}
                        <td>{{ $user->created_at->format('d.m.Y') }}</td> {{-- Дата регистрации. --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
