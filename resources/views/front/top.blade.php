<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('common.head')
</head>
<body>
    <div id="app">
        <header>
            <the-header-component />
        </header>
        <main>
            <router-view />
        </main>
        <!-- <footer>
            <footer-component />
        </footer>    -->
    </div>
</body>
</html>
