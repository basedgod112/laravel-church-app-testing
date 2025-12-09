@extends('layouts.app')

@section('title', 'Contact')

@section('content')
    <div class="container">
        <p>Ask us a question or become member at our church!</p>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form id="contactForm" action="{{ route('contact.send') }}" method="POST" novalidate>
            @csrf

            <div>
                <label for="name">Full Name</label>
                <input id="name" name="name" value="{{ old('name') }}" required maxlength="255" aria-describedby="nameHelp">
                @error('name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required maxlength="255">
                @error('email') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="message">Message</label>
                <textarea id="message" name="message" required maxlength="2000">{{ old('message') }}</textarea>
                @error('message') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <button type="submit">Send</button>
        </form>
    </div>

    <script>
        // Basic client-side validation and trimming to complement server-side rules.
        (function () {
            const form = document.getElementById('contactForm');
            if (!form) return;

            function showError(el, msg) {
                let next = el.nextElementSibling;
                if (!next || !next.classList.contains('client-error')) {
                    next = document.createElement('div');
                    next.className = 'client-error text-danger';
                    el.parentNode.insertBefore(next, el.nextSibling);
                }
                next.textContent = msg;
            }

            function clearError(el) {
                const next = el.nextElementSibling;
                if (next && next.classList.contains('client-error')) next.remove();
            }

            form.addEventListener('submit', function (e) {
                let valid = true;

                // trim inputs
                ['name','email','message'].forEach(function (id) {
                    const el = document.getElementById(id);
                    if (el && el.value) el.value = el.value.trim();
                });

                const name = document.getElementById('name');
                if (!name.value) { showError(name, 'Please enter your name'); valid = false; } else { clearError(name); }

                const email = document.getElementById('email');
                if (!email.value) { showError(email, 'Please enter your email'); valid = false; } else if (!/^\S+@\S+\.\S+$/.test(email.value)) { showError(email, 'Please enter a valid email'); valid = false; } else { clearError(email); }

                const message = document.getElementById('message');
                if (!message.value) { showError(message, 'Please enter a message'); valid = false; } else if (message.value.length > 2000) { showError(message, 'Message too long'); valid = false; } else { clearError(message); }

                if (!valid) {
                    e.preventDefault();
                    return false;
                }
            });
        })();
    </script>
@endsection
