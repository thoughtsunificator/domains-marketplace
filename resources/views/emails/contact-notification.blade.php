@extends('emails.base')

@section('mail_subject')
  New Contact Form Notification!
@endsection

@section('email_title')
  New Contact Form Notification!
@endsection

@section('intro_heading')
  New contact message was received via contact form.
@endsection

@section('intro_message')

  <dl>
    <dt>Name</dt>
    <dd>{{ $input['name'] }}</dd>
    <dt>Email</dt>
    <dd>{{ $input['email'] }}</dd>
    <dt>Subject</dt>
    <dd>{{ $input['subject'] }}</dd>
    <dt>Message</dt>
    <dd>{{ $input['message'] }}</dd>
  </dl>

@endsection

@section('mail_content')

@endsection
