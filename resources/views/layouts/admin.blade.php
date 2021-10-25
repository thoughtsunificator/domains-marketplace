<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="crivion">
	<meta name="token" content="{{ csrf_token() }}">
	<title>Admin Panel</title>
	<link href="/lib/bootstrap.min.css" rel="stylesheet">
	<link href="/lib/font-awesome.min.css" rel="stylesheet">
	<link href="/lib/sweetalert.min.css" rel="stylesheet">
	<link href="/lib/socialicons.min.css" rel="stylesheet">
	<link href="/lib/select2.min.css" rel="stylesheet">
	<link href="/lib/jquery.dataTables.min.css" rel="stylesheet">
	<link href="/lib/dataTables.bootstrap.min.css" rel="stylesheet">
	<link href="/lib/bootstrap-wysihtml5.min.css" rel="stylesheet">
	<link href="/lib/bootstrap-datetimepicker.min.css" rel="stylesheet">
	<link href="/resource/admin.css" rel="stylesheet">
	<script src="/lib/jquery.min.js"></script>
	<script src="/lib/moment_with_locales.min.js"></script>
	<script src="/lib/bootstrap.min.js"></script>
	<script src="/lib/sweetalert.min.js"></script>
	<script src="/lib/raphael-min.js"></script>
	<script src="/lib/morris.min.js"></script>
	<script src="/lib/select2.full.min.js"></script>
	<script src="/lib/jquery.dataTables.min.js"></script>
	<script src="/lib/dataTables.bootstrap.min.js"></script>
	<script src="/lib/jquery-ui.min.js"></script>
	<script src="/lib/wysiwyg.min.js"></script>
	<script src="/lib/bootstrap-wysihtml5.min.js"></script>
	<script src="/lib/bootstrap-datetimepicker.min.js"></script>
	<script src="/resource/admin.js"></script>
	@stack('head')
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="/lib/html5shiv.min.js"></script>
	<script src="/lib/respond.min.js"></script>
 <![endif]-->
</head>

<body>
	<form id="logout-form" action="{{ route('logout') }}" method="post" style="display: none" name="logout-form">
		{{ csrf_field() }}
	</form>
	<nav class="navbar navbar-inverse sidebar" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-sidebar-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="/admin">Admin</a>
			</div>
			<div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li @if (isset($active) and $active == 'dashboard') class="active" @endif>
						<a href="/admin"><i class="fa fa-link pull-right hidden-xs showopacity"></i>
							<span>Dashboard</span></a>
					</li>
					<li @if (isset($active) and $active == 'vendors') class="active" @endif>
						<a href="/admin/vendors"><i class="fa pull-right hidden-xs showopacity fa-users"></i>
							<span>Vendors</span></a>
					</li>
					<li @if (isset($active) and $active == 'domains') class="active" @endif>
						<a href="/admin/domains"><i class="fa pull-right hidden-xs showopacity fa-globe"></i>
							<span>Domains</span></a>
					</li>
					<li @if (isset($active) and $active == 'premiumdomains') class="active" @endif>
						<a href="/admin/premium_domains"><i class="fa pull-right hidden-xs showopacity fa-star"></i>
							<span>Premium</span></a>
					</li>
					<li @if (isset($active) and $active == 'offers') class="active" @endif>
						<a href="/admin/domain_offers"><i class="fa pull-right hidden-xs showopacity fa-gift"></i>
							<span>Domain Offers</span></a>
					</li>
					<li @if (isset($active) and $active == 'price_drop') class="active" @endif>
						<a href="/admin/price_drop_request"><i class="fa pull-right hidden-xs showopacity fa-bullseye"></i>
							<span>Price Drop
								Request</span></a>
					</li>
					<li @if (isset($active) and $active == 'categories') class="active" @endif>
						<a href="/admin/categories"><i class="fa pull-right hidden-xs showopacity fa-bars"></i>
							<span>Categories</span></a>
					</li>
					<li @if (isset($active) and $active == 'pages') class="active" @endif>
						<a href="/admin/cms"><i class="fa pull-right hidden-xs showopacity fa-sticky-note-o"></i>
							<span>Pages</span></a>
					</li>
					<li @if (isset($active) and $active == 'navi') class="active" @endif>
						<a href="/admin/navigation"><i class="fa pull-right hidden-xs showopacity fa-unsorted"></i>
							<span>Navigation</span></a>
					</li>
					<li @if (isset($active) and $active == 'config') class="active" @endif>
						<a href="/admin/configuration"><i class="fa pull-right hidden-xs showopacity fa-cog"></i>
							<span>Configuration</span></a>
					</li>
					<li @if (isset($active) and $active == 'payments') class="active" @endif>
						<a href="/admin/payments-settings"><i class="fa pull-right hidden-xs showopacity fa-bank"></i>
							<span>Payments & Plans</span></a>
					</li>
					<li @if (isset($active) and $active == 'admin-login') class="active" @endif>
						<a href="/admin/config-logins"><i class="fa pull-right hidden-xs showopacity fa-cog"></i>
							<span>Admin Logins</span></a>
					</li>
					<li>
						<a href="{{ route('logout') }}"
							onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
								class="fa pull-right hidden-xs showopacity fa-power-off"></i> <span>Log Out</span></a>
					</li>
				</ul>
			</div>
		</div>
	</nav>

	<div class="main">

		@if (session('msg'))
			<div class="row">
				<div class="col-xs-12">
					<div class="alert alert-info alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-info"></i> Alert!</h4>
						{{ session('msg') }}
					</div>
				</div>
			</div>
		@endif

		@if (count($errors) > 0)
			<div class="alert alert-danger alert-dismissible">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		@yield('extra_top')

		<div class="col-xs-12">
			<div class="row">
				<div class="box">
					<div class="box-header with-border">@yield('section_title', 'Section Title')</div>
					<div class="box-body">
						@yield('section_body', 'Body')
					</div>
					<div class="box-footer"></div>
				</div>
			</div>
		</div>
		@yield('extra_bottom')

	</div>
	<script>
		jQuery(document).ready(function($) {
			$(".textarea").wysihtml5({
				stylesheets: [""]
			});
			$(".sortableUI tbody").sortable({
				update: function() {
					var order = $(".sortableUI tbody").sortable('toArray');
					$.get('/admin/navigation-ajax-sort', {
						'navi_order': order
					}, function(r) {
						$('.order-result').show();
					});
				}
			});
			$(".sortableUI").disableSelection();
			$('.dataTable').dataTable();

		});
	</script>
	<script>
		var auto_refresh = setInterval(
			function() {
				$('#load_updates').load('/admin/dashboard_load').fadeIn("slow");
				$('#load_updates1').load('https://domain-marketplace.herokuapp.com/load_feature_domain').fadeIn("slow");
			}, 1000);
	</script>
	<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none">
		{{ csrf_field() }}
	</form>
</body>

</html>
