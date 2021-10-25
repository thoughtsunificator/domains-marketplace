@extends('layouts/admin')

@section('section_title')
	<strong>Payments & Plans Configuration</strong>
@endsection

@section('section_body')
	<form method="POST" action="/admin/save-payments-settings">
		{{ csrf_field() }}

		<div class="col-xs-6">
			<dl>
				<dt>Enable PayPal Payments?</dt>
				<dd>
					<input type="radio" name="paypalEnable" value="No" @if ('No' == App\Models\Options::get_option('paypalEnable')) checked @endif> No
					<input type="radio" name="paypalEnable" value="Yes" @if ('Yes' == App\Models\Options::get_option('paypalEnable')) checked @endif> Yes
				</dd>
				<dt>Paypal Email</dt>
				<dd>
					<input type="text" name="paypal_email"
						value="{{ App\Models\Options::get_option('paypal_email', 'you@paypal.com') }}" class="form-control">
					<br>
				<dt>Enable Stripe Payments (if yes, configure credentials in .env file as per documentation)?</dt>
				<dd>
					<input type="radio" name="stripeEnable" value="No" @if ('No' == App\Models\Options::get_option('stripeEnable')) checked @endif> No
					<input type="radio" name="stripeEnable" value="Yes" @if ('Yes' == App\Models\Options::get_option('stripeEnable')) checked @endif> Yes
				</dd>
			</dl>
		</div>

		<div class="col-xs-6">
			<dl>
				<dt>Currency Symbol</dt>
				<dd>
					<input type="text" name="currency_symbol" value="{{ App\Models\Options::get_option('currency_symbol') }}"
						class="form-control">
				</dd>
				<br>
				<dt>Currency ISO Code <small><a href="https://www.xe.com/iso4217.php" target="_blank">ISO List</a></small>
				</dt>
				<dd>
					<input type="text" name="currency_code" value="{{ App\Models\Options::get_option('currency_code') }}"
						class="form-control">
				</dd>
			</dl>
		</div>

		<div class="col-xs-12">
			<h3>Pricing Setup <small>just the number, without currency symbol or code</small></h3>
			<hr>

			<dt>Enable Free Trial?</dt>
			<dd>
				<select name="free_trial_enabled">
					<option value="Yes" @if (App\Models\Options::get_option('free_trial_enabled', 'Yes') == 'Yes') selected @endif>Yes</option>
					<option value="No" @if (App\Models\Options::get_option('free_trial_enabled', 'Yes') == 'No') selected @endif>No</option>
				</select>
			</dd>
			<br>
			<dt>Free Trial Days:</dt>
			<dd>
				<select name="free_trial_days">
					@for ($i = 1; $i <= 365; $i++)
						<option value="{{ $i }}" @if (App\Models\Options::get_option('free_trial_days', 7) == $i) selected @endif>{{ $i }} Days</option>
					@endfor
				</select>
			</dd><br>
		</div>

		<div class="row ml-0">
			<div class="col-xs-3">
				<dt>Starter Plan Price</dt>
				<dd>
					<input type="text" name="starter_price" value="{{ App\Models\Options::get_option('starter_price') }}"
						class="form-control" placeholder="9.99">
				</dd>
			</div>

			<div class="col-xs-3">
				<dt>Pro Plan Price</dt>
				<dd>
					<input type="text" name="pro_price" value="{{ App\Models\Options::get_option('pro_price') }}"
						class="form-control" placeholder="19.99">
				</dd>
			</div>

			<div class="col-xs-3">
				<dt>Unlimited Price</dt>
				<dd>
					<input type="text" name="unlimited_price" value="{{ App\Models\Options::get_option('unlimited_price') }}"
						class="form-control" placeholder="49.99">
				</dd>
			</div>
		</div>
		<br>
		<div class="row ml-0">
			<div class="col-xs-3">
				<dt>Starter Plan Domain Limit</dt>
				<dd>
					<input type="text" name="starter_limit" value="{{ App\Models\Options::get_option('starter_limit') }}"
						class="form-control" placeholder="100">
				</dd>
			</div>

			<div class="col-xs-3">
				<dt>Pro Plan Domain Limit</dt>
				<dd>
					<input type="text" name="pro_limit" value="{{ App\Models\Options::get_option('pro_limit') }}"
						class="form-control" placeholder="1000">
				</dd>
			</div>
		</div>
		<br>
		<hr>
		<center>
			<input type="submit" name="sb_settings" value="Save Payment Settings" class="btn btn-primary">
		</center>

	</form>
@endsection
