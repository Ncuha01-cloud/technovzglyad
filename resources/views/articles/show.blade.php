@extends('layouts.app')

@section('title', e($text->heading))
@section('description', e($text->excerpt))

@section('content')
    <nav class="breadcrumbs" aria-label="Хлебные крошки">
        <a href="{{ route('home') }}">Главная</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('categories.show', $text->category) }}">{{ $text->category->name }}</a>
    </nav>

    <article class="article">
        <header class="article__header">
            <a class="badge badge--category" href="{{ route('categories.show', $text->category) }}">
                {{ $text->category->name }}
            </a>

            <h1 class="article__title">{{ $text->heading }}</h1>

            <div class="article__meta">
                <span>Автор: <strong>{{ $text->author->login }}</strong></span>
                <time datetime="{{ $text->created_at->toIso8601String() }}">
                    {{ $text->created_at->translatedFormat('d M Y, H:i') }}
                </time>

                @if (! $text->is_published)
                    <span class="badge badge--draft">Черновик</span>
                @endif

                @if ($text->is_blocked)
                    <span class="badge badge--blocked">Заблокирована</span>
                @endif
            </div>
        </header>

        @if (! $text->isVisible())
            <div class="alert alert--warning" role="status">
                Статья скрыта от читателей. Вы видите её как автор или администратор.
            </div>
        @endif

        <div class="article__body">
            {!! nl2br(e($text->text)) !!}
        </div>

        <footer class="article__footer">
            <a class="btn btn--ghost" href="{{ route('home') }}">← На главную</a>
            <a class="btn btn--ghost" href="{{ route('categories.show', $text->category) }}">
                Ещё из «{{ $text->category->name }}»
            </a>
        </footer>
    </article>
@endsection
