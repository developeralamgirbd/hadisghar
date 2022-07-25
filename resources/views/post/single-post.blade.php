    @extends('layout.app')
    @section('main')
        <div class="container mx-auto px-4">
            <div class=" p-4 h-screen">
                <p class="my-4">ক্যাটেগরি: {{ $post ? $post->category->category_name : '' }} । আপডেট: {{ $post ? $post->updated_at : '' }}</p>
                <h1 class="text-3xl">{{ $post ? $post->title : ''  }}</h1>
                @if($post->feature_img != null)
                    <img src="{{ asset( $post ? $post->feature_img : '') }}" alt="" class="w-[60%] h-[450px] mb-8">
                @endif
                <div class="py-14">
                    <p >{!! $post ? $post->description : '' !!}</p>
                </div>

            </div>
        </div>
    @endsection
