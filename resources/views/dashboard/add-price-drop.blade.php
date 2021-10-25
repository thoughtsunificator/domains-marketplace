@extends('layouts/app')

@section('section_title', 'Add Price Drop Domain')

@section('content')
	<div class="container">
		<div class="row">

			@include( 'dashboard/navi' )

			<div class="col-md-9">
				<div class="panel panel-default">
					<div class="panel-heading">Add Price Drop Domain</div>

					<div class="panel-body">

						<small>If you wish a domain to be available only for "Make Offer" set the price as
							"0"</small><br><br>

						<form method="POST" action="{{ action('HomeController@addPriceDropDomainProcess') }}"
							enctype="multipart/form-data">
							{{ csrf_field() }}

							<div class="col-xs-12 col-md-8">
								<label>Domain Name</label><br />
								<input type="text" name="domain" class="form-control" value="{{ old('domain') }}"><br />
							</div>

							@if (App\Models\Options::get_option('enable_logos') == 'Yes')
								<div class="col-xs-12 col-md-4">
									<label>Logo Upload</label><br />
									<input type="file" name="domain_logo" class="form-control">
								</div>
							@endif

							<div class="col-xs-12 col-md-6">
								<label>Youtube Video ID</label><br />
								<input type="text" name="youtube_video_id" class="form-control"
									value="{{ old('youtube_video_id') }}"><br />
							</div>
							<div class="col-xs-12 col-md-6">
								<label>Price Drop Value</label><br />
								<input type="text" name="price_drop_value" class="form-control"
									value="{{ old('price_drop_value') }}"><br />
							</div>
							<div class="col-xs-12 col-md-6">
								<label>Start Date Time</label><br />
								<div class='input-group date' id='datetimepicker1'>
									<input type='text' class="form-control" name="end_datetime" />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
								<br />
							</div>
							<div class="col-xs-12 col-md-6">
								<label>Wbm History(Optional)</label><br />
								<select name="domain_history" class="form-control">
									<option value="1">Yes</option>
									<option value="0">No</option>
								</select>
								<br />
							</div>

							<div class="col-xs-12 col-md-6">
								<label>Price (no formatting, it will auto format to money)</label><br />
								<input type="text" name="pricing" class="form-control" value="{{ old('pricing', 0) }}"><br />
							</div>

							<div class="col-xs-12 col-md-12">
								<label>Registrar</label><br />
								<input type="text" name="registrar" class="form-control" value="{{ old('registrar') }}"><br />
							</div>

							<div class="col-xs-12 col-md-6">
								<label>Registration Date (day-month-year)</label><br />
								<input type="text" name="reg_date" class="form-control" value="{{ old('reg_date') }}"><br />
							</div>

							<div class="col-xs-12 col-md-6">
								<label>Category</label><br />
								<select name="category" class="form-control" required="">
									@if (!count($categories))
										<option value="">Please add some categories first</option>
									@endif
									@foreach ($categories as $c)
										<option value="{{ $c['catID'] }}">{{ stripslashes($c['catname']) }}</option>
									@endforeach
								</select>
							</div>

							<div class="col-xs-12 col-md-12">
								<label>Keywords - ( Type any keywork then enter)</label><br />
								<input type="text" id="#inputTag" class="form-control" name="keywords" data-role="tagsinput">

							</div>
							<br /><br />
							<div class="col-xs-12 col-md-12"><br />
								<label>Description - ( optional but keep in mind that this is your change to deliver the
									best Sales Pitch)</label><br />
								<textarea name="description" class="form-control textarea" rows="8">{{ old('description') }}</textarea>
								<br />
							</div>

							<div class="col-xs-12 col-md-6 col-xs-offset-0 col-md-offset-3">
								<input type="submit" value="Save" class="btn btn-primary btn-block">
							</div>

						</form>

					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
