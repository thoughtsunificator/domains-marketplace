@extends('layouts/app')

@section('section_title', 'Register to Become a Domain Seller')

@section('content')
  <div class="container">
    <div class="signup-form">
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
            {{ csrf_field() }}
            @if ($message = Session::get('success'))
              <div class="alert alert-success">
                <p>{{ $message }}</p>
              </div>
            @endif
            <div class="col-md-6 col-md-offset-3">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Name">

              </div> <br>
              <div class="{{ $errors->has('name') ? ' has-error' : '' }}">
                @if ($errors->has('name'))
                  <span class="help-block">
                    <strong style="color: red;">{{ $errors->first('name') }}</strong>
                  </span>
                @endif
              </div>
            </div>
            <div class="col-md-6 col-md-offset-3">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email">

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

              </div><br>
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
                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation"
                  placeholder="Confirm Password">

              </div>
              <div class"{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                @if ($errors->has('password_confirmation'))
                  <span class="help-block">
                    <strong style="color: red;">{{ $errors->first('password_confirmation') }}</strong>
                  </span>
                @endif
              </div>
            </div>

            <div class="col-md-6 col-md-offset-3">
              <h5><label for="plan-selection">Plan (<a href="/pricing">See Plans & Pricing</a>)</label></h5>
              <select id="plan-selection" name="plan" class="form-control">
                <option value="Starter" @if (request('plan') == 'Starter') selected @endif>
                  Starter Plan -
                  {{ \App\Models\Options::get_option('starter_price') . \App\Models\Options::get_option('currency_symbol') }}
                  / month @if ('Yes' == \App\Models\Options::get_option('free_trial_enabled', 'Yes')) ({{ App\Models\Options::get_option('free_trial_days', 7) }} Days Free Trial) @endif
                </option>
                <option value="Pro" @if (request('plan') == 'Pro') selected @endif>
                  Pro Plan -
                  {{ \App\Models\Options::get_option('pro_price') . \App\Models\Options::get_option('currency_symbol') }}
                  / month @if ('Yes' == \App\Models\Options::get_option('free_trial_enabled', 'Yes')) ({{ App\Models\Options::get_option('free_trial_days', 7) }} Days Free Trial) @endif
                </option>
                <option value="Unlimited" @if (request('plan') == 'Unlimited') selected @endif>
                  Unlimited Plan -
                  {{ \App\Models\Options::get_option('unlimited_price') . \App\Models\Options::get_option('currency_symbol') }}
                  / month @if ('Yes' == \App\Models\Options::get_option('free_trial_enabled', 'Yes')) ({{ App\Models\Options::get_option('free_trial_days', 7) }} Days Free Trial) @endif
                </option>
              </select>
            </div>
            <div class="col-md-6 col-md-offset-3">
              <div class="input-group">
                <h5><label class="form-check-label"><input type="checkbox" required="required"> I accept the
                    <a href="/p-tos">Terms of Use</a> &amp; <a href="/p-privacy-policy">Privacy
                      Policy</a></label></h5>
              </div>
            </div>
            <div class="col-md-6 col-md-offset-3">
              <button type="submit" class="btn btn-inverse btn-block"
                style="font-size: 16px;background: #323c46;color: #ffffff;width: 100%;display: block;margin: 0;">
                <i class="fa fa-btn fa-user"></i> Register
              </button>
            </div>


            <br>
          </form>
          <div class="col-md-6 col-md-offset-3">
            <h5>
              <div class="text-center">Already have an account? <a href="/login">Login here</a></div>
            </h5>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
