@extends('layouts/app')

@section('section_title', 'My Profile')

@section('content')
  <div class="container">
    <div class="row">

      @include( 'dashboard/navi' )

      <div class="col-md-9">
        <div class="panel panel-default">
          <div class="panel-heading">My Profile</div>

          <div class="panel-body">

            <h4>General Profile Settings</h4>

            <form method="POST" enctype="multipart/form-data">
              {{ csrf_field() }}

              <p>Email Address</p>
              <div class="row">
                <div class="col-xs-6">
                  <input type="email" name="email" value="{{ auth()->user()->email }}" class="form-control" />
                </div>
              </div>
              <hr>

              <p>Name ( shown on your profile - enter "Private" if you don't want to publish it)</p>
              <div class="row">
                <div class="col-xs-6">
                  <input type="text" name="name" value="{{ auth()->user()->name }}" class="form-control">
                </div>
              </div>
              <hr>

              <p>Profile Picture ( will be resized to 140x140 if higher than this dimensions )</p>
              <input type="file" name="profilePic" accept="image/*">
              <hr>

              <p>Profile Headline ( will appear on your profile page)</p>
              <textarea name="headline" class="form-control" rows="7">{{ auth()->user()->headline }}</textarea>
              <hr>


              <input type="submit" name="sb" value="Save Profile Settings" class="btn btn-primary">

            </form>
            <hr>

            <h4>Update Account Password</h4>
            <form method="POST" action="/dashboard/update-account-credentials">
              {{ csrf_field() }}
              <div class="row">
                <div class="col-xs-4">
                  <p>New Password</p>
                  <input type="password" name="password" class="form-control">
                </div>
                <div class="col-xs-4">
                  <p>Confirm New Password</p>
                  <input type="password" name="password_confirmation" class="form-control">
                </div>
              </div>

              <br>
              <input type="submit" name="sb_password" class="btn btn-primary" value="Update Password">
              <hr>
            </form>


          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
