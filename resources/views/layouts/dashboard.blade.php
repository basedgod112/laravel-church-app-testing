<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header>@include('components.navbar')</header>

    {{--Layout for home page and admin dashboard, each implementing some sections--}}
    <main>
        <h1>@yield('title')</h1>

        @hasSection('users')
            @yield('users')
        @endif

        @hasSection('bible')
            @yield('bible')
        @endif

        @hasSection('news')
            @yield('news')
        @endif

        @hasSection('program')
            @yield('program')
        @endif

        @hasSection('connect')
            @yield('connect')
        @endif

        @hasSection('resources')
            @yield('resources')
        @endif

        @hasSection('faq')
            @yield('faq')
        @endif

        @hasSection('contact')
            @yield('contact')
        @endif

        {{ $slot ?? null }}
    </main>

    @include('components.footer')
</body>
</html>
