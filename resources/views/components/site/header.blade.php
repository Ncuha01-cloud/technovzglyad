<header class="header"> {{-- Шапка сайта. --}}
    <div class="container header-inner"> {{-- Контейнер ограничивает ширину. --}}
        <a class="logo" href="{{ route('home') }}">{{ config('app.name') }}</a> {{-- Название сайта ведёт на главную. --}}

        <nav class="nav"> {{-- Главное меню. --}}
            <a href="{{ route('home') }}">Главная</a> {{-- Ссылка на главную страницу. --}}
            <a href="{{ route('categories.index') }}">Категории</a> {{-- Ссылка на список категорий. --}}

            @auth {{-- Меню для авторизованных пользователей. --}}
                @if (auth()->user()->hasAnyRole([\App\Models\Role::ADMIN, \App\Models\Role::JOURNALIST])) {{-- Журналист и админ. --}}
                    <a href="{{ route('journalist.index') }}">Журналист</a> {{-- Ссылка в кабинет журналиста. --}}
                @endif

                @if (auth()->user()->isAdmin()) {{-- Только для администратора. --}}
                    <a href="{{ route('admin.dashboard') }}">Админка</a> {{-- Ссылка в админ-панель. --}}
                @endif
            @endauth
        </nav>

        <div class="header-user"> {{-- Блок с данными пользователя. --}}
            @auth {{-- Если пользователь вошёл. --}}
                <span>{{ auth()->user()->login }} ({{ auth()->user()->role?->label }})</span> {{-- Логин и роль. --}}
                <form action="{{ route('logout') }}" method="POST"> {{-- Форма выхода. --}}
                    @csrf {{-- Защита формы от подделки. --}}
                    <button class="btn btn-small" type="submit">Выйти</button> {{-- Кнопка выхода. --}}
                </form>
            @else {{-- Если пользователь гость. --}}
                <a class="btn btn-small" href="{{ route('login') }}">Войти</a> {{-- Ссылка на вход. --}}
                <a class="btn btn-small btn-primary" href="{{ route('register') }}">Регистрация</a> {{-- Ссылка на регистрацию. --}}
            @endauth
        </div>
    </div>
</header>
