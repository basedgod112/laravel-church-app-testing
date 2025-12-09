@php use Carbon\Carbon; @endphp
@extends('layouts.app')

@section('title', 'Resources')

@section('content')
    @can('admin')
        <div>
            <a href="{{ route('resources.categories.create') }}">Create category</a>
            <a href="{{ route('resources.categories.index') }}">Manage categories</a>
            <a href="{{ route('resources.create') }}">Create Resource</a>
            <a href="{{ route('resources.manage') }}">Manage Resources</a>
        </div>
    @endcan

    {{-- Search and filters --}}
    <form method="GET" action="{{ route('resources.index') }}" style="margin:1em 0;">
        <input type="search" name="search" placeholder="Search title or content" value="{{ request('search') }}" />

        <select name="category">
            <option value="">All categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>

        <select name="author">
            <option value="">All authors</option>
            @foreach($authors as $a)
                <option value="{{ $a }}" {{ request('author') == $a ? 'selected' : '' }}>{{ $a }}</option>
            @endforeach
        </select>

        <button type="submit">Filter</button>
        <a href="{{ route('resources.index') }}">Reset</a>
    </form>

    @foreach($resources as $post)
        <article tabindex="0" data-href="{{ route('resources.show', $post->id) }}" style="cursor:pointer;">
            <h2>{{ $post->title }}</h2>
            <h3>{{ $post->author }}</h3>

            {{-- determine whether to load from public/images (default) or storage --}}
            <img
                src="{{ Str::startsWith($post->image, 'default-resources-image') ? asset('images/' . $post->image) : asset('storage/' . $post->image) }}"
                alt="{{ $post->title }}" style="max-width:300px;height:auto;">

            {{-- show only an excerpt on the listing --}}
            <p>{{ Str::limit(strip_tags($post->content), 200) }}</p>

            <p>Published on {{ $post->published_at ? Carbon::parse($post->published_at)->toFormattedDateString() : $post->published_at }}</p>

        </article>
    @endforeach

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('article[data-href]').forEach(function (el) {
                // click to navigate
                el.addEventListener('click', function () {
                    // if click came from a control that stopped propagation, nothing happens
                    window.location = el.dataset.href;
                });
                // keyboard support: Enter key
                el.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        window.location = el.dataset.href;
                    }
                });
            });
        });
    </script>

    @if(method_exists($resources, 'links'))
        {{ $resources->links() }}
    @endif
@endsection
