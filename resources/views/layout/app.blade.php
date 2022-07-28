<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {!! SEOMeta::generate() !!}
        <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('images/apple-touch-icon-iphone-60x60.png') }}"/>
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('images/apple-touch-icon-ipad-76x76.png') }}"/>
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('images/apple-touch-icon-iphone-retina-120x120.png') }}"/>
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('images/apple-touch-icon-iphone-retina-152x152.png') }}"/>
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
        <script src="{{ mix('js/custom.js') }}" defer></script>
    </head>
    <body>
        @include('layout.navbar')
        <main>
            @yield('main')
        </main>
        <div id="bg" class="bg-gray-800 opacity-25 w-full fixed left-0 top-0 z-10 hidden" style="min-height: 100%"></div>
    @include('layout.footer')
    </body>
    </html>
