<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Subscriptions;
use App\Models\Domain;
use App\Models\Options;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements MustVerifyEmail {

	use HasFactory;
	use Notifiable;

	protected $casts = ['user_meta' => 'array'];
	protected $fillable = ['name', 'email', 'token', 'password', 'profilePic', 'is_activated', 'headline', 'plan', 'plan_expires', 'user_meta', 'plan_gateway'];
	protected $hidden = ['password', 'remember_token', ];

	public static function getMetaField($meta_key, $default = '', $user = null) {
		if (is_null($user)) {
			$user = auth()->user();
		} else {
			$user = User::find($user);
		}
		$user_meta = $user->user_meta;
		if (isset($user_meta[$meta_key])) {
			return $user_meta[$meta_key];
		}
		return $default;
	}

	public function subscriptions() {
		return $this->hasMany(Subscriptions::class);
	}

	public function domains() {
		return $this->hasMany(Domain::class, 'vendor_id');
	}

	public function getPlanLimitAttribute($domainsToBeAdded) {
		$userPlan = strtolower($this->plan);
		if ($userPlan == 'unlimited') return false;
		$planLimit = (int)Options::get_option($userPlan . '_limit');
		$userCurrentDomainsCount = auth()->user()->domains()->count();
		return (bool)($userCurrentDomainsCount > $planLimit);
	}

	public function getProfileImageAttribute() {
		$pic = $this->profilePic;
		if (is_null($pic)) return 'default-picture.webp';
		return $pic;
	}

	public function getStripePublicAttribute() {
		$userMeta = $this->user_meta;
		if (isset($userMeta['stripe_enabled']) && $userMeta['stripe_enabled'] == 'Yes' && isset($userMeta['stripe_public_key']) && isset($userMeta['stripe_private_key']) && !empty($userMeta['stripe_public_key']) && !empty($userMeta['stripe_private_key'])) {
			return $userMeta['stripe_public_key'];
		}
		return 'N/A';
	}

	public function getStripePrivateAttribute() {
		$userMeta = $this->user_meta;
		if (isset($userMeta['stripe_enabled']) && $userMeta['stripe_enabled'] == 'Yes' && isset($userMeta['stripe_public_key']) && isset($userMeta['stripe_private_key']) && !empty($userMeta['stripe_public_key']) && !empty($userMeta['stripe_private_key'])) {
			return $userMeta['stripe_private_key'];
		}
		return 'N/A';
	}

	public function getPaypalEmailAttribute() {
		$userMeta = $this->user_meta;
		if (isset($userMeta['paypal_enabled']) && $userMeta['paypal_enabled'] == 'Yes' && isset($userMeta['paypal_email']) && !empty($userMeta['paypal_email'])) {
			return $userMeta['paypal_email'];
		}
		return 'N/A';
	}

}

