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

        const date = document.getElementById('date');
        setInterval(function (){
            date.innerText = moment().format('dddd, Do MMM YYYY, LTS')
        }, 1000)

        if (screen.width < 768){
            window.addEventListener('click', close);
        }
        const bar = document.getElementById('bar');
        bar.addEventListener('click', function (){
            const menu = document.getElementById('menu');
            const bg = document.getElementById('bg');
            menu.classList.remove('w-0');
            menu.style.width = '70%';
            menu.style.transition = '0.5s';
            this.classList.remove('block')
            this.classList.add('hidden')
            bg.classList.remove('hidden');
            bg.classList.add('block');
        });
        function  close(e){
            const menu = document.getElementById('menu');
            const bar = document.getElementById('bar');
            const bg = document.getElementById('bg');
            if (!menu.contains(e.target) && !bar.contains(e.target)){
                // menu.style.display = 'none';
                menu.classList.remove('w-0');
                menu.classList.add('shadow-lg');

                menu.style.width = '0px';
                menu.style.transition = '0.5s';

                bar.classList.remove('hidden');
                bar.classList.add('block');
                bg.style.display = 'none';
            }else {
                menu.classList.remove('w-0');
                menu.style.width = '70%';
                menu.style.transition = '0.5s';
                bg.style.display = 'block';
            }
        }
    </script>
</html>
