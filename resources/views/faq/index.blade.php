@extends('layouts.app')

@section('title', 'FAQ')

@section('content')
    @if(\App\Helpers\isAdmin())
        <div>
            <a href="{{ route('faq.categories.create') }}">Create category</a>
            <a href="{{ route('faq.categories.index') }}">Manage categories</a>
            <a href="{{ route('faq.faqs.create') }}">Create FAQ</a>
            <a href="{{ route('faq.faqs.index') }}">Manage FAQs</a>
        </div>
    @endif

    @foreach($categories as $category)
        <section>
            <h2>{{ $category->name }}</h2>

            @if($category->description)
                <p>{{ $category->description }}</p>
            @endif

            <ul>
                @foreach($category->faqs as $faq)
                    <li>
                        <strong>{{ $faq->question }}</strong>
                        <div>{{ $faq->answer }}</div>
                    </li>
                @endforeach
            </ul>
        </section>
    @endforeach
@endsection
