@extends('layouts.app')

@section('title', 'Кабинет журналиста')

@section('content')
    <div class="page-heading page-heading--row">
        <div>
            <span class="eyebrow">Кабинет автора</span>
            <h1 class="page-heading__title">
                {{ auth()->user()->isAdmin() ? 'Все статьи' : 'Мои статьи' }}
            </h1>
            <p class="page-heading__text">
                Всего материалов: {{ $texts->total() }}. Черновики и заблокированные статьи не видны читателям.
            </p>
        </div>

        <a class="btn btn--primary" href="{{ route('journalist.articles.create') }}">+ Новая статья</a>
    </div>

    @if ($texts->count())
        <div class="table-wrap">
            <table class="table table--articles">
                <thead>
                    <tr>
                        <th>Заголовок</th>
                        <th>Категория</th>
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
                                <a class="btn btn--ghost btn--small" href="{{ route('journalist.articles.edit', $text) }}">
                                    Редактировать
                                </a>

                                <form
                                    class="inline-form"
                                    action="{{ route('journalist.articles.destroy', $text) }}"
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
            <h2>У вас пока нет статей</h2>
            <p>Создайте первый материал — он появится на главной и в выбранной категории.</p>
            <a class="btn btn--primary" href="{{ route('journalist.articles.create') }}">Написать статью</a>
        </div>
    @endif
@endsection
