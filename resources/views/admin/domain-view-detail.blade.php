@extends('layouts/admin')

@section('section_title')
	<strong>Domains Detail {{ $domain_detail->domain }}</strong>
@endsection

@section('section_body')
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
				<td>{{ $domain_detail->id }}</td>
			</tr>

			<tr class="table-primary">
				<th scope="row">Domain</th>
				<td><a href="/{{ $domain_detail->domain }}" target="_blank">{{ $domain_detail->domain }}</a></td>
			</tr>
			<tr class="table-secondary">
				<th scope="row">Vendor</th>
				<td>{{ $domain_detail->user->name }}<br>
					{{ $domain_detail->user->email }}</td>
			</tr>
			<tr class="table-success">
				<th scope="row">Category</th>
				<td>{{ stripslashes($domain_detail->industry->catname) }}</td>
			</tr>
			<tr class="table-danger">
				<th scope="row">Registrar</th>
				<td>{{ $domain_detail->registrar }}</td>
			</tr>
			<tr class="table-warning">
				<th scope="row">Age</th>
				<td>
					@if ($domain_detail->reg_date)
						@if ($domain_detail->domain_age != 0)
							{{ $domain_detail->domain_age }} yrs
						@else
							Less than 1 year old
						@endif
					@else
						N/A
					@endif
				</td>
			</tr>
			<tr class="table-info">
				<th scope="row">Price</th>
				<td>
					@if (!is_null($domain_detail->discount) and $domain_detail->discount != 0)
						<strike
							class="text-discount">{{ \App\Models\Options::get_option('currency_symbol') . number_format($domain_detail->pricing, 0) }}</strike>
						{{ App\Models\Options::get_option('currency_symbol') . number_format($domain_detail->discount, 0) }}
					@else
						{{ App\Models\Options::get_option('currency_symbol') . number_format($domain_detail->pricing, 0) }}
					@endif
				</td>
			</tr>
			<tr class="table-light">
				<th scope="row">Premium</th>
				<td>
					@if (!$domain_detail->is_premium)

						<span class="text text-danger">Not Premium</span>

					@else
						<span class="text text-success">Premium</span>
					@endif
				</td>
			</tr>
			<tr class="table-dark">
				<th scope="row">Status</th>
				<td>{{ $domain_detail->domain_status }}</td>

			</tr>
			<tr class="table-dark">
				<th scope="row">Ownership</th>
				<td>
					@if (!$domain_detail->is_verified)
						<a href="/admin/domains?verify={{ $domain_detail->id }}" class="text-danger">Set as
							Verified</a>
					@else
						<span class="text-success">Ownership Verified</span>
					@endif
				</td>
			</tr>
			<tr class="table-dark">
				<th scope="row">Wbm History(Optional)</th>
				<td>{{ $domain_detail->domain_history }}</td>

			</tr>
			<tr class="table-dark">
				<th scope="row">Approved / Disapproved</th>
				<td>
					@if (!$domain_detail->is_approved)
						<a href="#" class="text-danger">Not approved</a>
					@else
						<span class="text-success">Approved</span>
					@endif
				</td>
			</tr>
		</tbody>
	</table>


@endsection
