@extends('layouts/app')

@section('section_title', 'Manage Domain')

@section('content')
	<div class="container">
		<div class="row">

			@include( 'dashboard/navi' )

			<div class="col-md-9">
				<table class="table">
					<thead>
						<tr>
							<th scope="col"> </th>
							<th scope="col"> </th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th scope="row">ID</th>
							<td>{{ $d->id }}</td>
						</tr>

						<tr class="table-primary">
							<th scope="row">Domain</th>
							<td><a href="/domains/{{ $d->domain }}" target="_blank">{{ $d->domain }}</a></td>
						</tr>
						<tr class="table-secondary">
							<th scope="row">Vendor</th>
							<td>{{ $d->user->name }}<br>
								{{ $d->user->email }}</td>
						</tr>
						<tr class="table-info">
							<th scope="row">Price</th>
							<td>
								@if (!is_null($d->discount) and $d->discount != 0)
									<strike
										class="text-discount">{{ \App\Models\Options::get_option('currency_symbol') . number_format($d->pricing, 0) }}</strike>
									{{ App\Models\Options::get_option('currency_symbol') . number_format($d->discount, 0) }}
								@else
									{{ App\Models\Options::get_option('currency_symbol') . number_format($d->pricing, 0) }}
								@endif
							</td>
						</tr>
						<tr class="table-light">
							<th scope="row">Start Day & Time</th>
							<td>{{ $d->start_datetime }}</td>
						</tr>
						<tr class="table-light">
							<th scope="row">Ending Date & Time</th>
							<td>{{ $d->end_datetime }}</td>
						</tr>
						<tr class="table-dark">
							<th scope="row">Price Drop</th>
							<td>${{ $d->price_drop_value }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection
