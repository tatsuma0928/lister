<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('common.head')
</head>
<body>
    <div id="app">
        <header-component />
        <main>
            @yield('content')
        </main>
        <footer-component />
    </div>
</body>
</html>
