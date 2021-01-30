<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.includes.head')
</head>
<body>
<div class="d-flex flex-column min-vh-100">
    <header class="column">
        @include('layouts.includes.header')
        @include('layouts.includes.navbar')
    </header>

    <div class="flex-grow-1">
        <!-- main content -->
        <div id="content">
            @yield('content')
        </div>
    </div>

    <footer>
        @include('layouts.includes.footer')
    </footer>
</div>
</body>
</html>