@extends('emails.base')

@section('mail_subject')
  You've got an offer!
@endsection

@section('email_title')
  You've got an offer!
@endsection

@section('intro_heading')
  You've got a new offer of {{ \App\Models\Options::get_option('currency_symbol') . $offer['offer-price'] }} for
  domain {{ $domain->domain }} <br />
  Potential Customer: {{ $offer['offer-name'] }} ( {{ $offer['offer-email'] }} )
  <br>
  Phone Number: {{ $offer['offer-phone'] }}<br>
  <strong>Notes:</strong> <br>
  @if (empty($offer['offer-message']))
    No notes from potential customer!
  @else
    {{ $offer['offer-message'] }}
  @endif
  <br>


  Offer Value: {{ \App\Models\Options::get_option('currency_symbol') . $offer['offer-price'] }}<br>
  Actual Domain Price: {{ App\Models\Options::get_option('currency_symbol') . number_format($domain->pricing, 0) }}<br>

  <br>

  <h4>To reply to this offer just hit Reply in your Mail Client/Service.</h4>

@endsection

@section('intro_message', ' ')

@section('mail_content', '')

@endsection
