@extends('layouts.app') {{-- Используем общий каркас сайта. --}}

@section('title', 'Пользователи — админка') {{-- Заголовок вкладки. --}}

@section('content')
    <h1>Пользователи</h1> {{-- Заголовок страницы. --}}

    <div class="table-wrap"> {{-- Обёртка для прокрутки таблицы. --}}
        <table class="table"> {{-- Таблица пользователей. --}}
            <thead>
                <tr>
                    <th>Логин</th> {{-- Название столбца. --}}
                    <th>Роль</th> {{-- Название столбца. --}}
                    <th>Сменить роль</th> {{-- Название столбца. --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user) {{-- Перебираем пользователей. --}}
                    <tr>
                        <td>
                            {{ $user->login }} {{-- Логин пользователя. --}}
                            @if ($user->id === auth()->id()) {{-- Проверяем, это текущий админ. --}}
                                (это вы)
                            @endif
                        </td>
                        <td>{{ $user->role?->label }}</td> {{-- Текущая роль. --}}
                        <td>
                            <form class="inline" action="{{ route('admin.users.role', $user) }}" method="POST"> {{-- Форма смены роли. --}}
                                @csrf {{-- Защита формы. --}}
                                @method('PUT') {{-- Метод обновления. --}}

                                <select name="role_id"> {{-- Список ролей. --}}
                                    @foreach ($roles as $role) {{-- Перебираем роли. --}}
                                        <option value="{{ $role->id }}" @selected($user->role_id === $role->id)>{{ $role->label }}</option> {{-- Текущая роль выбрана. --}}
                                    @endforeach
                                </select>

                                <button class="btn btn-small" type="submit">Сохранить</button> {{-- Кнопка сохранения. --}}
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $users->links('vendor.pagination.site') }} {{-- Выводим кнопки страниц. --}}
@endsection
