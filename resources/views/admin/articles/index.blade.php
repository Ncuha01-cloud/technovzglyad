{{-- Список всех статей для администратора. --}}
{{-- Изменено: удалены поиск и фильтры, осталась таблица действий. --}}
@extends('layouts.app') {{-- Используем общий каркас сайта. --}}

@section('title', 'Все статьи — админка') {{-- Заголовок вкладки. --}}

@section('content')
    <h1>Все статьи</h1> {{-- Заголовок страницы. --}}

    @if ($texts->count()) {{-- Проверяем, есть ли статьи. --}}
        <div class="table-wrap"> {{-- Обёртка для прокрутки таблицы. --}}
            <table class="table"> {{-- Таблица статей. --}}
                <thead>
                    <tr>
                        <th>Заголовок</th> {{-- Название столбца. --}}
                        <th>Категория</th> {{-- Название столбца. --}}
                        <th>Автор</th> {{-- Название столбца. --}}
                        <th>Статус</th> {{-- Название столбца. --}}
                        <th>Действия</th> {{-- Название столбца. --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($texts as $text) {{-- Перебираем статьи. --}}
                        <tr>
                            <td><a href="{{ route('articles.show', $text) }}">{{ $text->heading }}</a></td> {{-- Ссылка на статью. --}}
                            <td>{{ $text->category->name }}</td> {{-- Категория. --}}
                            <td>{{ $text->author->login }}</td> {{-- Автор. --}}
                            <td>{{ $text->is_blocked ? 'Заблокирована' : 'Опубликована' }}</td> {{-- Статус. --}}
                            <td>
                                <a class="btn btn-small" href="{{ route('admin.articles.edit', $text) }}">Изменить</a> {{-- Кнопка редактирования. --}}

                                <form class="inline" action="{{ route('admin.articles.block', $text) }}" method="POST"> {{-- Форма блокировки. --}}
                                    @csrf {{-- Защита формы. --}}
                                    <button class="btn btn-small" type="submit">{{ $text->is_blocked ? 'Разблокировать' : 'Заблокировать' }}</button> {{-- Кнопка меняет текст. --}}
                                </form>

                                <form class="inline" action="{{ route('admin.articles.destroy', $text) }}" method="POST" onsubmit="return confirm('Удалить статью?')"> {{-- Форма удаления. --}}
                                    @csrf {{-- Защита формы. --}}
                                    @method('DELETE') {{-- Метод удаления. --}}
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
        <p>Статей пока нет.</p> {{-- Сообщение для пустого списка. --}}
    @endif
@endsection
