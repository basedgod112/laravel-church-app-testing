@extends('layouts.app')

@section('title', 'Favorite Verses')

@section('content')
    @if($favorites->isEmpty())
        <p>You have no favorite verses yet.</p>
    @else
        <div id="favoritesList">
            @foreach($favorites as $fav)
                <div class="fav-item" data-id="{{ $fav->id }}" data-book="{{ $fav->book }}" data-chapter="{{ $fav->chapter }}" data-verse="{{ $fav->verse }}" style="padding:8px;border-bottom:1px solid #eee;display:flex;justify-content:space-between;align-items:center;">
                    <div class="fav-link" style="cursor:pointer;">
                        <strong>{{ $fav->book }} {{ $fav->chapter }}:{{ $fav->verse }}</strong>
                        <div style="font-size:0.9em;color:#666;">Added {{ $fav->created_at->diffForHumans() }}</div>
                    </div>
                    <div>
                        <button class="gotoButton">Open</button>
                        <button class="removeButton">Remove</button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <script>
        document.querySelectorAll('#favoritesList .fav-item').forEach(item => {
            const book = item.dataset.book;
            const chapter = item.dataset.chapter;
            const verse = item.dataset.verse;

            item.querySelector('.fav-link').addEventListener('click', () => {
                // set last visited and requested verse then go to bible
                localStorage.setItem('bible_last', JSON.stringify({ book: book, chapter: Number(chapter) }));
                localStorage.setItem('bible_scroll', String(verse));
                window.location.href = '{{ route('bible.index') }}';
            });

            item.querySelector('.gotoButton').addEventListener('click', () => {
                localStorage.setItem('bible_last', JSON.stringify({ book: book, chapter: Number(chapter) }));
                localStorage.setItem('bible_scroll', String(verse));
                window.location.href = '{{ route('bible.index') }}';
            });

            item.querySelector('.removeButton').addEventListener('click', async (e) => {
                e.preventDefault();
                const id = item.dataset.id;
                if (!confirm('Remove this favorite?')) return;
                const res = await fetch(`{{ url('/favorites') }}/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } });
                if (res.ok) {
                    item.remove();
                } else {
                    alert('Could not remove favorite');
                }
            });
        });
    </script>

@endsection

