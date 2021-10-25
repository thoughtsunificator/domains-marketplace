@extends('layouts/app')

@section('section_title', 'Checkout Complete')

@section('content')
	<div class="container-fluid container-white">
		<div class="container add-paddings">
			<div class="col-xs-12 col-xs-offset-0 col-md-6 col-md-offset-3">
				<div class="row">
					<div class="col-lg-8 col-lg-offset-2">
						<h1 class="text-theme-checkout text-center">
							<i class="fa fa-check-circle-o"></i> Thank you for your order
						</h1>
						<div class="separator-3"></div>
					</div>
				</div>
				<div class="text-center">
					<hr />We have sent you a confirmation email and also notified the vendor.
					<br />We will get back to you shortly for discussing transfer details.
					<br />
				</div>
				<hr />
				<h4 class="text-center">Thank you for your order.</h4>
				<hr />
			</div>
		</div>
	</div>
@endsection
