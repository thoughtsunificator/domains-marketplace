@extends('layouts/admin')

@section('section_title')
  <strong>Navigation Manager</strong>
  <br />
  You can drag items to sort the order
@endsection

@section('extra_top')
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header with-border"><strong>Create Page</strong></div>
        <div class="box-body">
          <form method="POST">
            {{ csrf_field() }}
            <dl>
              <dt>Title</dt>
              <dd><input type="text" name="title" placeholder="Enter menu item title" class="form-control"
                  required="required"></dd>
              <dt>URL</dt>
              <dd><input type="text" name="url" placeholder="Enter full page url" class="form-control"
                  required="required"></dd>
              <dt>Open item in new page?</dt>
              <dd>
                <input type="radio" name="target" value="_blank"> Yes
                <input type="radio" name="target" value="_self" checked="checked"> No
              </dd>
              <dt>&nbsp;</dt>
              <dd><input type="submit" name="sb_navi" class="btn btn-primary" value="Save">
            </dl>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('section_body')

  <div class="alert alert-info alert-dismissible order-result d-none">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-info"></i> Alert!</h4>
    Rordering successfully saved!
  </div>

  <table class="table sortableUI">
    <thead>
      <tr id="0">
        <th width="50">Drag</th>
        <th>Menu Item</th>
        <th>URL</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($navi as $m)
        <tr id="{{ $m->id }}">
          <td><a href="javascript:void(0);"><i class="glyphicon glyphicon-list-alt"></i></a></td>
          <td>{{ $m->title }}</td>
          <td><a href="{{ $m->url }}" target="{{ $m->target }}">{{ $m->url }}</a></td>
          <td>
            <a href="/admin/navigation/edit/{{ $m->id }}"><i class="glyphicon glyphicon-edit"></i></a>
            <a href="/admin/navigation/delete/{{ $m->id }}" data-method="delete" data-confirm="Are you sure?"><i
                class="glyphicon glyphicon-remove"></i></a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

@endsection
