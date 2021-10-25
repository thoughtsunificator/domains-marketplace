@extends('layouts/app')

@section('section_title', 'Checkout via PayPal')

@section('content')
	<div class="container">

		<form method="post" name="paypal_form" id="paypal_form" action="https://www.paypal.com/cgi-bin/webscr">
			<input type="hidden" name="business" value="{{ $userPaypalEmail }}" />
			<input type="hidden" name="return" value="{{ env('APP_URL') }}/checkout/success" />
			<input type="hidden" name="cancel_return" value="{{ env('APP_URL') }}" />
			<input type="hidden" name="notify_url" value="{{ env('APP_URL') }}/checkout/paypal-complete" />
			<input type="hidden" name="item_name" value="Domain : {{ $domain->domain }}" />
			<input type="hidden" name="amount" value="{{ $domain->finalPrice }}" />
			<input type="hidden" name="currency_code" value="{{ \App\Models\Options::get_option('currency_code') }}" />
			<input type="hidden" name="custom" value="{{ base64_encode($domain->id) }}" />
			<input type="hidden" name="cmd" value="_xclick" />
			<input type="hidden" name="rm" value="2" />
			<center><br /><br />If you are not automatically redirected to paypal within 5 seconds...<br /><br />
				<input type="submit" value="Click Here" class='btn btn-default'>
			</center>
		</form>

		<script>
			document.getElementById("paypal_form").submit();
		</script>

	</div>
@endsection
