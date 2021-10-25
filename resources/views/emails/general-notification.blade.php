@extends('emails.base')

@section('mail_subject')
	{!! clean($data['subject']) !!}
@endsection

@section('email_title')
	{!! clean($data['subject']) !!}
@endsection

@section('intro_heading')
	{!! clean($data['intromessage']) !!}
@endsection

@section('intro_message')
	{!! clean($data['message']) !!}
@endsection

@section('mail_content', '')
