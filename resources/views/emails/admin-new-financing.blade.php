@extends('emails.base')

@section('mail_subject')
  You've got a new financing request!
@endsection

@section('email_title')
  You've got a new financing request!
@endsection

@section('intro_heading')
  You've got a new financing request for {{ $offer['financing-months'] }} Months for domain {{ $domain->domain }}
  <br />
  Potential Customer: {{ $offer['financing-name'] }} ( {{ $offer['financing-email'] }} ) <br />
  Tel: @if (!empty($offer['financing-phone'])) {{ $offer['financing-phone'] }} @else --- @endif

  <br>

  Notes:
  @if (empty($offer['financing-notes']))
    No notes from potential customer!
  @else
    {{ $offer['financing-notes'] }}
  @endif
  <br>

  Actual Domain Price: {{ App\Models\Options::get_option('currency_symbol') . number_format($domain->pricing, 0) }}<br>

  <br>
  <h4>To reply to this offer just hit Reply in your Mail Client/Service and start discussing options to setup this between
    you and your customer.</h4>

@endsection

@section('intro_message', ' ')

@section('mail_content', ' ')
