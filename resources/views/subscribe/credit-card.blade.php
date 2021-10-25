@extends('layouts/app')

@section('section_title', 'Subscribe with Credit Card')

@section('content')
	<div class="container">
		<div class="row">

			@include( 'dashboard/navi' )

			<div class="col-md-9">
				<div class="panel panel-default">
					<div class="panel-heading">{{ $plan }} Plan -
						{{ App\Models\Options::get_option('currency_symbol') }}{{ $price }} / month</div>

					<div class="panel-body">

						<form action="/dashboard/subscribe/credit-card-process" method="POST" id="payment-form"> <span
								class="payment-errors"></span>
							{{ csrf_field() }}

							<input type="hidden" name="plan" value="{{ $plan }}">

							<div class="row">
								<div class='col-xs-12 form-group required'>
									<label class='control-label'>{{ _('Name on Card') }}</label>
									<input class='form-control name-on-card' size='4' type='text' required="required"
										value="{{ old('customer') }}">
								</div>
								<div class='col-xs-12 form-group required'>
									<label class='control-label'>{{ _('Card Number') }}</label>
									<input autocomplete='off' class='form-control card-number' size='20' type='text' required="required">
								</div>
								<div class='col-xs-4 form-group cvc required'>
									<label class='control-label'>{{ _('CVC') }}</label>
									<input autocomplete='off' class='form-control card-cvc' placeholder='ex. 311' size='4' type='text'
										required="required">
								</div>
								<div class='col-xs-4 form-group expiration required'>
									<label class='control-label'>{{ _('Expiration') }}</label>
									<input class='form-control card-expiry-month' placeholder='MM' size='2' type='text' required="required">
								</div>
								<div class='col-xs-4 form-group expiration required'>
									<label class='control-label'> </label>
									<input class='form-control card-expiry-year' placeholder='YYYY' size='4' type='text'
										required="required">
								</div>

								<div class='col-xs-12 form-group'>
									<input type="submit" class="btn btn-primary" value="Submit Payment">
								</div>
							</div>
						</form>

						<hr>
						<div class="row">
							<div class="col-xs-12 col-md-6">
								<img src="{{ asset('/image/powered-by-stripe.webp') }}" alt='stripe' class="img-responsive" />
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
