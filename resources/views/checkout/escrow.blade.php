@extends('layouts/app')
@section('seo_title') Checkout - {{ \App\Models\Options::get_option('seo_title') }} @endsection

@section('section_title', 'Escrow Request')

@section('content')
	<div class="container-fluid container-white">
		<div class="container add-paddings">
			<div class="col-xs-12 col-xs-offset-0 col-md-6 col-md-offset-3">
				<div class="row">
					<div class="col-lg-8 col-lg-offset-2">
						<h1 class="text-theme-checkout text-center"><i class="fa fa-shield"></i> Escrow Checkout -
							{{ App\Models\Options::get_option('currency_symbol') . number_format($price, 0) }}</h1>
						<div class="separator-3"></div>
					</div>
				</div>
				<hr />Checkout via an Escrow Service requires vendor review.
				<br />We'll keep in touch with you via email for further instructions!
				<hr />
				<form method="POST" action="/checkout/confirm-escrow">
					{{ csrf_field() }}
					<input type="hidden" name="domain" value="{{ $domain->domain }}">

					<div class="hp-css">
						<label>Message</label>
						<input type="text" name="message" class="form-control">
					</div>

					<label>Your Name</label>
					<input type="text" name="customer" class="form-control" required="required">
					<br>
					<label>Your Email</label>
					<input type="email" name="email" class="form-control" required="required">
					<br>
					<label>Your Phone No</label>
					<input type="text" name="phoneNo" class="form-control" required="required">
					<br />
					<input type="submit" name="sb" value="Confirm Escrow Service Order" class="btn btn-navi btn-block">
				</form>
				<hr />
			</div>
		</div>
	</div>
@endsection
