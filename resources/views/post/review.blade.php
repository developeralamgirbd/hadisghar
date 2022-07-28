    @extends('layout.app')
    @section('main')
        <div class="md:container md:mx-auto md:px-8 px-4 py-14">
            <div class="">
                <div class="">
                    <input type="hidden" id="postDate" value="{{ $post->created_at }}">
                    <p class="my-4 font-semibold text-[14px]">ক্যাটেগরী: <span class="text-green-700 font-semibold"> {{ $post ? $post->category->category_name : '' }} </span> | আপডেট:
                        <span class="text-green-700 font-semibold" id="setPostDate"> </span>
                    </p>
                    <h1 class="text-lg lg:text-2xl text-gray-800 font-semibold">{{ $post ? $post->title : ''  }}</h1>
                    @if($post->feature_img != null)
                        <img src="{{ asset( $post ? $post->feature_img : '') }}" alt="" class="w-[60%] h-[450px] mb-8">
                    @endif
                    <div class="pb-14 pt-4 text-gray-700 text-[16px]  post-content">
                        {!! $post ? $post->description : '' !!}
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
