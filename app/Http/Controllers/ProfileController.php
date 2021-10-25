<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use App\Models\Domain;
use App\Models\Options;

class ProfileController extends Controller {

	public function profile(User $user, $name) {
		$page_title = $user->name . ' domains portfolio';
		if ($user->plan_expires < time()) {
			return redirect('/domains')->with('msg', 'VENDOR PLAN EXPIRED');
		}
		$domains = Domain::orderBy('domain', 'ASC')->where('is_verified', true);
		$domains = $domains->where('domain_status', '!=', 'SOLD');
		$domains = $domains->where('vendor_id', $user->id)->paginate(env('PAGINATION_PER_PAGE'));
		return view('user-profile', compact('page_title', 'domains', 'user'));
	}

}

