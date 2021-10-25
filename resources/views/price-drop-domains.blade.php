@extends('layouts/app')
@section('seo_title') Domains List - {{ \App\Models\Options::get_option('seo_title') }} @endsection

@section('section_title', 'Price Drop Domains')

@section('content')
	<div>
		<div class="container">
			<form method="POST" action="/ajax/table_domain_filtering" id="table-ajax-search-form">
				{{ csrf_field() }}
				<div id="custom-search-input">
					<div class="col-md-2">
					</div>
					<div class="input-group col-md-8">
						<input id="input" placeholder="Find Price Drop Domains - Type a Word or Phrase" @if (!empty($autoKeyword)) value="{{ $autoKeyword }}" @endif
							name="keyword" class="form-control input-lg" />
						<span class="input-group-btn">
							<button id="tablebuttonAjaxFilter" class="btn btn-default btn-lg" type="submit">
								<i class="glyphicon glyphicon-search"></i>
							</button>
						</span>
					</div>
					<hr style="width: 761px;">
					<div class="col-md-2">
					</div>
				</div>
				<br />


		</div>
		</form>
	</div>
	<div class="clearfix"></div>

	<div class="container add-paddings-2">

		<div class="preload-search container-white">
			<h3><img src="/image/ajax.webp" alt="preloading image"> Loading domains matching your criteria..</h3>
			<div class="clearfix"></div>
		</div>

		<div class="row" id="table-ajax-filtered-domains">
			<div class="tab table-responsive">
				<table class="table" id="jsWebKitTable" cellspacing="0" id="myTable">
					<thead>
						<tr>
							<th>Domain Name</th>
							<th>Status</th>
							<th>Current Price</th>
							<th>Next Drop</th>
							<th>Days Left</th>
							<th>Time Left</th>
							<th>Buy Now</th>
							<th>Share It</th>
						</tr>
					</thead>
					<tbody>

						@foreach ($normal_domain as $norm_dom)
							<tr>
								<td><a href="{{ $norm_dom->domain }}">{{ $norm_dom->domain }}</a></td>
								<td>{{ $norm_dom->domain_status }}</td>
								@if (!is_null($norm_dom->discount) and $norm_dom->discount != 0)
									<input type="hidden" value="{{ $norm_dom->discount }}" id="hide_price{{ $norm_dom->id }}" />
									<td>
										<div class="diss{{ $norm_dom->id }}"></div>
										<div id="cls{{ $norm_dom->id }}">
											{{ App\Models\Options::get_option('currency_symbol') . number_format($norm_dom->discount, 0) }}
										</div>
									</td>
								@else
									<input type="hidden" value="{{ $norm_dom->pricing }}" id="hide_price{{ $norm_dom->id }}" />
									<td>
										<div id="diss1{{ $norm_dom->id }}"></div>
										<div id="cls{{ $norm_dom->id }}">
											{{ App\Models\Options::get_option('currency_symbol') . number_format($norm_dom->pricing, 0) }}
										</div>
									</td>
								@endif
								<td>
									<p id="timesec{{ $norm_dom->id }}"></p>
								</td>
								<td id="days{{ $norm_dom->id }}"></td>
								<td id="example{{ $norm_dom->id }}"> </td>
								<td><a class="btn btn-inverse btn-block" href="/buy/{{ $norm_dom->domain }}">Buy Now</a>
								</td>
								<td>
									<ul class="social-links colored circle-share small text-center">
										<li class="facebook">
											<a rel="nofollow" class="open-new-tab"
												href="https://www.facebook.com/sharer/sharer.php?u={{ $norm_dom->domain }}&amp;description={{ $norm_dom->description }}&amp;src='{{ '/storage/' . $norm_dom->domain_logo }}'"
												target="_blank">
												<i class="fa fa-facebook"></i>
											</a>
										</li>
										<li class="twitter">
											<a rel="nofollow" class="open-new-tab"
												href="http://twitter.com/intent/tweet?url={{ $norm_dom->domain }}&amp;text={{ $norm_dom->description }}"
												target="_blank">
												<i class="fa fa-twitter"></i>
											</a>
										</li>
										<li class="pinterest">
											<a rel="nofollow" class="open-new-tab"
												href="//pinterest.com/pin/create/link/?url={{ $norm_dom->domain }}&amp;description={{ $norm_dom->description }}&amp;src='{{ '/storage/' . $norm_dom->domain_logo }}'"
												target="_blank">
												<i class="fa fa-pinterest"></i></a>
										</li>
										<li class="linkedin">
											<a rel="nofollow" class="open-new-tab"
												href="https://www.linkedin.com/shareArticle?mini=true&url={{ $norm_dom->domain }}&amp;description={{ $norm_dom->description }}&amp;src='{{ '/storage/' . $norm_dom->domain_logo }}'"
												target="_blank">
												<i class="fa fa-linkedin"></i>
											</a>
										</li>

									</ul>
								</td>
							</tr>
							<script>
								var finalEventDt{{ $norm_dom->id }} = new Date(
									"{{ Carbon\Carbon::parse($norm_dom->end_datetime)->format('M') }} {{ Carbon\Carbon::parse($norm_dom->end_datetime)->format('d') }}, {{ Carbon\Carbon::parse($norm_dom->end_datetime)->format('Y') }} {{ Carbon\Carbon::parse($norm_dom->end_datetime)->format('H:i:s') }}"
								).getTime();

								var x = setInterval(function() {


									var now = new Date().getTime();

									delay_total = finalEventDt{{ $norm_dom->id }} - now;

									var days = Math.floor(delay_total / (1000 * 60 * 60 * 24));
									var hours = Math.floor((delay_total % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
									var minutes = Math.floor((delay_total % (1000 * 60 * 60)) / (1000 * 60));
									var seconds = Math.floor((delay_total % (1000 * 60)) / 1000);
									console.log(days, new Date());
									document.getElementById("example{{ $norm_dom->id }}").innerHTML = Math.abs(hours - (days * 24)) +
										"h " + minutes + "m " + seconds + "s ";
									document.getElementById("days{{ $norm_dom->id }}").innerHTML = days + " days ";
									if (hours <= 2) {
										document.getElementById("timesec{{ $norm_dom->id }}").innerHTML = "<span class='error_red'>" +
											"$" + "{{ $norm_dom->price_drop_value }} in " + hours + "h " + minutes + "m " + seconds +
											"s " + "</span>";
									} else {
										document.getElementById("timesec{{ $norm_dom->id }}").innerHTML = "$" +
											"{{ $norm_dom->price_drop_value }} in " + hours + "h " + minutes + "m " + seconds + "s ";
									}
									if (hours == 0 && minutes == 0 && seconds == 0) {
										console.log(days, 'dats');
										var final_price = document.getElementById('hide_price{{ $norm_dom->id }}').value -
											{{ $norm_dom->price_drop_value }}
										console.log(final_price);
										var doamin = {{ $norm_dom->id }};
										var CSRF_TOKEN = $('input[name="_token"]').val();
										$.ajax({
											url: "{{ url('/domain/update_domain_drop_price_value') }}",
											method: "POST",
											data: {
												_token: CSRF_TOKEN,
												'domain': doamin,
												'final_price': final_price,
												'days_difference': days,
												'orignal_amount': document.getElementById('hide_price{{ $norm_dom->id }}').value
											},
											success: function(data) {
												console.log(data);
												$(".diss{{ $norm_dom->id }}").html('$' + data.vaa);
												$("#text-discount{{ $norm_dom->id }}").html('$' + data.org);
												$("#diss1{{ $norm_dom->id }}").html('$' + data.vaa);
												document.getElementById('cls{{ $norm_dom->id }}').style.display = 'none';
											}
										});
									}

									if (delay_total < 0) {
										clearInterval(x);
										document.getElementById("example{{ $norm_dom->id }}").innerHTML = "EXPIRED OFFER";
										var final_price = 0;
										var doamin = {{ $norm_dom->id }};
										var CSRF_TOKEN = $('input[name="_token"]').val();
										$.ajax({
											url: "{{ url('/domain/update_domain_drop_price_value') }}",
											method: "POST",
											data: {
												_token: CSRF_TOKEN,
												'domain': doamin,
												'final_price': final_price,
												'orignal_amount': document.getElementById('hide_price{{ $norm_dom->id }}').value
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

				<div class="col-xs-12 col-xs-offset-0 col-md-4 col-md-offset-4">
					<a id="show" type="button" class="btn btn-inverse btn-block"> <i class="glyphicon glyphicon-th"></i>
						Load More</a>
				</div>
			</div>

		</div>
	</div>
	<div class="clearboth"></div>
@endsection

@push('head')
	@if (isset($autoSearch) and $autoSearch == 'yes')
		<script>
			$(function() {
				$('#ajax-search-form').trigger('submit');
			});
		</script>
	@endif
@endpush
