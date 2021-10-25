@extends('layouts/app')

@section('section_title', 'Dashboard')

@section('content')
  <div class="container">
    <style type="text/css">
      .marketin-tools .panel-body div>a {
        color: black !important;
      }

      .marketin-tools .panel-body div>a:first-of-type {
        border: 35px solid #ffd702;
        display: grid;
        width: 180px;
        height: 150px;
        place-items: center;
      }

    </style>
    <div class="row">

      @include( 'dashboard/navi' )

      <div class="col-md-9">

        <div class="panel panel-default">
          <div class="panel-body text-center" style=" border: 15px solid #ffd702; ">
            <h4 style="font-size: 30px; letter-spacing: 2px;">Welcome to your Marketing tools Dashboard</h4>
          </div>
        </div>

        <div class="panel panel-default  marketin-tools" style="">
          <div class="panel-heading text-center"
            style=" color: black; border:10px solid #ffd702; background-color: white;">Note: Choose your
            marketing tool below and click to allow google to install the extension for you.</div>

          <div class="panel-body">
            <div style="display: flex; column-gap: 30px; row-gap: 20px; flex-wrap: wrap;">
              <div class="text-center">
                <a href="#/dashboard/marketing-tools/linkobot">Linkobot</a>
                <a class="" href="#">
                  <h4>Tutorial</h4>
                </a>
              </div>
              <div>
                <a href="#">Tweetbot</a>
              </div>
              <div>
                <a href="#">Coming soon</a>
              </div>
              <div>
                <a href="#">Coming soon</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
