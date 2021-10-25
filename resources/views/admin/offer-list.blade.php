@extends('layouts/admin')

@section('section_title')
  <strong>Offer List</strong>
@endsection

@section('section_body')

  @if ($alloffer)
    <table class="table table-striped table-bordered table-responsive dataTable table-fit">
      <thead>
        <tr>
          <th>Domain</th>
          <th>Name</th>
          <th>Phone No</th>
          <th>Email</th>
          <th>Offer Price</th>
          <th>Message</th>
          <th>Seller Name</th>
          <th>Seller Email</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($alloffer as $alloffer)
          <tr>

            <td>
              <a href="/{{ $alloffer->domain }}" target="_blank">{{ $alloffer->domain }}</a>
            </td>


            <td>{{ $alloffer->user_name }}</td>
            <td>
              {{ $alloffer->phone_no }}
            </td>

            <td>
              {{ $alloffer->email }}
            </td>
            <td>
              {{ $alloffer->offer_price }}
            </td>
            <td>
              {{ $alloffer->remarks }}
            </td>
            <td>
              {{ $alloffer->name }}
            </td>
            <td>
              {{ $alloffer->email }}
            </td>
            <td>
              <div class="btn-group">
                <a href="/admin/delete_offer_list/{{ $alloffer->id }}"
                  onclick="return confirm('Are you sure you want to remove this Offer?');" class="btn btn-danger btn-xs">
                  <i class="glyphicon glyphicon-remove"></i>
                </a>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    No Offer in database.
  @endif

@endsection
