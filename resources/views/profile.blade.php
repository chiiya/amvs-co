@extends('layouts.app')

@section('title', $user->name)

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

        @include('modules.latestAMV')
        
@verbatim
        <main>
            <div class="container boxbg">
                <div class="clear"></div>
                <div class="catalogue">
                    <h2 class="border">AMV Catalogue</h2>
                    <div class="row active-with-click">
                        <amvcard v-for="amv in amvs" :amv="amv" :user="user"></amvcard>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endverbatim
@endsection

@section('scripts')
    @parent
    <script src="/js/profile.js"></script>
@endsection


