    @extends('layout.app')
    @section('main')
        <div class="md:container md:mx-auto md:px-8 px-4 py-14">
            <div class="lg:flex lg:gap-4">
                <div class="lg:w-3/4">
                    <input type="hidden" id="postDate" value="{{ $posts->updated_at }}">
                    <p class="my-4 font-semibold text-[14px]">ক্যাটেগরী: <span class="text-green-700 font-semibold"> {{ $posts ? $posts->category->category_name : '' }} </span> | আপডেট:
                        <span class="text-green-700 font-semibold" id="setPostDate"> </span>
                    </p>
                    <h1 class="text-lg lg:text-2xl text-gray-800 font-semibold">{{ $posts ? $posts->title : ''  }}</h1>
                    @if($posts->feature_img != null)
                        <img src="{{ asset( $posts ? $posts->feature_img : '') }}" alt="" class="w-[60%] h-[450px] mb-8">
                    @endif
                    <div class="pb-14 pt-4 text-gray-700 text-[14px]">
                       {!! $posts ? $posts->description : '' !!}
                    </div>
                    <!--
                    ! Previous and next post pagination
                    -->
                    <div class="lg:flex justify-between items-center pb-14">
                        @if( !empty($previous->slug))
                        <div class="previews">
                            <h1 class="mb-4 flex items-center gap-4 font-semibold text-gray-700"><svg class="w-[15px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M77.25 256l137.4-137.4c12.5-12.5 12.5-32.75 0-45.25s-32.75-12.5-45.25 0l-160 160c-12.5 12.5-12.5 32.75 0 45.25l160 160C175.6 444.9 183.8 448 192 448s16.38-3.125 22.62-9.375c12.5-12.5 12.5-32.75 0-45.25L77.25 256zM269.3 256l137.4-137.4c12.5-12.5 12.5-32.75 0-45.25s-32.75-12.5-45.25 0l-160 160c-12.5 12.5-12.5 32.75 0 45.25l160 160C367.6 444.9 375.8 448 384 448s16.38-3.125 22.62-9.375c12.5-12.5 12.5-32.75 0-45.25L269.3 256z"/></svg> আগের হাদিস</h1>
                            <a href="{{ route('post.view', $previous->slug) }}" class="block text-green-600">{{ $previous->title }}</a>
                        </div>
                        @else
                            <div class="">

                            </div>
                        @endif
                        @if( !empty($next->slug))
                        <div class="next mt-6 lg:mt-0">
                            <h1 class="mb-2 lg:mb-4 flex items-center gap-4 float-right text-gray-700 font-semibold"> পরের হাদিস  <svg xmlns="http://www.w3.org/2000/svg" class="w-[15px]" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M246.6 233.4l-160-160c-12.5-12.5-32.75-12.5-45.25 0s-12.5 32.75 0 45.25L178.8 256l-137.4 137.4c-12.5 12.5-12.5 32.75 0 45.25C47.63 444.9 55.81 448 64 448s16.38-3.125 22.62-9.375l160-160C259.1 266.1 259.1 245.9 246.6 233.4zM438.6 233.4l-160-160c-12.5-12.5-32.75-12.5-45.25 0s-12.5 32.75 0 45.25L370.8 256l-137.4 137.4c-12.5 12.5-12.5 32.75 0 45.25C239.6 444.9 247.8 448 256 448s16.38-3.125 22.62-9.375l160-160C451.1 266.1 451.1 245.9 438.6 233.4z"/></svg></h1>
                            <a href="{{ route('post.view', $next->slug) }}" class="block text-green-600 float-right w-full text-right">{{ $next->title }}</a>
                        </div>
                            @else
                            <div class=""></div>
                        @endif
                    </div>
                    <!--
                  ! Previous and next post pagination End
                  -->
                </div>
                <!--
                ! Single page sidebar
                -->
                <div class="lg:w-1/4">
                    <div class="popular-post border p-2 mb-8 mt-8">
                        <h1 class="font-semibold text-rose-500 mb-4 text-lg">সর্বাদিক পঠিত হাদিস</h1>
                        <ul>
                            @foreach($popular_posts as $post)
                                <li class="border-b last:border-b-0"><a href="{{ $post->slug }}" class="py-2 text-green-600 block">{{ $post->title  }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="popular-post border p-2">
                        <h1 class="font-semibold text-rose-500 mb-4 text-lg">সম্পর্কৃত হাদিস</h1>
                        <ul>
                            @foreach($related_post as $post)
                            <li class="border-b last:border-b-0"><a href="{{ $post->slug }}" class="py-2 text-green-600 block">{{ $post->title  }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            const postDate = document.getElementById('postDate').value;
            const setPostDate = document.getElementById('setPostDate');
            const event = new Date(postDate);
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' };
            setPostDate.innerText = event.toLocaleDateString('bn-bd', options);
        </script>
    @endsection
