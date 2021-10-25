@extends('layouts/admin')


@section('extra_top')
	<div id="load_updates">
		<div class="row">
			<div class="col-lg-3 col-xs-6">
				<div class="small-box bg-yellow">
					<div class="inner">
						<h3>{{ $totalVendors }}</h3>
						<p>Vendors</p>
					</div>
					<div class="icon">
						<i class="fa fa-users"></i>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-xs-6">
				<div class="small-box bg-red">
					<div class="inner">
						<h3>{{ $totalPaying }}</h3>
						<p>Paying</p>
					</div>
					<div class="icon">
						<i class="fa fa-money"></i>
					</div>
				</div>
			</div>

			<div class="col-lg-3 col-xs-6">
				<div class="small-box bg-aqua">
					<div class="inner">
						<h3>{{ $freeTrialVendors }}</h3>
						<p>On Free Trial</p>
					</div>
					<div class="icon">
						<i class="fa fa-shopping-cart"></i>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-xs-6">
				<div class="small-box bg-green">
					<div class="inner">
						<h3>{{ \App\Models\Options::get_option('currency_symbol') . number_format($monthEarnings, 2) }}
						</h3>
						<p>Month Earnings</p>
					</div>
					<div class="icon">
						<i class="fa fa-money"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-header with-border"><strong>Past 30 Days</strong></div>
				<div class="box-body">
					<div class="chart-responsive">
						<div class="chart" id="past-30-days"></div>

						<script>
							new Morris.Line({
								element: 'past-30-days',
								data: [
									@if (isset($earnings_30_days))
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

@endsection

@section('section_title')
	<strong>All vendors</strong>
@endsection

@section('section_body')

	<a href="/admin/vendors" class="btn btn-primary">View Vendors</a>

@endsection
