@extends('layouts/app')

@section('section_title', 'Add Domain')

@section('content')
  <div class="container">
    <div class="row">

      @include( 'dashboard/navi' )

      <div class="col-md-9">
        <div class="panel panel-default">
          <div class="panel-heading">Add Domain</div>

          <div class="panel-body">

            Sorry, you reached your plan limit and cannot list more domains.

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
