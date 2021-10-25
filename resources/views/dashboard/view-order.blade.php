@extends('layouts/app')

@section('section_title', 'View Order - #' . $order->id)

@section('content')
	<div class="container">

		@include( 'dashboard/navi' )

		<div class="col-md-9">

			<div class="row">
				<div class="col-xs-12 col-md-4">
					<div class="panel panel-default">
						<div class="panel-heading"><strong>Order Contact</strong></div>
						<div class="panel-body">

							<dl>
								<dt>Customer Name:</dt>
								<dd>{{ $order->customer }}</dd><br>
								<dt>Customer Email:</dt>
								<dd>
									<a href="mailto:{{ $order->email }}?subject=Order #{{ $order->id }}">
										{{ $order->email }}
									</a>
								</dd>
							</dl>

						</div>
					</div>
				</div>
				<div class="col-xs-12 col-md-8">
					<div class="panel panel-default">
						<div class="panel-heading"><strong>Order Info</strong></div>
						<div class="panel-body">

							<div class="row">
								<div class="col-xs-6">
									<dl>
										<dt>Order Status</dt>
										<dd>{{ $order->order_status }}</dd><br>
										<dt>Total</dt>
										<dd>${{ number_format($order->total, 0) }}</dd>
									</dl>
								</div>
								<div class="col-xs-6">
									<dl>
										<dt>Order Date</dt>
										<dd>{{ date('jS F Y H:i', strtotime($order->order_date)) }}</dd>
										<br>
										<dt>Payment Type</dt>
										<dd>{{ $order->payment_type }}</dd>
									</dl>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>

			<a href="/dashboard/orders" class="btn btn-primary">Back to Orders</a>
		</div>

	</div>
@endsection
