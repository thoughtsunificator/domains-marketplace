@extends('layouts/app')

@section('section_title', 'Manage Domain')

@section('content')
	<div class="container">
		<div class="row">

			@include( 'dashboard/navi' )
			<div class="col-md-9">
				<div class="panel panel-default">
					<div class="panel-heading">Welcome to Price Drop (Search and Submit your domains) to Price Drop </div>

					<div class="panel-body">

						@if ($domains->count())
							<div class="input-group"> <span class="input-group-addon">Filter</span>

								<input id="filter" type="text" class="form-control" placeholder="Type here...">
							</div>
							<br>
							<table class="table table-striped table-bordered table-responsive dataTable">
								<thead>
									<tr>
										<th>ID</th>
										<th>Domain</th>
										<th>Age</th>
										<th>Price</th>
										<th>Premium</th>
										<th>Status</th>
										<th>Approved / Disapproved</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody class="searchable">
									@foreach ($domains as $d)
										<tr>
											<td>
												{{ $d->id }}
											</td>
											<td>
												<a href="/domains/{{ $d->domain }}" target="_blank">
													{{ $d->domain }}
												</a>
												<br>

												@if (!$d->is_verified)
													<a href="/dashboard/verify-domain-ownership/{{ $d->domain }}">
														<span class="text text-danger">Verify Ownership</span>
													</a>
												@else
													<span class="text text-success">Ownership Verified</span>
												@endif
											</td>
											<td>
												@if ($d->reg_date)
													{{ $d->domain_age }}
												@else
													N/A
												@endif
											</td>
											<td>
												@if (!is_null($d->discount) and $d->discount != 0)
													<strike
														class="text-discount">{{ \App\Models\Options::get_option('currency_symbol') . number_format($d->pricing, 0) }}</strike>
													{{ App\Models\Options::get_option('currency_symbol') . number_format($d->discount, 0) }}
												@else
													{{ App\Models\Options::get_option('currency_symbol') . number_format($d->pricing, 0) }}
												@endif
											</td>
											<td>
												@if (!$d->is_premium || !$d->is_approved)

													<span class="text text-danger">Not Premium</span>

												@else
													<span class="text text-success">Premium</span>
												@endif
											</td>
											<td>
												{{ $d->domain_status }}
											</td>
											<td>
												@if (!$d->is_approved)
													<a href="#" class="text-danger">Not approved</a>
												@else
													<span class="text-success">Approved</span>
												@endif
											</td>

											<td>
												<div class="btn-group">
													<a class="btn btn-primary btn-xs price_drop_popup" data-domain="{{ $d->domain }}"
														data-pricing="{{ $d->pricing }}" data-id="{{ $d->id }}" data-toggle="modal"
														data-target="#myModal2">
														<i class="glyphicon glyphicon-pencil"></i>
													</a>

												</div>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>

							{{ $domains->links() }}
						@else
							No domains listed yet. <a href="/dashboard/domains/add">Add a domain</a> now.
						@endif

					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div class="row">

						<div class="col-xs-12">
							<h3 class="modal-title header-model"></h3>
							<button type="button" class="close" data-dismiss="modal">×</button>
						</div>
					</div>
				</div>
				<div class="modal-body">
					<form id="CustomerForm" name="CustomerForm" method="POST">
						<div class="form-group">
							<div id="pb-modalreglog-progressbar"></div>
						</div>
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="form-group">
							<input type="hidden" name="domain_id" id="domain_id" value="">
						</div>
						<h5 style="text-align: center;color:red">Note: Price drop domains can only be listed from 1 to 7
							days and will automatically be price dropped based on your settings.</h5>
						<div class="form-group">
							<label for="email">Start Day & Time</label>
							<div class='input-group date' id='datetimepicker1'>
								<input type='text' class="form-control" name="start_datetime" id="start_datetime" value=""
									autocomplete="off" require />
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="country">Current Price</label>
									<div class="">
										<input type="text" class="form-control" name="pricing" id="pricing">
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="country">Price Drop Value</label>
									<div class="">
										<input type="text" class="form-control" name="price_drop_value" id="price_drop_value">
									</div>
								</div>

							</div>
						</div>
						<button type="submit" id="saveBtn" name="sb-offer"
							class="btn btn-md btn-primary btn-block">Submit</button><br>
					</form>
				</div>
				<div class="modal-footer">
					<h5 style="text-align: center;">Have Questions?<a href="/contact"> Contact us</a> at any time.</h5>
				</div>
			</div>
		</div>
	</div>
@endsection
