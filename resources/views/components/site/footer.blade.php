<footer class="site-footer">
    <div class="container site-footer__grid">
        <div class="site-footer__brand">
            <a class="brand brand--footer" href="{{ route('home') }}">
                <span class="brand__mark" aria-hidden="true">ТВ</span>
                <span class="brand__text">
                    <span class="brand__name">{{ config('app.name') }}</span>
                    <span class="brand__tagline">Новости науки и технологий</span>
                </span>
            </a>

            <p class="site-footer__text">
                Учебный новостной портал на Laravel: роли, категории, статьи и админ-панель.
            </p>
        </div>

        <div>
            <h2 class="site-footer__title">Разделы</h2>
            <ul class="site-footer__list">
                <li><a href="{{ route('home') }}">Главная</a></li>
                <li><a href="{{ route('categories.index') }}">Категории</a></li>
                @auth
                    @if (auth()->user()->hasAnyRole([\App\Models\Role::ADMIN, \App\Models\Role::JOURNALIST]))
                        <li><a href="{{ route('journalist.index') }}">Кабинет журналиста</a></li>
                    @endif
                    @if (auth()->user()->isAdmin())
                        <li><a href="{{ route('admin.dashboard') }}">Админ-панель</a></li>
                    @endif
                @endauth
            </ul>
        </div>

        <div>
            <h2 class="site-footer__title">О проекте</h2>
            <p class="site-footer__text">
                Лабораторные работы 1–4: вёрстка, база данных, роли и middleware.
                Стек: Laravel 13, Blade, PostgreSQL и чистый CSS.
            </p>
        </div>
    </div>

    <div class="container site-footer__bottom">
        <span>© {{ date('Y') }} {{ config('app.name') }}</span>
        <span>Учебный проект • Laravel 13</span>
    </div>
</footer>
