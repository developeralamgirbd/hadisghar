    @extends('layout.app')
    @section('main')
        <div class="container mx-auto px-4 py-14">
            <div class="h-screen">
                <input type="hidden" id="postDate" value="{{ $post->updated_at }}">
                <p class="my-4 text-lg font-semibold">ক্যাটেগরী: <span class="text-green-700 font-semibold"> {{ $post ? $post->category->category_name : '' }} </span> | আপডেট:
                    <span class="text-green-700 font-semibold" id="setPostDate"> </span>
                </p>
                <h1 class="text-3xl">{{ $post ? $post->title : ''  }}</h1>
                @if($post->feature_img != null)
                    <img src="{{ asset( $post ? $post->feature_img : '') }}" alt="" class="w-[60%] h-[450px] mb-8">
                @endif
                <div class="py-14">
                    <p >{!! $post ? $post->description : '' !!}</p>
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
