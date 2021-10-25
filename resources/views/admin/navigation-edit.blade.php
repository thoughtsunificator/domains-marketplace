@extends('layouts/admin')

@section('section_title')
  <strong>Navigation Manager - Edit Menu Item</strong>
  <br />
  <a href="/admin/navigation">Navigation Overview</a>
@endsection

@section('section_body')

  <form method="POST">
    {{ csrf_field() }}
    <dl>
      <dt>Title</dt>
      <dd><input type="text" name="title" value="{{ $n->title }}" class="form-control" required="required"></dd>
      <dt>URL</dt>
      <dd><input type="text" name="url" value="{{ $n->url }}" class="form-control" required="required"></dd>
      <dt>Open item in new page?</dt>
      <dd>
        <input type="radio" name="target" value="_blank" @if ($n->target == '_blank') checked="checked" @endif> Yes
        <input type="radio" name="target" value="_self" @if ($n->target == '_self') checked="checked" @endif> No
      </dd>
      <dt>&nbsp;</dt>
      <dd><input type="submit" name="sb_navi" class="btn btn-primary" value="Save">
    </dl>
  </form>

@endsection
