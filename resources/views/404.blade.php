@extends('layouts.app')

@section('title', '404 - Not Found')

@section('content')
    <div class="container">
        <img class="img-responsive center-block" src="/img/404.png">
        <div class="wrapper">
            <h1>Sorry, the requested page could not be found.</h1>
            <p>Maybe one of these topics could be useful:</p>
            <ul>
                <li><a href="/users">User Overview</a></li>
                <li><a href="/amvs">AMV Overview</a></li>
                <li><a href="/contests">Current Contests</a></li>
            </ul>
        </div>
    </div>
@endsection

@section('scripts')
  @parent
@endsection