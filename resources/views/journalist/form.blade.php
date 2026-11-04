{{-- Общая форма создания и редактирования статьи. --}}
{{-- Изменено: убран чекбокс публикации, остались заголовок, категория и текст. --}}
@extends('layouts.app') {{-- Используем общий каркас сайта. --}}

@section('title', $text->exists ? 'Редактирование статьи' : 'Новая статья') {{-- Заголовок вкладки. --}}

@section('content')
    <h1>{{ $text->exists ? 'Редактирование статьи' : 'Новая статья' }}</h1> {{-- Заголовок формы. --}}

    <form class="form" action="{{ $action }}" method="POST"> {{-- Отправляем статью на сохранение. --}}
        @csrf {{-- Защита формы от подделки. --}}
        @if ($method !== 'POST') {{-- Для редактирования нужен другой метод. --}}
            @method($method) {{-- Подставляем PUT. --}}
        @endif

        <label for="heading">Заголовок</label> {{-- Подпись поля. --}}
        <input id="heading" name="heading" type="text" value="{{ old('heading', $text->heading) }}" required> {{-- Поле заголовка. --}}
        @error('heading') {{-- Проверяем ошибку поля. --}}
            <p class="error">{{ $message }}</p> {{-- Выводим текст ошибки. --}}
        @enderror

        <label for="category_id">Категория</label> {{-- Подпись поля. --}}
        <select id="category_id" name="category_id" required> {{-- Выпадающий список категорий. --}}
            <option value="">Выберите категорию</option> {{-- Пустой вариант. --}}
            @foreach ($categories as $category) {{-- Перебираем категории. --}}
                <option value="{{ $category->id }}" @selected(old('category_id', $text->category_id) == $category->id)>{{ $category->name }}</option> {{-- Выбранная категория. --}}
            @endforeach
        </select>
        @error('category_id') {{-- Проверяем ошибку поля. --}}
            <p class="error">{{ $message }}</p> {{-- Выводим текст ошибки. --}}
        @enderror

        <label for="text">Текст статьи</label> {{-- Подпись поля. --}}
        <textarea id="text" name="text" rows="12" required>{{ old('text', $text->text) }}</textarea> {{-- Поле текста. --}}
        @error('text') {{-- Проверяем ошибку поля. --}}
            <p class="error">{{ $message }}</p> {{-- Выводим текст ошибки. --}}
        @enderror

        <button class="btn btn-primary" type="submit">Сохранить</button> {{-- Кнопка сохранения. --}}
        <a class="btn" href="{{ route('journalist.index') }}">Отмена</a> {{-- Кнопка возврата. --}}
    </form>
@endsection
