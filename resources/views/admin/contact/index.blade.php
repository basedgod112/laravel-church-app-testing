@extends('layouts.app')

@section('title', 'Contact messages')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Replied</th>
                <th>Submitted</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($messages as $m)
            <tr>
                <td>{{ $m->name }}</td>
                <td>{{ $m->email }}</td>
                <td>{{ Str::limit($m->message, 80) }}</td>
                <td>{{ $m->replied_at ? 'Yes' : 'No' }}</td>
                <td>{{ $m->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{ route('admin.contact.show', $m) }}">View / Reply</a>

                    <form action="{{ route('admin.contact.destroy', $m) }}" method="POST" onsubmit="return confirm('Delete this message?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $messages->links() }}
@endsection
