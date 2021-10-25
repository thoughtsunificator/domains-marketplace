@extends('layouts/app')
@section('seo_title') Checkout - {{ \App\Models\Options::get_option('seo_title') }} @endsection

@section('section_title', 'Checkout - <a href="/' . $domain->domain . '">' . $domain->domain . '</a>')

@section('content')
	<div class="container">
		<div class="col-xs-12 col-xs-offset-0 col-md-6 col-md-offset-3">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2">
					<h1 class="text-theme-checkout text-center"><i class="fa fa-credit-card"></i> Credit Card</h1>
					<div class="separator-3"></div>
				</div>
			</div>
			<hr />
			<form action="" method="POST" id="checkout-form"> <span class="payment-errors"></span>
				{{ csrf_field() }}
				<input type="hidden" name="domain" value="{{ $domain->domain }}" class="domain-checkout" />
				<div class='form-row'>
					<div class='col-xs-12 form-group required'>
						<label class='control-label'>Email Address</label>
						<input class='form-control email-address' size='4' type='text' required="required"
							value="{{ old('email') }}">
					</div>
					<div class='form-row'>
						<div class='col-xs-12 form-group required'>
							<label class='control-label'>Name on Card</label>
							<input class='form-control name-on-card' size='4' type='text' required="required"
								value="{{ old('customer') }}">
						</div>
					</div>
					<div class='form-row'>
						<div class='col-xs-12 form-group card required'>
							<label class='control-label'>Card Number</label>
							<input autocomplete='off' class='form-control card-number' size='20' type='text' required="required">
						</div>
					</div>
					<div class='form-row'>
						<div class='col-xs-4 form-group cvc required'>
							<label class='control-label'>CVC</label>
							<input autocomplete='off' class='form-control card-cvc' placeholder='ex. 311' size='4' type='text'
								required="required">
						</div>
						<div class='col-xs-4 form-group expiration required'>
							<label class='control-label'>Expiration</label>
							<input class='form-control card-expiry-month' placeholder='MM' size='2' type='text' required="required">
						</div>
						<div class='col-xs-4 form-group expiration required'>
							<label class='control-label'> </label>
							<input class='form-control card-expiry-year' placeholder='YYYY' size='4' type='text' required="required">
						</div>
					</div>
					<div class='form-row'>
						<div class='col-md-12 form-group'>
							<input type="submit" class="btn btn-primary form-control btn-block" value="Submit Payment">
						</div>
					</div>
			</form>
			<hr />
			<div class="text-center">
				<h3>Total: {{ App\Models\Options::get_option('currency_symbol') . number_format($price, 0) }}</h3>
			</div>
		</div>
		</div>
	</div>
@endsection
