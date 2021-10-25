@extends('layouts/app')

@section('seo_title') {{ $page->page_title }} - {{ \App\Models\Options::get_option('seo_title') }} @endsection

@section('section_title', e($page->page_title))

@section('content')
	<div class="container">
		{!! clean(nl2br($page->page_content)) !!}
	</div>

@endsection
