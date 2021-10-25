@extends('layouts/app')

@section('section_title', 'Thank you')

@section('content')
	<div class="container">
		<div class="row">

			@include( 'dashboard/navi' )

			<div class="col-md-9">
				<div class="panel panel-default">
					<div class="panel-heading">Thank you</div>

					<div class="panel-body">
						Thank you for subscribing to our platform.
						<br>
						Please allow up to 30 mins for your plan to get active.
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
