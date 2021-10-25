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

<div class="col-xs-12 col-pagination-ajax">
	{{ $domains->links() }}
</div>
