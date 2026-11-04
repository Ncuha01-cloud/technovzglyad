<footer class="footer"> {{-- Подвал сайта. --}}
    <div class="container footer-inner"> {{-- Контейнер ограничивает ширину. --}}
        <p>© {{ date('Y') }} {{ config('app.name') }} — учебный проект на Laravel.</p> {{-- Текущий год и название. --}}
        <p> {{-- Вторая строка подвала. --}}
            <a href="{{ route('home') }}">Главная</a> {{-- Ссылка на главную. --}}
            ·
            <a href="{{ route('categories.index') }}">Категории</a> {{-- Ссылка на категории. --}}
        </p>
    </div>
</footer>
