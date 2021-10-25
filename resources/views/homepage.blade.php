@extends('layouts/app')

@section('content')
	<div class="homepage-img">
		<div class="container">
			<div id="load_updates1">
				<div class="row">
					<div class="col-md-12">
						<div class="d-flex justify-content-between align-items-center breaking-news bg-white">
							<marquee class="news-scroll" behavior="scroll" direction="left" onmouseover="this.stop();"
								scrollamount="10" onmouseout="this.start();">
								@foreach ($premium_domain as $premium_dom)
									<span class="dot"></span> <a href="/domains/{{ $premium_dom->domain }}">{{ $premium_dom->domain }}</a>
								@endforeach
							</marquee>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-home">
			<div class="container">
				<div class="section-title">
					<h1 class="text-center text-white">
						{{ \App\Models\Options::get_option('homepage_headline') }}
					</h1>
					<p class="lead text-center">{{ \App\Models\Options::get_option('homepage_intro') }}</p>
					<div class="separator-3"></div>
					<br />
					<div class="col-md-8  col-sm-offset-2 col-xs-12">
						<form action="/domains" method="get">
							<div id="custom-search-input">
								<div class="input-group col-md-12">
									<input type="text" name="keyword" class="form-control input-lg" placeholder="Domain or keyword"
										required />
									<span class="input-group-btn">
										<button class="btn btn-default btn-lg" type="submit">
											<i class="glyphicon glyphicon-search"></i>
										</button>
									</span>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="visible-xs-block">&nbsp;<br></div>
	</div>
	<div class="container add-paddings-home">
		<div class="col-xs-12 col-xs-offset-0">
			<br />
			<div class="text-center">
				<h1>
					{{ \App\Models\Options::get_option('homepage_title', 'Config Text in admin->configuration->Homepage Title') }}
				</h1>
				{!! clean(nl2br(\App\Models\Options::get_option('homepage_text', 'Config in admin->configuration->Homepage Subtitle'))) !!}
			</div>
			<br />
			<br />
			<div class="row">
				<?php $i = 0; ?>
				@foreach ($domains as $d)
					<?php $i++; ?>
					<div class="col-xs-12 col-md-3">
						<div class="col-listings">
							@if ($d->is_premium)
								<div class="domain-ribbon">Premium</div>
							@endif
							@if ('Yes' == \App\Models\Options::get_option('enable_logos'))
								<div class="domain-logo-width">
									<center> <a href="/domains/{{ $d->domain }}">
											<img src="{{ $d->domain_logo ? "/storage/".$d->domain_logo : "/image/default-logo.webp" }}" alt="{{ $d->domain }} logo"
												class="img-responsive domain-logo" />
										</a>
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
										<h5 class="domain-price">
											@if ($d->pricing > 0 and $d->domain_status == 'AVAILABLE')
												@if (!is_null($d->discount) and $d->discount != 0)
													<strike
														class="text-discount">{{ \App\Models\Options::get_option('currency_symbol') . number_format($d->pricing, 0) }}</strike>
													{{ App\Models\Options::get_option('currency_symbol') . number_format($d->discount, 0) }}
												@else
													{{ App\Models\Options::get_option('currency_symbol') . number_format($d->pricing, 0) }}
												@endif
											@elseif( $d->domain_status == 'SOLD' )
												@if ($d->pricing > 0)
													{{ App\Models\Options::get_option('currency_symbol') . number_format($d->pricing, 0) }}
												@endif
												<strong>SOLD</strong>
											@else
												Open to Offers
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
		<br />
		<br />
		<div class="container">
			<div class="col-xs-12 col-xs-offset-0 col-md-4 col-md-offset-4">
				<a href="/domains" class="btn btn-inverse btn-block"> <i class="glyphicon glyphicon-th"></i> VIEW ALL DOMAINS </a>
			</div>
			<br />
			<br />
		</div>
		<div class="container-fluid container-white">
			<div class="container footer footer-home">
				<div class="site-wrapper">
					<section class="tabs-wrapper">
						<div class="tabs-container">
							<div class="tabs-block">
								<div class="tabs">
									<input type="radio" name="tabs" id="tab1" checked="checked" />
									<label for="tab1"><span class="material-icons">Premium Domains</span></label>
									<div class="tab table-responsive">

										<table class="table" id="jsWebKitTable1" cellspacing="0">
											<thead>
												<tr>
													<th>Domain Name</th>
													<th>Price</th>
													<th>Finance</th>
													<th>Offer</th>
													<th>Buy Now</th>
												</tr>
											</thead>
											<tbody>
												@foreach ($premium_domain as $premium_dom)
													<tr>
														<td><a href="/domains/{{ $premium_dom->domain }}">{{ $premium_dom->domain }}</a>
														</td>
														<td>
															{{ App\Models\Options::get_option('currency_symbol') . number_format($premium_dom->pricing, 0) }}
														</td>
														<td><a class="btn btn-inverse btn-block offer" href="#"
																data-domain="{{ $premium_dom->domain }}"
																data-amount="{{ App\Models\Options::get_option('currency_symbol') . number_format($premium_dom->pricing, 0) }}"
																data-installment="{{ \App\Models\Options::get_option('currency_symbol') . number_format($premium_dom->pricing / 12, 0) }}"
																data-toggle="modal" data-target="#myModal2">Finance</a></td>
														<td><a class="btn btn-inverse btn-block damain_name" href="#"
																data-domain="{{ $premium_dom->domain }}" data-id="{{ $premium_dom->id }}"
																data-toggle="modal" data-target="#myModal">Offer</a></td>
														<td><a class="btn btn-inverse btn-block" href="/buy/{{ $premium_dom->domain }}">Buy Now</a>
														</td>
													</tr>
												@endforeach
											</tbody>
										</table>
										<div class="col-xs-12 col-xs-offset-0 col-md-4 col-md-offset-4">
											<a id="show1" type="button" class="btn btn-inverse btn-block"> <i
													class="glyphicon glyphicon-th"></i> Load More</a>
										</div>
									</div>

									<input type="radio" name="tabs" id="tab2" />
									<label for="tab2"><span class="material-icons">Price Drop</span></label>
									<div class="tab table-responsive">
										<table class="table" id="jsWebKitTable" cellspacing="0" id="myTable">
											<thead>
												<tr>
													<th>Domain Name</th>
													<th>Current Price</th>
													<th>Next Drop</th>
													<th>Days Left</th>
													<th>Hours Left</th>
													<th>Buy Now</th>
												</tr>
											</thead>
											<tbody>

												@foreach ($normal_domain as $norm_dom)
													<tr>
														<td><a href="/domains/{{ $norm_dom->domain }}">{{ $norm_dom->domain }}</a>
														</td>
														@if (!is_null($norm_dom->discount) and $norm_dom->discount != 0)
															<input type="hidden" value="{{ $norm_dom->discount }}"
																id="hide_price{{ $norm_dom->id }}" />
															<td>
																<div class="diss{{ $norm_dom->id }}"></div>
																<div id="cls{{ $norm_dom->id }}">
																	{{ App\Models\Options::get_option('currency_symbol') . number_format($norm_dom->discount, 0) }}
																</div>
															</td>
														@else
															<input type="hidden" value="{{ $norm_dom->pricing }}"
																id="hide_price{{ $norm_dom->id }}" />
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
															console.log(new Date());
															document.getElementById("example{{ $norm_dom->id }}").innerHTML = Math.abs(hours - (days * 24)) +
																"h " + minutes + "m " + seconds + "s ";
															document.getElementById("days{{ $norm_dom->id }}").innerHTML = days + " days ";
															if (hours <= 1) {
																document.getElementById("timesec{{ $norm_dom->id }}").innerHTML = "<span class='error_red'>" +
																	"$" + "{{ $norm_dom->price_drop_value }} in " + hours + "h " + minutes + "m " + seconds +
																	"s " + "</span>";
															} else {
																document.getElementById("timesec{{ $norm_dom->id }}").innerHTML = "$" +
																	"{{ $norm_dom->price_drop_value }} in " + hours + "h " + minutes + "m " + seconds + "s ";
															}
															if (hours == 0 && minutes == 0 && seconds == 0) {
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
																		//      console.log(data);
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

									<input type="radio" name="tabs" id="tab3" />
									<label for="tab3"><span class="material-icons">Recent Sales</span></label>
									<div class="tab table-responsive">
										<table class="table" id="jsWebKitTable3" cellspacing="0">
											<thead>
												<tr>
													<th>Domain Name</th>
													<th>Price</th>
													<th>Sold</th>
												</tr>
											</thead>
											<tbody>
												@foreach ($sold_domain as $d)
													<tr>
														<td><a href="/domains/{{ $d->domain }}">{{ $d->domain }}</a></td>
														<td> <strike
																class="text-discount">{{ App\Models\Options::get_option('currency_symbol') . number_format($d->pricing, 0) }}</strike>
														</td>
														<td><a class="btn btn-inverse btn-block sold_domain" href="#"
																data-domain="{{ $d->domain }}"
																data-price="{{ App\Models\Options::get_option('currency_symbol') . number_format($d->pricing, 0) }}"
																data-description="{{ $d->description }}"
																data-register="{{ date('jS F Y', strtotime($d->reg_date)) }}"
																data-registrar="{{ $d->registrar }}"
																data-category="{{ stripslashes($d->industry->catname) }}"
																data-age="@if ($d->domain_age != 0) {{ $d->domain_age }} Years Old @else Less than 1 Year Old @endif" data-toggle="modal"
																data-target="#myModalSold">Information</a></td>
													</tr>
												@endforeach
											</tbody>
										</table>
										<div class="col-xs-12 col-xs-offset-0 col-md-4 col-md-offset-4">
											<a id="show3" type="button" class="btn btn-inverse btn-block"> <i
													class="glyphicon glyphicon-th"></i> Load More</a>
										</div>
									</div>


								</div>
							</div>
						</div>
					</section>

				</div>
			</div>
		</div>

		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<div class="row">

							<div class="col-xs-12">
								<h3 class="modal-title offer_model"></h3>
								<button type="button" class="close" data-dismiss="modal">×</button>
							</div>
						</div>
					</div>
					<div class="modal-body">

						<div class="make-offer-form-div">
							<h5>Make your best offer for this domain to see if the seller will accept it. There are no other
								hidden fees or charges.</h5>
							<form method="POST" action="/make-offer" id="make-offer">
								{{ csrf_field() }}
								<input type="hidden" name="domainId" id="domainId" value="">
								<div class="hp-css">
									<label>Message</label>
									<input type="text" name="mkofmessage" class="form-control">
								</div>
								<dl>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-usd"></i></span>
										<input type="text" class="form-control" name="offer-price" placeholder="Amount (USD)">

									</div> <br>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-user"></i></span>
										<input type="text" class="form-control" name="offer-name" placeholder="Full Name">

									</div><br>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
										<input type="text" class="form-control" name="offer-email" placeholder="Email">

									</div><br>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-phone"></i></span>
										<input type="text" class="form-control" name="offer-phone" placeholder="Phone Number">

									</div><br>
									<textarea rows="5" class="form-control" id="message" style="min-width: 100%" name="offer-message"
										placeholder="Your Message"></textarea>
									<br>

									<h5>Your offer is legally binding for 7 days. <a href="/contact">Contact us</a> at any
										time for questions.</h5>
									<button type="submit" name="sb-offer" class="btn btn-lg btn-primary btn-block">Submit
										Offer</button>
							</form>
						</div>
						<div class="make-offer-result"></div>
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

						<div class="make-financing-div">
							<h5>Purchase this domain name with a <strong>one-time payment</strong>. The delivery is
								guaranteed or we'll send you a full refund back.</h5>
							<strong>Amount: <span class="amount-model"></span></strong> <br>
							<a class="btn btn-warning btn-autosize" id="credit-card" href="#"><i class="fa fa-credit-card"></i> Credit
								Card</a>
							<a class="btn btn-warning btn-autosize" id="paypal-card" href=""><i class="fa fa-paypal"></i> Paypal</a>
							<a class="btn btn-warning btn-autosize" id="escrow-card" href=""><i class="fa fa-shield"></i> Escrow</a>
							<a class="btn btn-warning btn-autosize" href=""><i class="fa fa-university"></i> Bank Wire</a>

							<br>
							<div class="hr-or"><span>or</span></div>
							<h5>Pay for this domain using <strong>monthly installments</strong>. We'll hold it in escrow
								until it's paid off. But you can start using it right away!</h5>
							<table class="m-t-15">
								<tbody>
									<tr>
										<td><b>Installments:</b>&nbsp;</td>
										<td>
											<select id="installment-select" name="financing-months" class="form-control" style="width:auto;">
												<option value="3" selected="selected">3</option>
												<option value="6">6</option>
												<option value="12">12</option>
												<option value="24">24</option>
												<option value="36">36</option>
											</select>
										</td>
									</tr>
									<tr>
										<td height="5">

										</td>
										<td></td>
									</tr>
									<tr>
										<td><b>Amount:</b>&nbsp;</td>
										<td>
											<h5>
												<div class="installment-amount"></div>
											</h5>
										</td>
									</tr>
								</tbody>
							</table>

							<h5>All payments will be final and non-refundable once the domain is in escrow.</h5>

							<button type="submit" name="sb-financing" class="btn btn-lg btn-warning btn-block">Setup
								Payment Plan</button>
							<h5 style="text-align: center;">Have Questions?<a href="/contact">Contact us</a> at any time.
							</h5>
							</br>

						</div>
						<div class="make-financing-result"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="myModalSold" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<div class="row">

							<div class="col-xs-12">
								<h3 class="modal-title sold-model"></h3>
								<button type="button" class="close" data-dismiss="modal">×</button>
							</div>
						</div>
					</div>
					<div class="modal-body">
						<h1 class="text-center">
							<strike class="text-discount sold-price" style="color: #ff5f02;"></strike>
						</h1>
						<h1 class="text-center text-white sold-domain" style="color: #ff5f02;"></h1>
						<div class="make-financing-div">
							<h3>Description</h3>
							<p class="descrip"></p>
							<h3>Summary</h3>
							<table class="table table-bordered table-responsive">
								<tbody>
									<tr style="color: #ff5f02;">
										<td class="theading">Registered On</td>
										<td class="register-date"></td>
									</tr>
									<tr style="color: #ff5f02;">
										<td class="theading">Registrar</td>
										<td class="registrar"></td>
									</tr>
									<tr style="color: #ff5f02;">
										<td class="theading">Domain Age</td>
										<td class="age"></td>
									</tr>
									<tr style="color: #ff5f02;">
										<td class="theading">Domain Category</td>
										<td class="is_premiumss"></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
