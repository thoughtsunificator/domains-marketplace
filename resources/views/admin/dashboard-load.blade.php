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
				<h3>{{ \App\Models\Options::get_option('currency_symbol') . number_format($monthEarnings, 2) }}</h3>
				<p>Month Earnings</p>
			</div>
			<div class="icon">
				<i class="fa fa-money"></i>
			</div>
		</div>
	</div>
</div>
