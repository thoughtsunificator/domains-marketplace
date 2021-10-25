@extends('layouts/admin')

@section('section_title')
	<strong>Price Drop Request Overview</strong>
@endsection

@section('section_body')

	@if ($domains)
		<table class="table table-striped table-bordered table-responsive dataTable table-fit">
			<thead>
				<tr>
					<th>ID</th>
					<th>Domain</th>
					<th>Seller Name</th>
					<th>Seller Email</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($domains as $d)
					<tr>
						<td>
							{{ $d->id }}
						</td>
						<td>
							<a href="/domains/{{ $d->domain }}" target="_blank">{{ $d->domain }}</a>
						</td>
						<td>
							{{ $d->user->name }}
						</td>
						<td>
							{{ $d->user->email }}
						</td>
						<td>
							<div class="btn-group">
								<a href="/admin/domains?remove={{ $d->id }}"
									onclick="return confirm('Are you sure you want to remove this domain from database?');"
									class="btn btn-danger btn-xs">
									<i class="glyphicon glyphicon-remove"></i>
								</a>
								<a href="/admin/price_drop_view_detail/{{ $d->id }}" target="_blank" class="btn btn-info btn-xs"><i
										class="glyphicon glyphicon-eye-open"></i></a>
								<a href="/admin/price_drop_edit_domain_detail/{{ $d->id }}" class="btn btn-primary btn-xs"><i
										class="glyphicon glyphicon-edit"></i></a>
							</div>
						</td>
					</tr>

				@endforeach
			</tbody>
		</table>
	@else
		No domains in database.
	@endif

@endsection
