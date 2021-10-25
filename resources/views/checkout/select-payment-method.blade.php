@extends('layouts/app')
@section('seo_title') Checkout - {{ \App\Models\Options::get_option('seo_title') }} @endsection

@section('section_title', 'Select Payment Method')

@section('content')
	<div class="container">

		<div class="col-xs-12 col-xs-offset-0 col-md-8 col-md-offset-2">

			<h3 class="text-center">You are buying <a href="/{{ $domain->domain }}">{{ $domain->domain }}</a><br>
				<h3 class="text-center">
					Price: @if (!is_null($domain->discount) and $domain->discount != 0)
						<strike
							class="text-discount">{{ \App\Models\Options::get_option('currency_symbol') . number_format($domain->pricing, 0) }}</strike>
						{{ App\Models\Options::get_option('currency_symbol') . number_format($domain->discount, 0) }}
					@else
						{{ App\Models\Options::get_option('currency_symbol') . number_format($domain->pricing, 0) }}
					@endif
				</h3>
				<hr>

				<div class="text-center">
					@if ($isStripeEnabled)
						<a href="/checkout/credit-card?domain={{ $domain->domain }}" class="btn btn-primary">
							<i class="fa fa-credit-card payment-icons"></i> Credit Card</a>
					@endif

					@if ($isPayPalEnabled)
						<a href="/checkout/paypal?domain={{ $domain->domain }}" class="btn btn-warning paypalSubmit">
							<i class="fa fa-paypal payment-icons"></i> PayPal</a>
					@endif

					@if ($isEscrowEnabled)
						<a href="/checkout/escrow?domain={{ $domain->domain }}" class="btn btn-navi"><i
								class="fa fa-shield payment-icons"></i> Escrow</a>
					@endif
				</div>

		</div>

	</div>

@endsection
