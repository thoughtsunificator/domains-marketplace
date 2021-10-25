@extends('layouts/admin')

@section('section_title')
	<strong>General Configuration</strong>

	<form method="POST" enctype="multipart/form-data">
	@endsection

	@section('section_body')

		<dl>
			<dt>Enable Domain Logos?</dt>
			<dd>
				<input type="radio" name="enable_logos" value="No" @if ('No' == App\Models\Options::get_option('enable_logos')) checked @endif> No
				<input type="radio" name="enable_logos" value="Yes" @if ('Yes' == App\Models\Options::get_option('enable_logos')) checked @endif> Yes
			</dd>
		</dl>
		<dl>
			<dt>Contact Email</dt>
			<dd>
				<input type="text" name="contact_email" value="{{ App\Models\Options::get_option('contact_email') }}"
					class="form-control">
			</dd>
		</dl>
		<dl>
			<dt>Admin Email</dt>
			<dd>
				<input type="text" name="admin_email" value="{{ App\Models\Options::get_option('admin_email') }}"
					class="form-control">
			</dd>

		</dl>
		<input type="submit" name="sb_settings" value="Save" class="btn btn-primary">
	@endsection

	@section('extra_bottom')

		<div class="row">
			{{ csrf_field() }}

			<div class="col-xs-12"></div>
			<div class="col-xs-12 col-md-6">
				<div class="box">
					<div class="box-header with-border"><strong>SEO</strong></div>
					<div class="box-body">
						<dl>
							<dt>SEO Title Tag</dt>
							<dd><input type="text" name="seo_title" value="{{ App\Models\Options::get_option('seo_title') }}"
									class="form-control"></dd>
							<dt>SEO Description Tag</dt>
							<dd><input type="text" name="seo_desc" value="{{ App\Models\Options::get_option('seo_desc') }}"
									class="form-control"></dd>
							<dt>SEO Keywords</dt>
							<dd><input type="text" name="seo_keys" value="{{ App\Models\Options::get_option('seo_keys') }}"
									class="form-control"></dd>
							<dt>Site Title (appears in navigation bar)</dt>
							<dd><input type="text" name="site_title" value="{{ App\Models\Options::get_option('site_title') }}"
									class="form-control">
							</dd>
							<dt>Site Logo</dt>
							<dd><input type="file" name="site_logo" class="form-control"></dd>
							<dt>Homepage Header Image</dt>
							<dd><input type="file" name="homepage_header_image" class="form-control"></dd>
							<td>
								<h3>Header Icons</h3>
							</td>
							<dt>Enable Phone Icon?</dt>
							<dd>
								<select name="phoneIcon">
									<option value="No">-Select-</option>
									<option value="No" @if ('No' == App\Models\Options::get_option('phoneIcon')) selected @endif>No</option>
									<option value="Yes" @if ('Yes' == App\Models\Options::get_option('phoneIcon')) selected @endif>Yes</option>
								</select>
							</dd>
							<dt>Enable Facebook Link</dt>
							<dd>
							<dd>
								<select name="fbIcon">
									<option value="No">-Select-</option>
									<option value="No" @if ('No' == App\Models\Options::get_option('fbIcon')) selected @endif>No</option>
									<option value="Yes" @if ('Yes' == App\Models\Options::get_option('fbIcon')) selected @endif>Yes</option>
								</select>
							</dd>
							</dd>
							<dt>Enable Twitter Link</dt>
							<dd>
								<select name="twIcon">
									<option value="No">-Select-</option>
									<option value="No" @if ('No' == App\Models\Options::get_option('twIcon')) selected @endif>No</option>
									<option value="Yes" @if ('Yes' == App\Models\Options::get_option('twIcon')) selected @endif>Yes</option>
								</select>
							</dd>
							<dt>Enable Linkedin Link</dt>
							<dd>
								<select name="linkedIcon">
									<option value="No">-Select-</option>
									<option value="No" @if ('No' == App\Models\Options::get_option('linkedIcon')) selected @endif>No</option>
									<option value="Yes" @if ('Yes' == App\Models\Options::get_option('linkedIcon')) selected @endif>Yes</option>
								</select>
							</dd>
						</dl>
					</div>
				</div>
			</div>

			<div class="col-xs-12 col-md-6">
				<div class="box">
					<div class="box-header with-border"><strong>Homepage Headlines</strong></div>
					<div class="box-body">
						<dl>
							<dt>Homepage Headline</dt>
							<dd>
								<input type="text" name="homepage_headline"
									value="{{ App\Models\Options::get_option('homepage_headline') }}" class="form-control">
							</dd>
							<dt>Homepage Introductory Text</dt>
							<dd>
								<textarea name="homepage_intro" class="form-control"
									rows="5">{{ App\Models\Options::get_option('homepage_intro') }}</textarea>
							</dd>
							<dt>Homepage About Us</dt>
							<dd>
								<textarea name="about_us" class="form-control"
									rows="9">{{ App\Models\Options::get_option('about_us') }}</textarea>
							</dd>
							<dt>Homepage Title (Random Domains Heading)</dt>
							<dd>
								<input type="text" name="homepage_title" value="{{ App\Models\Options::get_option('homepage_title') }}"
									class="form-control">
							</dd>
							<dt>Homepage Subtitle (Under Random Domains Heading)</dt>
							<dd>
								<textarea name="homepage_text" class="form-control"
									rows="5">{{ App\Models\Options::get_option('homepage_text') }}</textarea>
							</dd>
							<dt>Phone Number</dt>
							<dd>
								<textarea name="phone_number" class="form-control"
									rows="1">{{ App\Models\Options::get_option('phone_number') }}</textarea>
							</dd>
							<dt>Facebook Link</dt>
							<dd>
								<textarea name="facebook_follow_us" class="form-control"
									rows="1">{{ App\Models\Options::get_option('facebook_follow_us') }}</textarea>
							</dd>
							<dt>Twitter Link</dt>
							<dd>
								<textarea name="twitter_follow_us" class="form-control"
									rows="1">{{ App\Models\Options::get_option('twitter_follow_us') }}</textarea>
							</dd>
							<dt>Linkedin Link</dt>
							<dd>
								<textarea name="linkedin_follow_us" class="form-control"
									rows="1">{{ App\Models\Options::get_option('linkedin_follow_us') }}</textarea>
							</dd>
						</dl>
					</div>
				</div>
			</div>

			<div class="col-xs-6">
				<input type="submit" name="sb_settings" value="Save" class="btn btn-block btn-primary">
			</div>

	</form>

	</div>
@endsection
