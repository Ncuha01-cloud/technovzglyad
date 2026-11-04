<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8"> {{-- Кодировка страницы. --}}
    <meta name="viewport" content="width=device-width, initial-scale=1"> {{-- Нормальное отображение на телефоне. --}}
    <title>@yield('title', 'Главная') — {{ config('app.name') }}</title> {{-- Заголовок вкладки браузера. --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> {{-- Подключаем отдельный файл стилей. --}}
</head>
<body>
    {{-- Шапка вынесена в отдельный компонент и подключается на каждой странице. --}}
    <x-site.header />

    <main class="container"> {{-- Основное содержимое страницы. --}}
        @if (session('success')) {{-- Проверяем сообщение об успехе. --}}
            <div class="alert alert-success">{{ session('success') }}</div> {{-- Выводим сообщение. --}}
        @endif

        @if (session('error')) {{-- Проверяем сообщение об ошибке. --}}
            <div class="alert alert-error">{{ session('error') }}</div> {{-- Выводим сообщение. --}}
        @endif

        @yield('content') {{-- Сюда вставляется содержимое конкретной страницы. --}}
    </main>

    {{-- Подвал вынесен в отдельный компонент и подключается на каждой странице. --}}
    <x-site.footer />
</body>
</html>
