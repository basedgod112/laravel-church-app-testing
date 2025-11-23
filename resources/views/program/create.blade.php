@extends('layouts.app')

@section('title', 'Create Program Item')

@section('content')
    <h1>Create Program Item</h1>
    <form method="POST" action="{{ route('program.store') }}">
        @csrf
        @include('program._form')
        <button type="submit">Create</button>
    </form>
@endsection

