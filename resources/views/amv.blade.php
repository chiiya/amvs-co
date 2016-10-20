@extends('layouts.app')

@section('title', $amv->title)

@section('meta')
    @parent
    <meta name="user" id="{{ $user->id }}">
@endsection

@section('content')
    <div id="app">
        @include('modules.userbar')
        <header>
            <div class="clear"></div>
            <div class="container">
                <a href="/user/{{ $user->name }}">&lt; Back to Profile</a>
                <h1>{{ $amv->title }}</h1>
                <ul>
                    <li>
                        @foreach ($amv->genres as $genre)
                            @if ($loop->last)
                                {{ $genre->name }}
                            @else
                                {{ $genre->name . ' - ' }}
                            @endif
                        @endforeach
                    </li>
                    <li>{{  $amv->created_at->format('M Y') }}</li>
                </ul>
                <div class='embed-container'><iframe src='http://player.vimeo.com/video/{{ $amv->video }}' frameborder='0' webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>
            </div>
        </header>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2>Anime</h2>
                    <p>{{ $amv->animes }}</p>
                    <h2>Music</h2>
                    <p>{{ $amv->music }}</p>
                    <h2>Contest Placements</h2>
                    @foreach ($amv->contests as $contest)
                    <p>{{ $contest->name . ' ' . $contest->year . ' ' . $contest->award }}</p>
                    @endforeach
                </div>
                <div class="col-md-6">
                    <h2>Description</h2>
                    <p>{{ $amv->description }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
@endsection


