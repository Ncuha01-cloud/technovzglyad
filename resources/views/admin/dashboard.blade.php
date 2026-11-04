@extends('layouts.app')

@section('title', 'Админ-панель')

@section('content')
    <div class="page-heading page-heading--row">
        <div>
            <span class="eyebrow">Управление сайтом</span>
            <h1 class="page-heading__title">Админ-панель</h1>
            <p class="page-heading__text">Статистика, последние публикации и быстрые переходы.</p>
        </div>

        <div class="button-row">
            <a class="btn btn--ghost" href="{{ route('admin.articles.index') }}">Все статьи</a>
            <a class="btn btn--primary" href="{{ route('admin.users.index') }}">Пользователи</a>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-card__label">Пользователи</span>
            <strong class="stat-card__value">{{ $stats['users'] }}</strong>
        </div>
        <div class="stat-card">
            <span class="stat-card__label">Всего статей</span>
            <strong class="stat-card__value">{{ $stats['texts'] }}</strong>
        </div>
        <div class="stat-card">
            <span class="stat-card__label">Опубликовано</span>
            <strong class="stat-card__value stat-card__value--success">{{ $stats['published'] }}</strong>
        </div>
        <div class="stat-card">
            <span class="stat-card__label">Заблокировано</span>
            <strong class="stat-card__value stat-card__value--danger">{{ $stats['blocked'] }}</strong>
        </div>
    </div>

    <div class="admin-columns">
        <section class="panel">
            <div class="panel__header">
                <h2 class="panel__title">Последние статьи</h2>
                <a class="link-arrow" href="{{ route('admin.articles.index') }}">Все →</a>
            </div>

            <div class="table-wrap">
                <table class="table table--compact">
                    <thead>
                        <tr>
                            <th>Заголовок</th>
                            <th>Автор</th>
                            <th>Статус</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($latestTexts as $text)
                            <tr>
                                <td>
                                    <a class="table__title" href="{{ route('admin.articles.edit', $text) }}">
                                        {{ $text->heading }}
                                    </a>
                                </td>
                                <td>{{ $text->author->login }}</td>
                                <td>
                                    @if ($text->is_blocked)
                                        <span class="badge badge--blocked">Блок</span>
                                    @elseif (! $text->is_published)
                                        <span class="badge badge--draft">Черновик</span>
                                    @else
                                        <span class="badge badge--published">Опубликована</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section class="panel">
            <div class="panel__header">
                <h2 class="panel__title">Новые пользователи</h2>
                <a class="link-arrow" href="{{ route('admin.users.index') }}">Все →</a>
            </div>

            <div class="table-wrap">
                <table class="table table--compact">
                    <thead>
                        <tr>
                            <th>Логин</th>
                            <th>Роль</th>
                            <th>Дата</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($latestUsers as $user)
                            <tr>
                                <td>{{ $user->login }}</td>
                                <td><span class="badge badge--role">{{ $user->role?->label }}</span></td>
                                <td>{{ $user->created_at->format('d.m.Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
