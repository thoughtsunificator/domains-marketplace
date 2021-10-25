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

							@if (!is_null($d->youtube_video_id))
								<div class="col-xs-12 col-md-12">
									<label>Youtube Video ID</label><br />
									<input type="text" name="youtube_video_id" class="form-control"
										value="{{ $d->youtube_video_id }}"><br />
								</div>
							@else
								<div class="col-xs-12 col-md-12">
									<label>Youtube Video ID</label><br />
									<input type="text" name="youtube_video_id" class="form-control" value=""><br />
								</div>
							@endif
							<div class="col-xs-12 col-md-12">
								<label>Wbm History(Optional)</label><br />
								<select name="domain_history" class="form-control">
									<option {{ $d->domain_history ? 'selected' : '' }} value="1">Yes</option>
									<option {{ !$d->domain_history ? 'selected' : '' }} value="0">No</option>
								</select>
								<br />
							</div>
							<div class="col-xs-12 col-md-6">
								<label>Status</label><br />
								<select name="domain_status" class="form-control">
									<option {{ $d->domain_status == 'AVAILABLE' ? 'selected' : '' }} value="AVAILABLE">
										AVAILABLE</option>
									<option {{ $d->domain_status == 'SOLD' ? 'selected' : '' }} value="SOLD">SOLD
									</option>
								</select>
								<br />
							</div>

							<div class="col-xs-12 col-md-6">
								<label>Price</label><br />
								<input type="text" name="pricing" value="{{ $d->pricing }}" class="form-control"><br />
							</div>

							<div class="col-xs-12 col-md-6">
								<label>Discount Price</label><br />
								<input type="text" name="discount" value="{{ $d->discount }}" class="form-control"><br />
							</div>

							<div class="col-xs-12 col-md-6">
								<label>Registrar</label><br />
								<input type="text" name="registrar" value="{{ $d->registrar }}" class="form-control"><br />
							</div>

							<div class="col-xs-12 col-md-6">
								<label>Registration Date (day-month-year)</label><br />
								<input type="text" name="reg_date" value="{{ $d->reg_date }}" class="form-control"><br />
							</div>

							<div class="col-xs-12 col-md-6">
								<label>Category</label><br />
								<select name="category" class="form-control" required="">
									@if (!count($categories))
										<option value="">Please add some categories first</option>
									@endif
									@foreach ($categories as $c)
										@if ($c['catID'] == $d->category)
											<option value="{{ $c['catID'] }}" selected>
												{{ stripslashes($c['catname']) }}</option>
										@else
											<option value="{{ $c['catID'] }}">{{ stripslashes($c['catname']) }}
											</option>
										@endif
									@endforeach
								</select>
							</div>

							<div class="col-xs-12 col-md-12">
								<label>Description</label><br />
								<textarea name="description" class="form-control textarea" rows="8">{{ $d->description }}</textarea>
								<br />
							</div>

							<div class="col-xs-12 col-md-12">
								<label>Approved / Disapproved</label><br />
								<select name="is_approved" class="form-control">
									<option {{ $d->is_approved ? 'selected' : '' }} value="1">Yes</option>
									<option {{ !$d->is_approved ? 'selected' : '' }} value="0">No</option>
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
