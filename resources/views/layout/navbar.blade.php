<?php
   $categories = \App\Models\Category::select('id', 'category_name', 'slug')->get();
?>
<header class="relative">
    <div class="top-nev border-b">
        <div class="lg:container lg:mx-auto lg:px-8 px-4">
            <div class="lg:flex lg:items-center lg:gap-5 h-[145px] lg:h-[60px]">
                <div class="flex gap-1 items-center lg:py-4 pt-4 w-full lg:w-[370px]">
                    <svg class="w-[15px]" fill="#15803D" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M160 32V64H288V32C288 14.33 302.3 0 320 0C337.7 0 352 14.33 352 32V64H400C426.5 64 448 85.49 448 112V160H0V112C0 85.49 21.49 64 48 64H96V32C96 14.33 110.3 0 128 0C145.7 0 160 14.33 160 32zM0 192H448V464C448 490.5 426.5 512 400 512H48C21.49 512 0 490.5 0 464V192zM64 304C64 312.8 71.16 320 80 320H112C120.8 320 128 312.8 128 304V272C128 263.2 120.8 256 112 256H80C71.16 256 64 263.2 64 272V304zM192 304C192 312.8 199.2 320 208 320H240C248.8 320 256 312.8 256 304V272C256 263.2 248.8 256 240 256H208C199.2 256 192 263.2 192 272V304zM336 256C327.2 256 320 263.2 320 272V304C320 312.8 327.2 320 336 320H368C376.8 320 384 312.8 384 304V272C384 263.2 376.8 256 368 256H336zM64 432C64 440.8 71.16 448 80 448H112C120.8 448 128 440.8 128 432V400C128 391.2 120.8 384 112 384H80C71.16 384 64 391.2 64 400V432zM208 384C199.2 384 192 391.2 192 400V432C192 440.8 199.2 448 208 448H240C248.8 448 256 440.8 256 432V400C256 391.2 248.8 384 240 384H208zM320 432C320 440.8 327.2 448 336 448H368C376.8 448 384 440.8 384 432V400C384 391.2 376.8 384 368 384H336C327.2 384 320 391.2 320 400V432z"/></svg>
                    <date class="font-serif font-semibold text-green-700 text-sm" id="date">
                    </date>
                </div>
                <div class="lg:flex items-center gap-1">
                    <h1 class="lg:py-1 pt-2 lg:px-2 lg:bg-[#D3EACA] font-semibold text-gray-700 text-sm rounded-l font-semibold">সর্বশেষ আপডেট</h1>
                    <div class="element text-green-700 text-sm md:text-lg"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="md:container md:mx-auto md:px-8 px-4">
        <div class="md:flex md:justify-between md:items-center md:py-4">
            <div class="logo py-4"><a href="/" class="text-2xl text-rose-600 font-semibold flex items-center gap-1">{{ config('app.name')  }}
                    <svg class="w-[20px]" fill="#E11D48" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M352 0H48C21.49 0 0 21.49 0 48v288c0 14.16 6.246 26.76 16 35.54v81.36C6.607 458.5 0 468.3 0 479.1C0 497.7 14.33 512 31.1 512h320c53.02 0 96-42.98 96-96V96C448 42.98 405 0 352 0zM324.8 170.4c3.006 .4297 4.295 4.154 2.004 6.301L306.2 196.9l4.869 28.5c.4297 2.434-1.576 4.439-3.725 4.439c-.5723 0-1.145-.1445-1.719-.4297L280 215.9l-25.63 13.46c-.5723 .2852-1.145 .4297-1.719 .4297c-2.146 0-4.152-2.006-3.723-4.439l4.869-28.5l-20.62-20.19c-2.291-2.146-1.002-5.871 2.006-6.301l28.64-4.152l12.89-25.92C277.3 138.9 278.7 138.2 280 138.2s2.721 .7168 3.295 2.148l12.89 25.92L324.8 170.4zM216 72c23.66 0 46.61 6.953 66.36 20.09c3.219 2.141 4.438 6.281 2.906 9.844c-1.547 3.547-5.453 5.562-9.172 4.594C268.8 104.8 262.2 104 256 104C207.5 104 168 143.5 168 192S207.5 280 256 280c6.234 0 12.81-.8281 20.09-2.531c3.719-.9687 7.625 1.047 9.172 4.594c1.531 3.562 .3125 7.703-2.906 9.844C262.6 305 239.7 312 216 312C149.8 312 96 258.2 96 192S149.8 72 216 72zM352 448H64v-64h288c17.67 0 32 14.33 32 32C384 433.7 369.7 448 352 448z"/></svg>
                </a></div>
            <div class="search md:w-[400px]">
                <form action="{{ route('home') }}" method="GET" id="search" class="flex">
                    <input type="search" name="search" value="{{ request()->query('search')  }}" class="rounded-l border-gray-300 w-full" placeholder="হাদিস">
                    <button type="submit" class="border-r border-t border-b px-2">খুজুন</button>
                </form>

            </div>
        </div>
            <nav class="mt-4">
                <div class="flex justify-between items-center md:hidden text-lg font-semibold">
                    <a href="/" class="font-semibold text-xl {{ (request()->is('/')) ? 'text-rose-600' : 'text-green-600' }}">হোম</a>
                    <button type="button" class="bg-slate-100 rounded p-2" id="bar">
                        <svg class="w-[22px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M0 96C0 78.33 14.33 64 32 64H416C433.7 64 448 78.33 448 96C448 113.7 433.7 128 416 128H32C14.33 128 0 113.7 0 96zM0 256C0 238.3 14.33 224 32 224H416C433.7 224 448 238.3 448 256C448 273.7 433.7 288 416 288H32C14.33 288 0 273.7 0 256zM416 448H32C14.33 448 0 433.7 0 416C0 398.3 14.33 384 32 384H416C433.7 384 448 398.3 448 416C448 433.7 433.7 448 416 448z"/></svg>
                    </button>

                </div>
                <ul class="fixed left-0 top-0 md:relative w-[0px] overflow-scroll lg:overflow-hidden md:w-full bg-white z-50 md:h-auto text-lg h-[100%] lg:h-auto" id="menu">
                    <li class="mr-1 hidden md:inline"><a href="/" class="font-semibold {{ (request()->is('/')) ? 'text-rose-600' : 'text-green-600' }}"> হোম </a><span class="hidden md:inline">/</span></li>
                    @foreach($categories as $category)
                        <li class="md:inline md:mr-1">
                            <a href="{{route('category.post', $category->slug)}}" class=" {{ (request()->is('category/'.$category->slug)) ? 'text-rose-600' : 'text-green-600' }} hover:text-rose-500 transition duration-300 bg-gray-100 md:bg-transparent w-full md:w-auto block md:inline p-2 md:p-0 border-b border-white md:border-none text-center md:text-left font-semibold">{{\Illuminate\Support\Str::replace(' ', ', ', $category->category_name)}}
                                 </a><span class="hidden md:inline">/</span></li>
                    @endforeach
                </ul>
            </nav>
        @if(Request::segments())
        <ul class="breadcrumb md:flex items-center gap-1 mt-2">
            <li><a href="/" class="flex items-center gap-1">Home / </a></li>
            <?php $segments = ''; ?>
            @foreach(Request::segments() as $segment)
                <?php $segments .= '/'.$segment; ?>
                <li class="flex items-center gap-1 p-0 after:content-['/'] last:after:content-none last:text-rose-500">
                    <a href="{{ $segments }}">{{$segment}}</a>
                </li>
            @endforeach
        </ul>
        @endif
    </div>
</header>
<script type="text/javascript">
    @php
        $posts = \App\Models\Post::withoutTrashed()->where('status', 'published')->select('slug','title')->take(5)->latest()->get();
        $array = array();

        if ($posts){
               foreach ($posts as $post){
              array_push($array, "<a href='".route('post.view', $post->slug)."'>".$post->title."</a>");
              $strings = json_encode($array);
               echo "let strings = $strings";
            }
        }


    @endphp

    const date = document.getElementById('date');
    const element = document.querySelector('.element');
    setInterval(function (){
        const event = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' };
       date.innerText = event.toLocaleDateString('bn-bd', options)
    }, 1000);
    let typed = null;
    if( typeof strings != 'undefined' ){
         typed = new Typed('.element', {
            strings: strings,
            typeSpeed: 60,
            loop: true,
            showCursor: true,
            cursorChar: "_",
            backDelay: 2000,
            smartBackspace: true,
            onStop: function(pos, self) { },
        });
    }


    element.addEventListener('mouseenter', function (){
        typed.stop();
    })
    element.addEventListener('mouseout', function (){
        typed.start();
    })

    const search = document.getElementById('search')

</script>
