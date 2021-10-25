@extends('layouts/app')
@section('seo_title') {{ ucfirst($domain->domain) }} - {{ \App\Models\Options::get_option('seo_title') }}
@endsection

@section('section_title', 'Ownership verification required')

@section('content')

	<div class="container-fluid domain-info-fluid">
		<div class="container">
			<div class="section-title text-center">
				<h1 class="text-center text-white">{{ $domain->domain }}</h1>

				<a href="/dashboard/domains-overview" class="btn btn-danger">My Domains Overview</a>
				<br><br><br>
			</div>

		</div>
	</div>
	<div class="clearfix"></div>

@endsection
