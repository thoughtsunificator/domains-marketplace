@extends('layouts/app')

@section('section_title', 'Dashboard')

@section('content')
	<div class="container">
		<div class="row">

			@include( 'dashboard/navi' )

			<div class="col-md-9">

				<div class="row">
					<div class="col-lg-3 col-xs-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								MTD Orders
							</div>
							<div class="panel-body text-center">
								<h3><i class="fa fa-bar-chart"></i> {{ $mtd_count }}</h3>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-xs-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								MTD Earnings
							</div>
							<div class="panel-body text-center">
								<h3><i class="fa fa-money"></i> ${{ number_format($earnings_mtd, 0) }}</h3>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-xs-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								Total Orders
							</div>
							<div class="panel-body text-center">
								<h3><i class="fa fa-shopping-cart"></i> {{ $all_time_sales }}</h3>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-xs-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								Total Earnings
							</div>
							<div class="panel-body text-center">
								<h3><i class="fa fa-money"></i> ${{ number_format($all_time_earnings, 0) }}</h3>
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">Past 30 Days</div>
					<div class="panel-body">
						<div class="chart-responsive">
							<div class="chart" id="past-30-days"></div>
							<script>
								new Morris.Line({
									element: 'past-30-days',
									data: [
										@if ($earnings_30_days)
											@foreach ($earnings_30_days as $date => $earnings)
												{ date: '{{ $date }}', value: {{ $earnings }} },
											@endforeach
										@else
											{ date: '{{ date('jS F Y') }}', value: 0 }
										@endif
									],
									xkey: 'date',
									ykeys: ['value'],
									labels: ['Earnings']
								});
							</script>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
@endsection

@push('head')
	<link rel="stylesheet" href="/lib/morris.min.css">
	<script src="/lib/raphael-min.js"></script>
	<script src="/lib/morris.min.js"></script>
@endpush
