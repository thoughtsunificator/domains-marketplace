@extends('layouts/app')

@section('section_title', 'Manage Domain')

@section('content')
	<div class="container">
		<div class="row">

			@include( 'dashboard/navi' )

			<div class="col-md-9">
				<div class="panel panel-default">
					<div class="panel-heading">Domains Overview</div>

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
												@if (!$d->is_approved)
													<a href="#" class="text-danger">Not approved</a>
												@else
													<span class="text-success">Approved</span>
												@endif
											</td>

											<td>
												<div class="btn-group">
													<a class="btn btn-primary btn-xs" href="/dashboard/manage-domain/{{ $d->domain }}">
														<i class="glyphicon glyphicon-pencil"></i>
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
							No domains listed yet. <a href="/dashboard/domains/add">Add a domain</a> now.
						@endif

					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
