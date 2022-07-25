    @extends('layout.app')
    @section('main')
        <section class="py-14">
            <div class="container mx-auto px-4">
        <div class="grid grid-cols-3 gap-2">
            @foreach($posts as $post)
                <div class="post-grid bg-gray-100 p-4 rounded w-full h-full">
                    <h1 class="mb-4">{{ $post->title }}</h1>
                    <img src=" {{ asset($post->feature_img)  }}" alt="" class="w-full">
                    <p class="my-2">
                        {{ $post->description }}
                    </p>
                    <a href="{{ route('post.view', $post->slug) }}" class="bg-white py-1 px-2 rounded mt-4 shadow">View</a>
                </div>
            @endforeach
        </div>
        {{ $posts->links() }}
            </div>
        </section>
    @endsection
