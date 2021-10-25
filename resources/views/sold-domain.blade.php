@extends('layouts/app')
@section('seo_title') {{ ucfirst($domain->domain) }} - {{ \App\Models\Options::get_option('seo_title') }}
@endsection

@section('section_title', 'This domain was SOLD')

@section('content')

	<div class="container-fluid domain-info-fluid">
		<div class="container">
			<div class="section-title">
				@if ($domain->pricing > 0)
					<h1 class="text-center">
						<strike class="text-discount" style="color: #ff5f02;">
							{{ App\Models\Options::get_option('currency_symbol') . number_format($domain->pricing, 0) }}
						</strike>
					</h1>
				@endif

				<h1 class="text-center text-white" style="color: #ff5f02;">{{ $domain->domain }}</h1>

				<h3 class="text-center">
					<ul class="social-links colored circle-share small text-center">
						<li class="facebook">
							<a rel="nofollow" class="open-new-tab"
								href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::fullUrl()) }}" target="_blank">
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
								href="https://pinterest.com/pin/create/button/?url={{ urlencode(Request::fullUrl()) }}" target="_blank">
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

					<br>
			</div>

		</div>
	</div>
	</div>
	<div class="clearfix"></div>


	</div>
	</div>
	<div class="container-white">
		<div class="container">
			<div class="col-xs-12 col-md-7">

				@if (!empty($domain->description))
					<h3>Description</h3>
				@endif

				{!! clean(nl2br($domain->description)) !!}

				<h3>Summary</h3>
				<div class="table-responsive">
					<table class="table table-bordered table-responsive">
						@if ($domain->reg_date)
							<tr style="color: #ff5f02;">
								<td class="theading">Registered On</td>
								<td>{{ date('jS F Y', strtotime($domain->reg_date)) }}</td>
							</tr>
						@endif
						<tr style="color: #ff5f02;">
							<td class="theading">Registrar</td>
							<td>{{ $domain->registrar }}</td>
						</tr>
						@if ($domain->reg_date)
							<tr style="color: #ff5f02;">
								<td class="theading">Domain Age</td>
								<td>@if ($domain->domain_age != 0) {{ $domain->domain_age }} Years Old @else Less than 1 Year Old @endif</td>
							</tr>
						@endif
						<tr style="color: #ff5f02;">
							<td class="theading">Domain Category</td>
							<td>
								{{ stripslashes($domain->industry->catname) }}
							</td>
						</tr>
						@if ($domain->domain_history)
							<tr style="color: #ff5f02;">
								<td class="theading">Domain Wbm History</td>
								<td>
									<a href="https://web.archive.org/web/*/{{ $domain->domain }}">Archive: Click Here</a>
								</td>
							</tr>
						@endif
					</table>
				</div>

			</div>

			<div class="col-xs-12 col-md-5">
				@if (App\Models\Options::get_option('enable_logos'))
					<br>
					<center>
						<img src="{{ $domain->domain_logo ? "/storage/".$domain->domain_logo : "/image/default-logo.webp" }}" alt="{{ $domain->domain }} logo"
							class="img-responsive" />
					</center>
				@else
					&nbsp;
				@endif
			</div>

		</div>

	</div>

	</div>

@endsection
