@extends('layouts/app')

@section('seo_title') Plans & Pricing - {{ \App\Models\Options::get_option('seo_title') }} @endsection

@section('section_title', 'Plans & Pricing - Pick a Plan and Start Selling Today!')

@section('content')
	<div class="container">
		<div class="row">
			<div id="generic_price_table">
				<section>

					<div class="container">

						<div class="row">
							<div class="col-md-4">

								<div class="generic_content clearfix" style="border: 1px solid #ded5d54d;">

									<div class="generic_head_price clearfix">

										<div class="generic_head_content clearfix">

											<div class="head_bg"></div>
											<div class="head">
												<span>Starter</span>
											</div>

										</div>

										<div class="generic_price_tag clearfix">
											<span class="price">
												<span class="sign">{{ App\Models\Options::get_option('currency_symbol') }}</span>
												<span class="currency">{{ App\Models\Options::get_option('starter_price') }}</span>
												<span class="month">/MON</span>
											</span>
											<span class="price">
												<h5>Up to {{ App\Models\Options::get_option('starter_limit') }} domains
												</h5>
											</span>
										</div>

									</div>

									<div class="generic_feature_list">
										<ul>
											<li><span>Create Individual Domain Listings or Bulk Import Domains</span> </li>
											<li><span>Sell For Fixed Price or Accept Offers Directly to Your Inbox</span>
											</li>
											<li><span>Price Drop</span></li>
											<li><span>Get paid to your Own PayPal</span></li>
											<li><span>Take Credit Card Payments (if you have Stripe)</span></li>
											<li><span>Accept Bank Transfer Payments</span></li>
											<li><span>Use the ESCROW service of Your Own Choice</span></li>
										</ul>
									</div>

									<div class="generic_price_btn clearfix">
										@if (App\Models\Options::get_option('free_trial_enabled', 'Yes') == 'Yes')
											<center>
												<strong>- Free {{ App\Models\Options::get_option('free_trial_days') }}
													Days Trial -</strong>
												<br>
										@endif
										<br>
										</center>
										<a class="" href="@if (auth()->check()) /dashboard/subscribe?plan=Starter  @else /register?plan=Starter @endif">Sign up</a>
									</div>

								</div>

							</div>

							<div class="col-md-4">

								<div class="generic_content active clearfix" style="border: 1px solid #ded5d54d;">

									<div class="generic_head_price clearfix">

										<div class="generic_head_content clearfix">

											<div class="head_bg"></div>
											<div class="head">
												<span>Pro</span>
											</div>

										</div>

										<div class="generic_price_tag clearfix">
											<span class="price">
												<span class="sign">{{ App\Models\Options::get_option('currency_symbol') }}</span>
												<span class="currency">{{ App\Models\Options::get_option('pro_price') }}</span>
												<span class="month">/MON</span>
											</span>
											<span class="price">
												<h5>Up to {{ App\Models\Options::get_option('pro_limit') }} domains</h5>
											</span>
										</div>

									</div>

									<div class="generic_feature_list">
										<ul>
											<li><span>Create Individual Domain Listings or Bulk Import Domains</span></li>
											<li><span>Sell For Fixed Price or Accept Offers Directly to Your Inbox</span>
											</li>
											<li><span>Linkobot (your own audience on LinkedIn)</span></li>
											<li><span>Price Drop</span></li>
											<li><span>Get paid to your Own PayPal</span></li>
											<li><span>Take Credit Card Payments (if you have Stripe)</span></li>
											<li><span>Accept Bank Transfer Payments</span></li>
											<li><span>Use the ESCROW service of Your Own Choice</span></li>
										</ul>
									</div>

									<div class="generic_price_btn clearfix">
										@if (App\Models\Options::get_option('free_trial_enabled', 'Yes') == 'Yes')
											<center>
												<strong>- Free {{ App\Models\Options::get_option('free_trial_days') }}
													Days Trial -</strong>
												<br>
										@endif
										<br>

										</center>
										<a class="" href="@if (auth()->check()) /dashboard/subscribe?plan=Pro  @else /register?plan=Pro @endif">Sign up</a>
									</div>

								</div>

							</div>
							<div class="col-md-4">

								<div class="generic_content clearfix" style="border: 1px solid #ded5d54d;">

									<div class="generic_head_price clearfix">

										<div class="generic_head_content clearfix">

											<div class="head_bg"></div>
											<div class="head">
												<span>Go Unlimited</span>
											</div>

										</div>

										<div class="generic_price_tag clearfix">
											<span class="price">
												<span class="sign">{{ App\Models\Options::get_option('currency_symbol') }}</span>
												<span class="currency">{{ App\Models\Options::get_option('unlimited_price') }}</span>
												<span class="month">/MON</span>
											</span>
											<span class="price">
												<h5>Domainers Choice</h5>
											</span>
										</div>

									</div>

									<div class="generic_feature_list">
										<ul>
											<li><span>Create Individual Domain Listings or Bulk Import Domains</span></li>
											<li><span>Sell For Fixed Price or Accept Offers Directly to Your Inbox</span>
											</li>
											<li><span>Linkobot (your own audience on LinkedIn)</span></li>
											<li><span>Price Drop</span></li>
											<li><span>Get paid to your Own PayPal</span></li>
											<li><span>Take Credit Card Payments (if you have Stripe)</span></li>
											<li><span>Accept Bank Transfer Payments</span></li>
											<li><span>Use the ESCROW service of Your Own Choice</span></li>
										</ul>
									</div>

									<div class="generic_price_btn clearfix">
										@if (App\Models\Options::get_option('free_trial_enabled', 'Yes') == 'Yes')
											<center>
												<strong>- Free {{ App\Models\Options::get_option('free_trial_days') }}
													Days Trial -</strong>
												<br>
										@endif
										<br>

										</center>
										<a class="" href="@if (auth()->check()) /dashboard/subscribe?plan=Unlimited  @else /register?plan=Unlimited @endif">Sign up</a>
									</div>

								</div>

							</div>
						</div>

					</div>
				</section>

			</div>

		</div>
	</div>

@endsection
