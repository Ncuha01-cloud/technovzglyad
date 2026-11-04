@extends('layouts.app')

@section('title', 'Главная')
@section('description', 'Свежие новости науки, технологий, космоса и медицины.')

@section('content')
    <section class="hero">
        <div class="hero__content">
            <span class="eyebrow">Наука • Технологии • Будущее</span>
            <h1 class="hero__title">Новости, которые объясняют завтрашний день</h1>
            <p class="hero__text">
                Разбираем научные открытия, медицинские разработки и технологии,
                которые меняют повседневную жизнь.
            </p>

            <div class="hero__actions">
                <a class="btn btn--primary" href="{{ route('categories.index') }}">Смотреть категории</a>

                @guest
                    <a class="btn btn--ghost" href="{{ route('register') }}">Создать аккаунт</a>
                @endguest

                @auth
                    @if (auth()->user()->hasAnyRole([\App\Models\Role::ADMIN, \App\Models\Role::JOURNALIST]))
                        <a class="btn btn--ghost" href="{{ route('journalist.articles.create') }}">Написать статью</a>
                    @endif
                @endauth
            </div>
        </div>

        <div class="hero__panel" aria-label="Статистика сайта">
            <div class="hero__stat">
                <span class="hero__stat-value">{{ $texts->total() }}</span>
                <span class="hero__stat-label">опубликованных материалов</span>
            </div>
            <div class="hero__stat">
                <span class="hero__stat-value">{{ $categoriesCount }}</span>
                <span class="hero__stat-label">тематических категорий</span>
            </div>
            <div class="hero__stat">
                <span class="hero__stat-value">3</span>
                <span class="hero__stat-label">роли пользователей</span>
            </div>
        </div>
    </section>

    <div class="section-heading">
        <div>
            <span class="eyebrow">Свежие материалы</span>
            <h2 class="section-heading__title">Последние новости</h2>
        </div>

        <a class="link-arrow" href="{{ route('categories.index') }}">Все категории →</a>
    </div>

    @if ($texts->count())
        <div class="card-grid">
            @foreach ($texts as $text)
                <x-site.article-card :text="$text" />
            @endforeach
        </div>

        {{ $texts->links('vendor.pagination.site') }}
    @else
        <div class="empty-state">
            <span class="empty-state__code">0</span>
            <h2>Пока нет опубликованных статей</h2>
            <p>Как только журналист опубликует материал, он появится в этой ленте.</p>

            @auth
                @if (auth()->user()->hasAnyRole([\App\Models\Role::ADMIN, \App\Models\Role::JOURNALIST]))
                    <a class="btn btn--primary" href="{{ route('journalist.articles.create') }}">Создать первую статью</a>
                @endif
            @endauth
        </div>
    @endif
@endsection
