@extends('layouts/app')

@section('section_title', 'Bulk Upload Domains')

@section('content')
	<div class="container">
		<div class="row">

			@include( 'dashboard/navi' )

			<div class="col-md-9">
				<div class="panel panel-default">
					<div class="panel-heading">CSV Upload</div>

					<div class="panel-body">

						<div class="alert alert-warning">
							The <strong>CSV File</strong> format must be exactly following the format below:<br />
							domain name, pricing (integer only no currency), registrar, registration date ( day-month-year
							), description, category name, logo image url ( optional )
							<br />
							<strong>Example CSV File</strong><br />
							<a href="/download/example-bulk-domains.csv" target="_blank">Download</a>
						</div>

						<form method="POST" enctype="multipart/form-data" class="form-horizontal">
							{{ csrf_field() }}

							<div class="col-xs-12 col-md-8">
								<label>CSV File</label><br />
								<input type="file" name="csv" class="form-control"><br />
								<input type="submit" name="sb" value="Save" class="btn btn-primary btn-block">
							</div>

						</form>

					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
