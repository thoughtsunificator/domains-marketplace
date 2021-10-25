@extends('layouts/admin')

@section('section_title')
  <strong>Manage Domain: {{ $d->domain }}</strong>
  <br /><br />
  <a href="/admin/domains" class="btn btn-default btn-xs">Back to Domains Overview</a>
@endsection

@section('section_body')
  <form method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}

    <div class="col-xs-12 col-md-8">
      <label>Domain Name</label><br />
      <input type="text" name="domain" value="{{ $d->domain }}" class="form-control"><br />
    </div>

    <div class="col-xs-12 col-md-4">
      <label>Logo Upload (ignore to keep current logo)</label><br />
      <input type="file" name="domain_logo" class="form-control">
    </div>

    <div class="col-xs-12 col-md-6">
      <label>Status</label><br />
      <select name="domain_status" class="form-control">
        <option @if ($d->domain_status == 'AVAILABLE') selected @endif>AVAILABLE</option>
        <option @if ($d->domain_status == 'SOLD') selected @endif>SOLD</option>
      </select>
      <br />
    </div>

    <div class="col-xs-12 col-md-6">
      <label>Price</label><br />
      <input type="text" name="pricing" value="{{ $d->pricing }}" class="form-control"><br />
    </div>

    <div class="col-xs-12 col-md-6">
      <label>Discount (enter final price after discount, NOT percentage)</label><br />
      <input type="text" name="discount" value="{{ $d->discount }}" class="form-control"><br />
    </div>

    <div class="col-xs-12 col-md-6">
      <label>Registrar</label><br />
      <input type="text" name="registrar" value="{{ $d->registrar }}" class="form-control"><br />
    </div>

    <div class="col-xs-12 col-md-6">
      <label>Registration Date (day-month-year)</label><br />
      <input type="text" name="reg_date" value="{{ $d->reg_date }}" class="form-control"><br />
    </div>

    <div class="col-xs-12 col-md-6">
      <label>Category</label><br />
      <select name="category" class="form-control" required="">
        @if (!count($categories))
          <option value="">Please add some categories first</option>
        @endif
        @foreach ($categories as $c)
          @if ($c['catID'] == $d->category)
            <option value="{{ $c['catID'] }}" selected>{{ stripslashes($c['catname']) }}</option>
          @else
            <option value="{{ $c['catID'] }}">{{ stripslashes($c['catname']) }}</option>
          @endif
        @endforeach
      </select>
    </div>

    <div class="col-xs-12 col-md-12">
      <label>Short description</label><br />
      <textarea name="short_description" class="form-control" rows="4">{{ $d->short_description }}</textarea><br />
    </div>

    <div class="col-xs-12 col-md-12">
      <label>Full description</label><br />
      <textarea name="description" class="form-control textarea" rows="8">{{ $d->description }}</textarea>
      <br />
    </div>


    <div class="col-xs-12 col-md-6 col-xs-offset-0 col-md-offset-3">
      <input type="submit" name="sb" value="Save" class="btn btn-primary btn-block">
    </div>

  </form>

@endsection
