@extends('layout.app')
@section('main')
    <section class="py-14">
        <div class="container mx-auto px-4">
    <div class="grid grid-cols-3 gap-2">
        @foreach($posts as $post)
        <div class="post-grid p-4 rounded w-full h-full">
            <h1 class="mb-4 text-2xl font-semibold text-green-700"> <a href="{{ route('post.view', $post->slug) }}">{{ $post->title }}</a></h1>
            <img src=" {{ asset($post->feature_img)  }}" alt="" class="w-full">
            <p class="my-2 text-gray-800">
                {!! \Illuminate\Support\Str::limit($post->description, 100)  !!}... <a href="{{ route('post.view', $post->slug) }}" class="ml-2 text-green-700">আরো পড়ুন</a>
            </p>
        </div>
        @endforeach
    </div>
    {{ $posts->links() }}
        </div>
    </section>
@endsection

