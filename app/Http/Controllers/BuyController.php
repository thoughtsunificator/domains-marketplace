<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Domain;

class BuyController extends Controller {

	public function selectPaymentMethod(Domain $domain) {
		$user = $domain->user;
		$userMeta = $user->user_meta;
		if ($domain->pricing == 0) {
			return redirect('/domains')->with('msg', 'This domain is not available for purchase. Only for make offers.');
		}
		$isPayPalEnabled = false;
		$isStripeEnabled = false;
		$isEscrowEnabled = false;
		if (isset($userMeta['paypal_enabled']) && $userMeta['paypal_enabled'] == 'Yes' && isset($userMeta['paypal_email']) && !empty($userMeta['paypal_email'])) {
			$isPayPalEnabled = true;
		}
		if (isset($userMeta['stripe_enabled']) && $userMeta['stripe_enabled'] == 'Yes' && isset($userMeta['stripe_public_key']) && isset($userMeta['stripe_private_key']) && !empty($userMeta['stripe_public_key']) && !empty($userMeta['stripe_private_key'])) {
			$isStripeEnabled = true;
		}
		if (isset($userMeta['escrow_enabled']) and ($userMeta['escrow_enabled'] == 'Yes')) {
			$isEscrowEnabled = true;
		}
		return view('checkout/select-payment-method', compact('domain', 'isPayPalEnabled', 'isStripeEnabled', 'isEscrowEnabled'));
	}

}

