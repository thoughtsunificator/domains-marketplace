@extends('layouts/app')
@section('seo_title') {{ ucfirst($domain->domain) }} - {{ \App\Models\Options::get_option('seo_title') }}
@endsection

@section('section_title', 'This domain is available for sale')

@section('content')
	<div class="container-fluid domain-info-fluid">
		<br>
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-md-8">
					<div class="card-box">
						<div class="section-title">
							@if (!is_null($domain->youtube_video_id))
								<div class="text-center embed-responsive embed-responsive-16by9">
									<iframe class="embed-responsive-item"
										src="https://www.youtube.com/embed/{{ $domain->youtube_video_id }}?autoplay=1&amp;mute=1&amp;enablejsapi=1"
										allow="autoplay"></iframe>
							</div>@else
								<div class="row"></div>@endif @if (!empty($domain->description))
									<h3 class="text-white">Description</h3>
									<hr>
								@endif
								{!! clean(nl2br($domain->description)) !!}
								<ul class="social-links colored circle-share small text-center">
									<li class="facebook">
										<a rel="nofollow" class="open-new-tab"
											href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::fullUrl()) }}&amp;description={{ $domain->description }}&amp;src='{{ '/storage/' . $domain->domain_logo }}'"
											target="_blank"></a>
									</li>
									<li class="twitter">
										<a rel="nofollow" class="open-new-tab"
											href="http://twitter.com/intent/tweet?url={{ urlencode(Request::fullUrl()) }}&amp;text={{ $domain->description }}"
											target="_blank"></a>
									</li>
									<li class="pinterest">
										<a rel="nofollow" class="open-new-tab"
											href="//pinterest.com/pin/create/link/?url={{ urlencode(Request::fullUrl()) }}&amp;description={{ $domain->description }}&amp;src='{{ '/storage/' . $domain->domain_logo }}'"
											target="_blank"></a>
									</li>
									<li class="linkedin">
										<a rel="nofollow" class="open-new-tab"
											href="https://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(Request::fullUrl()) }}&amp;description={{ $domain->description }}&amp;src='{{ '/storage/' . $domain->domain_logo }}'"
											target="_blank"></a>
									</li>
								</ul>
						</div>
						<h3 class="text-white">Related Keywords</h3>
						<hr>
						@foreach (explode(',', $domain->keywords) as $key => $value) <a class="btn btn-sm btn-white font-13" href="#">{{ $value }}</a> @endforeach
					</div>
				</div>
				<div class="col-xs-12 col-md-4">
					@if ($domain->is_premium)
						<div class="domain-ribbon">
							Premium
						</div>@endif @if ($domain->price_drop)
							<div class="card-box">
								<p id="example" style="text-align: center;"></p>
								<h3 class="text-white" style="text-align: center;">Buy {{ $domain->domain }}</h3>
								<hr>
								<div class="name-page-price" style="text-align: center;">
									@if (!is_null($domain->discount) and $domain->discount != 0)
										<div class="update_price">
											<input type="hidden" value="{{ $domain->discount }}" id="hide_price"> <strike
												class="text-discount">{{ \App\Models\Options::get_option('currency_symbol') . number_format($domain->pricing, 0) }}</strike>
											<div class="diss"></div>
											<div id="cls">
												{{ App\Models\Options::get_option('currency_symbol') . number_format($domain->discount, 0) }}
											</div>
									</div>@else <input type="hidden" value="{{ $domain->pricing }}" id="hide_price">
										<strike id="text-discount"></strike>
										<div id="diss1"></div>
										<div id="cls">
											{{ App\Models\Options::get_option('currency_symbol') . number_format($domain->pricing, 0) }}
										</div>
									@endif
								</div>
								<hr>
								<div class="row">
									<div class="col-xs-12 col-md-12">
										@if ($domain->pricing > 0) <a class="btn btn-success btn-block" href="/buy/{{ $domain->domain }}">Buy Now</a> @endif
									</div>
								</div>
						</div>@else
							<div class="card-box">
								<h3 class="text-white">Buy {{ $domain->domain }}</h3>
								<hr>
								<div class="name-page-price">
									@if (!is_null($domain->discount) and $domain->discount != 0)
										<div class="update_price">
											<input type="hidden" value="{{ $domain->discount }}" id="hide_price"> <strike
												class="text-discount">{{ \App\Models\Options::get_option('currency_symbol') . number_format($domain->pricing, 0) }}</strike>
											{{ App\Models\Options::get_option('currency_symbol') . number_format($domain->discount, 0) }}
									</div>@else <input type="hidden" value="{{ $domain->pricing }}" id="hide_price">
										{{ App\Models\Options::get_option('currency_symbol') . number_format($domain->pricing, 0) }}
										@endif @if (!is_null($domain->discount) and $domain->discount != 0)
											<div class="name-page-price-or">
												<span>or</span>
										</div>@else
											<div class="name-page-price-ors">
												<span>or</span>
											</div>
										@endif
										<div class="name-page-price-monthly">
											{{ \App\Models\Options::get_option('currency_symbol') . number_format($domain->pricing / 12, 0) }}/month
											<div class="name-page-price-per-month">
												PER MONTH
											</div>
										</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-xs-12 col-md-12">
										<a class="btn btn-primary btn-block" href="#make-offer" data-toggle="modal"
											data-target="#myModal">Offer</a> @if ($domain->pricing > 0) <a class="btn btn-success btn-block" href="/buy/{{ $domain->domain }}">Buy Now</a> @endif @if ('Yes' == $domain->user->financingEnabled and $domain->pricing != 0) <a class="btn btn-warning btn-block" href="#make-offer" data-toggle="modal" data-target="#myModal2">Finance</a> @endif
									</div>
								</div>
							</div>
						@endif
						<div class="card-box">
							<h3 class="text-white">Questions?</h3>
							<hr>
							<div class="name-page-contact">
								<a href="tel:07731570743"><span>07731570743</span></a>
							</div>
							<div class="name-page-contact">
								<a href="mailto:sales@domain-marketplace"><span>sales@domain-marketplace</span></a>
							</div>
						</div>
						<div class="card-box">
							<h3 class="text-white">Suggested Categories</h3>
							<hr>
							@foreach ($categories as $cat) <a class="btn btn-sm btn-white font-13" href="/category/{{ $cat->catID }}">{{ $cat->catname }}</a> @endforeach
						</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<div class="row">
							<div class="col-xs-12">
								<h3 class="modal-title">{{ $domain->domain }} Offer</h3><button type="button" class="close"
									data-dismiss="modal">×</button>
							</div>
						</div>
					</div>
					<div class="modal-body">
						<div class="make-offer-form-div">
							<h5>Make your best offer for this domain to see if the seller will accept it. There are no other
								hidden fees or charges.</h5>
							<form method="post" action="/domain_web/make-offer" id="make-offer" name="make-offer">
								{{ csrf_field() }} <input type="hidden" name="domainId" value="{{ $domain->id }}">
								<div class="hp-css">
									<label>Message</label> <input type="text" name="mkofmessage" class="form-control">
								</div>
								<dl>
									<dd>
										<div class="input-group">
											<input type="text" class="form-control" name="offer-price" placeholder="Amount (USD)">
										</div><br>
										<div class="input-group">
											<input type="text" class="form-control" name="offer-name" placeholder="Full Name">
										</div><br>
										<div class="input-group">
											<input type="text" class="form-control" name="offer-email" placeholder="Email">
										</div><br>
										<div class="input-group">
											<input type="text" class="form-control" name="offer-phone" placeholder="Phone Number">
										</div><br>
										<textarea rows="5" class="form-control" id="message" style="min-width: 100%" name="offer-message"
											placeholder="Your Message"></textarea><br>
										<h5>Your offer is legally binding for 7 days. <a href="/contact">Contact us</a> at
											any time for questions.</h5><button type="submit" name="sb-offer"
											class="btn btn-lg btn-primary btn-block">Submit Offer</button>
									</dd>
								</dl>
							</form>
						</div>
						<div class="make-offer-result"></div>
					</div>

				</div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div class="row">
						<div class="col-xs-12">
							<h3 class="modal-title">{{ $domain->domain }} Payment</h3><button type="button" class="close"
								data-dismiss="modal">×</button>
						</div>
					</div>
				</div>
				<div class="modal-body">
					<div class="make-financing-div">
						<h5>Purchase this domain name with a <strong>one-time payment</strong>. The delivery is guaranteed
							or we'll send you a full refund back.</h5><strong>Amount:@if (!is_null($domain->discount) and $domain->discount != 0) <strike class="text-discount">{{ \App\Models\Options::get_option('currency_symbol') . number_format($domain->pricing, 0) }}</strike> {{ App\Models\Options::get_option('currency_symbol') . number_format($domain->discount, 0) }} @else {{ App\Models\Options::get_option('currency_symbol') . number_format($domain->pricing, 0) }} @endif</strong><br>
						<a class="btn btn-warning btn-autosize" href="/checkout/credit-card?domain={{ $domain->domain }}">Credit
							Card</a> <a class="btn btn-warning btn-autosize"
							href="/checkout/paypal?domain={{ $domain->domain }}">Paypal</a> <a class="btn btn-warning btn-autosize"
							href="/checkout/escrow?domain={{ $domain->domain }}">Escrow</a> <a class="btn btn-warning btn-autosize"
							href="">Bank Wire</a><br>
						<div class="hr-or">
							<span>or</span>
						</div>
						<h5>Pay for this domain using <strong>monthly installments</strong>. We'll hold it in escrow until
							it's paid off. But you can start using it right away!</h5>
						<table class="m-t-15">
							<tbody>
								<tr>
									<td><b>Installments:</b>&nbsp;</td>
									<td><select id="installment-select" name="financing-months" class="form-control" style="width:auto;">
											<option value="3" selected="selected">
												3
											</option>
											<option value="6">
												6
											</option>
											<option value="12">
												12
											</option>
											<option value="24">
												24
											</option>
											<option value="36">
												36
											</option>
										</select></td>
								</tr>
								<tr>
									<td height="5"></td>
									<td></td>
								</tr>
								<tr>
									<td><b>Amount:</b>&nbsp;</td>
									<td>
										<h5>
											{{ \App\Models\Options::get_option('currency_symbol') . number_format($domain->pricing / 12, 0) }}/month
										</h5>
									</td>
								</tr>
							</tbody>
						</table>
						<h5>All payments will be final and non-refundable once the domain is in escrow.</h5><button type="submit"
							name="sb-financing" class="btn btn-lg btn-warning btn-block">Setup Payment
							Plan</button>
						<h5 style="text-align: center;">Have Questions?<a href="/contact">Contact us</a> at any time.</h5>
						<br>
					</div>
					<div class="make-financing-result"></div>
				</div>

			</div>
		</div>
	</div>
	<div class="container add-paddings-home">
		<div class="col-xs-12 col-xs-offset-0">
			<br>
			<div class="text-center">
				<h3>Similar Domains</h3>
			</div>
			<br>
			<br>
			<div class="row list">
				<?php $i = 0; ?>@foreach ($domain_list as $d) <?php $i++; ?>
					<div class="col-xs-12 col-md-3 view_more_product">
						<div class="col-listings">
							@if ($d->is_premium)
								<div class="domain-ribbon">
									Premium
								</div>@endif @if ('Yes' == \App\Models\Options::get_option('enable_logos'))
									<div class="domain-logo-width">
										<center>
											<a href="/domains/{{ $d->domain }}"><img src="{{ $d->domain_logo ? "/storage/".$d->domain_logo : "/image/default-logo.webp" }}"
													alt="{{ $d->domain }} logo" class="img-responsive domain-logo"></a>
										</center>
									</div>
								@endif
								@if ('Yes' == \App\Models\Options::get_option('enable_shortdesc'))
								@if ('Yes' == \App\Models\Options::get_option('enable_logos'))
								@endif
								@endif

								<div class="domain-desc">
									<div class="row">
										<div class="col-xs-8 col-md-8">
											<h5 class="domain-price"><a href="/domains/{{ $d->domain }}">{{ $d->domain }}</a></h5>
										</div>
										<div class="col-xs-4 col-md-4">
										<h5 class="domain-price">@if ($d->pricing > 0 and $d->domain_status == 'AVAILABLE')
											@if (!is_null($d->discount) and $d->discount != 0)
											<strike class="text-discount">{{ \App\Models\Options::get_option('currency_symbol') . number_format($d->pricing, 0) }}</strike>
											{{ App\Models\Options::get_option('currency_symbol') . number_format($d->discount, 0) }}
											@else {{ App\Models\Options::get_option('currency_symbol') . number_format($d->pricing, 0) }}
											@endif
											@elseif( $d->domain_status== 'SOLD' )
											@if ($d->pricing > 0)
												{{ App\Models\Options::get_option('currency_symbol') . number_format($d->pricing, 0) }}
											@endif
											<strong>SOLD</strong>
											@else Open to Offers
								@endif
							</h5>
						</div>
					</div>
			</div>
			<div class="clearboth"></div>
		</div>
	</div>
	@if ($i % 4 == 0)
		<div class="clearboth"></div>
	@endif
	@endforeach
	</div>
	</div>
	</div><br>
	<br>
	<div class="container">
		<div class="col-xs-12 col-xs-offset-0 col-md-4 col-md-offset-4">
			<a href="#" class="btn btn-inverse btn-block load-more">VIEW MORE NAMES</a>
		</div><br>
		<br>
	</div>
	<hr>
	<div class="container-white">
		<div class="container">
			<div class="col-xs-12 col-md-7">
				<h3>Summary</h3>
				<div class="table-responsive">
					@if ($domain->domain_history) @endif
					<table class="table table-bordered table-responsive">
						<tr>
							<td class="theading">Registered On</td>
							<td>{{ date('jS F Y', strtotime($domain->reg_date)) }}</td>
						</tr>
						<tr>
							<td class="theading">Registrar</td>
							<td>{{ $domain->registrar }}</td>
						</tr>
						<tr>
							<td class="theading">Domain Age</td>
							<td>@if ($domain->domain_age != 0) {{ $domain->domain_age }} Years Old @else Less than 1 Year Old @endif</td>
						</tr>
						<tr>
							<td class="theading">Domain Category</td>
							<td>{{ stripslashes($domain->industry->catname) }}</td>
						</tr>
						<tr>
							<td class="theading">Domain Wbm History</td>
							<td>
								<a href="https://web.archive.org/web/*/{{ $domain->domain }}">Archive: Click Here</a>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="col-xs-12 col-md-5">
				@if (App\Models\Options::get_option('enable_logos'))<br>
					<center>
						<img src="{{ $domain->domain_logo ? "/storage/".$domain->domain_logo : "/image/default-logo.webp" }}" alt="{{ $domain->domain }} logo" class="img-responsive">
				</center>@else &nbsp;
				@endif
			</div>
		</div>

	</div>

@endsection

<script>
	if (document.getElementById("example")) {
		var finalEventDt = new Date(
			"{{ Carbon\Carbon::parse($domain->end_datetime)->format('M') }} {{ Carbon\Carbon::parse($domain->end_datetime)->format('d') }}, {{ Carbon\Carbon::parse($domain->end_datetime)->format('Y') }} {{ Carbon\Carbon::parse($domain->end_datetime)->format('H:i:s') }}"
		).getTime();

		var x = setInterval(function() {

			//   var now = new Date("{{ Carbon\Carbon::parse($domain->start_datetime)->format('Y-m-d H:i:s') }}").getTime();
			var now = new Date().getTime();

			var delay_total = finalEventDt - now;

			var days = Math.floor(delay_total / (1000 * 60 * 60 * 24));
			var hours = Math.floor((delay_total % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
			var minutes = Math.floor((delay_total % (1000 * 60 * 60)) / (1000 * 60));
			var seconds = Math.floor((delay_total % (1000 * 60)) / 1000);
			// console.log(document.getElementById('hide_price').value);

			if (hours == 0 && minutes == 0 && seconds == 0) {
				var final_price = document.getElementById('hide_price').value - "{{ $domain->price_drop_value }}"
				console.log("zeshan", final_price);
				var doamin = {{ $domain->id }};
				var CSRF_TOKEN = $('input[name="_token"]').val();
				$.ajax({
					url: "{{ url('/domain/update_domain_drop_price_value') }}",
					method: "POST",
					data: {
						_token: CSRF_TOKEN,
						'domain': doamin,
						'final_price': final_price,
						'days_difference': days,
						'orignal_amount': document.getElementById('hide_price').value
					},
					success: function(data) {
						console.log(data);
						$(".diss").html('$' + data.vaa);

						$("#text-discount").html('$' + data.org);

						$("#diss1").html('$' + data.vaa);
						document.getElementById('cls').style.display = 'none';
					}
				});
			}
			document.getElementById("example").innerHTML = "<span class='glyphicon glyphicon-time'></span> " +
				days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

			if (delay_total < 0) {
				clearInterval(x);
				document.getElementById("example").innerHTML = "EXPIRED OFFER";
				var final_price = 0;
				var doamin = {{ $domain->id }};
				var CSRF_TOKEN = $('input[name="_token"]').val();
				$.ajax({
					url: "{{ url('/domain/update_domain_drop_price_value') }}",
					method: "POST",
					data: {
						_token: CSRF_TOKEN,
						'domain': doamin,
						'final_price': final_price,
						'orignal_amount': document.getElementById('hide_price').value
					},
					success: function(data) {
						console.log(data);
						location.reload();
					}
				});
			}
		}, 1000);
	}
</script>
