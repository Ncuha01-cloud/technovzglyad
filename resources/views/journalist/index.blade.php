@extends('layouts.app') {{-- Используем общий каркас сайта. --}}

@section('title', 'Кабинет журналиста') {{-- Заголовок вкладки. --}}

@section('content')
    <div class="page-head"> {{-- Заголовок страницы и кнопка. --}}
        <h1>{{ auth()->user()->isAdmin() ? 'Все статьи' : 'Мои статьи' }}</h1> {{-- Админ видит все статьи. --}}
        <a class="btn btn-primary" href="{{ route('journalist.articles.create') }}">Новая статья</a> {{-- Кнопка создания. --}}
    </div>

    @if ($texts->count()) {{-- Проверяем, есть ли статьи. --}}
        <div class="table-wrap"> {{-- Обёртка для прокрутки таблицы. --}}
            <table class="table"> {{-- Таблица статей. --}}
                <thead>
                    <tr>
                        <th>Заголовок</th> {{-- Название столбца. --}}
                        <th>Категория</th> {{-- Название столбца. --}}
                        <th>Статус</th> {{-- Название столбца. --}}
                        <th>Действия</th> {{-- Название столбца. --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($texts as $text) {{-- Перебираем статьи. --}}
                        <tr>
                            <td><a href="{{ route('articles.show', $text) }}">{{ $text->heading }}</a></td> {{-- Ссылка на статью. --}}
                            <td>{{ $text->category->name }}</td> {{-- Категория статьи. --}}
                            <td>
                                @if ($text->is_blocked) {{-- Проверяем блокировку. --}}
                                    Заблокирована
                                @else
                                    Опубликована
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-small" href="{{ route('journalist.articles.edit', $text) }}">Изменить</a> {{-- Кнопка редактирования. --}}
                                <form class="inline" action="{{ route('journalist.articles.destroy', $text) }}" method="POST" onsubmit="return confirm('Удалить статью?')"> {{-- Форма удаления. --}}
                                    @csrf {{-- Защита формы. --}}
                                    @method('DELETE') {{-- Laravel поймёт метод DELETE. --}}
                                    <button class="btn btn-small btn-danger" type="submit">Удалить</button> {{-- Кнопка удаления. --}}
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $texts->links('vendor.pagination.site') }} {{-- Выводим кнопки страниц. --}}
    @else
        <p>У вас пока нет статей.</p> {{-- Сообщение для пустого списка. --}}
    @endif
@endsection
