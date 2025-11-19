@extends('layouts.app')

@section('title', 'FAQ')

@section('content')
    @if(Auth::check() && Auth::user()->isAdmin())
        <div>
            <a href="{{ route('faq.categories.create') }}">Create category</a>
            <a href="{{ route('faq.faqs.create') }}">Create FAQ</a>
        </div>
    @endif

    @foreach($categories as $category)
        <section>
            <h2>{{ $category->name }}</h2>
            @if(Auth::check() && Auth::user()->isAdmin())
                <a href="{{ route('faq.categories.edit', $category->id) }}">Edit category</a>
                <form action="{{ route('faq.categories.destroy', $category->id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            @endif
            @if($category->description)
                <p>{{ $category->description }}</p>
            @endif
            <ul>
                @foreach($category->faqs as $faq)
                    <li>
                        <strong>{{ $faq->question }}</strong>
                        <div>{{ $faq->answer }}</div>
                        @if(Auth::check() && Auth::user()->isAdmin())
                            <a href="{{ route('faq.faqs.edit', $faq->id) }}">Edit FAQ</a>
                            <form action="{{ route('faq.faqs.destroy', $faq->id) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>
        </section>
    @endforeach
@endsection
