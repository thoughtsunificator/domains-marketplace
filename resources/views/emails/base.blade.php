<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="format-detection" content="telephone=no" />
	<title>@yield('mail_subject', 'New Email')</title>
	<style type="text/css">
		/* RESET STYLES */
		html {
			background-color: #E1E1E1;
			margin: 0;
			padding: 0;
		}

		body,
		#bodyTable,
		#bodyCell,
		#bodyCell {
			height: 100% !important;
			margin: 0;
			padding: 0;
			width: 100% !important;
			font-family: Helvetica, Arial, "Lucida Grande", sans-serif;
		}

		.white-container {
			margin: 40px;
			padding: 20px;
			background: white;
			border-radius: 6px;
		}

	</style>

</head>

<body bgcolor="#E1E1E1" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">

	<br><br>
	<div class="white-container">

		@yield( 'email_title' )
		<hr>

		@yield( 'intro_heading' )
		@yield( 'intro_message' )
		<hr>

		@yield( 'mail_content' )

		<br><br>

		&copy; {{ App\Models\Options::get_option('site_title') }}
	</div>

</body>

</html>
