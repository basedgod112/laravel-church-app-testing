@extends('layouts.app')

@section('title', 'Manage FAQs')

@section('content')
    <a href="{{ route('faq.create') }}">Create new FAQ</a>

    <ul>
        @foreach($faqs as $faq)
            <li>
                <strong>{{ $faq->question }}</strong> (Category: {{ $faq->category->name ?? 'None' }})
                <a href="{{ route('faq.edit', $faq->id) }}">Edit</a>
                <form action="{{ route('faq.destroy', $faq->id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
                <div>{{ $faq->answer }}</div>
            </li>
        @endforeach
    </ul>

    @if(method_exists($faqs, 'links'))
        {{ $faqs->links() }}
    @endif
@endsection
