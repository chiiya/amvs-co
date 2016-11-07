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
                                <p>
                                @foreach ($amv->genres as $genre)
                                    @if ($loop->last)
                                        {{ $genre->name }}
                                    @else
                                        {{ $genre->name . ' - ' }}
                                    @endif
                                @endforeach
                                </p>
                                <p>
                                {{  $amv->created_at->format('M Y') }}
                                </p>
                            </div>
                            <div class="card-action">
                                <a class="button button--square button--transparent button--primary" href="/user/{{ $user->name }}/{{ $amv->url }}">Watch Now</a>
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


