@extends('layouts/admin')

@section('section_body')
	<div class=" ">
		<div class=" ">
			<div class=" ">
				<div class="panel panel-default">
					<div class="panel-heading">{{ $d->domain }}</div>

					<div class="panel-body">

						<form method="POST" action="{{ url('admin/update_domain_detail', $d->id) }}" enctype="multipart/form-data">
							{{ csrf_field() }}

							<div class="col-xs-12 col-md-8">
								<label>Domain Name</label><br />
								<input type="text" name="domain" value="{{ $d->domain }}" class="form-control"><br />
							</div>

							@if (App\Models\Options::get_option('enable_logos') == 'Yes')
								<div class="col-xs-12 col-md-4">
									<label>Logo Upload (ignore to keep current logo)</label><br />
									<input type="file" name="domain_logo" class="form-control">
								</div>
							@endif

							<div class="col-xs-12 col-md-6">
								<label>Start Day & Time</label><br />
								<div class='input-group date' id='datetimepicker1'>
									<input type='text' class="form-control" name="start_datetime" value="{{ $d->start_datetime }}" />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
								<br />
							</div>
							<div class="col-xs-12 col-md-6">
								<label>Ending Date & Time</label><br />
								<div class='input-group date' id='datetimepicker2'>
									<input type='text' class="form-control" name="end_datetime" value="{{ $d->end_datetime }}" />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
								<br />
							</div>

							<div class="col-xs-12 col-md-6">
								<label>Price</label><br />
								<input type="text" name="pricing" value="{{ $d->pricing }}" class="form-control"><br />
							</div>
							<div class="col-xs-12 col-md-6">
								<label>Price Drop Value</label><br />
								<input type="text" name="price_drop_value" class="form-control"
									value="{{ $d->price_drop_value }}"><br />
							</div>

							<div class="col-xs-12 col-md-12">
								<label>Price drop status</label><br />
								<select name="price_drop" class="form-control">
									<option {{ $d->price_drop ? 'selected' : '' }} value="1">Approved</option>
									<option {{ !$d->price_drop ? 'selected' : '' }} value="0">Disapproved</option>
								</select>
								<br />
							</div>

							<div class="col-xs-12 col-md-6 col-xs-offset-0 col-md-offset-3">
								<input type="submit" name="sb" value="Update" class="btn btn-primary btn-block">
							</div>

						</form>

					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
@endsection
