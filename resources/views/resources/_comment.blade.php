@php use Carbon\Carbon; @endphp
<div class="resource-comment" style="border-bottom:1px solid #eee;padding:8px 0;">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <strong>{{ $comment->user->name ?? 'Unknown' }}</strong>
            <small style="color:#666;">&middot; {{ $comment->created_at ? Carbon::parse($comment->created_at)->diffForHumans() : '' }}</small>
        </div>
        @if(auth()->check() && (auth()->id() === $comment->user_id || (auth()->user()->is_admin ?? false)))
            <form method="POST" action="{{ route('resources.comments.destroy', ['resource' => $comment->resource_id, 'comment' => $comment->id]) }}" style="margin:0;">
                @csrf
                @method('DELETE')
                <button type="submit" style="background:none;border:none;color:#c00;cursor:pointer;">Delete</button>
            </form>
        @endif
    </div>

    <div style="margin-top:6px;">
        {!! nl2br(e($comment->body)) !!}
    </div>
</div>
