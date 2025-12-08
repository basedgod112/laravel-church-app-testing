@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <p><a href="{{ route('admin.users.index') }}">Manage Users</a></p>
    <p><a href="{{ route('admin.contact.index') }}">See All Contact Forms</a></p>

    @if(isset($messages) && $messages->count())
        <h2>Recent contact forms</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Replied</th>
                    <th>Sent</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($messages as $m)
                    <tr>
                        <td>{{ $m->name }}</td>
                        <td>{{ $m->email }}</td>
                        <td>{{ Str::limit($m->message, 100) }}</td>
                        <td>{{ $m->replied_at ? 'Yes' : 'No' }}</td>
                        <td>{{ $m->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.contact.show', $m) }}">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $messages->links() }}
    @else
        <p>No recent contact forms.</p>
    @endif

@endsection
