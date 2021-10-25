<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Options;
use App\Models\Offer;
use DB;
use Carbon\Carbon;

class Ajax extends Controller {

	public function make_offer(Request $r) {
		$rules = ['offer-name' => 'required', 'offer-email' => 'required|email', 'offer-price' => 'required', 'offer-message' => 'required', 'domainId' => 'required|exists:domains,id'];
		$this->validate($r, $rules);
		$domain = \App\Models\Domain::find($r->domainId);
		if (!$domain) {
			return response()->json(['message' => '<div class="alert alert-danger">Invalid Domain Id!</div>']);
		}
		$vendor = $domain->user;
		$offer = $r->all();
		$offerss = new Offer;
		$offerss->user_name = $offer['offer-name'];
		$offerss->email = $offer['offer-email'];
		$offerss->phone_no = $offer['offer-phone'];
		$offerss->remarks = $offer['offer-message'];
		$offerss->offer_price = $offer['offer-price'];
		$offerss->domain_id = $offer['domainId'];
		$offerss->is_read_admin = false;
		$offerss->is_read_seller = false;
		$offerss->datetime = Carbon::now();
		$offerss->date = Carbon::now();
		$offerss->save();
		\Mail::send('emails.admin-new-offer', ['offer' => $offer, 'domain' => $domain, 'vendor' => $vendor], function ($m) use ($offer, $domain, $vendor) {
			$m->from(env('MAIL_FROM_ADDRESS'), \App\Models\Options::get_option('site_title'));
			$m->replyTo($offer['offer-email'], $offer['offer-name']);
			$m->to($vendor->email, 'Admin')->subject('You\'ve got an Offer!');
		});
		$message = '<script>$(function() { $( ".make-offer-form-div" ).hide( "slow" ) });</script>';
		$message .= '<div class="alert alert-warning">You offer has been received! <br />We will get back to you soon.</div> Click outside of this window to close!';
		return response()->json(['message' => $message]);
	}

	public function make_financing(Request $r) {
		$rules = ['financing-name' => 'required', 'financing-email' => 'required|email', 'financing-months' => 'required', 'financing-answer' => 'required|integer', 'domainId' => 'required|exists:domains,id'];
		$this->validate($r, $rules);
		if ($r->total != $r->{'financing-answer'}) {
			return response()->json(['message' => '<div class="alert alert-danger">Hmmmm.. is your math so bad today?</div>']);
		}
		$domain = \App\Models\Domain::find($r->domainId);
		if (!$domain) {
			return response()->json(['message' => '<div class="alert alert-danger">Invalid Domain Id!</div>']);
		}
		$financing = $r->all();
		$vendor = $domain->user;
		\Mail::send('emails.admin-new-financing', ['offer' => $financing, 'domain' => $domain, 'vendor' => $vendor], function ($m) use ($financing, $domain, $vendor) {
			$m->from(env('MAIL_FROM_ADDRESS'), \App\Models\Options::get_option('site_title'));
			$m->replyTo($financing['financing-email'], $financing['financing-name']);
			$m->to($vendor->email, 'Admin')->subject('You\'ve got a financing request!');
		});
		$message = '<script>$(function() { $( ".make-financing-div" ).hide( "slow" ) });</script>';
		$message .= '<div class="alert alert-warning">You financing request has been received! <br />We will get back to you soon.</div> Click outside of this window to close!';
		return response()->json(['message' => $message]);
	}

	public function domain_filtering(Request $r) {
		$allowedSort = ['id.desc', 'pricing.asc', 'pricing.desc', 'domain.asc'];
		if (!in_array($r->sortby, $allowedSort)) {
			die('Invalid sort order');
		}
		$this->validate($r, ['category' => 'required|integer', 'age' => 'required|integer', 'length' => 'required|integer', 'pricing' => 'required|integer']);
		$orderBy = explode('.', $r->sortby);
		if ('pricing.desc' == $r->sortby || 'pricing.asc' == $r->sortby) {
			$domains = \App\Models\Domain::orderByRaw('(CASE WHEN (discount != 0 AND discount IS NOT NULL)
																								 THEN
																										discount
																								ELSE
																										pricing
																								END) ' . $orderBy[1]);
		} else {
			$domains = \App\Models\Domain::orderBy('domains.' . $orderBy[0], $orderBy[1]);
		}

		$domains->where('domain_status', '!=', 'SOLD');
		$domains->where('is_approved', true);
		$domains->join('users', 'domains.vendor_id', '=', 'users.id');
		$domains->where('users.plan', '!=', null);
		$domains->where('users.plan_expires', '>=', time());
		if ($r->category > 0) $domains->where('category', $r->category);
		if ($r->extension != '') $domains->where('domain', 'like', '%' . $r->extension);
		if ($r->keyword != '') $domains->where('domain', 'like', '%' . $r->keyword . '%');
		if ($r->pricing > 0) $domains->where('pricing', '>=', $r->pricing);
		$domains->where('is_verified', true);
		$d = $domains->get();
		if ($r->age > 0) {
			$d = $d->reject(function ($domain) use ($r) {
				return \App\Models\Domain::computeAge($domain->reg_date, '') <= $r->age;
			});
		}
		if ($r->length > 0) {
			$d = $d->filter(function ($domain) use ($r) {
				return \App\Models\Domain::getCharacterCount($domain->domain) >= $r->length;
			});
		}
		if (!count($d)) {
			return '<h3 class="text-center"><i class="fa fa-alert"></i> No domains matching the selected criteria</h3><br/><br/>';
		}
		$currentPage = intval($r->page);
		$d = $this->paginateCollection($d, env('PAGINATION_PER_PAGE', 20), $currentPage);
		$d->withPath('/domains');
		return view('ajax/all-domains-filtered')->with('domains', $d);
	}

	public function table_domain_filtering(Request $r) {
		$domains = \App\Models\Domain::orderBy('domains.' . 'id');
		$domains->where('is_approved', true);
		$domains->join('users', 'domains.vendor_id', '=', 'users.id');
		$domains->where('users.plan', '!=', null);
		$domains->where('users.plan_expires', '>=', time());
		if ($r->category > 0) {
			$domains->where('category', $r->category);
		}
		if ($r->extension != '') {
			$domains->where('domain', 'like', '%' . $r->extension);
		}
		if ($r->keyword != '') {
			$domains->where('domain', 'like', '%' . $r->keyword . '%');
		}
		if ($r->pricing > 0) {
			$domains->where('pricing', '>=', $r->pricing);
		}
		$domains->where('is_verified', true);
		$domains->where('price_drop', true);
		$domains->where('domain_status', '!=', 'SOLD');
		$d = $domains->get();
		if ($r->age > 0) {
			$d = $d->reject(function ($domain) use ($r) {
				return \App\Models\Domain::computeAge($domain->reg_date, '') <= $r->age;
			});
		}
		if ($r->length > 0) {
			$d = $d->filter(function ($domain) use ($r) {
				return \App\Models\Domain::getCharacterCount($domain->domain) >= $r->length;
			});
		}
		if (!count($d)) {
			return '<h3 class="text-center"><i class="fa fa-alert"></i> No domains matching the selected criteria</h3><br/><br/>';
		}
		$currentPage = intval($r->page);
		$d = $this->paginateCollection($d, env('PAGINATION_PER_PAGE', 20), $currentPage);
		$d->withPath('/price-drop-domains');
		return view('ajax/all-table-domains-filtered')->with('domains', $d);
	}

	public function paginateCollection($items, $perPage = 15, $page = null, $options = []) {
		$page = $page ? : (Paginator::resolveCurrentPage() ? : 1);
		$items = $items instanceof Collection ? $items : Collection::make($items);
		return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
	}

}

