@extends('layouts/app')

@section('section_title', 'Manage Domain')

@section('content')
	<div class="container">
		<div class="row">

			@include( 'dashboard/navi' )

			<div class="col-md-9">
				<div class="panel panel-default">
					<div class="panel-heading">Welcome to Price Drop Overview (View and Delete) Your Live Price Drop Domains
					</div>

					<div class="panel-body">

						@if ($domains->count())
							<div class="input-group"> <span class="input-group-addon">Filter</span>

								<input id="filter" type="text" class="form-control" placeholder="Type here...">
							</div>
							<br>
							<table class="table table-striped table-bordered table-responsive dataTable">
								<thead>
									<tr>
										<th>Domain</th>
										<th>Price</th>
										<th>Next Drop</th>
										<th>Time Left</th>
										<th>Actions</th>
										<th>Share It</th>
									</tr>
								</thead>
								<tbody class="searchable">
									@foreach ($domains as $d)
										<tr>
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
												{{ App\Models\Options::get_option('currency_symbol') . number_format($d->discount, 0) }}
											</td>
											<td id="timesec{{ $d->id }}"></td>
											<td id="example{{ $d->id }}"> </td>


											<td>
												<div class="btn-group">
													<a class="btn btn-primary btn-xs" href="/dashboard/manage-pricedrop-domain/{{ $d->domain }}">
														<i class="glyphicon glyphicon-eye-open"></i>
													</a>
													<a href="/dashboard/price-drop-overview?remove={{ $d->id }}"
														onclick="return confirm('Are you sure you want to remove this domain?');"
														class="btn btn-danger btn-xs">
														<i class="glyphicon glyphicon-remove"></i>
													</a>
												</div>
											</td>
											<td>
												<ul class="social-links colored circle-share small text-center">
													<li class="facebook">
														<a rel="nofollow" class="open-new-tab"
															href="https://www.facebook.com/sharer/sharer.php?u={{ $d->domain }}&amp;description={{ $d->description }}&amp;src='./domain-logos/{{ $d->domain_logo }}'"
															target="_blank">
															<i class="fa fa-facebook"></i>
														</a>
													</li>
													<li class="twitter">
														<a rel="nofollow" class="open-new-tab"
															href="http://twitter.com/intent/tweet?url={{ $d->domain }}&amp;text={{ $d->description }}"
															target="_blank">
															<i class="fa fa-twitter"></i>
														</a>
													</li>
													<li class="pinterest">
														<a rel="nofollow" class="open-new-tab"
															href="//pinterest.com/pin/create/link/?url={{ $d->domain }}&amp;description={{ $d->description }}&amp;src='./domain-logos/{{ $d->domain_logo }}'"
															target="_blank">
															<i class="fa fa-pinterest"></i></a>
													</li>

													<li class="linkedin">
														<a rel="nofollow" class="open-new-tab"
															href="https://www.linkedin.com/shareArticle?mini=true&url={{ $d->domain }}&amp;description={{ $d->description }}&amp;src='./domain-logos/{{ $d->domain_logo }}'"
															target="_blank">
															<i class="fa fa-linkedin"></i>
														</a>
													</li>
												</ul>
											</td>
										</tr>
										<script>
											var finalEventDt{{ $d->id }} = new Date(
												"{{ Carbon\Carbon::parse($d->end_datetime)->format('M') }} {{ Carbon\Carbon::parse($d->end_datetime)->format('d') }}, {{ Carbon\Carbon::parse($d->end_datetime)->format('Y') }} {{ Carbon\Carbon::parse($d->end_datetime)->format('H:i:s') }}"
											).getTime();

											var x = setInterval(function() {


												var now = new Date().getTime();

												delay_total = finalEventDt{{ $d->id }} - now;

												var days = Math.floor(delay_total / (1000 * 60 * 60 * 24));
												var hours = Math.floor((delay_total % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
												var minutes = Math.floor((delay_total % (1000 * 60 * 60)) / (1000 * 60));
												var seconds = Math.floor((delay_total % (1000 * 60)) / 1000);
												console.log(days, new Date());
												document.getElementById("example{{ $d->id }}").innerHTML = Math.abs(hours - (days * 24)) +
													"h " + minutes + "m " + seconds + "s ";
												document.getElementById("timesec{{ $d->id }}").innerHTML = "$" +
													"{{ $d->price_drop_value }} in " + hours + "h " + minutes + "m " + seconds + "s";
												if (hours == 0 && minutes == 0 && seconds == 0) {
													console.log(days, 'dats');
													var final_price = document.getElementById('hide_price{{ $d->id }}').value -
														{{ $d->price_drop_value }}
													console.log(final_price);
													var doamin = {{ $d->id }};
													var CSRF_TOKEN = $('input[name="_token"]').val();
													$.ajax({
														url: "{{ url('/domain/update_domain_drop_price_value') }}",
														method: "POST",
														data: {
															_token: CSRF_TOKEN,
															'domain': doamin,
															'final_price': final_price,
															'days_difference': days,
															'orignal_amount': document.getElementById('hide_price{{ $d->id }}').value
														},
														success: function(data) {
															console.log(data);
															$(".diss{{ $d->id }}").html('$' + data.vaa);
															$("#text-discount{{ $d->id }}").html('$' + data.org);
															$("#diss1{{ $d->id }}").html('$' + data.vaa);
															document.getElementById('cls{{ $d->id }}').style.display = 'none';
														}
													});
												}

												if (delay_total < 0) {
													clearInterval(x);
													document.getElementById("example{{ $d->id }}").innerHTML = "EXPIRED OFFER";
													var final_price = 0;
													var doamin = {{ $d->id }};
													var CSRF_TOKEN = $('input[name="_token"]').val();
													$.ajax({
														url: "{{ url('/domain/update_domain_drop_price_value') }}",
														method: "POST",
														data: {
															_token: CSRF_TOKEN,
															'domain': doamin,
															'final_price': final_price,
															'orignal_amount': document.getElementById('hide_price{{ $d->id }}').value
														},
														success: function(data) {
															console.log(data);
															location.reload();
														}
													});
												}

											}, 1000);
										</script>

									@endforeach
								</tbody>
							</table>

							{{ $domains->links() }}
						@endif

					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
