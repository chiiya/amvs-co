@extends('layouts.app')

@section('title', $amv->title)

@section('meta')
    @parent
    <meta name="user" id="{{ $user->id }}">
@endsection

@section('styles')
    @parent
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@endsection

@section('content')
    <div id="app">
        @include('modules.userbar')
        <header>
            <div class="clear"></div>
            <div class="container">
                <a href="/user/{{ $user->name }}"><i class="material-icons">arrow_back</i> Back to Profile</a>
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
                <div class='embed-container'>
                @if ($amv->video)
                    @if ($amv->videoHost === 'Vimeo')
                        <iframe src='http://player.vimeo.com/video/{{ $amv->videoCode }}' 
                        frameborder='0' webkitAllowFullScreen mozallowfullscreen allowFullScreen>
                        </iframe>
                    @elseif ($amv->videoHost === 'Youtube')
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/{{ $amv->videoCode }}" 
                        frameborder="0" allowfullscreen>
                        </iframe>
                    @endif
                @endif
                </div>
            </div>
        </header>
        <div class="container content">
            <div class="row">
                <div class="col-md-6">
                    <h2>Anime Sources</h2>
                    <p>{{ $amv->animes }}</p>
                    <h2>Music</h2>
                    <p>{{ $amv->music }}</p>
                    @if ($amv->download)
                    <h2>Download</h2>
                    <p><a href="{{ $amv->download }}">Download from Google Drive</a></p>
                    @endif
                    <h2>Contest Placements</h2>
                    @foreach ($amv->awards as $award)
                    <p>{{ $award->contest->name . ' ' . $award->contest->year . ' ' . $award->award }}</p>
                    @endforeach
                </div>
                <div class="col-md-6">
                    <h2>Description</h2>
                    <p>{!! nl2br(e($amv->description)) !!}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
@endsection


