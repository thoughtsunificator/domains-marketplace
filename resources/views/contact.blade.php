@extends('layouts/app')
@section('seo_title') Contact - {{ \App\Models\Options::get_option('seo_title') }} @endsection

@section('section_title', 'Get in touch with us')

@section('content')
	<div class="container-fluid">
		<div class="container add-paddings">
			<div class="col-xs-12 col-xs-offset-0">
				<div class="row">
					<div class="col-lg-8 col-lg-offset-2">
						<form class="form-horizontal" method="post">
							{{ csrf_field() }}
							<div class="hp-css">
								<label>Message</label>
								<input type="text" name="tmessage" class="form-control">
							</div>
							<fieldset>
								@if (Session::has('message'))
									<div class="alert alert-warning">{{ Session::get('message') }}</div>
								@endif

								<div class="row">
									<div class="col-md-6 col-sm-12">
										<div class="form-group">
											<div class="input-group input-group-lg col-md-11">
												<span class="input-group-addon"><i class="fa fa-user"></i></span>
												<input type="text" class="form-control" name="name" value="{{ old('name') }}"
													placeholder="Your Name">

											</div>
										</div>
									</div>
									<div class="col-md-6 col-sm-12">
										<div class="form-group">
											<div class="input-group input-group-lg col-md-11">
												<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
												<input type="email" id="email" class="form-control" name="email" value="{{ old('email') }}"
													placeholder="Your email">
											</div>
										</div>
									</div>

									<div class="col-md-6 col-sm-12">
										<div class="form-group">
											<div class="input-group input-group-lg col-md-11">
												<span class="input-group-addon"><i class="fa fa-edit"></i></span>
												<input id="subject" name="subject" type="text" placeholder="Subject" class="form-control"
													value="{{ old('subject') }}">

											</div>
										</div>
									</div>
									<div class="col-md-6 col-sm-12">
										<div class="form-group">
											<div class="input-group input-group-lg col-md-11">
												<span class="input-group-addon"><i class="fa fa-info"></i></span>
												<input type="number" name="offer-answer" class="form-control"
													placeholder="How much is {{ $no1 . '+' . $no2 }} = ?">

											</div>
										</div>
									</div>

									<div class="col-md-12 col-sm-12">
										<div class="form-group">
											<textarea rows="9" class="form-control" id="message" name="message"
												placeholder="Your message">{{ old('message') }}</textarea>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="form-group">
										<div class="col-md-12 col-sm-12">

											<button type="submit" class="btn btn-inverse btn-block"
												style="font-size: 16px;background: #323c46;color: #ffffff;width: 100%;display: block;margin: 0;">
												<i class="fa fa-btn fa-paper-plane-o"></i> Send Message
											</button>
										</div>
									</div>
							</fieldset>
					</div>
				</div>
			</div>
			</form>
		</div>
	</div>
	</div>
	</div>
@endsection
