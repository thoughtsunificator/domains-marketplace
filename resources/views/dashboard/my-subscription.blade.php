@extends('layouts/app')

@section('section_title', 'My Subscription')

@section('content')
	<div class="container">
		<div class="row">

			@include( 'dashboard/navi' )

			<div class="col-md-9">
				<div class="panel panel-default">
					<div class="panel-heading">My Subscription</div>

					<div class="panel-body">

						<p>
							<strong>Join Date</strong><br>
							{{ auth()->user()->created_at->format('jS F Y') }}
						</p>

						<hr>

						<p>
							<strong>Subscription Plan</strong><br>
							{{ auth()->user()->plan }}
						</p>

						<hr>

						<p>
							<strong>Plan Status</strong><br>
							@if (auth()->user()->plan_expires >= time())
								Active, expires on {{ date('jS F Y', auth()->user()->plan_expires) }}
							@else
								Expired on {{ date('jS F Y', auth()->user()->plan_expires) }}<br>
								<a href="/pricing">Get a plan</a>
							@endif
						</p>

						<hr>

						<p>
							<strong>Payment Method</strong><br>
							@if (auth()->user()->plan_gateway)
								{{ auth()->user()->plan_gateway }}
							@else
								- None Selected -
							@endif
						</p>

						<hr>

						@if (auth()->user()->plan_gateway == 'Credit Card' and auth()->user()->plan_expires >= time() and $hasActiveSubscriptions)
							<a href="/dashboard/subscribe/cancel-plan">Cancel Plan</a>
						@endif

					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
