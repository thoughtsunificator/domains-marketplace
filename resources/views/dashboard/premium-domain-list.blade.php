@extends('layouts/app')

@section('section_title', 'Manage Domain')

@section('content')
	<div class="container">
		<div class="row">

			@include( 'dashboard/navi' )

			<div class="col-md-9">
				<div class="panel panel-default">
					<div class="panel-heading">Welcome to your Premium Submission Overview (Please edit, submit,view or
						delete)
						your domains that you are about to submit to premium.Please allow us 24 Hours to review it.</div>

					<div class="panel-body" style="overflow:auto;">

						@if ($domains->count())
							<div class="input-group"> <span class="input-group-addon">Filter</span>

								<input id="filter" type="text" class="form-control" placeholder="Type here...">
							</div>
							<br>
							<table class="table table-striped table-bordered table-responsive dataTable" style="overflow:auto;">
								<thead>
									<tr>
										<th>ID</th>
										<th>Domain</th>
										<th>Category</th>
										<th>Price</th>
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
												{{ stripslashes($d->industry->catname) }}
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
												<div class="btn-group">
													<a class="btn btn-primary btn-xs premium_popup" data-domain="{{ $d->domain }}"
														data-id="{{ $d->id }}" data-toggle="modal" data-target="#myModal3">
														<i class="glyphicon glyphicon-pencil"></i>
													</a>
													<a class="btn btn-primary btn-xs" href="/domains/{{ $d->domain }}">
														<i class="glyphicon glyphicon-eye-open"></i>
													</a>
													<a href="/dashboard/domains-overview?remove={{ $d->id }}"
														onclick="return confirm('Are you sure you want to remove this domain?');"
														class="btn btn-danger btn-xs">
														<i class="glyphicon glyphicon-remove"></i>
													</a>
												</div>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>

							{{ $domains->links() }}
						@else
							No domains listed yet now.
						@endif

					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="country">Premium Submission</label>
									<div class="">
										<select name="is_premium" id="is_premium" class="form-control">
											<option value="1">Yes</option>
											<option value="0">No</option>
										</select>
									</div>
								</div>
							</div>

						</div>


						<button type="submit" id="savepremium" name="sb-offer"
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
