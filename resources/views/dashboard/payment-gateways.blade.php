@extends('layouts/app')

@section('section_title', 'Payment Gateways')

@section('content')
  <div class="container">
    <div class="row">

      @include( 'dashboard/navi' )

      <div class="col-md-9">
        <div class="panel panel-default">
          <div class="panel-heading">Payment Gateways</div>

          <div class="panel-body">

            <form method="POST">
              {{ csrf_field() }}

              <div class="row">

                <div class="col-xs-6">
                  <p>Accept Paypal?</p>
                  <input type="radio" name="payment_gateways[paypal_enabled]" value="Yes" @if (App\Models\User::getMetaField('paypal_enabled', 'No') == 'Yes') checked @endif> Yes
                  <input type="radio" name="payment_gateways[paypal_enabled]" value="No" @if (App\Models\User::getMetaField('paypal_enabled', 'No') == 'No') checked @endif> No
                </div>
                <div class="col-xs-6">
                  <p>Paypal Email:</p>
                  <input type="text" name="payment_gateways[paypal_email]"
                    value="{{ App\Models\User::getMetaField('paypal_email') }}" class="form-control" />
                </div>

              </div>
              <hr>

              <div class="row">
                <div class="col-xs-4">

                  <p>Accept Credit Card (via Stripe)?</p>
                  <input type="radio" name="payment_gateways[stripe_enabled]" value="Yes" @if (App\Models\User::getMetaField('stripe_enabled', 'No') == 'Yes') checked @endif> Yes
                  <input type="radio" name="payment_gateways[stripe_enabled]" value="No" @if (App\Models\User::getMetaField('stripe_enabled', 'No') == 'No') checked @endif> No
                </div>

                <div class="col-xs-4">
                  <p>Stripe Public API KEY</p>
                  <input type="text" name="payment_gateways[stripe_public_key]"
                    value="{{ App\Models\User::getMetaField('stripe_public_key') }}" class="form-control" />
                </div>

                <div class="col-xs-4">
                  <p>Stripe Private API KEY</p>
                  <input type="text" name="payment_gateways[stripe_private_key]"
                    value="{{ App\Models\User::getMetaField('stripe_private_key') }}" class="form-control" />

                </div>

              </div>
              <hr>

              <p>Accept Escrow? <br>
                <small>Free to use Your Own Service for escrow - you will receive requests and you'll setup
                  all the escrow details directly with the customer</small>
              </p>
              <input type="radio" name="payment_gateways[escrow_enabled]" value="Yes" @if (App\Models\User::getMetaField('escrow_enabled', 'Yes') == 'Yes') checked @endif> Yes
              <input type="radio" name="payment_gateways[escrow_enabled]" value="No" @if (App\Models\User::getMetaField('escrow_enabled', 'Yes') == 'No') checked @endif>
              No

              <hr>

              <p>Accept Financing Requests? <br>
              </p>
              <input type="radio" name="financingEnabled" value="1" @if (auth()->user()->financingEnabled) checked @endif> Yes
              <input type="radio" name="financingEnabled" value="0" @if (!auth()->user()->financingEnabled) checked @endif> No

              <hr>


              <input type="submit" name="sb" value="Save Payment Settings" class="btn btn-primary">

            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
