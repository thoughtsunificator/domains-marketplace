@extends('layouts/admin')

@section('section_title')
	<strong>Categories Overview</strong>
@endsection

@section('section_body')

	<div class="row">
		<div class="col-xs-12 col-md-6">
			@if (empty($catname))
				<form method="POST" action="/admin/add_category">
				@else
					<form method="POST" action="/admin/update_category">
						<input type="hidden" name="catID" value="{{ $catID }}">
						Category Name:
			@endif
			{{ csrf_field() }}
			<input type="text" name="catname" value="{{ $catname }}" class="form-control">
			<br />
			<input type="submit" name="sb" value="Save Category" class="btn btn-primary">
			</form>
		</div>
	</div>

	<br />
	<hr />

	@if ($categories)
		<table class="table table-striped table-bordered table-responsive dataTable">
			<thead>
				<tr>
					<th>ID</th>
					<th>Domain</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($categories as $c)
					<tr>
						<td>
							{{ $c->catID }}
						</td>
						<td>
							{{ $c->catname }}
						</td>
						<td>
							<div class="btn-group">
								<a class="btn btn-primary btn-xs" href="/admin/categories?update={{ $c->catID }}">
									<i class="glyphicon glyphicon-pencil"></i>
								</a>
								<a href="/admin/categories?remove={{ $c->catID }}"
									onclick="return confirm('Are you sure you want to remove this category from database?');"
									class="btn btn-danger btn-xs">
									<i class="glyphicon glyphicon-remove"></i>
								</a>
							</div>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@else
		No categories in database.
	@endif

@endsection
