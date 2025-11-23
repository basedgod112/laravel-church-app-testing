@extends('layouts.app')

@section('title', 'Program')

@section('content')
    <h1>Weekly Program</h1>

    @if($programs->isEmpty())
        <p>No program items available.</p>
    @else
        <div class="program-list">
            @foreach($programs as $item)
                <article class="program-item">
                    <h2>{{ $item->title }}</h2>
                    <p>
                        {{ $item->day_of_week ? $item->day_of_week . ' — ' : '' }}
                        @if($item->start_time)
                            {{ \\Carbon\\Carbon::createFromFormat('H:i:s', $item->start_time)->format('H:i') }}
                        @endif
                        @if($item->end_time)
                            - {{ \\Carbon\\Carbon::createFromFormat('H:i:s', $item->end_time)->format('H:i') }}
                        @endif
                    </p>
                    @if($item->description)
                        <p>{{ $item->description }}</p>
                    @endif
                </article>
            @endforeach
        </div>
    @endif

    @can('manage-programs')
        <p><a href="{{ route('program.manage') }}">Manage program (admin)</a></p>
    @endcan

@endsection

