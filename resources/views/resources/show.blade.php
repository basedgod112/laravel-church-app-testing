@php use Carbon\Carbon; @endphp
@extends('layouts.app')

@section('title', $resource->title ?? 'Resource')

@section('content')
    <article>
        <h3>{{ $resource->author }}</h3>

        {{-- image handling: default images live in public/images, uploaded ones in storage --}}
        <img
            src="{{ Str::startsWith($resource->image, 'default-resources-image') ? asset('images/' . $resource->image) : asset('storage/' . $resource->image) }}"
            alt="{{ $resource->title }}" style="max-width:600px;height:auto;">

        <p>Published on {{ $resource->published_at ? Carbon::parse($resource->published_at)->toFormattedDateString() : $resource->published_at }}</p>

        @if(!empty($resource->resource_category_id) && $resource->category)
            <p>Category: {{ $resource->category->name }}</p>
        @endif

        <div>
            {!! $resource->content !!}
        </div>

        @if(!empty($resource->link))
            <p>External link: <a href="{{ $resource->link }}" target="_blank" rel="noopener noreferrer">{{ $resource->link }}</a></p>
        @endif

        <p><a href="{{ route('resources.index') }}">Back to resources</a></p>

        {{-- Comments section --}}
        <section id="comments" style="margin-top:32px;">
            <h4>Comments ({{ $resource->comments->count() }})</h4>

            @if(session('success'))
                <div style="color:green;margin-bottom:8px;">{{ session('success') }}</div>
            @endif

            @if($resource->comments->isEmpty())
                <p>No comments yet. Be the first to comment!</p>
            @else
                <div class="comments-list">
                    @foreach($resource->comments as $comment)
                        @include('resources._comment', ['comment' => $comment])
                    @endforeach
                </div>
            @endif

            @auth
                <form method="POST" action="{{ route('resources.comments.store', ['resource' => $resource->id]) }}" style="margin-top:16px;">
                    @csrf
                    <div>
                        <label for="body">Leave a comment</label><br>
                        <textarea name="body" id="body" rows="4" style="width:100%;">{{ old('body') }}</textarea>
                        @error('body')
                            <div style="color:red;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div style="margin-top:8px;">
                        <button type="submit">Post comment</button>
                    </div>
                </form>
            @else
                <p><a href="{{ route('login') }}">Log in</a> to leave a comment.</p>
            @endauth
        </section>

    </article>
@endsection
