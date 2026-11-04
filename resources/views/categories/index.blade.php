{{-- Страница со списком всех категорий. --}}
{{-- Изменено: вместо карточек — простой список ссылок с количеством статей. --}}
@extends('layouts.app') {{-- Используем общий каркас сайта. --}}

@section('title', 'Категории') {{-- Заголовок вкладки. --}}

@section('content')
    <h1>Категории</h1> {{-- Заголовок страницы. --}}

    <ul class="category-list"> {{-- Список категорий. --}}
        @foreach ($categories as $category) {{-- Перебираем все категории. --}}
            <li>
                <a href="{{ route('categories.show', $category) }}">{{ $category->name }}</a> {{-- Ссылка на категорию. --}}
                <span>({{ $category->texts_count }})</span> {{-- Количество статей в категории. --}}
            </li>
        @endforeach
    </ul>
@endsection
