@extends('layouts/admin')

@section('section_title')
  <strong>Vendors</strong>
@endsection

@section('section_body')
  <table class="table dataTable">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Plan</th>
        <th>Domains</th>
        <th>Join Date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($vendors as $v)
        <tr>
          <td>{{ $v->id }}</td>
          <td>{{ $v->name }}</td>
          <td>{{ $v->email }}</td>
          <td>
            Plan: {{ $v->plan }}
            <br>
            Gateway: {{ $v->plan_gateway }}
            <br>
            Expires: {{ date('jS F Y', $v->plan_expires) }}
          </td>
          <td>
            <a href="/admin/vendor-domains/{{ $v->id }}">{{ $v->domains()->count() }} domains</a>
          </td>
          <td>{{ $v->created_at->format('jS F Y') }}</td>
          <td>
            <a href="/admin/add-plan/{{ $v->id }}">Add Plan Manually</a><br>

            <a href="/admin/loginAs/{{ $v->id }}"
              onclick="return confirm('This will log you out as an admin and login as a vendor. Continue?')">Login
              as User</a>

            <br>
            <br>
            <a href="/admin/vendors?remove-vendor={{ $v->id }}"
              onclick="return confirm('Are you sure you want to delete this user and his data? This is irreversible!!!')"
              class="text-danger">Delete User & His Data</a>
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
