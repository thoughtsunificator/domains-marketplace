@extends('layouts/app')

@section('section_title', 'Orders Overview')

@section('content')
  <div class="container">

    @include( 'dashboard/navi' )

    <div class="col-md-9">

      @if ($orders->count())
        <table class="table table-striped table-bordered table-responsive dataTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Customer</th>
              <th>Email</th>
              <th>Total</th>
              <th>Date</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($orders as $o)
              <tr>
                <td>
                  #{{ $o->id }}
                </td>
                <td>
                  <a href="/dashboard/view-order/{{ $o->id }}">{{ $o->customer }}</a>
                </td>
                <td>
                  {{ $o->email }}
                </td>
                <td>
                  ${{ number_format($o->total, 0) }}
                </td>
                <td>
                  {{ date('jS F Y', strtotime($o->order_date)) }}
                  <br />
                  {{ date('H:i', strtotime($o->order_date)) }}
                </td>
                <td>
                  {{ $o->order_status }} <br />
                  <small><em>via</em> {{ $o->payment_type }}</small>
                </td>
                <td>
                  <div class="btn-group">
                    <a class="btn btn-warning btn-xs" href="/dashboard/view-order/{{ $o->id }}">
                      <i class="glyphicon glyphicon-eye-open"></i>
                    </a>
                    <a href="/dashboard/orders?remove={{ $o->id }}"
                      onclick="return confirm('Are you sure you want to remove this order from database?');"
                      class="btn btn-danger btn-xs">
                      <i class="glyphicon glyphicon-remove"></i>
                    </a>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        {{ $orders->links() }}
      @else
        <div class="panel panel-default">
          <div class="panel-heading">Orders Overview</div>
          <div class="panel-body">
            <h3>No orders yet.</h3>
          </div>
        </div>
      @endif
    </div>

  </div>
@endsection
