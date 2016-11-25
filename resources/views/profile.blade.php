@extends('layouts.app')

@section('title', $user->name)

@section('meta')
    @parent
@endsection

@section('styles')
    @parent
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@endsection

@section('content')
        @include('modules.userbar')

        @include('modules.latestAMV')
        

        <main>
            <div class="container boxbg">
                <div class="clear"></div>
                <div class="catalogue">
                    <h2 class="border">AMV Catalogue</h2>
                    <p>Browse the newest AMVs by {{ $user->name }}</p>
                    <div class="row active-with-click">
                    @foreach ($amvs as $amv)
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="card sticky-action">
                            <div class="card-image waves-effect waves-block waves-light">
                                @if ($amv->poster)
                                <img src="{{ $amv->poster }}">
                                @else 
                                <div class="poster-placeholder">
                                    <h3>{{ $amv->title }}</h3>
                                </div>
                                @endif
                            </div>
                            <div class="card-content">
                                <span class="card-title grey-text text-darken-4 truncate"><a 
                                    href="/user/{{ $user->name }}/{{ $amv->url }}">
                                    {{ $amv->title }}
                                </a></span>
                                <div class="labels">
                                @foreach ($amv->genres as $genre)
                                    <span><span class="label label-primary">{{ $genre->name }}</span> &nbsp;</span>
                                @endforeach
                                </div>
                                <p>
                                {{  $amv->created_at->format('M Y') }}
                                </p>
                            </div>
                            <div class="card-action">
                                <a class="button button--square button--transparent button--primary" href="/user/{{ $user->name }}/{{ $amv->url }}">Watch Now</a>
                                <div class="card__likes">
                                    <svg style="width:12px;height:11px" viewBox="0 0 24 24">
                                        <path fill="#9E9E9E" d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z" />
                                    </svg>
                                    <span>{{ $amv->likes_count }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    </div>
                </div>
            </div>
        </main>
@endsection

@section('scripts')
    @parent
@endsection


