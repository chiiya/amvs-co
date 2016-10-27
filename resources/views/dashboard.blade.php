@extends('layouts.app')

@section('title', 'Dashboard | ' . $user->name)

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
    <nav class="nav nav--side nav--fixed nav--slide">
        <ul>
            <li class="dashboard__user">
                <img class="img--circle avatar" src='{{ $user->avatar }}'/>
                <div>
                    <h3><a href="/user/{{ $user->name }}">{{ $user->name }}</a></h3>
                    <p><a href="#" v-on:click="display('profile')">Edit Profile</a></p>
                </div>
            </li>
            <li><a class="elem active" href="#" v-on:click="display('overview', $event)"><i class="material-icons">home</i> Dashboard</a></li>
            <li><a class="elem" href="#" v-on:click="display('amvs', $event)"><i class="material-icons">movie</i> My AMVS</a></li>
        </ul>
    </nav>
    <main class="dashboard__content">
        <transition 
            name="fade" 
            enter-active-class="animated fadeIn" 
            leave-activeclass="animated fadeOut" 
            mode="out-in"
        >
            <component :is="currentView" :user="user" :amvs="amvs"
                :loading="loading" :display="display" :add-amv="addAmv" :update-avatar="updateAvatar">
            </component>
        </transition>
    </main>
  </div>
@endsection

@section('scripts')
  @parent
  <script src="/js/dashboard.js"></script>
@endsection