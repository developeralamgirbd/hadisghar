@extends('layout.app')
@section('main')
    <section class="py-8">
        <div class="container mx-auto px-8">
            {!! $privacy ? $privacy->content : '' !!}
        </div>
    </section>
@endsection

