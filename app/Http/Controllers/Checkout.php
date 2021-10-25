<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use PayPal;
use App\Models\Domain;
use App\Models\User;

class Checkout extends Controller {

	private function _isPaymentMethodEnabled(Domain $domain, $method) {
		$user = $domain->user;
		$userMeta = $user->user_meta;
		if ($method == 'PayPal') {
			if (isset($userMeta['paypal_enabled']) && $userMeta['paypal_enabled'] == 'Yes' && isset($userMeta['paypal_email']) && !empty($userMeta['paypal_email'])) {
				return true;
			}
		}
		if ($method == 'Stripe') {
			if (isset($userMeta['stripe_enabled']) && $userMeta['stripe_enabled'] == 'Yes' && isset($userMeta['stripe_public_key']) && isset($userMeta['stripe_private_key']) && !empty($userMeta['stripe_public_key']) && !empty($userMeta['stripe_private_key'])) {
				return true;
			}
		}
		if ($method == 'Escrow') {
			if (isset($userMeta['escrow_enabled']) and ($userMeta['escrow_enabled'] == 'Yes')) {
				return true;
			}
		}
		return false;
	}

	public function _isPlanActive(Domain $domain) {
		$user = $domain->user;
		return $user->plan_expires >= time();
	}

	public function credit_card() {
		if (!request('domain')) return redirect('/domains')->with('msg', 'Select a domain to checkout.');
		$domain = Domain::where('domain', request('domain'))->firstOrFail();
		if ($domain->pricing == 0) {
			return redirect('/domains')->with('msg', 'This domain is not available for purchase. Only for make offers.');
		}
		if (!$this->_isPaymentMethodEnabled($domain, 'Stripe')) {
			return redirect('/domains')->with('msg', 'Payment Method not enabled for this vendor.');
		}
		if (!$this->_isPlanActive($domain)) {
			return redirect('/domains')->with('msg', 'User Plan Inactive');
		}
		$userStripeKey = $domain->user->stripePublic;
		$stripePublicKey = e($userStripeKey);
		$envStripeKey = sprintf("STRIPE_PUBLISHABLE_KEY=%s", $stripePublicKey);
		putenv($envStripeKey);
		$price = $domain->pricing;
		if ($domain->discount > 0) {
			$price = $domain->discount;
		}
		return view('checkout.credit-card', compact('domain', 'price'));
	}

	public function credit_card_processing(Request $r) {
		$this->validate($r, ['stripeToken' => 'required', 'customer' => 'required', 'email' => 'required|email', 'domain' => 'required']);
		$domain = Domain::where('domain', $r->domain)->firstOrFail();
		$user = $domain->user;
		$price = $domain->pricing;
		if ($domain->discount > 0) {
			$price = $domain->discount;
		}
		$userStripePrivate = $user->stripePrivate;
		\Stripe\Stripe::setApiKey($userStripePrivate);
		$token = $r->stripeToken;
		try {
			$amount = $price * 100;
			if ($amount < 1) {
				throw new \Exception("Error. Total amount can't be less than 1.00", 1);
			}
			$charge = \Stripe\Charge::create(array("amount" => $amount, // amount in cents, again
			"currency" => \App\Models\Options::get_option('currency_code'), "source" => $token, "description" => "Web Domain: " . $domain->domain,));
			$order = new \App\Orders;
			$order->customer = $r->customer;
			$order->email = $r->email;
			$order->total = $price;
			$order->order_contents = $domain;
			$order->payment_type = 'Stripe';
			$order->order_status = 'Paid';
			$order->order_date = date("Y-m-d H:i:s");
			$order->save();
			$domain->domain_status = 'SOLD';
			$domain->save();
			$vendor = $user->email;
			\Mail::send('emails.user-order-confirmation', ['order' => $order, 'domain' => $domain, 'vendor' => $user], function ($m) use ($order, $vendor) {
				$m->from(env('MAIL_FROM_ADDRESS'), \App\Models\Options::get_option('site_title'));
				$m->to($order->email, $order->customer)->subject('Your Order Confirmation!');
				$m->replyTo($vendor);
			});
			\Mail::send('emails.admin-order-confirmation', ['order' => $order, 'domain' => $domain, 'vendor' => $user], function ($m) use ($order, $vendor) {
				$m->from(env('MAIL_FROM_ADDRESS'), \App\Models\Options::get_option('site_title'));
				$m->replyTo($order->email, $order->customer);
				$m->to($vendor, 'Admin')->subject('New Order Confirmation!');
			});
			return redirect('checkout/success');
		} catch(\Exception $e) {
			return redirect('checkout/credit-card?domain=' . $domain->domain)->withErrors([$e->getMessage() ])->withInput();
		}
	}

	public function success() {
		return view('checkout.success');
	}

	public function paypal() {
		if (!request('domain')) return redirect('/domains')->with('msg', 'Select a domain to checkout.');
		$domain = Domain::where('domain', request('domain'))->firstOrFail();
		if ($domain->pricing == 0) {
			return redirect('/domains')->with('msg', 'This domain is not available for purchase. Only for make offers.');
		}
		if (!$this->_isPaymentMethodEnabled($domain, 'PayPal')) {
			return redirect('/domains')->with('msg', 'Payment Method not enabled for this vendor.');
		}
		if (!$this->_isPlanActive($domain)) {
			return redirect('/domains')->with('msg', 'User Plan Inactive');
		}
		$userPaypalEmail = $domain->user->paypalEmail;
		$price = $domain->pricing;
		return view('checkout.paypal-redirect', compact('price', 'domain', 'userPaypalEmail'));
	}

	public function paypal_complete(Request $request) {
		$this->validate($r, ['custom' => 'required']);
		$raw_post_data = file_get_contents('php://input');
		$raw_post_array = explode('&', $raw_post_data);
		$myPost = array();
		foreach ($raw_post_array as $keyval) {
			$keyval = explode('=', $keyval);
			if (count($keyval) == 2) $myPost[$keyval[0]] = urldecode($keyval[1]);
		}
		$req = 'cmd=_notify-validate';
		if (function_exists('get_magic_quotes_gpc')) {
			$get_magic_quotes_exists = true;
		}
		foreach ($myPost as $key => $value) {
			if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
				$value = urlencode(stripslashes($value));
			}
			else {
				$value = urlencode($value);
			}
			$req .= "&$key=$value";
		}
		$ch = curl_init('https://ipnpb.paypal.com/cgi-bin/webscr');
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
		if (!($res = curl_exec($ch))) {
			error_log("Got " . curl_error($ch) . " when processing IPN data");
			curl_close($ch);
			exit;
		}
		else {
			error_log('IPN_POSTED_SUCCESSFULLY_CHECKOUTController_LINE_289');
		}
		curl_close($ch);
		if (strcmp($res, "VERIFIED") == 0) {
			$custom = trim(strip_tags($_POST['custom']));
			$custom = base64_decode($custom);
			$domain = Domain::find($custom);
			$user = $domain->user;
			if (!$domain) {
				error_log('Domain with id ' . intval($custom) . ' not found - PayPal IPN line 304 (Checkout Controller)');
			}
			$order = new \App\Orders;
			$order->customer = $r->first_name . ' ' . $r->last_name;
			$order->email = $r->payer_email;
			$order->total = $r->mc_gross;
			$order->order_contents = $domain;
			$order->payment_type = 'PayPal';
			$order->order_status = 'Paid';
			$order->order_date = date("Y-m-d H:i:s");
			$order->save();
			$domain->domain_status = 'SOLD';
			$domain->save();
			$vendor = $user->email;
			\Mail::send('emails.user-order-confirmation', ['order' => $order, 'domain' => $domain, 'vendor' => $user], function ($m) use ($order, $vendor) {
				$m->from(env('MAIL_FROM_ADDRESS'), \App\Models\Options::get_option('site_title'));
				$m->to($order->email, $order->customer)->subject('Your Order Confirmation!');
				$m->replyTo($vendor);
			});
			\Mail::send('emails.admin-order-confirmation', ['order' => $order, 'domain' => $domain, 'vendor' => $user], function ($m) use ($order, $vendor) {
				$m->from(env('MAIL_FROM_ADDRESS'), \App\Models\Options::get_option('site_title'));
				$m->replyTo($order->email, $order->customer);
				$m->to($vendor, 'Admin')->subject('New Order Confirmation!');
			});
		} else {
			$log = '';
			foreach ($_POST as $K => $V) {
				$log .= $K . '=>' . $V . PHP_EOL;
			}
			error_log('Got Invalid Result for TXN_TYPE: ' . $r->txn_type);
			error_log($log);
		}
	}

	public function escrow() {
		if (!request('domain')) return redirect('/domains')->with('msg', 'Select a domain to checkout.');
		$domain = Domain::where('domain', request('domain'))->firstOrFail();
		if ($domain->pricing == 0) {
			return redirect('/domains')->with('msg', 'This domain is not available for purchase. Only for make offers.');
		}
		if (!$this->_isPaymentMethodEnabled($domain, 'Escrow')) {
			return redirect('/domains')->with('msg', 'Payment Method not enabled for this vendor.');
		}
		if (!$this->_isPlanActive($domain)) {
			return redirect('/domains')->with('msg', 'User Plan Inactive');
		}
		$price = $domain->pricing;
		if ($domain->discount > 0) {
			$price = $domain->discount;
		}
		return view('checkout/escrow', compact('domain', 'price'));
	}

	public function confirm_escrow(Request $r) {
		$this->validate($r, ['customer' => 'required', 'email' => 'required|email', 'phoneNo' => 'required', 'domain' => 'required']);
		$domain = Domain::where('domain', $r->domain)->firstOrFail();
		$user = $domain->user;
		$vendor = $user;
		try {
			$amount = $domain->finalPrice;
			if ($amount < 1) {
				throw new \Exception("Error. Total amount can't be less than 1.00", 1);
			}
			$order = new \App\Orders;
			$order->customer = $r->customer . ' - Phone: ' . $r->phoneNo;
			$order->email = $r->email;
			$order->total = $domain->finalPrice;
			$order->order_contents = $domain;
			$order->payment_type = 'Escrow';
			$order->order_status = 'Pending';
			$order->order_date = date("Y-m-d H:i:s");
			$order->save();
			\Mail::send('emails.admin-order-confirmation', ['order' => $order, 'domain' => $domain, 'vendor' => $user], function ($m) use ($order, $vendor) {
				$m->from(env('MAIL_FROM_ADDRESS'), \App\Models\Options::get_option('site_title'));
				$m->replyTo($order->email, $order->customer);
				$m->to($vendor->email, 'Admin')->subject('New Escrow Request!');
			});
			\Mail::send('emails.user-order-confirmation', ['order' => $order, 'domain' => $domain, 'vendor' => $user], function ($m) use ($order, $vendor) {
				$m->from(env('MAIL_FROM_ADDRESS'), \App\Models\Options::get_option('site_title'));
				$m->to($order->email, $order->customer)->subject('Your Escrow Request!');
				$m->replyTo($vendor->email);
			});
			return redirect('checkout/success');
		} catch(\Exception $e) {
			return redirect('checkout/escrow?domain=' . $r->domain)->withErrors([$e->getMessage() ])->withInput();
		}
	}
}

