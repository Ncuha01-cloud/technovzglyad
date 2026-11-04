@extends('layouts.app')

@section('title', 'Статьи — админка')

@section('content')
    <div class="page-heading">
        <span class="eyebrow">Модерация</span>
        <h1 class="page-heading__title">Все статьи</h1>
        <p class="page-heading__text">Редактируйте, блокируйте и удаляйте публикации.</p>
    </div>

    <form class="filters" action="{{ route('admin.articles.index') }}" method="GET">
        <div class="filters__group filters__group--grow">
            <label class="form__label" for="q">Поиск</label>
            <input class="form__input" id="q" name="q" type="search" value="{{ $search }}" placeholder="Заголовок или текст">
        </div>

        <div class="filters__group">
            <label class="form__label" for="status">Статус</label>
            <select class="form__select" id="status" name="status">
                <option value="">Все</option>
                <option value="published" @selected($status === 'published')>Опубликованные</option>
                <option value="draft" @selected($status === 'draft')>Черновики</option>
                <option value="blocked" @selected($status === 'blocked')>Заблокированные</option>
            </select>
        </div>

        <div class="filters__actions">
            <button class="btn btn--primary" type="submit">Применить</button>
            <a class="btn btn--ghost" href="{{ route('admin.articles.index') }}">Сбросить</a>
        </div>
    </form>

    @if ($texts->count())
        <div class="table-wrap">
            <table class="table table--articles">
                <thead>
                    <tr>
                        <th>Заголовок</th>
                        <th>Категория</th>
                        <th>Автор</th>
                        <th>Статус</th>
                        <th>Дата</th>
                        <th class="table__actions">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($texts as $text)
                        <tr>
                            <td>
                                <a class="table__title" href="{{ route('articles.show', $text) }}">
                                    {{ $text->heading }}
                                </a>
                            </td>
                            <td>{{ $text->category->name }}</td>
                            <td>{{ $text->author->login }}</td>
                            <td>
                                @if ($text->is_blocked)
                                    <span class="badge badge--blocked">Заблокирована</span>
                                @elseif (! $text->is_published)
                                    <span class="badge badge--draft">Черновик</span>
                                @else
                                    <span class="badge badge--published">Опубликована</span>
                                @endif
                            </td>
                            <td>{{ $text->created_at->format('d.m.Y') }}</td>
                            <td class="table__actions">
                                <a class="btn btn--ghost btn--small" href="{{ route('admin.articles.edit', $text) }}">
                                    Изменить
                                </a>

                                <form class="inline-form" action="{{ route('admin.articles.block', $text) }}" method="POST">
                                    @csrf
                                    <button class="btn btn--ghost btn--small" type="submit">
                                        {{ $text->is_blocked ? 'Разблокировать' : 'Заблокировать' }}
                                    </button>
                                </form>

                                <form
                                    class="inline-form"
                                    action="{{ route('admin.articles.destroy', $text) }}"
                                    method="POST"
                                    onsubmit="return confirm('Удалить эту статью?')"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn--danger btn--small" type="submit">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $texts->links('vendor.pagination.site') }}
    @else
        <div class="empty-state">
            <span class="empty-state__code">0</span>
            <h2>Статьи не найдены</h2>
            <p>Измените параметры поиска или сбросьте фильтры.</p>
            <a class="btn btn--primary" href="{{ route('admin.articles.index') }}">Сбросить фильтры</a>
        </div>
    @endif
@endsection
