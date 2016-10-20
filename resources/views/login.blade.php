@extends('layouts.app')

@section('title', 'Login')

@section('content')
@verbatim
  <div id="app">
    <header>
      <div class="clear"></div>
      <div class="container">
        <h1>AMVS.CO</h1>
        <h2>Anime Music Video Catalogue</h2>
      </div>
    </header>
    <div class="form">
      <ul>
        <li><a href="#" v-on:click="display('login')">Login</a></li>
        <li><a href="#" v-on:click="display('signup')">Signup</a></li>
      </ul>
      <keep-alive>
        <transition name="fade"
          enter-active-class="animated fadeIn"
          leave-activeclass="animated fadeOut"
          mode="out-in">
        <component :is="currentView" :display.sync="display">
        </component>
        </transition>
      </keep-alive>
    </div>
  </div>
@endverbatim
@endsection

@section('scripts')
  @parent
  <script src="/js/home.js"></script>
@endsection