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
    </article>
@endsection

