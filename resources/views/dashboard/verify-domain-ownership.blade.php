@extends('layouts/app')

@section('section_title', 'Verify Domain Ownership')

@section('content')
  <div class="container">
    <div class="row">

      @include( 'dashboard/navi' )

      <div class="col-md-9">
        <div class="panel panel-default">
          <div class="panel-heading">Verify <strong>{{ $domain->domain }}</strong> Ownership</div>

          <div class="panel-body">

            <div class="alert alert-info">
              You may select either <strong>DNS entry</strong> or <strong>File Upload</strong> Verification.
            </div>

            <hr>

            <h3>DNS Verification</h3>

            @if (session('dnsEntries'))

              <strong>Sorry, we could not yet find the DNS entry, it might take up 24 hours untill the DNS
                will be propagated. Please try again later.</strong>

              <div class='alert alert-danger'>
                Found only these entries:<br>
                @foreach (session('dnsEntries') as $e)
                  {{ $e }}<br>
                @endforeach
              </div>
            @endif

            Add a TXT DNS record containing the following details
            <br>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Type</th>
                  <th>Name</th>
                  <th>Content</th>
              </thead>
              <tbody>
                <td>TXT</td>
                <td>@</td>
                <td>{{ md5($domain->id . $domain->domain) }}</td>
              </tbody>
            </table>

            <a href="/dashboard/verify-domain-dns/{{ $domain->domain }}" class="dns-verification btn btn-primary">
              I've added the DNS entry, validate my ownership
            </a>

            <hr>

            <h3>or File Upload Verification</h3>

            <div class="well" style="padding: 20px;">
              File name: {{ md5($domain->id . $domain->domain) }}.html<br>
              Upload an empty file with the name on your host so it would be accessible at
              <strong>
                <a href="http://{{ $domain->domain . '/' . md5($domain->id . $domain->domain) . '.html' }}"
                  target="_blank">
                  {{ $domain->domain . '/' . md5($domain->id . $domain->domain) . '.html' }}
                </a>
              </strong>
            </div>

            <a href="/dashboard/verify-domain-file/{{ $domain->domain }}" class="file-verification btn btn-default">
              I've uploaded the file, validate my ownership
            </a>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
