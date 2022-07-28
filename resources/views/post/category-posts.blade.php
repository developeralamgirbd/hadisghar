    @extends('layout.app')
    @section('main')
        <section class="py-14">
            <div class="md:container md:mx-auto md:px-8 px-4">
                @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($posts as $post)
                            <div class="post-grid rounded w-full h-full mb-10">
                                <h1 class="text-lg lg:text-xl font-semibold text-green-700"> <a href="{{ route('post.view', $post->slug) }}">{{ $post->title }}</a></h1>
                                @if($post->feature_img)
                                    <img src=" {{ asset($post->feature_img)  }}" alt="" class="w-full">
                                @endif
                                <div class="my-2 text-gray-800 text-[16px] font-light">
                                    {{ strip_tags(\Illuminate\Support\Str::limit($post->description, 290)) }}
                                    <a href="{{ route('post.view', $post->slug) }}" class="ml-2 text-green-700">আরো পড়ুন</a>
                                </div>
                            </div>
                        @endforeach
                </div>
                {{ $posts->links('vendor.pagination.tailwind') }}
                @else
                    <h1 class="text-center text-2xl text-rose-500">হাদিস খুজে পাওয়া যায়নি</h1>
                @endif
            </div>
        </section>
    @endsection
