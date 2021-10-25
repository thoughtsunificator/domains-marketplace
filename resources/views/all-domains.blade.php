@extends('layouts/app')
@section('seo_title') Domains List - {{ \App\Models\Options::get_option('seo_title') }} @endsection

@section('section_title', 'Domains For Sale')

@section('content')
	<div>
		<div class="container">
			<form method="POST" action="/ajax/domain_filtering" id="ajax-search-form">
				{{ csrf_field() }}
				<div id="custom-search-input">
					<div class="input-group col-md-12">
						<input id="input" placeholder="Domain or keyword" @if (!empty($autoKeyword)) value="{{ $autoKeyword }}" @endif name="keyword"
							class="form-control input-lg" />
						<span class="input-group-btn">
							<button id="buttonAjaxFilter" class="btn btn-default btn-lg" type="submit">
								<i class="glyphicon glyphicon-search"></i>
							</button>
						</span>
					</div>
				</div>
				<br />
				<select name="category">
					<option value="0">All Categories</option>
					@foreach ($categories as $c)
						<option value="{{ $c->catID }}">{{ stripslashes($c->catname) }}</option>
					@endforeach
				</select>
				<select name="extension">
					<option value="">Any TLD</option>
					@foreach ($tlds as $tld)
						<option value="{{ $tld }}">.{{ $tld }}</option>
					@endforeach
				</select>
				<select name="age">
					<option value="0">Any Age</option>
					@for ($i = 1; $i <= 10; $i++)
						<option value="{{ $i }}">{{ $i }}+ Years Old</option>
					@endfor
				</select>
				<select name="length">
					<option value="0">Any Length</option>
					@for ($i = 1; $i <= 19; $i++)
						@if ($i > 1)
							<option value="{{ $i }}">{{ $i }}+ Characters</option>
						@else
							<option value="{{ $i }}">{{ $i }}+ Character</option>
						@endif
					@endfor
					<option value="20">20+ Characters</option>
				</select>
				<select name="pricing">
					<option value="0">Any Price</option>
					@for ($i = 1; $i <= 10; $i++)
						<option value="{{ $i }}000">{{ $i }}K+</option>
					@endfor
					<option value="20000">20K+</option>
					<option value="30000">30K+</option>
					<option value="40000">40K+</option>
					<option value="50000">50K+</option>
					<option value="100000">100K+</option>
					<option value="250000">250K+</option>
					<option value="500000">500K+</option>
					<option value="1000000">1M+</option>
				</select>
				<select name="sortby">
					<option value="id.desc">Sort Order</option>
					<option value="id.desc">Added Date</option>
					<option value="pricing.asc">Lowest Price</option>
					<option value="pricing.desc">Highest Price</option>
					<option value="domain.asc">Alphabetically</option>
				</select>
				<input type="submit" name="sbAjaxSearch" value="&nbsp; Filter &nbsp; &nbsp;" class="btn btn-default">
		</div>
		</form>
	</div>
	<div class="clearfix"></div>

	<div class="container add-paddings-2">
		<hr />
		<div class="preload-search container-white">
			<h3><img src="{{ asset('/image/ajax.webp') }}" alt="preloading image"> Loading domains matching
				your criteria..</h3>
			<div class="clearfix"></div>
		</div>

		<div class="row" id="ajax-filtered-domains">
			<?php $i = 0; ?>
			@foreach ($domains as $d)
				<?php $i++; ?>
				<div class="col-xs-12 col-md-3 col-listing">
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

			<div class="col-xs-12">
				{{ $domains->links() }}
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
