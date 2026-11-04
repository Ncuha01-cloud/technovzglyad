@extends('layouts.app') {{-- Используем общий каркас сайта. --}}

@section('title', $text->heading) {{-- Заголовок статьи в названии вкладки. --}}

@section('content')
    <p><a href="{{ route('home') }}">← На главную</a></p> {{-- Ссылка назад на главную. --}}

    <article> {{-- Начало статьи. --}}
        <a class="category" href="{{ route('categories.show', $text->category) }}">{{ $text->category->name }}</a> {{-- Категория статьи. --}}
        <h1>{{ $text->heading }}</h1> {{-- Заголовок статьи. --}}
        <p class="meta">Автор: {{ $text->author->login }} · {{ $text->created_at->format('d.m.Y H:i') }}</p> {{-- Автор и дата. --}}

        @if ($text->is_blocked) {{-- Проверяем блокировку. --}}
            <div class="alert alert-error">Статья заблокирована администратором.</div> {{-- Предупреждение. --}}
        @endif

        <div class="article-text">{!! nl2br(e($text->text)) !!}</div> {{-- Текст статьи с переносами строк. --}}
    </article>
@endsection
