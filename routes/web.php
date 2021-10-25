<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
require __DIR__.'/auth.php';

Route::get('/clear-cache', function() {
	Artisan::call('cache:clear');
	return "Cache is cleared";
});

Route::get('/', 'Main@home');
Route::get( 'load_feature_domain', 'Main@load_feature_domain');
Route::get('domains', 'Main@all_domains');
Route::get('domains/{domain}', 'Main@domain_info');
Route::get('premium-domains', 'Main@premium_domains');
Route::post( 'ajax/domain_filtering', 'Ajax@domain_filtering' );
Route::post( 'ajax/table_domain_filtering', 'Ajax@table_domain_filtering' );
Route::get('price-drop-domains', 'Main@price_drop_domains');
Route::get( 'pricing', 'Main@plansAndPricing' );
Route::get( 'buy/{domain}', 'BuyController@selectPaymentMethod' );
Route::get( 'p-{any}', 'PageController@page' );
Route::get( 'info/{id}', function( $id )  {
	try  {
		$d = App\Models\Domain::find( $id );
		return redirect( '/' . $d->domain );
	} catch(  Exception $e ) {
		dd( $e->getMessage() );
	}
});

Route::post('/domain/postinsert', 'HomeController@postinsert');
Route::post('/domain/update_premium_data', 'HomeController@update_premium_data');
Route::post('/domain/update_domain_drop_price_value', 'Main@update_domain_drop_price_value');
Route::get('category/{id}', 'Main@category_domains');

Route::group(['middleware' => 'verified', 'prefix' => 'dashboard'], function () {

	Route::get('/', 'HomeController@index');
	Route::get( 'orders', 'HomeController@orders' );
	Route::get( 'view-order/{order}', 'HomeController@viewOrder' );
	Route::get( 'domains-overview', 'HomeController@domainsOverview' );

	Route::get( 'premium-submission-overview', 'HomeController@premiumdomainOverview' );
	Route::get( 'premium-domain-overview', 'HomeController@approved_premium_domain' );

	Route::get( 'price-drop-overview', 'HomeController@pricedropOverview' );
	Route::get( 'price-drop', 'HomeController@pricedrop' );

	Route::get('domains/add', 'HomeController@addDomain');
	Route::get('domains/bulk-add', 'HomeController@addDomainBulk');
	Route::post('domains/add', 'HomeController@addDomainProcess');

	Route::post('domains/bulk-add', 'HomeController@addDomainBulkProcess');
	Route::get('manage-domain/{domain}', 'HomeController@manage_domain');
	Route::post('manage-domain/{domain}', 'HomeController@manage_domain_update');

	Route::get('premium-manage-domain/{domain}', 'HomeController@premium_manage_domain');
	Route::post('premium-manage-domain/{domain}', 'HomeController@premium_manage_domain_update');


	Route::get('manage-pricedrop-domain/{domain}', 'HomeController@manage_pricedrop_domain');
	Route::post('manage-pricedrop-domain/{domain}', 'HomeController@manage_pricedrop_update');

	Route::get( 'payment-gateways', 'HomeController@paymentGateways');
	Route::post( 'payment-gateways', 'HomeController@processPaymentGateways');
	Route::get( 'my-profile', 'HomeController@myProfile');
	Route::post( 'my-profile', 'HomeController@processMyProfile');
	Route::get('marketing-tools', 'HomeController@marketingTools');
	Route::get( 'domain-offer-price', 'HomeController@offerlist');
	Route::get( 'offer_list_delete/{id}', 'HomeController@offer_list_delete');
	Route::post( 'update-account-credentials', 'HomeController@updateUserPassword');
	Route::get( 'subscription', 'HomeController@mySubscription');
	Route::get( 'subscribe', 'SubscriptionController@subscribe' );
	Route::get( 'subscribe/credit-card', 'SubscriptionController@credit_card' );
	Route::post( 'subscribe/credit-card-process', 'SubscriptionController@credit_card_processing' );
	Route::get( 'subscribe/success', 'SubscriptionController@success' );
	Route::get( 'subscribe/cancel-plan', 'SubscriptionController@cancelStripe' );
	Route::get( 'subscribe/paypal', 'SubscriptionController@paypal' );
	Route::get( 'subscribe/paypal-process', 'SubscriptionController@paypalProcessing' );
	Route::get('verify-domain-ownership/{domain}', 'HomeController@verifyDomainOwnership');
	Route::get('verify-domain-dns/{domain}', 'HomeController@verifyDNS');
	Route::get('verify-domain-file/{domain}', 'HomeController@verifyFile');

});

Route::get( 'user/{user}/{name}', 'ProfileController@profile' );

Route::post( 'make-offer', 'Ajax@make_offer' );
Route::post( 'make-financing', 'Ajax@make_financing' );

Route::get('checkout/credit-card', 'Checkout@credit_card');
Route::post('checkout/credit-card', 'Checkout@credit_card_processing');
Route::get('checkout/paypal', 'Checkout@paypal');
Route::get('checkout/success', 'Checkout@success');
Route::get('checkout/paypal-complete', 'Checkout@paypal_complete');
Route::get( 'checkout/escrow', 'Checkout@escrow' );
Route::post( 'checkout/confirm-escrow', 'Checkout@confirm_escrow' );

Route::get('contact', 'Main@contact');
Route::post('contact', 'Main@process_contact');

Route::any('admin/login', 'Admin@login');

Route::group(['middleware' => 'App\Http\Middleware\AdminMiddleware'], function () {

	Route::get('admin', 'Admin@dashboard');
	Route::get('admin/dashboard_load', 'Admin@dashboard_load');
	Route::get( 'admin/vendors', 'Admin@vendors');
	Route::get( 'admin/vendor-domains/{vendorId}', 'Admin@vendorsDomains');
	Route::get( 'admin/loginAs/{vendorId}', 'Admin@loginAsVendor');
	Route::get( 'admin/add-plan/{vendorId}', 'Admin@addPlanManually');
	Route::post( 'admin/add-plan/{vendorId}', 'Admin@addPlanManuallyProcess');

	Route::get('admin/domains', 'Admin@domains_overview');
	Route::get('admin/domains_view_detail/{id}', 'Admin@domains_view_detail');
	Route::get('admin/edit_domain_detail/{id}', 'Admin@edit_domain_detail');
	Route::post('admin/update_domain_detail/{id}', 'Admin@update_domain_detail');

	Route::get('admin/premium_domains', 'Admin@premium_domain_overview');
	Route::get('admin/edit_premium_domain_detail/{id}', 'Admin@edit_premium_domain_detail');
	Route::post('admin/update_premium_domain_detail/{id}', 'Admin@update_premium_domain_detail');

	Route::get('admin/price_drop_request', 'Admin@price_drop_domains');
	Route::get('admin/price_drop_view_detail/{id}', 'Admin@price_drop_view_detail');
	Route::get('admin/price_drop_edit_domain_detail/{id}', 'Admin@price_drop_edit_domain_detail');
	Route::post('admin/update_price_drop_detail/{id}', 'Admin@update_price_drop_detail');

	Route::get('admin/categories', 'Admin@categories_overview');
	Route::post('admin/add_category', 'Admin@add_category');
	Route::post('admin/update_category', 'Admin@update_category');

	Route::get( 'admin/payments-settings', 'Admin@paymentsSetup');
	Route::post( 'admin/save-payments-settings', 'Admin@paymentsSetupProcess');

	Route::get('admin/cms', ['uses' => 'Admin@pages', 'as' => 'admin-cms'] );
	Route::post('admin/cms', 'Admin@create_page');

	Route::get( 'admin/config-logins', 'Admin@configLogins');
	Route::post( 'admin/save-logins', 'Admin@saveLogins');

	Route::get( 'admin/domain_offers', 'Admin@offer_list');
	Route::get( 'admin/delete_offer_list/{id}', 'Admin@delete_offer_list');

	Route::get('admin/cms-edit/{id}', function($id) {
		$page = App\Models\Page::findOrFail($id);
		return view('admin.update-page')->with('p', $page)->with('active', 'pages');
	});

	Route::post('admin/cms-edit/{id}', function($id) {

		$page = App\Models\Page::findOrFail($id);
		$page->page_title = Request::get('page_title');
		$page->page_content = Request::get('page_content');
		$page->save();

		return redirect('admin/cms-edit/' . $id)->with('msg', 'Page successfully updated.');

	});

	Route::get('admin/cms-delete/{id}', function($id) {
		if( $id != 1 ) {
			App\Models\Page::destroy($id);
			$msg = 'Page successfully removed';
		} else {
			$msg = 'You cannot remove homepage sorry.';
		}
		return redirect()->route('admin-cms')->with('msg', $msg);
	});


	Route::get('admin/navigation', 'Admin@navigation');
	Route::post('admin/navigation', 'Admin@navigation_save');
	Route::get('admin/navigation/edit/{id}', function($id) {
		$nav_item = App\Models\Navi::findOrFail($id);
		return view('admin.navigation-edit')->with('n', $nav_item);
	});

	Route::post('admin/navigation/edit/{id}', function($id) {
		$nav_item = App\Models\Navi::findOrFail($id);
		$nav_item->title = request('title');
		$nav_item->url = request('url');
		$nav_item->target = request('target');
		$nav_item->save();
		return redirect('admin/navigation')->with('msg', 'Menu item successfully saved');
	});

	Route::get('admin/navigation/delete/{id}', function($id) {
		App\Models\Navi::destroy($id);
		return redirect('admin/navigation')->with('msg', 'Menu item successfully removed');
	});

	Route::get('admin/navigation-ajax-sort', function() {
		$navi_order = implode(',',request('navi_order'));
		App\Models\Options::update_option('navi_order', $navi_order);
		return "Order successfully saved";
	});

	Route::get('admin/configuration', function() {
		return view('admin.configuration')->with('active', 'config');
	});

	Route::post('admin/configuration', function() {
		$options = request()->except(['_token', 'sb_settings', 'admin_current_pass', 'admin_new_pass']);
		foreach( $options as $name => $value ) {
			App\Models\Options::update_option( $name, $value );
		}
		$headImage = '';
		if( request()->hasFile('homepage_header_image') ) {
			$ext = request()->file('homepage_header_image')->getClientOriginalExtension();
			$destinationPath = base_path() . '/image/';
			$fileName = uniqid(rand()) . '.' . $ext;
			request()->file('homepage_header_image')->move($destinationPath, $fileName);
			$headImage = App\Models\Options::update_option('homepage_header_image', '/image/' . $fileName);
		}
		$siteLogo = '';
		if( request()->hasFile('site_logo') ) {
			$ext = request()->file('site_logo')->getClientOriginalExtension();
			$destinationPath = base_path() . '/image/';
			$fileName = uniqid(rand()) . '.' . $ext;
			request()->file('site_logo')->move($destinationPath, $fileName);
			$siteLogo = App\Models\Options::update_option('site_logo', '/image/' . $fileName);
		}
		return redirect('admin/configuration')->with('msg', 'Configuration settings successfully saved!');
	});


});
