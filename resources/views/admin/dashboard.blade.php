@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <p><a href="{{ route('admin.users.index') }}">Manage Users</a></p>


@endsection

