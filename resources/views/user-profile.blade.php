@extends('layouts/app')
@section('seo_title') {{ $page_title }} - {{ \App\Models\Options::get_option('seo_title') }}
@endsection
@section('section_title', 'Profile')
@section('content')
	<div class="container">
		<div class="row">
			<div class="col-xs-2 col-xs-offset-0 col-md-2 col-md-offset-2"><img
					src="{{ $user->profileImage ? "/image/".$user->profileImage : "/image/default-picture.webp" }}" class="img-responsive" alt="profile picture"></div>
			<div class="col-xs-10 col-md-8">
				<h2>{{ $page_title }}</h2>
				<h3>{{ $user->headline }}</h3>
			</div>
			<div class="clearboth"></div>
		</div><br>
		<br>
		<div class="row">
			<?php $i = 0; ?>
			@foreach ($domains as $d)
				<?php $i++; ?>
				<div class="col-xs-12 col-md-4">
					<div class="col-listings">
						<h3 class="text-center domain-title"><a href="/domains/{{ $d->domain }}">{{ $d->domain }}</a></h3>
						<div class="text-center">
							<a href="/user/{{ $d->user->id }}/{{ \Illuminate\Support\Str::slug($d->user->name) }}"
								class="vendor">{{ $d->user->name }}</a>
						</div>
						<hr class="clearboth">
						@if ('Yes' == \App\Models\Options::get_option('enable_logos'))
							<center>
								<a href="/domains/{{ $d->domain }}"><img src="{{ $d->domain_logo ? "/storage/thumbnail-".$d->domain_logo : "/image/default-logo.webp" }}"
										alt="{{ $d->domain }} logo" class="img-responsive domain-logo"></a>
							</center>
						@endif
						@if ('Yes' == \App\Models\Options::get_option('enable_shortdesc'))
							@if ('Yes' == \App\Models\Options::get_option('enable_logos'))
								<hr>
							@endif
							<p class="text-center">{{ $d->short_description }}</p>
						@endif
						<hr>
						<div class="row">
							<div class="col-xs-12 col-md-8">
								<h4 class="domain-price">
									@if ($d->pricing == 0 and $d->domain_status == 'AVAILABLE')
										@if (!is_null($d->discount) and $d->discount != 0)
											<strike
												class="text-discount">{{ \App\Models\Options::get_option('currency_symbol') . number_format($d->pricing, 0) }}</strike>
											{{ App\Models\Options::get_option('currency_symbol') . number_format($d->discount, 0) }}
										@else
											{{ App\Models\Options::get_option('currency_symbol') . number_format($d->pricing, 0) }}
										@endif
									@elseif( $d->domain_status == 'SOLD' )
										@if ($d->pricing == 0)
											<strike
												class="text-discount">{{ App\Models\Options::get_option('currency_symbol') . number_format($d->pricing, 0) }}</strike>
										@endif
										SOLD
									@else
										Open to Offers
									@endif
								</h4>
							</div>
							<div class="col-xs-12 col-md-4">
								<a class="btn btn btn-inverse" href="/domains/{{ $d->domain }}">Details</a>
							</div>
						</div>
						<div class="clearboth"></div>
					</div>
				</div>
				@if ($i % 3 == 0)
					<div class="clearboth"></div>
				@endif
			@endforeach
		</div>{{ $domains->links() }}
</div>@endsection
