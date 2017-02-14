@extends('layouts.app')

@section('title', $amv->title)

@section('meta')
    @parent
    <meta name="amv" id="{{ $amv->id }}">
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
                @if (Auth::check())
                @if (!$likedByUser)
                <div class="amv__like--unfilled">
                    <svg style="width:24px;height:24px" viewBox="0 0 24 24" class="like">
                        <path class="like-empty" fill="#ffffff" d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z" />
                        <path class="like-fill" fill="#ff3860" d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z" />
                    </svg>
                </div>
                <div class="amv__like--filled" style="display:none">
                    <svg style="width:24px;height:24px" viewBox="0 0 24 24" class="unlike">
                        <path class="like-fill" fill="#ff3860" d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z" />
                        <path class="like-hover" fill="#ffffff" d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z" />
                    </svg>
                </div>
                @else
                <div class="amv__like--unfilled" style="display:none">
                    <svg style="width:24px;height:24px" viewBox="0 0 24 24" class="like">
                        <path class="like-empty" fill="#ffffff" d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z" />
                        <path class="like-fill" fill="#ff3860" d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z" />
                    </svg>
                </div>
                <div class="amv__like--filled">
                    <svg style="width:24px;height:24px" viewBox="0 0 24 24" class="unlike">
                        <path class="like-fill" fill="#ff3860" d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z" />
                        <path class="like-hover" fill="#ffffff" d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z" />
                    </svg>
                </div>
                @endif
                @endif
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
                    <li>
                        <svg style="width:14px;height:13px" viewBox="0 0 24 24">
                            <path fill="#ffffff" d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z" />
                        </svg>
                        <span>{{ $amv->likes_count }}</span>
                    </li>
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
                    @if ($amv->download !== null && $amv->download !== 'null' && strlen($amv->download)>0)
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
    <script src="/js/app.js"></script>
    <script src="/js/details.js"></script>
@endsection


