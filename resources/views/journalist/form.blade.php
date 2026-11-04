@extends('layouts.app')

@section('title', $text->exists ? 'Редактирование статьи' : 'Новая статья')

@section('content')
    <div class="page-heading">
        <span class="eyebrow">Редактор</span>
        <h1 class="page-heading__title">
            {{ $text->exists ? 'Редактирование статьи' : 'Новая статья' }}
        </h1>
        <p class="page-heading__text">
            Заполните заголовок, выберите категорию и напишите текст. После сохранения статья появится на сайте.
        </p>
    </div>

    <div class="editor-layout">
        <form class="form card card--form" action="{{ $action }}" method="POST">
            @csrf
            @if ($method !== 'POST')
                @method($method)
            @endif

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
                    <option value="">Выберите категорию</option>
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
                    rows="14"
                    minlength="80"
                    required
                >{{ old('text', $text->text) }}</textarea>
                <p class="form__hint">Минимум 80 символов. Абзацы разделяйте пустой строкой.</p>
                @error('text')
                    <p class="form__error">{{ $message }}</p>
                @enderror
            </div>

            <label class="checkbox">
                <input type="hidden" name="is_published" value="0">
                <input
                    type="checkbox"
                    name="is_published"
                    value="1"
                    @checked(old('is_published', $text->exists ? $text->is_published : true))
                >
                <span>Опубликовать сразу</span>
            </label>

            @if ($text->exists && $text->is_blocked)
                <div class="alert alert--warning" role="status">
                    Статья заблокирована администратором. Вы можете исправить текст, но снять блокировку может только администратор.
                </div>
            @endif

            <div class="form__actions">
                <button class="btn btn--primary" type="submit">
                    {{ $text->exists ? 'Сохранить изменения' : 'Сохранить статью' }}
                </button>
                <a class="btn btn--ghost" href="{{ route('journalist.index') }}">Отмена</a>
            </div>
        </form>

        <aside class="editor-help card">
            <h2 class="editor-help__title">Подсказки</h2>
            <ul class="editor-help__list">
                <li>Заголовок должен быть коротким и понятным.</li>
                <li>Выбирайте категорию, к которой относится материал.</li>
                <li>Черновик можно сохранить и опубликовать позже.</li>
                <li>Заблокированную статью видит только автор и администратор.</li>
            </ul>
        </aside>
    </div>
@endsection
