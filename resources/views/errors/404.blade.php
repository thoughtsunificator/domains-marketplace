@extends('layouts/app')

@section('seo_title')
  Page not found - {{ \App\Models\Options::get_option('seo_title') }}
@endsection

@section('section_title', '404 Error!')

@section('content')
  <div class="container text-center">

    <h4>The page you're looking for was not found!</h4>

  </div>
@endsection
