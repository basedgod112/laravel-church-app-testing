@extends('layouts.app')

@section('title', 'Contact message')

@section('content')
    <h2>From {{ $message->name }}</h2>

    <p><strong>Email:</strong> {{ $message->email }}</p>
    <p><strong>Message:</strong></p>
    <p>{!! nl2br(e($message->message)) !!}</p>

    @if($message->replied_at)
        <h3>Reply</h3>
        <p>{!! nl2br(e($message->reply_message)) !!}</p>
        <p><em>Sent on {{ $message->replied_at->format('Y-m-d H:i') }}</em></p>
    @else
        <h3>Reply to this message</h3>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.contact.reply', $message) }}" method="POST">
            @csrf
            <div>
                <label for="reply_message">Reply</label>
                <textarea id="reply_message" name="reply_message">{{ old('reply_message') }}</textarea>
            </div>
            <button type="submit">Send reply</button>
        </form>
    @endif

    <form action="{{ route('admin.contact.destroy', $message) }}" method="POST" onsubmit="return confirm('Delete this message?')">
        @csrf
        @method('DELETE')
        <button type="submit">Delete message</button>
    </form>

    <p><a href="{{ route('admin.contact.index') }}">Back to list</a></p>
@endsection
