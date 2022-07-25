<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {!! SEOMeta::generate() !!}
        <link rel="stylesheet" href="{{ mix('css/app.css') }}"/>
        <script src="{{ mix('js/custom.js') }}" defer></script>
    </head>
    <body>
        @include('layout.navbar')
        <main>
            @yield('main')
        </main>
        <div id="bg" class="bg-gray-800 opacity-25 w-full h-full fixed left-0 top-0 z-10 hidden"></div>
    </body>
    <script type="text/javascript">


    </script>
</html>
