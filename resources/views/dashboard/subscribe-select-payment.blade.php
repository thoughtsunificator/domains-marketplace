@extends('layouts/app')

@section('section_title', 'Payment Method')

@section('content')
	<div class="container">
		<div class="row">

			@include( 'dashboard/navi' )

			<div class="col-md-9">
				<div class="panel panel-default">
					<div class="panel-heading">{{ $plan }} Plan -
						{{ App\Models\Options::get_option('currency_symbol') }}{{ $planPricing }} / month</div>

					<div class="panel-body">

						@if (App\Models\Options::get_option('stripeEnable', 'No') == 'Yes')
							<h4>Subscribe with Credit Card <small>powered by Stripe</small></h4>
							<br>
							<a href="/dashboard/subscribe/credit-card?plan={{ $plan }}">
								<img src="{{ asset('/image/stripe-cards.webp') }}" alt="Stripe Payment" width="280" />
							</a>
							<hr>
						@endif

						@if (App\Models\Options::get_option('paypalEnable', 'No') == 'Yes')
							<h4>Subscribe with PayPal</h4>
							<a href="/dashboard/subscribe/paypal?plan={{ $plan }}">
								<img src="{{ asset('/image/paypal-btn.webp') }}" alt="PayPal Payment" width="300" />
							</a>
							<hr>
						@endif


					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
