<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')

</head>
<body>

    <nav>
        <h1 class="text-3xl font-bold underline">
      Hello world!
    </h1>

        <hr>
    </nav>

    <div>
        @yield('content')
    </div>

    <footer>
        <hr>
        <p>© 2026 Sakuja</p>
    </footer>

</body>
</html>
