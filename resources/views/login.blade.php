@extends('layouts.app') 

@section('title', 'Login') 

@section('content')

<div id="app">
  <header>
    <div class="container center">
      <h1>AMVS.CO</h1>
      <h2>Anime Music Video Catalogue</h2>
    </div>
    <div class="form">
      <div class="container">
        <div class="login-card">
          <div class="login-card-content">
            <p class="card-title">Login</p>
            <form role="form" method="POST" action="{{ url('/login') }}">
              {{ csrf_field() }}

              <input type="text" name="name" id="name" placeholder="Username" required autofocus>
              <input type="password" name="password" id="password" placeholder="Password" required>
              
              <div class="checkbox">
                  <input type="checkbox" class="filled-in" id="remember" name="remember" />
                  <label for="remember">Remember Me</label>
              </div>

              @if ($errors->has('name'))
              <p class="help-block">
                  <strong>{{ $errors->first('name') }}</strong>
              </p>
              @endif
              @if ($errors->has('password'))
              <p class="help-block">
                  <strong>{{ $errors->first('password') }}</strong>
              </p>
              @endif

              <input type="submit" class="button button--square button--primary" value="Login">
              <p><a href="/signup">Or create a new account.</a></p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </header>
  <main>
      <div class="container boxbg">
        <div class="clear"></div>
        <div class="catalogue">
          <h2 class="border">Newest AMVs</h2>
          <p>Browse the newest AMVs created by our members.</p>
          <div class="row">
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
                    <span class="card-title grey-text text-darken-4"><a 
                      href="/user/{{ $amv->user->name }}/{{ $amv->url }}">
                      @if (strlen($amv->title) > 20)
                        {{ substr($amv->title, 0, 20) . '...' }}
                      @else 
                        {{ $amv->title }}
                      @endif
                        </a></span>
                      <p><a class="profile-link" href="/user/{{ $amv->user->name }}/">{{ $amv->user->name }}</a></p>
                      <p>
                        @foreach ($amv->genres as $genre)
                          @if ($loop->last)
                            {{ $genre->name }}
                          @else
                            {{ $genre->name . ' - ' }}
                          @endif
                        @endforeach
                      </p>
                    </div>
                  <div class="card-action">
                    <a class="button button--square button--transparent button--primary" href="/user/{{ $amv->user->name }}/{{ $amv->url }}">Watch Now</a>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </main>
  </div>
  @endsection 
  
  @section('scripts') 
  @parent
  @endsection