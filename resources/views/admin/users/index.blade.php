@extends('layouts.app')

@section('title', 'Пользователи — админка')

@section('content')
    <div class="page-heading">
        <span class="eyebrow">Доступ</span>
        <h1 class="page-heading__title">Пользователи и роли</h1>
        <p class="page-heading__text">
            Назначайте роли: читатель видит новости, журналист пишет статьи, администратор управляет сайтом.
        </p>
    </div>

    <div class="table-wrap">
        <table class="table table--users">
            <thead>
                <tr>
                    <th>Логин</th>
                    <th>Текущая роль</th>
                    <th>Дата регистрации</th>
                    <th class="table__actions">Сменить роль</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>
                            {{ $user->login }}
                            @if ($user->id === auth()->id())
                                <span class="badge badge--self">это вы</span>
                            @endif
                        </td>
                        <td><span class="badge badge--role">{{ $user->role?->label }}</span></td>
                        <td>{{ $user->created_at->format('d.m.Y') }}</td>
                        <td class="table__actions">
                            <form class="inline-form inline-form--wide" action="{{ route('admin.users.role', $user) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <select class="form__select form__select--small" name="role_id" aria-label="Роль пользователя {{ $user->login }}">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" @selected($user->role_id === $role->id)>
                                            {{ $role->label }}
                                        </option>
                                    @endforeach
                                </select>

                                <button class="btn btn--ghost btn--small" type="submit">Сохранить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $users->links('vendor.pagination.site') }}
@endsection
