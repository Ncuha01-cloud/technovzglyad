<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Главная') — {{ config('app.name') }}</title>
    <meta name="description" content="@yield('description', 'Новости науки, технологий и будущего. Учебный проект на Laravel.')">

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <a class="skip-link" href="#main">Перейти к содержимому</a>

    <x-site.header />

    <main class="site-main" id="main">
        <div class="container">
            @if (session('success'))
                <div class="alert alert--success" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert--error" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <x-site.footer />
</body>
</html>
