<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="crivion">
	<meta name="description" content="{{ \App\Models\Options::get_option('seo_desc') }}">
	<meta name="keywords" content="{{ \App\Models\Options::get_option('seo_keys') }}">
	<title>@if (trim($__env->yieldContent('seo_title'))) @yield('seo_title') @else {{ \App\Models\Options::get_option('seo_title') }} @endif</title>
	<link href="/lib/bootstrap.min.css" rel="stylesheet">
	<link href="/lib/bootstrap-datetimepicker.min.css" rel="stylesheet">
	<link href="/lib/font-awesome.min.css" rel="stylesheet">
	<link href="/lib/font-awesome.min.css" rel="stylesheet" />
	<link href="/lib/sweetalert.min.css" rel="stylesheet">
	<link href="/lib/socialicons.min.css" rel="stylesheet">
	<link href="/lib/bootstrap-tagsinput.min.css" rel="stylesheet">
	<link href="/resource/app.css" rel="stylesheet">
	<script src="/lib/jquery.min.js"></script>
	<script src="/lib/moment_with_locales.min.js"></script>
	<script src="/lib/bootstrap.min.js"></script>
	<script src="/lib/bootstrap-datetimepicker.min.js"></script>
	<script src="/lib/sweetalert.min.js"></script>
	<script src="https://js.stripe.com/v2/"></script>
	<script>
		Stripe.setPublishableKey('{{ env('STRIPE_PUBLISHABLE_KEY') }}');
	</script>
	<script src="/lib/typeahead.bundle.min.js"></script>
	<script src="/lib/bootstrap-tagsinput.min.js"></script>
	<script src="/resource/app.js"></script>
	@if (isset($isHome) and \App\Models\Options::get_option('homepage_header_image'))
		<style>
			.homepage-img {
				background-image: url('{{ \App\Models\Options::get_option('homepage_header_image') }}');
			}

		</style>
	@endif
	<link rel="stylesheet" type="text/css" href="/lib/cookieconsent.min.css" />
	@stack('head')
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="/lib/html5shiv.min.js"></script>
	<script src="/lib/respond.min.js"></script>
 <![endif]-->
</head>

<body>
	<div id="wrapper">
		<header id="header">
			<div class="nav-top">
				<div class="container">
					<div class="row">
						<div class="col-sm-4 col-md-7">
							@if (($logo = App\Models\Options::get_option('site_logo')) and !empty($logo)) <a href="/"><img
										src="{{ \App\Models\Options::get_option('site_logo') }}" class="top-logo" alt="site logo"
									height="40"></a> @else
								<h1 class="brand"><a href="/">{{ App\Models\Options::brand_name() }}</a></h1>
							@endif
						</div>
						<div class="col-sm-8 col-md-5 text-right">
							<ul class="nav-top-social-icons">
								@if ('Yes' == \App\Models\Options::get_option('fbIcon'))
									<li class="facebook">
										<a rel="nofollow" target="_blank" class="open-new-tab"
											href="{{ \App\Models\Options::get_option('facebook_follow_us') }}">
											<i class="fa fa-facebook-square"></i>
										</a>
									</li>@endif @if ('Yes' == \App\Models\Options::get_option('twIcon'))
										<li class="twitter">
											<a rel="nofollow" target="_blank" class="open-new-tab"
												href="{{ \App\Models\Options::get_option('twitter_follow_us') }}">
													<i class="fa fa-twitter-square"></i>
											</a>
										</li>@endif @if ('Yes' == \App\Models\Options::get_option('linkedIcon'))
											<li class="linkedin">
												<a rel="nofollow" target="_blank" class="open-new-tab"
													href="{{ \App\Models\Options::get_option('linkedin_follow_us') }}">
														<i class="fa fa-linkedin-square"></i>
												</a>
											</li>@endif @if ('Yes' == \App\Models\Options::get_option('phoneIcon'))
												<li>
													<a href="tel:{{ \App\Models\Options::get_option('phone_number') }}">
														<i class="fa fa-phone-square"></i>
													</a>
												</li>
											@endif
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="navbar-nav-top-container">
				<nav class="navbar navbar-nav-top">
					<div class="container">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle navbar-toggle-home collapsed" data-toggle="collapse"
								data-target="#bs-example-navbar-collapse-1" aria-expanded="false"><span class="sr-only">Toggle
									navigation</span></button>
							<div class="visible-xs">
								<a href="javascript:void(0);" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"
									aria-expanded="false">
									<h2 class="navigation-text-mobile">Menu</h2>
								</a>
							</div>
						</div>@php
							$navi_order = \App\Models\Options::get_option('navi_order');
							if (!empty($navi_order)) {
									$navi = \App\Models\Navi::orderByRaw("FIELD(id, $navi_order)")->get();
							} else {
									$navi = [];
							}
						@endphp
						@if (Auth::check())
							<form id="logout-form" action="{{ route('logout') }}" method="post" style="display: none"
								name="logout-form">
								{{ csrf_field() }}
							</form>
							<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
								<ul class="nav navbar-nav navbar-nav-menu">
									@foreach ($navi as $nav_item)
										<li>
											<a href="{{ $nav_item->url }}"
												target="{{ $nav_item->target }}">{{ $nav_item->title }}</a>
										</li>
									@endforeach
								</ul>
								<ul class="nav navbar-nav pull-right signup">
									<li>
										<a href="/dashboard">{{ Auth::user()->name }}</a>
									</li>
									<li class="btn btn-primary1">
										<a class="signn" href="{{ route('logout') }}"
											onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
									</li>
								</ul>
							</div>
						@else
							<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
								<ul class="nav navbar-nav navbar-nav-menu">
									@foreach ($navi as $nav_item)
										<li>
											<a href="{{ $nav_item->url }}"
												target="{{ $nav_item->target }}">{{ $nav_item->title }}</a>
										</li>
									@endforeach
								</ul>
								<ul class="nav navbar-nav pull-right signup">
									<li class="">
										<a href="/login">Login</a>
									</li>
									<li class="btn btn-primary1">
										<a href="/register" class="signn">SignUp</a>
									</li>
								</ul>
							</div>
						@endif
					</div>
				</nav>
			</div>
		</header>
		<main id="main">
			@hasSection('section_title')
				<div class="well text-center">
					<div class="container">
						<h3>@yield('section_title', 'Section Title')</h3>
					</div>
				</div>
			@endif
			@if (isset($errors) and count($errors) > 0)
				<div class="alert alert-danger container">
					@foreach ($errors->all() as $error)
						{{ $error }}
					@endforeach
				</div>
			@endif

			@if (session('msg'))
				<div class="alert alert-info container">
					{{ session('msg') }}
				</div>
			@endif

			@yield('content')
		</main>
		<footer id="footer">
			<div class="container-fluid footer-bg">
				<div class="container footer">
					<div class="col-xs-12 col-md-4 footer-text">
						<h1 class="brand brand-footer"><a href="/">{{ App\Models\Options::brand_name() }}</a></h1>
						<br>
						<br>
						{!! clean(nl2br(\App\Models\Options::get_option('homepage_intro'))) !!}
					</div>
					<div class="col-xs-6 col-md-2">
						<h6>Buyers</h6>
						<ul class="footer-links">
							<li>
								<a href="/domains">Buy Domains</a>
							</li>
							<li>
								<a href="/price-drop-domains">Price Drop</a>
							</li>
							<li>
								<a href="/premium-domains">Premium Names</a>
							</li>
						</ul>
					</div>
					<div class="col-xs-6 col-md-2">
						<h6>Sellers</h6>
						<ul class="footer-links">
							<li>
								<a href="/pricing">Sell Domains</a>
							</li>
							<li>
								<a href="/register">Seller Signup</a>
							</li>
							<li>
								<a href="/login">Seller Login</a>
							</li>
						</ul>
					</div>
					<div class="col-xs-6 col-md-2">
						<h6>Company</h6>
						<ul class="footer-links">
							<li>
								<a href="/p-tos">Terms of Service</a>
							</li>
							<li>
								<a href="/p-privacy-policy">Privacy Policy</a>
							</li>
							<li>
								<a href="/p-cookie-policy">Cookie Policy</a>
							</li>
						</ul>
					</div>
					<div class="col-xs-6 col-md-2">
						<h6>Follow</h6>
						<ul class="footer-links">
							@if ('Yes' == \App\Models\Options::get_option('fbIcon'))
								<li class="facebook">
									<a rel="nofollow" target="_blank" class="open-new-tab"
										href="{{ App\Models\Options::get_option('facebook_follow_us') }}"><i class="fa fa-facebook"></i> <span
											class="">Facebook</span></a>
								</li>@endif @if ('Yes' == \App\Models\Options::get_option('twIcon'))
									<li class="twitter">
										<a rel="nofollow" target="_blank" class="open-new-tab"
											href="{{ App\Models\Options::get_option('twitter_follow_us') }}"><i class="fa fa-twitter"></i> <span
												class="">Twitter</span></a>
									</li>@endif @if ('Yes' == \App\Models\Options::get_option('linkedIcon'))
										<li class="linkedin">
											<a rel="nofollow" target="_blank" class="open-new-tab"
												href="{{ App\Models\Options::get_option('linkedin_follow_us') }}"><i class="fa fa-linkedin"></i> <span
													class="">LinkedIn</span></a>
										</li>
									@endif
						</ul>
					</div>
				</div>
			</div>
		</footer>
	</div>
	<script src="/lib/cookieconsent.min.js"></script>
	<script>
		window.cookieconsent.initialise({
			"palette": {
				"popup": {
					"background": "#edeff5",
					"text": "#838391"
				},
				"button": {
					"background": "#4b81e8"
				}
			},
			"content": {
				"message": "This website uses cookies for a better experience.",
				"dismiss": "Ok, I understand",
				"link": "Cookie Policy",
				"href": "/p-cookie-policy"
			}
		});
	</script>
	<script>
		var auto_refresh = setInterval(
			function() {
				$('#load_updates1').load('/load_feature_domain').fadeIn("slow");
			}, 1000 * 30);
	</script>
</body>

</html>
