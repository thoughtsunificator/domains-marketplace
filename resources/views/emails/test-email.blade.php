@extends('emails.base')

@section('mail_subject')
  Email Configuration Test
@endsection

@section('email_title')
  Email Configuration Test
@endsection

@section('intro_heading')
  {{ $data['intromessage'] }}
@endsection

@section('intro_message')

  {{ $data['message'] }}

@endsection

@section('mail_content', '')
