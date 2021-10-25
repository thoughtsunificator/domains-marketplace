@extends('emails.base')

@section('mail_subject')
	Invoice!
@endsection

@section('email_title')
	{{ $domain->domain }} Invoice
@endsection

@section('intro_heading')
	{{ $domain->domain }} Invoice
@endsection

@section('intro_message')

	Hi There,
	<br><br>

	Thank you for choosing the domain-marketplace marketplace to purchase your new domain name.
	Please wire payment using the following instructions:
	<br><br>

	<strong>Amount:</strong> ${{ $domain->pricing }} <br>
	<strong>Account Name:</strong> domain-marketplace <br>
	<strong>Account Number:</strong> 123456789 <br>
	<strong>Routing Number:</strong> 123456789 <br>
	<strong>Swift Code:</strong> ABC123 <br>
	<strong>Bank Name:</strong> ABC Bank <br>
	<strong>Bank Address:</strong> ABC Bank <br>
	<strong>Reference:</strong> {{ $domain->domain }} <br>

	Once your payment is received. We'all work with you and the seller to translate this domain name
	securely into your own registrar account. The delivery is guaranted or we'll send you a full refund back.<br><br>
	<strong>Action Required:</strong> Please replay to this email and let us know when you have sent the wire so
	we can process it as quickly as possible.<br><br>
	Thanks<br>

@endsection
<br><br>

@section('mail_content')


@endsection
