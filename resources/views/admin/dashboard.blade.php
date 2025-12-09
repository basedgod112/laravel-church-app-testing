@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('users')
    @if(isset($users) && $users->count())
        <h2>Newest users</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                    <tr>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->created_at->format('Y-m-d') }}</td>
                        <td><a href="{{ route('admin.users.index') }}#user-{{ $u->id }}">Manage</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No recent users.</p>
    @endif

    <p><a href="{{ route('admin.users.index') }}">View All Users</a></p>
@endsection

@section('news')
    @if(isset($latestNews))
        <h2>Latest news</h2>
        <article>
            @if(!empty($latestNews->image))
                <p>
                    <img src="{{ asset('storage/' . $latestNews->image) }}" alt="{{ $latestNews->title }}" style="max-width:220px; height:auto; display:block; margin-bottom:0.5rem;">
                </p>
            @endif

            <h3>{{ $latestNews->title }}</h3>
            <p>{{ Str::limit($latestNews->content ?? $latestNews->body ?? '', 200) }}</p>
            <p><a href="{{ route('news.index') }}">View All News</a> | <a href="{{ route('news.edit', $latestNews->id) }}">Edit</a></p>
        </article>
    @else
        <p>No news posts yet.</p>
    @endif
@endsection

@section('program')
    <p><a href="{{ route('program.manage') }}">Manage Program</a></p>
@endsection

@section('resources')
    <p>
        <a href="{{ route('resources.manage') }}">Manage Resources</a> |
        <a href="{{ route('resources.categories.index') }}">Manage Resource Categories</a>
    </p>
@endsection

@section('faq')
    <p>
        <a href="{{ route('faq.manage') }}">Manage FAQ</a> |
        <a href="{{ route('faq.categories.index') }}">Manage FAQ Categories</a>
    </p>
@endsection

@section('contact')
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

    <p><a href="{{ route('admin.contact.index') }}">View All Contact Forms</a></p>
@endsection
