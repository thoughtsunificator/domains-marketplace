<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Options;
use App\Models\Subscriptions;

class SubscriptionController extends Controller {

	public function subscribe() {
		$this->middleware('auth');
		if (!request('plan')) return redirect('/pricing');
		$plan = request('plan');
		$planPricing = $this->__computePrice($plan);
		return view('dashboard/subscribe-select-payment', compact('plan', 'planPricing'));
	}
	// compute price based on Plan
	private function __computePrice($plan) {
		if (!in_array($plan, ['starter', 'pro', 'unlimited', 'Starter', 'Pro', 'Unlimited'])) {
			throw new \Exception('Invalid plan on price calculation');
		}
		$planPricing = Options::get_option(strtolower($plan) . '_price');
		return $planPricing;
	}

	public function credit_card() {
		$this->middleware('auth');
		if (!request('plan')) return redirect('/pricing');
		$plan = request('plan');
		$price = $this->__computePrice($plan);
		return view('subscribe.credit-card', ['price' => $price, 'plan' => $plan]);
	}

	public function credit_card_processing(Request $r) {
		$this->middleware('auth');
		$this->validate($r, ['stripeToken' => 'required', 'plan' => 'required|in:Starter,Pro,Unlimited']);
		// set stripe secret§
		\Stripe\Stripe::setApiKey(Options::get_option('STRIPE_SECRET_KEY'));
		// do we have plans created?
		$this->__createStripePlans();
		// Get the credit card details submitted by the form
		$token = $r->stripeToken;
		// get plan data
		$plan = $r->plan;
		// get plan pricing
		$pricing = $this->__computePrice($plan);
		$price = $pricing;
		// compute stripe plan id
		if ($plan == 'Pro') $stripePlan = 'ProDomainer';
		elseif ($plan == 'Starter') $stripePlan = 'StarterDomainer';
		elseif ($plan == 'Unlimited') $stripePlan = 'UnlimitedDomainer';
		else throw new \Exception('Invalid plan on conversion to stripe plan.');
		// Create the charge on Stripe's servers - this will charge the user's card
		try {
			// Create Stripe Customer
			$customer = \Stripe\Customer::create(["description" => Options::get_option('site_title') . " Subscription", "source" => $token]);
			// Create Subscription
			$subscription = \Stripe\Subscription::create(["customer" => $customer->id, "items" => [["plan" => $stripePlan, ], ]]);
			// dd( $subscription );
			// get subscription id
			$subscriptionID = $subscription->id;
			// append card info
			$s['metadata']['last4'] = $r->last4;
			$s['metadata']['expiry'] = $r->expDate;
			$s['metadata']['token'] = $token;
			// save this order in database
			$subPlan = new Subscriptions;
			$subPlan->plan = $plan;
			$subPlan->user_id = auth()->user()->id;
			$subPlan->subscription_id = $subscriptionID;
			$subPlan->gateway = 'Credit Card';
			$subPlan->subscription_date = time();
			$subPlan->subscription_status = 'Active';
			$subPlan->subscription_price = $price;
			$subPlan->save();
			// get user
			$user = auth()->user();
			// update this user with plan expiration date
			$user->plan_expires = strtotime("+1 Month");
			$user->plan_gateway = 'Credit Card';
			$user->plan = $plan;
			$user->save();
			// mail the admin
			$data['message'] = _(sprintf('Hello,
																<br>You have just got a new subscriber which
																got a <strong>%s Plan</strong>!
																<br>Go to admin panel for details<br>
																<a href="' . env('APP_URL') . '/admin">' . env('APP_URL') . '/admin</a>', $plan));
			$data['intromessage'] = _('New Subscriber Notification!');
			$data['subject'] = _('New Subscriber Notification!');
			// get admin email
			$adminEmail = Options::get_option('admin_email');
			\Mail::send('emails.general-notification', ['data' => $data], function ($m) use ($adminEmail, $data) {
				$m->from(env('MAIL_FROM_ADDRESS'), Options::get_option('site_title'));
				$m->to($adminEmail);
				$m->subject($data['subject']);
			});
			// redirect with success message ( checkout/success )
			return redirect('/dashboard/subscribe/success');
		}
		catch(\Exception $e) {
			return back()->withErrors([$e->getMessage() ])->withInput();
		}
	}

	private function __createStripePlans() {
		try {
			// set stripe secret
			\Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
			// get the list of plans
			$stripePlans = \Stripe\Plan::all(["limit" => 100]);
			$hasStarter = false;
			$hasPro = false;
			$hasUnlimited = false;
			$stripePlans = $stripePlans->__toArray();
			$stripePlans = $stripePlans['data'];
			// check if plans exists
			foreach ($stripePlans as $plan) {
				if ($plan->id == 'StarterDomainer') $hasStarter = true;
				if ($plan->id == 'ProDomainer') $hasPro = true;
				if ($plan->id == 'UnlimitedDomainer') $hasUnlimited = true;
			}
			// if starter doesn't exist, create it
			if (!$hasStarter) {
				$price = $this->__computePrice('starter');
				\Stripe\Plan::create(["amount" => $price * 100, "interval" => "month", "product" => ["name" => "Starter - Monthly"], "currency" => Options::get_option('currency_code'), "id" => "StarterDomainer"]);
			}
			// if Pro plan doesn't exist, create it
			if (!$hasPro) {
				$price = $this->__computePrice('pro');
				\Stripe\Plan::create(["amount" => $price * 100, "interval" => "month", "interval_count" => 1, "product" => ["name" => "Pro - Monthly"], "currency" => Options::get_option('currency_code'), "id" => "ProDomainer", ]);
			}
			// if unlimited doesn't exist, create it
			if (!$hasUnlimited) {
				$price = $this->__computePrice('unlimited');
				\Stripe\Plan::create(["amount" => $price * 100, "interval" => "month", "product" => ["name" => "Unlimited - Monthly"], "currency" => Options::get_option('currency_code'), "id" => "UnlimitedDomainer"]);
			}
			return \Stripe\Plan::all(["limit" => 100]);
		}
		catch(\Exception $e) {
			die($e->getMessage());
		}
	}

	public function cancelStripe() {
		$this->middleware('auth');
		// get user
		$user = auth()->user();
		if ($user->plan_expires < time()) dd('NO_ACTIVE_PLAN');
		// get this user subscription id
		$subscription = Subscriptions::where('user_id', $user->id)->where('gateway', 'Credit Card')->where('subscription_status', 'Active')->orderBy('id', 'DESC')->firstOrFail();
		try {
			// set stripe secret
			\Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
			// cancel via stripe
			$id = $subscription->subscription_id;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/subscriptions/' . $id);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
			curl_setopt($ch, CURLOPT_USERPWD, env('STRIPE_SECRET_KEY') . ':' . '');
			$result = curl_exec($ch);
			if (curl_errno($ch)) {
				throw new \Exception('Error:' . curl_error($ch));
			}
			curl_close($ch);
			// result decode
			$result = json_decode($result);
			if (isset($result->error)) throw new \Exception($result->error->message);
			// cancel subscription
			$subscription->subscription_status = 'Canceled';
			$subscription->save();
			return back()->with('msg', 'Your subscription has been cancelled');
		}
		catch(\Exception $e) {
			return back()->with('msg', 'ERROR: ' . $e->getMessage());
		}
	}

	public function success() {
		return view('subscribe.success');
	}

	public function paypal(Request $r) {
		$this->middleware('auth');
		$this->validate($r, ['plan' => 'required:in:Starter,Pro,Unlimited']);
		// get user id
		$user = auth()->user()->id;
		// get plan data
		$plan = $r->plan;
		// get plan pricing
		$pricing = $this->__computePrice($plan);
		return view('subscribe.paypal', compact('plan', 'pricing'));
	}

	public function paypalProcessing(Request $request) {
		$this->validate($request, ['custom' => 'required']);
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
			error_log('IPN_POSTED_SUCCESSFULLY_CHECKOUTController_LINE_391');
		}
		curl_close($ch);
		if (strcmp($res, "VERIFIED") == 0) {
			if ($_POST['txn_type'] != 'subscr_signup') {
				error_log('SUBSCR_TYPE IS NOT subscr_signup BUT ' . $_POST['txn_type']);
				exit;
			}
			$receiver_email = $_POST['receiver_email'];
			if (Options::get_option('paypal_email', 'crivion@gmail.com') != $receiver_email) {
				error_log('RECEIVER_EMAIL = ' . $receiver_email);
				error_log('SHOULD_BE = ' . Options::get_option('paypal_email'));
				exit;
			}
			$custom = trim(strip_tags($_POST['custom']));
			$custom = base64_decode($custom);
			list($plan, $userId) = explode("_", $custom);
			$user = \App\Models\User::findOrFail($userId);
			$price = $this->__computePrice($plan);
			$subPlan = new Subscriptions;
			$subPlan->plan = $plan;
			$subPlan->user_id = $user->id;
			$subPlan->subscription_id = trim(strip_tags($_POST['subscr_id']));
			$subPlan->gateway = 'PayPal';
			$subPlan->subscription_date = time();
			$subPlan->subscription_status = 'Active';
			$subPlan->subscription_price = $price;
			$subPlan->save();
			$user->plan_expires = strtotime("+1 Month");
			$user->plan_gateway = 'PayPal';
			$user->plan = $plan;
			$user->save();
			$data['message'] = _(sprintf('Hello,
																<br>You have just got a new subscriber which
																got a <strong>%s Plan</strong>!
																<br>Go to admin panel for details<br>
																<a href="' . env('APP_URL') . '/admin">' . env('APP_URL') . '/admin</a>', $plan));
			$data['intromessage'] = _('New Subscriber Notification!');
			$data['subject'] = _('New Subscriber Notification!');
			$adminEmail = Options::get_option('admin_email');
			\Mail::send('emails.general-notification', ['data' => $data], function ($m) use ($adminEmail, $data) {
				$m->from(env('MAIL_FROM_ADDRESS'), Options::get_option('site_title'));
				$m->to($adminEmail);
				$m->subject($data['subject']);
			});
			$log = '';
			foreach ($_POST as $K => $V) {
				$log .= $K . '=>' . $V . PHP_EOL;
			}
		} else {
			$log = '';
			foreach ($_POST as $K => $V) {
				$log .= $K . '=>' . $V . PHP_EOL;
			}
			error_log('Got Invalid Result for TXN_TYPE: ' . $_POST['txn_type']);
			error_log($log);
		}
	}

}

