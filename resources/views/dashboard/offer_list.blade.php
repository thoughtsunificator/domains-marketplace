@extends('layouts/app')

@section('section_title', 'Offers')

@section('content')
	<div class="container">
		<div class="row">

			@include( 'dashboard/navi' )

			<div class="col-md-9">
				<div class="panel panel-default">
					<div class="panel-heading">Offer List</div>

					<div class="panel-body">

						@if ($alloffer)
							<table class="table table-striped table-bordered table-responsive dataTable">
								<thead>
									<tr>
										<th>Domain</th>
										<th>Name</th>
										<th>Email</th>
										<th>Offer Price</th>
										<th>DateTime</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($alloffer as $alloffer)
										<tr>

											<td>
												<a href="/{{ $alloffer->domain }}" target="_blank">
													{{ $alloffer->domain }}
												</a>

											</td>
											<td>
												{{ $alloffer->user_name }}
											</td>
											<td>
												{{ $alloffer->email }}
											</td>
											<td>
												${{ $alloffer->offer_price }}
											</td>

											<td>
												{{ $alloffer->datetime }}
											</td>
											<td>
												<div class=" ">
													<a data-toggle="modal" data-id="{{ $alloffer->id }}"
														data-domain_name="{{ $alloffer->domain }}" data-name="{{ $alloffer->user_name }}"
														data-phone="{{ $alloffer->phone_no }}" data-email="{{ $alloffer->email }}"
														data-offer="{{ $alloffer->offer_price }}" data-remark="{{ $alloffer->remarks }}"
														class="offer_listing" data-target="#modal-default"><i
															class="glyphicon glyphicon-eye-open"></i></a> ||

													<a href="/dashboard/offer_list_delete/{{ $alloffer->id }}"
														onclick="return confirm('Are you sure you want to remove this Offer?');"
														class="btn btn-danger btn-xs">
														<i class="glyphicon glyphicon-remove"></i>
													</a>
												</div>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>


						@else
							No Offer Availble.
						@endif

					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="modal fade" id="modal-default" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"></h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<table class="table">
						<thead>
							<tr>
								<th scope="col"> </th>
								<th scope="col"> </th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th scope="row">Domain Name</th>
								<td class="domains"></td>
							</tr>
							<tr class="table-primary">
								<th scope="row">Name</th>
								<td class="username"></td>
							</tr>
							<tr class="table-primary">
								<th scope="row">Phone#</th>
								<td class="phone"></td>
							</tr>
							<tr class="table-primary">
								<th scope="row">Email</th>
								<td class="email"></td>
							</tr>
							<tr class="table-primary">
								<th scope="row">Offer</th>
								<td class="offer"></td>
							</tr>
							<tr class="table-primary">
								<th scope="row">Remarks</th>
								<td class="remark"></td>
							</tr>
						</tbody>
					</table>

				</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

				</div>
			</div>
		</div>
	</div>
@endsection
