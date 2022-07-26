<?php
   $categories = \App\Models\Category::select('id', 'category_name', 'slug')->get();
?>
<header class="relative">
    <div class="top-nev border-b">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-1">
                <div class="flex gap-1 items-center py-4">
                    <svg class="w-[15px]" fill="#D3EACAs" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M160 32V64H288V32C288 14.33 302.3 0 320 0C337.7 0 352 14.33 352 32V64H400C426.5 64 448 85.49 448 112V160H0V112C0 85.49 21.49 64 48 64H96V32C96 14.33 110.3 0 128 0C145.7 0 160 14.33 160 32zM0 192H448V464C448 490.5 426.5 512 400 512H48C21.49 512 0 490.5 0 464V192zM64 304C64 312.8 71.16 320 80 320H112C120.8 320 128 312.8 128 304V272C128 263.2 120.8 256 112 256H80C71.16 256 64 263.2 64 272V304zM192 304C192 312.8 199.2 320 208 320H240C248.8 320 256 312.8 256 304V272C256 263.2 248.8 256 240 256H208C199.2 256 192 263.2 192 272V304zM336 256C327.2 256 320 263.2 320 272V304C320 312.8 327.2 320 336 320H368C376.8 320 384 312.8 384 304V272C384 263.2 376.8 256 368 256H336zM64 432C64 440.8 71.16 448 80 448H112C120.8 448 128 440.8 128 432V400C128 391.2 120.8 384 112 384H80C71.16 384 64 391.2 64 400V432zM208 384C199.2 384 192 391.2 192 400V432C192 440.8 199.2 448 208 448H240C248.8 448 256 440.8 256 432V400C256 391.2 248.8 384 240 384H208zM320 432C320 440.8 327.2 448 336 448H368C376.8 448 384 440.8 384 432V400C384 391.2 376.8 384 368 384H336C327.2 384 320 391.2 320 400V432z"/></svg>
                    <date class="font-serif font-semibold text-green-700" id="date">

                    </date>
                </div>
                <div class="flex items-center gap-1">
                    <h1 class="py-4 px-2 bg-[#D3EACA] font-semibold text-gray-700">সর্বশেষ আপডেট</h1>
                        <p class="element"></p>
                </div>
            </div>
        </div>
    </div>
    <div class="md:container md:mx-auto md:px-4 px-2">
        <div class="md:flex md:justify-between md:items-center md:py-4">
            <div class="logo py-4"><a href="/" class="text-2xl">{{ config('app.name')  }}</a></div>
            <div class="search flex md:w-[400px]">
                <input type="search" class="rounded-l border-gray-300 w-full" placeholder="হাদিস">
                <button class="border-r border-t border-b px-2">খুজুন</button>
            </div>
        </div>
            <nav class="mt-4">
                <div class="flex justify-between items-center md:hidden">
                    <a href="/">হোম</a>
                    <button type="button" class="bg-slate-100 rounded p-2" id="bar">
                        <svg class="w-[15px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M0 96C0 78.33 14.33 64 32 64H416C433.7 64 448 78.33 448 96C448 113.7 433.7 128 416 128H32C14.33 128 0 113.7 0 96zM0 256C0 238.3 14.33 224 32 224H416C433.7 224 448 238.3 448 256C448 273.7 433.7 288 416 288H32C14.33 288 0 273.7 0 256zM416 448H32C14.33 448 0 433.7 0 416C0 398.3 14.33 384 32 384H416C433.7 384 448 398.3 448 416C448 433.7 433.7 448 416 448z"/></svg>
                    </button>
                    <button type="button" class="bg-slate-100 rounded p-2 hidden" id="close">
                        <svg class="w-[15px]"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z"/></svg>
                    </button>

                </div>
                <ul class="absolute left-0 top-0 md:relative w-[0px] md:w-full bg-white z-50 h-[100vh] md:h-auto overflow-hidden" id="menu">
                    <li class="mr-1 hidden md:inline"><a href="/"> হোম <span class="hidden md:inline">/</span> </a></li>
                    @foreach($categories as $category)
                        <li class="md:inline md:mr-1"><a href="{{route('category.post', $category->slug)}}" class="bg-gray-100 md:bg-transparent w-full md:w-auto block md:inline p-2 md:p-0 text-rose-500 md:text-gray-900 border-b border-white md:border-none text-center md:text-left">{{\Illuminate\Support\Str::replace(' ', ', ', $category->category_name)}} <span class="hidden md:inline">/</span> </a></li>
                    @endforeach
                </ul>
            </nav>
    </div>
</header>
<script type="text/javascript">
    const date = document.getElementById('date');
    setInterval(function (){
        const event = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' };
       date.innerText = event.toLocaleDateString('bn-bd', options)
    }, 1000);
</script>
