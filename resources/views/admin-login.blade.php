@extends('layouts/app')

@section('section_title', 'Login')

@section('content')
	<div class="container add-paddings">
		<div class="col-md-8 col-md-offset-2">

			@if (isset($message) and !empty($message))
				<div class="alert alert-info">
					{{ $message }}
				</div>
			@endif

			<form method="POST" action="/admin/login">
				{{ csrf_field() }}
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-user"></i></span>
					<input type="text" class="form-control" name="ausername" placeholder="Username">

				</div> <br>
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-lock"></i></span>
					<input type="password" class="form-control" name="apassword" placeholder="Password">

				</div>
				<br>
				<button type="submit" class="btn btn-inverse btn-block"
					style="font-size: 16px;background: #323c46;color: #ffffff;width: 100%;display: block;margin: 0;">
					<i class="fa fa-btn fa-sign-in"></i> Login
				</button>
			</form>
			<hr>
			<div class="col-md-6 col-md-offset-3">
				<h5>
					<div class="text-center"><a class="btn btn-link" href="{{ url('/forgot-password') }}">Forgot Your
							Password?</a></div>
				</h5>
			</div>

		</div>
	</div>
@endsection
