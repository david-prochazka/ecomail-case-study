<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Ecomail | Case Study') }}</title>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css"
    >
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.colors.min.css"
    >
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="container">
        <nav>
            <ul>
                <li><strong>Ecomail Case Study</strong></li>
            </ul>
            <ul>
                <li><a href="{{ route('contacts.index') }}" class="contrast" {{ request()->routeIs('contacts.*') ? 'aria-current=page' : '' }}>Contacts</a></li>
                <li><a href="{{ route('import.form') }}" class="contrast" {{ request()->routeIs('import.*') ? 'aria-current=page' : '' }}>Import</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        @if (session('success'))
            <article class="pico-background-jade-500">
                {{ session('success') }}
            </article>
        @endif

        @yield('content')
    </main>
</body>
</html>
