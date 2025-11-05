@extends('layouts.app')

@section('title', 'Resources')

{{--Modify for articles instead of news posts--}}
{{--@section('content')--}}
{{--    @foreach($newsPosts as $post)--}}
{{--        <article>--}}
{{--            <h2>{{ $post->title }}</h2>--}}
{{--            <img src="{{ asset('images/' . $post->image) }}" alt="{{ $post->title }}">--}}
{{--            <p>{{ $post->content }}</p>--}}
{{--            <p>By {{ $post->author }} on {{ $post->published_at }}</p>--}}
{{--        </article>--}}
{{--    @endforeach--}}
{{--@endsection--}}
