@extends('emails.base')

@section('mail_subject')
  New Order Confirmation!
@endsection

@section('email_title')
  New Order Confirmation!
@endsection

@section('intro_heading')
  New Order was placed by {{ $order->customer }} ( {{ $order->email }} ) via {{ $order->payment_type }}
@endsection

@section('intro_message')
  @if ($order->payment_type == 'Escrow')
    Hello admin! Please get in touch with {{ $order->customer }} for Escrow arrangement!
    <br><br>

    Unlinke PayPal or Stripe which is fully automated, Escrow will have to be arranged between you as a seller and the
    buyer!
    <br><br>

    Fingers Crossed!
  @else
    Order is paid and confirmed and you should get in touch as soon as possible with the customer to arrange transfer
    details!
  @endif

  <br><br>
  You can reply directly to this email to get in touch with the customer: {{ $order->email }}
@endsection

@section('mail_content')


  Domain: {{ $domain->domain }}<br>
  Price: {{ App\Models\Options::get_option('currency_symbol') . number_format($order->total) }}

@endsection
