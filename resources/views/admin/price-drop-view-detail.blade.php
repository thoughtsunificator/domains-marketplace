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
				<td>{{ $domain_detail->user->name }}<br>{{ $domain_detail->user->email }}</td>
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
				<th scope="row">Start Day & Time</th>
				<td>{{ $domain_detail->start_datetime }}</td>
			</tr>
			<tr class="table-light">
				<th scope="row">Ending Date & Time</th>
				<td>{{ $domain_detail->end_datetime }}</td>
			</tr>
			<tr class="table-dark">
				<th scope="row">Price Drop</th>
				<td>${{ $domain_detail->price_drop_value }}</td>

			</tr>
			<tr class="table-dark">
				<th scope="row">Approved / Disapproved</th>
				<td>
					@if (!$domain_detail->is_approved)
						<a href="#" class="text-danger">No</span>
						@else
							<span class="text-success">Yes</span>
					@endif
				</td>
			</tr>
		</tbody>
	</table>


@endsection
