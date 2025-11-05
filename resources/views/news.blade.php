@extends('layouts.app')

@section('title', 'News')

@section('content')
    @foreach($newsPosts as $post)
        <article>
            <h2>{{ $post->title }}</h2>
            <img src="{{ asset('images/' . $post->image) }}" alt="img">
            <p>{{ $post->content }}</p>
            <p>Published on {{ $post->published_at }}</p>
        </article>
    @endforeach
@endsection
