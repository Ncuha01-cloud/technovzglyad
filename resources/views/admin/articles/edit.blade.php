@extends('layouts.app') {{-- Используем общий каркас сайта. --}}

@section('title', 'Редактирование статьи — админка') {{-- Заголовок вкладки. --}}

@section('content')
    <h1>Редактирование статьи</h1> {{-- Заголовок страницы. --}}
    <p>Автор: {{ $text->author->login }} · Категория: {{ $text->category->name }}</p> {{-- Информация о статье. --}}

    <form class="form" action="{{ route('admin.articles.update', $text) }}" method="POST"> {{-- Отправляем изменения. --}}
        @csrf {{-- Защита формы от подделки. --}}
        @method('PUT') {{-- Laravel поймёт метод PUT. --}}

        <label for="heading">Заголовок</label> {{-- Подпись поля. --}}
        <input id="heading" name="heading" type="text" value="{{ old('heading', $text->heading) }}" required> {{-- Поле заголовка. --}}
        @error('heading') {{-- Проверяем ошибку поля. --}}
            <p class="error">{{ $message }}</p> {{-- Выводим текст ошибки. --}}
        @enderror

        <label for="category_id">Категория</label> {{-- Подпись поля. --}}
        <select id="category_id" name="category_id" required> {{-- Выпадающий список. --}}
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

        <label class="checkbox"> {{-- Галочка блокировки. --}}
            <input type="hidden" name="is_blocked" value="0"> {{-- Значение, если галочка снята. --}}
            <input type="checkbox" name="is_blocked" value="1" @checked(old('is_blocked', $text->is_blocked))> {{-- Значение, если галочка стоит. --}}
            Заблокировать статью
        </label>

        <button class="btn btn-primary" type="submit">Сохранить</button> {{-- Кнопка сохранения. --}}
        <a class="btn" href="{{ route('admin.articles.index') }}">Отмена</a> {{-- Кнопка возврата. --}}
    </form>
@endsection
