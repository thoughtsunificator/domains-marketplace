@extends('emails.base')

@section('mail_subject')
  Your Order Confirmation!
@endsection

@section('email_title')
  Your Order Confirmation!
@endsection

@section('intro_heading')
  Hi {{ $order->customer }}, thanks for buying with us!
@endsection

@section('intro_message')

  @if ($order->payment_type == 'Escrow')
    Please allow up to 72 hours for us to contact and arrange Escrow Payment &amp; Transfer details!
  @else
    Please allow up to 72 hours for us to contact and arrange domain transfer details!
  @endif

  <br><br>
  You can reply directly to this email to get in touch with the vendor: {{ $vendor->email }}

@endsection

@section('mail_content')

  Domain: {{ $domain->domain }}<br>
  Price: {{ App\Models\Options::get_option('currency_symbol') . number_format($order->total) }}

@endsection
