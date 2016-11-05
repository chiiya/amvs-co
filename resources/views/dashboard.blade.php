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
                    <p><router-link to="/dashboard/profile">Edit Profile</router-link></p>
                </div>
            </li>
            <li><router-link class="elem" to="/dashboard" v-on:click="setNav()"><i class="material-icons">home</i> Dashboard</router-link></li>
            <li><router-link class="elem" to="/dashboard/amvs" v-on:click="setNav()"><i class="material-icons">movie</i> My AMVS</router-link></li>
        </ul>
    </nav>
    <main class="dashboard__content">
        <div>
            <header class="dashboard__title valign-wrapper">
                <router-link to="/dashboard" class="breadcrumb">Dashboard</router-link>
                <router-link :to="parent.path" class="breadcrumb" v-cloak>@{{ parent.title }}</router-link>
            </header>
            <transition 
                name="fade" 
                enter-active-class="animated fadeIn" 
                leave-active-class="animated fadeOut" 
                mode="out-in">
                <router-view></router-view>
            </transition>
        </div>
    </main>
  </div>
@endsection

@section('scripts')
  @parent
  <script src="/js/dashboard.js"></script>
@endsection