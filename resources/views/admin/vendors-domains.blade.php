@extends('layouts/admin')

@section('section_title')
  <strong>{{ $user->name }}'s Domains</strong><br>
  <a href="/admin/vendors">&raquo; Back to Vendors</a>
@endsection

@section('section_body')
  <table class="table dataTable">
    <thead>
      <tr>
        <th>ID</th>
        <th>Domain</th>
        <th>Vendor</th>
        <th>Category</th>
        <th>Registrar</th>
        <th>Age</th>
        <th>Price</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($domains as $d)
        <tr>
          <td>
            {{ $d->id }}
          </td>
          <td>
            {{ $d->domain }}
          </td>
          <td>
            {{ $d->user->name }}<br>
            {{ $d->user->email }}
          </td>
          <td>
            {{ stripslashes($d->industry->catname) }}
          </td>
          <td>
            {{ $d->registrar }}
          </td>
          <td>
            @if ($d->domain_age != 0)
              {{ $d->domain_age }} yrs
            @else
              Less than 1 year old
            @endif
          </td>
          <td>
            @if (!is_null($d->discount) and $d->discount != 0)
              <strike
                class="text-discount">{{ \App\Models\Options::get_option('currency_symbol') . number_format($d->pricing, 0) }}</strike>
              {{ App\Models\Options::get_option('currency_symbol') . number_format($d->discount, 0) }}
            @else
              {{ App\Models\Options::get_option('currency_symbol') . number_format($d->pricing, 0) }}
            @endif
          </td>
          <td>
            {{ $d->domain_status }}
          </td>
          <td>
            <div class="btn-group">
              <a href="/admin/domains?remove={{ $d->id }}"
                onclick="return confirm('Are you sure you want to remove this domain from database?');"
                class="btn btn-danger btn-xs">
                <i class="glyphicon glyphicon-remove"></i>
              </a>
            </div>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

@endsection

@section('extra_bottom')
  @if (count($errors) > 0)
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif
@endsection
