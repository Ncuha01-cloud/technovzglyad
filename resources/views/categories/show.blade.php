@extends('layouts.app')

@section('title', e($category->name))
@section('description', 'Статьи категории «'.$category->name.'».')

@section('content')
    <nav class="breadcrumbs" aria-label="Хлебные крошки">
        <a href="{{ route('home') }}">Главная</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('categories.index') }}">Категории</a>
        <span aria-hidden="true">/</span>
        <span>{{ $category->name }}</span>
    </nav>

    <div class="page-heading">
        <span class="eyebrow">Категория</span>
        <h1 class="page-heading__title">{{ $category->name }}</h1>
        <p class="page-heading__text">
            Всего материалов: {{ $texts->total() }}. Показаны только опубликованные статьи.
        </p>
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
            <h2>В этой категории пока пусто</h2>
            <p>Статьи появятся здесь после публикации журналистом.</p>
            <a class="btn btn--primary" href="{{ route('categories.index') }}">Другие категории</a>
        </div>
    @endif
@endsection
