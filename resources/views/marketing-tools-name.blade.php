@extends('layouts/app')

@section('section_title', 'Dashboard')

@section('content')
	<div class="container">
		<div class="row">
			@include( 'dashboard/navi' )
			<div class="col-md-9">
				<div class="panel panel-default">
					<div class="panel-body">
						{{ $name }}
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
@endsection
