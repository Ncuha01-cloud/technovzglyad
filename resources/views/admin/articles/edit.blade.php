@extends('layouts.app')

@section('title', 'Редактирование статьи — админка')

@section('content')
    <div class="page-heading">
        <span class="eyebrow">Модерация</span>
        <h1 class="page-heading__title">Редактирование статьи</h1>
        <p class="page-heading__text">Измените текст или управляйте статусами публикации и блокировки.</p>
    </div>

    <div class="editor-layout">
        <form class="form card card--form" action="{{ route('admin.articles.update', $text) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form__group">
                <label class="form__label" for="heading">Заголовок</label>
                <input
                    class="form__input @error('heading') is-invalid @enderror"
                    id="heading"
                    name="heading"
                    type="text"
                    value="{{ old('heading', $text->heading) }}"
                    minlength="5"
                    maxlength="255"
                    required
                >
                @error('heading')
                    <p class="form__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form__group">
                <label class="form__label" for="category_id">Категория</label>
                <select class="form__select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((int) old('category_id', $text->category_id) === $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="form__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form__group">
                <label class="form__label" for="text">Текст статьи</label>
                <textarea
                    class="form__textarea @error('text') is-invalid @enderror"
                    id="text"
                    name="text"
                    rows="16"
                    minlength="80"
                    required
                >{{ old('text', $text->text) }}</textarea>
                @error('text')
                    <p class="form__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="checkbox-row">
                <label class="checkbox">
                    <input type="hidden" name="is_published" value="0">
                    <input
                        type="checkbox"
                        name="is_published"
                        value="1"
                        @checked(old('is_published', $text->is_published))
                    >
                    <span>Опубликована</span>
                </label>

                <label class="checkbox checkbox--danger">
                    <input type="hidden" name="is_blocked" value="0">
                    <input
                        type="checkbox"
                        name="is_blocked"
                        value="1"
                        @checked(old('is_blocked', $text->is_blocked))
                    >
                    <span>Заблокирована</span>
                </label>
            </div>

            @if ($text->is_blocked)
                <div class="alert alert--warning" role="status">
                    Статья сейчас заблокирована и скрыта от читателей.
                </div>
            @endif

            <div class="form__actions">
                <button class="btn btn--primary" type="submit">Сохранить изменения</button>
                <a class="btn btn--ghost" href="{{ route('admin.articles.index') }}">Отмена</a>
            </div>
        </form>

        <aside class="editor-help card">
            <h2 class="editor-help__title">Информация</h2>
            <ul class="editor-help__list">
                <li>Автор: <strong>{{ $text->author->login }}</strong></li>
                <li>Категория: <strong>{{ $text->category->name }}</strong></li>
                <li>Создана: {{ $text->created_at->format('d.m.Y H:i') }}</li>
                <li>Обновлена: {{ $text->updated_at->format('d.m.Y H:i') }}</li>
            </ul>

            <a class="btn btn--ghost btn--full" href="{{ route('articles.show', $text) }}">Открыть на сайте</a>
        </aside>
    </div>
@endsection
