    @extends('layout.app')
    @section('main')
        <div class=" p-4 h-screen">
                <h1>{{ $post ? $post->title : ''  }}</h1>
                <p class="text-green-500 my-4">Category: {{ $post ? $post->category->category_name : '' }} Posted: {{ $post ? $post->created_at : '' }}</p>
                <img src="{{ asset( $post ? $post->feature_img : '') }}" alt="" class="w-[60%] h-[450px] mb-8">
                <p>{!! $post ? $post->description : '' !!}</p>
        </div>
    @endsection
