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
				 <th>Share Now</th>
			 </tr>
		 </thead>
		 <tbody>

			 @foreach ($domains as $norm_dom)
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
					 <td><a class="btn btn-inverse btn-block" href="/buy/{{ $norm_dom->domain }}">Buy Now</a></td>
					 <td>
						 <ul class="social-links colored circle-share small text-center">
							 <li class="facebook">
								 <a rel="nofollow" class="open-new-tab"
									 href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::fullUrl()) }}"
									 target="_blank">
									 <i class="fa fa-facebook"></i>
								 </a>
							 </li>
							 <li class="twitter">
								 <a rel="nofollow" class="open-new-tab"
									 href="http://twitter.com/intent/tweet?url={{ urlencode(Request::fullUrl()) }}" target="_blank">
									 <i class="fa fa-twitter"></i>
								 </a>
							 </li>
							 <li class="googleplus">
								 <a rel="nofollow" class="open-new-tab"
									 href="https://plus.google.com/share?url={{ urlencode(Request::fullUrl()) }}" target="_blank">
									 <i class="fa fa-google-plus"></i>
								 </a>
							 </li>
							 <li class="pinterest">
								 <a rel="nofollow" class="open-new-tab"
									 href="https://pinterest.com/pin/create/button/?url={{ urlencode(Request::fullUrl()) }}"
									 target="_blank">
									 <i class="fa fa-pinterest"></i>
								 </a>
							 </li>
							 <li class="linkedin">
								 <a rel="nofollow" class="open-new-tab"
									 href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(Request::fullUrl()) }}"
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
						 document.getElementById("timesec{{ $norm_dom->id }}").innerHTML = "$" +
							 "{{ $norm_dom->price_drop_value }} in " + hours + "h " + minutes + "m:" + seconds + "s ";
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

 </div>



 <div class="col-xs-12 col-pagination-ajax">
	 {{ $domains->links() }}
 </div>
