<header class="site-header">
    <div class="container site-header__inner">
        <a class="brand" href="{{ route('home') }}" aria-label="{{ config('app.name') }} — на главную">
            <span class="brand__mark" aria-hidden="true">ТВ</span>
            <span class="brand__text">
                <span class="brand__name">{{ config('app.name') }}</span>
                <span class="brand__tagline">Новости науки и технологий</span>
            </span>
        </a>

        <nav class="nav" aria-label="Основная навигация">
            <a class="nav__link {{ request()->routeIs('home') ? 'is-active' : '' }}" href="{{ route('home') }}">
                Главная
            </a>
            <a class="nav__link {{ request()->routeIs('categories.*') ? 'is-active' : '' }}" href="{{ route('categories.index') }}">
                Категории
            </a>

            @auth
                @if (auth()->user()->hasAnyRole([\App\Models\Role::ADMIN, \App\Models\Role::JOURNALIST]))
                    <a class="nav__link {{ request()->routeIs('journalist.*') ? 'is-active' : '' }}" href="{{ route('journalist.index') }}">
                        Журналист
                    </a>
                @endif

                @if (auth()->user()->isAdmin())
                    <a class="nav__link {{ request()->routeIs('admin.*') ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}">
                        Админка
                    </a>
                @endif
            @endauth
        </nav>

        <div class="header-actions">
            @auth
                <div class="user-chip">
                    <span class="user-chip__login">{{ auth()->user()->login }}</span>
                    <span class="badge badge--role">{{ auth()->user()->role?->label }}</span>
                </div>

                <form class="inline-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn--ghost btn--small" type="submit">Выйти</button>
                </form>
            @else
                <a class="btn btn--ghost btn--small" href="{{ route('login') }}">Войти</a>
                <a class="btn btn--primary btn--small" href="{{ route('register') }}">Регистрация</a>
            @endauth
        </div>
    </div>
</header>
