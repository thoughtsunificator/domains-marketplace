@extends('layouts/app')

@section('section_title', 'Subscribe with Credit Card')

@section('content')
	<div class="container">
		<div class="row">

			@include( 'dashboard/navi' )

			<div class="col-md-9">
				<div class="panel panel-default">
					<div class="panel-heading">{{ $plan }} Plan -
						{{ App\Models\Options::get_option('currency_symbol') }}{{ $pricing }} / month</div>

					<div class="panel-body">

						<h4>{{ _('Redirecting to PayPal.. Please Wait') }}</h4>

						<form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="paypalform" id="paypalform">
							<input type="hidden" name="business"
								value="{{ App\Models\Options::get_option('paypal_email', 'crivion@gmail.com') }}" />
							<input type="hidden" name="return" value="{{ env('APP_URL') }}/dashboard/subscribe/success" />
							<input type="hidden" name="cancel_return" value="{{ env('APP_URL') }}" />
							<input type="hidden" name="notify_url" value="{{ env('APP_URL') }}/dashboard/subscribe/paypal-process" />
							<input type="hidden" name="item_name"
								value="{{ \App\Models\Options::get_option('site_title') }} Subscription" />
							<input type="hidden" name="a3" value="<?= $pricing ?>" />
							<input type="hidden" name="p3" value="1" />
							<input type="hidden" name="t3" value="M" />
							<input type="hidden" name="src" value="1" />
							<input type="hidden" name="currency_code"
								value="{{ \App\Models\Options::get_option('currency_code') }}" />
							<input type="hidden" name="custom" value="{{ base64_encode($plan . '_' . auth()->user()->id) }}" />
							<input type="hidden" name="cmd" value="_xclick-subscriptions" />
							<input type="hidden" name="rm" value="2" />
						</form>

						<script>
							window.onload = function() {
								document.forms['paypalform'].submit();
							}
						</script>

						<hr>
						<div class="row">
							<div class="col-xs-12 col-md-6">
								<img src="{{ asset('/image/paypal-btn.webp') }}" alt='stripe' class="img-responsive" />
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
