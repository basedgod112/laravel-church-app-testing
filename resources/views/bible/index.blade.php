@extends('layouts.app')

@section('title', 'Bible')

@section('content')
    <p><a href="{{ route('favorites.index') }}">Favorite verses</a></p>

    <h2>WEB Translation</h2>

    <div id="books">
        <label for="bookSelect">Book:</label>
        <select id="bookSelect"></select>

        <label for="chapterSelect">Chapter:</label>
        <select id="chapterSelect"></select>

        <button id="loadChapter">Load</button>
    </div>

    <div id="chapterContent">
        <h2 id="chapterTitle"></h2>
        <div id="verses"></div>
    </div>

    <script>
        const translation = 'WEB';
        const CANONICAL_ORDER = [
            'Genesis','Exodus','Leviticus','Numbers','Deuteronomy','Joshua','Judges','Ruth','1 Samuel','2 Samuel','1 Kings','2 Kings','1 Chronicles','2 Chronicles','Ezra','Nehemiah','Esther','Job','Psalms','Proverbs','Ecclesiastes','Song of Solomon','Isaiah','Jeremiah','Lamentations','Ezekiel','Daniel','Hosea','Joel','Amos','Obadiah','Jonah','Micah','Nahum','Habakkuk','Zephaniah','Haggai','Zechariah','Malachi','Matthew','Mark','Luke','John','Acts','Romans','1 Corinthians','2 Corinthians','Galatians','Ephesians','Philippians','Colossians','1 Thessalonians','2 Thessalonians','1 Timothy','2 Timothy','Titus','Philemon','Hebrews','James','1 Peter','2 Peter','1 John','2 John','3 John','Jude','Revelation'
        ];

        function normalize(name) {
            return name.toLowerCase().trim().replace(/[^a-z0-9]+/g, ' ').replace(/\s+/g, ' ');
        }

        function reorderToCanonical(arr) {
            const pos = {};
            CANONICAL_ORDER.forEach((b, i) => pos[normalize(b)] = i);

            const mapped = arr.map(item => {
                const n = normalize(item.book);
                const p = (pos[n] !== undefined) ? pos[n] : Number.POSITIVE_INFINITY;
                return { orig: item, norm: n, pos: p };
            });

            mapped.sort((a, b) => {
                if (a.pos !== b.pos) return a.pos - b.pos;
                return a.orig.book.localeCompare(b.orig.book, undefined, { sensitivity: 'base' });
            });

            return mapped.map(m => m.orig);
        }

        function getLastVisited() {
            try {
                const raw = localStorage.getItem('bible_last');
                if (!raw) return null;
                const parsed = JSON.parse(raw);
                if (parsed && parsed.book && parsed.chapter) return parsed;
            } catch (e) {
                // ignore
            }
            return null;
        }

        function setLastVisited(book, chapter) {
            try {
                localStorage.setItem('bible_last', JSON.stringify({ book, chapter: Number(chapter) }));
            } catch (e) {
                // ignore
            }
        }

        // favorites state: map 'Book|Chapter|Verse' => favoriteId
        const favoritesMap = new Map();
        let isAuthenticated = false;

        async function loadFavorites() {
            try {
                const res = await fetch('{{ route('favorites.index') }}', { headers: { 'Accept': 'application/json' } });
                if (!res.ok) return;
                const favs = await res.json();
                favs.forEach(f => {
                    const key = `${f.book}|${f.chapter}|${f.verse}`;
                    favoritesMap.set(key, f.id);
                });
                isAuthenticated = true;
            } catch (e) {
                // likely guest or network error — keep isAuthenticated false
            }
        }

        async function toggleFavorite(book, chapter, verse, el) {
            const key = `${book}|${chapter}|${verse}`;
            const existingId = favoritesMap.get(key);
            if (existingId) {
                // delete
                const res = await fetch(`{{ url('/favorites') }}/${existingId}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                });
                if (res.ok) {
                    favoritesMap.delete(key);
                    markFavorite(el, false);
                } else {
                    alert('Could not remove favorite');
                }
                return;
            }

            // create
            const res = await fetch('{{ route('favorites.store') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ translation: translation, book: book, chapter: chapter, verse: verse })
            });

            if (res.ok) {
                const data = await res.json();
                favoritesMap.set(key, data.favorite.id);
                markFavorite(el, true);
            } else if (res.status === 401) {
                // not authenticated — redirect to login
                window.location = '{{ route('login') }}';
            } else {
                alert('Could not add favorite');
            }
        }

        function markFavorite(el, filled) {
            if (!el) return;
            el.dataset.favorited = filled ? '1' : '0';
            if (filled) {
                el.style.background = '#fff4c2';
                el.style.borderRadius = '6px';
            } else {
                el.style.background = 'transparent';
                el.style.borderRadius = '';
            }
        }

        async function loadIndex() {
            const url = `/bible/api/${translation}/index?_=${Date.now()}`;
            const res = await fetch(url, { cache: 'no-store' });
            if (!res.ok) {
                document.getElementById('books').innerText = 'Bible index not available.';
                return;
            }
            let data = await res.json();

            data = reorderToCanonical(data);

            const bookSelect = document.getElementById('bookSelect');
            bookSelect.innerHTML = '';
            data.forEach(book => {
                const opt = document.createElement('option');
                opt.value = book.book.replace(/\s+/g, '_');
                opt.textContent = `${book.book} (${book.chapter_count})`;
                opt.dataset.chapterCount = book.chapter_count;
                bookSelect.appendChild(opt);
            });
            populateChapters();

            await loadFavorites();

            const last = getLastVisited();
            let initialBook;
            let initialChapter;
            if (last) {
                initialBook = last.book;
                initialChapter = last.chapter;
            } else {
                initialBook = 'Genesis';
                initialChapter = 1;
            }

            if (![...bookSelect.options].some(o => o.value === initialBook)) {
                initialBook = bookSelect.options[0]?.value || initialBook;
            }

            bookSelect.value = initialBook;
            populateChapters();
            const chapterSelect = document.getElementById('chapterSelect');
            const chapterCount = chapterSelect.options.length ? Number(chapterSelect.options[chapterSelect.options.length - 1].value) : 0;

            if (!initialChapter || initialChapter < 1 || initialChapter > chapterCount) {
                initialChapter = 1;
            }

            chapterSelect.value = initialChapter;

            await loadChapter(initialBook, initialChapter);
        }

        function populateChapters() {
            const bookSelect = document.getElementById('bookSelect');
            const chapterSelect = document.getElementById('chapterSelect');
            chapterSelect.innerHTML = '';
            const count = parseInt(bookSelect.selectedOptions[0]?.dataset.chapterCount || 0, 10);
            for (let i = 1; i <= count; i++) {
                const opt = document.createElement('option');
                opt.value = i;
                opt.textContent = i;
                chapterSelect.appendChild(opt);
            }
        }

        document.getElementById('bookSelect').addEventListener('change', populateChapters);
        document.getElementById('loadChapter').addEventListener('click', async () => {
            const book = document.getElementById('bookSelect').value;
            const chapter = document.getElementById('chapterSelect').value;
            await loadChapter(book, chapter);
        });

        async function loadChapter(book, chapter) {
            const res = await fetch(`/bible/api/${translation}/${book}/${chapter}`);
            if (!res.ok) {
                document.getElementById('chapterContent').innerText = 'Chapter not found.';
                return;
            }
            const data = await res.json();
            document.getElementById('chapterTitle').textContent = `${data.book} ${data.chapter}`;
            const versesDiv = document.getElementById('verses');
            versesDiv.innerHTML = '';
            data.verses.forEach(v => {
                const wrapper = document.createElement('div');
                wrapper.dataset.book = data.book;
                wrapper.dataset.chapter = data.chapter;
                wrapper.dataset.verse = v.verse;
                wrapper.style.cursor = 'pointer';
                // keep default spacing; do not add extra padding so verse layout remains unchanged

                const p = document.createElement('p');
                p.innerHTML = `<sup>${v.verse}</sup> ${v.text}`;

                const key = `${data.book}|${data.chapter}|${v.verse}`;
                const filled = favoritesMap.has(key);
                markFavorite(wrapper, filled);

                wrapper.addEventListener('click', async (e) => {
                    e.preventDefault();
                    if (!isAuthenticated) {
                        // redirect to login
                        window.location = '{{ route('login') }}';
                        return;
                    }
                    await toggleFavorite(data.book, data.chapter, v.verse, wrapper);
                });

                wrapper.appendChild(p);
                versesDiv.appendChild(wrapper);
            });

            setLastVisited(book, data.chapter);
            // handle scroll to verse requested from favorites page
            try {
                const scrollTo = localStorage.getItem('bible_scroll');
                if (scrollTo) {
                    const el = Array.from(document.querySelectorAll('#verses > div')).find(d => d.dataset.verse === scrollTo);
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        // briefly highlight
                        el.style.transition = 'background-color 0.4s ease';
                        const prevBg = el.style.background;
                        const prevRadius = el.style.borderRadius;
                        el.style.background = '#dff0d8';
                        el.style.borderRadius = '6px';
                        setTimeout(() => {
                            el.style.background = prevBg;
                            el.style.borderRadius = prevRadius || '';
                        }, 2500);
                    }
                    localStorage.removeItem('bible_scroll');
                }
            } catch (e) {
                // ignore
            }
        }

        loadIndex();
    </script>

@endsection

