@extends('layouts.app') 

@section('title', 'Login') 

@section('content')

<div id="app">
  <header>
    <div class="clear"></div>
    <div class="container center">
      <h1>AMVS.CO</h1>
      <h2>Anime Music Video Catalogue</h2>
    </div>
    <div class="form">
      <div class="container">
        <div class="login-card">
          <div class="login-card-content">
            <p class="card-title">Signup</p>
            <form role="form" method="POST" action="{{ url('/register') }}">
              {{ csrf_field() }}

              <input type="text" name="name" id="name" placeholder="Username" required autofocus>
              <input type="text" name="email" id="email" placeholder="Email" required>
              <input type="password" name="password" id="password" placeholder="Password" required>
              <input type="password" name="password_confirmation" id="password_confirm" placeholder="Confirm Password" required>

              @if ($errors->has('name'))
              <p class="help-block">
                  <strong>{{ $errors->first('name') }}</strong>
              </p>
              @endif
              @if ($errors->has('email'))
              <p class="help-block">
                  <strong>{{ $errors->first('email') }}</strong>
              </p>
              @endif
              @if ($errors->has('password'))
              <p class="help-block">
                  <strong>{{ $errors->first('password') }}</strong>
              </p>
              @endif
               @if ($errors->has('password_confirmation'))
              <p class="help-block">
                  <strong>{{ $errors->first('password_confirmation') }}</strong>
              </p>
              @endif

              <input type="submit" class="button button--square button--primary" value="Register">
              <p><a href="/login">Or login now.</a></p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </header>
  </div>
  @endsection 
  
  @section('scripts') 
  @parent
  @endsection