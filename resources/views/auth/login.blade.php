@extends('layouts/app')

@section('section_title', 'Login to your Domain Seller Account')

@section('content')
  <div class="container">
    <div class="signup-form">
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}
            @if ($message = Session::get('success'))
              <div class="alert alert-success">
                <p>{{ $message }}</p>
              </div>
            @elseif ($message = Session::get('warning'))
              <div class="alert alert-warning">
                <p>{{ $message }}</p>
              </div>
            @elseif ($message = Session::get('error'))
              <div class="alert alert-warning">
                <p>{{ $message }}</p>
              </div>
            @endif


            <div class="col-md-6 col-md-offset-3">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <input type="text" id="email" class="form-control" name="email" value="{{ old('email') }}"
                  placeholder="Email">

              </div> <br>
              <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
                @if ($errors->has('email'))
                  <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                  </span>
                @endif
              </div>
            </div>

            <div class="col-md-6 col-md-offset-3">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                <input id="password" type="password" class="form-control" name="password" placeholder="Password">

              </div>
              <div class"{{ $errors->has('password') ? ' has-error' : '' }}">
                @if ($errors->has('password'))
                  <span class="help-block">
                    <strong style="color: red;">{{ $errors->first('password') }}</strong>
                  </span>
                @endif
              </div>
            </div>

            <div class="col-md-6 col-md-offset-3">
              <div class="input-group">
                <h5><label class="form-check-label"><input type="checkbox" name="remember"> Remember
                    Me</label></h5>
              </div>
            </div>

            <div class="col-md-6 col-md-offset-3">
              <button type="submit" class="btn btn-inverse btn-block"
                style="font-size: 16px;background: #323c46;color: #ffffff;width: 100%;display: block;margin: 0;">
                <i class="fa fa-btn fa-sign-in"></i> Login
              </button>
            </div>

          </form>
          <div class="col-md-6 col-md-offset-3">
            <h5>
              <div class="text-center"><a class="btn btn-link" href="{{ url('/forgot-password') }}">Forgot Your
                  Password?</a></div>
            </h5>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
