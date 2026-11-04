@extends('layouts.app')

@section('title', 'Категории')
@section('description', 'Все тематические категории новостного портала.')

@section('content')
    <div class="page-heading">
        <span class="eyebrow">Каталог</span>
        <h1 class="page-heading__title">Категории новостей</h1>
        <p class="page-heading__text">
            Выберите направление — от искусственного интеллекта до космических исследований.
        </p>
    </div>

    <div class="category-grid">
        @foreach ($categories as $category)
            @php
                $count = $category->texts_count;
                $word = match (true) {
                    $count % 10 === 1 && $count % 100 !== 11 => 'статья',
                    in_array($count % 10, [2, 3, 4], true) && ! in_array($count % 100, [12, 13, 14], true) => 'статьи',
                    default => 'статей',
                };
            @endphp

            <a class="category-card" href="{{ route('categories.show', $category) }}">
                <span class="category-card__name">{{ $category->name }}</span>
                <span class="category-card__count">{{ $count }} {{ $word }}</span>
                <span class="category-card__arrow" aria-hidden="true">→</span>
            </a>
        @endforeach
    </div>
@endsection
