    @extends('layout.app')
    @section('main')
        <div class="grid grid-cols-3 gap-2">
            <div class="post-grid bg-gray-100 p-4 rounded h-[400px]">
                <h1>Title</h1>
                <img src="{{ asset('images/feature/1736688016971988.jpg') }}" alt="" class="w-full h-full">
                <p>Description</p>
                <a href="#">View</a>
            </div>
        </div>
    @endsection
