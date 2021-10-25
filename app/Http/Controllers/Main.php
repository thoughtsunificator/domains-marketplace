<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Domain;
use App\Models\Categories;
use App\Models\Options;
use DB;
use Carbon\Carbon;

class Main extends Controller {

	public function load_feature_domain() {
		$premium_domain = Domain::where('is_premium', true)->where('is_verified', true)->where('domain_status', 'AVAILABLE')->get();
		return view('premium-home')->with('premium_domain', $premium_domain);
	}

	public function home(Request $r) {
		$hostname = str_replace("www.", "", strtolower($_SERVER['SERVER_NAME']));
		$domain = Domain::where(\DB::raw('LOWER(domain)'), $hostname)->first();
		if ($domain) return redirect(env('APP_URL') . '/' . $domain->domain);
		$domain_list = Domain::inRandomOrder()->where('is_verified', true)->where('is_approved', true);
		$domain_list = $domain_list->where('domain_status', '!=', 'SOLD');
		$premium_domain = Domain::where('is_premium', true)->where('domain_status', 'AVAILABLE')->where('is_verified', true)->where('is_approved', true)->join('users', 'domains.vendor_id', '=', 'users.id')->where('users.plan_expires', '>=', time())->get();
		$sold_domain = Domain::where('domain_status', 'SOLD')->where('is_approved', true)->where('is_verified', true)->get();
		$normal_domain = Domain::where('domain_status', 'AVAILABLE')->where('is_verified', true)->where('is_approved', true)->where('price_drop', true)->join('users', 'domains.vendor_id', '=', 'users.id')->where('users.plan_expires', '>=', time())->get();
		$domain_list = $domain_list->join('users', 'domains.vendor_id', '=', 'users.id')->where('users.plan_expires', '>=', time())->take(20)->get();
		foreach ($normal_domain as $normal_domain) {
			$to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $normal_domain->start_datetime);
			$from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $normal_domain->end_datetime);
			$carbon_date = \Carbon\Carbon::now();
			$diff_in_days = $from->diffInDays($carbon_date);
			$actual = $from->subDays($diff_in_days)->subHour(3);
			if ($normal_domain->days_difference > $diff_in_days) {
				if ($carbon_date == $actual or $carbon_date > $actual) {
					$final_days = $normal_domain->days_difference - ($diff_in_days);
					$minus_price_drop = $final_days * $normal_domain->price_drop_value;
					DB::table('domains')->where('id', $normal_domain->id) // find your user by their email
					->update(array('discount' => $normal_domain->pricing - $minus_price_drop, 'days_difference' => $final_days)); // update the record in the DB.

				}
			}
		}
		$normal_domain = Domain::where('domain_status', 'AVAILABLE')->where('is_verified', true)->where('price_drop', true)->orderBy('end_datetime', 'ASC')->get();
		$tlds = Domain::getAvailableExtensions();
		$domain_list->map(function ($d) {
			$d->domain_age = Domain::computeAge($d->reg_date, 0);
		});
		$categories = Categories::orderBy('catname', 'ASC')->get();
		$autoKeyword = '';
		$autoSearch = '';
		if ($r->has('keyword')) {
			$autoKeyword = trim(strip_tags($r->input('keyword')));
			$autoSearch = "yes";
		}
		return view('homepage')->with('domains', $domain_list)->with('isHome', true)->with('tlds', $tlds)->with('categories', $categories)->with('autoSearch', $autoSearch)->with('autoKeyword', $autoKeyword)->with('premium_domain', $premium_domain)->with('sold_domain', $sold_domain)->with('normal_domain', $normal_domain);
	}

	public function domain_info($domain) {
		$vendor = $domain->user;
		$no1 = rand(1, 5);
		$no2 = rand(1, 5);
		$total = $no1 + $no2;
		$domain->domain_age = Domain::computeAge($domain->reg_date, 0);
		$categories = Categories::inRandomOrder()->limit(10)->orderBy('catname', 'ASC')->get();
		if ($domain->domain_status == 'SOLD') {
			return view('sold-domain')->with('domain', $domain);
		}
		if ($vendor->plan_expires < time()) return redirect('/domains')->with('msg', 'VENDOR INACTIVE');
		if ($domain->domain_status == 'SOLD') {
			abort(404);
		}
		if (!$domain->is_verified) {
			return view('ownership-not-verified')->with('domain', $domain);
		}
		if (!$domain->is_approved) {
			return redirect('/domains')->with('msg', 'The domain ' . $domain->domain . ' has not been approved.');
		}
		$domain_list = Domain::inRandomOrder()->where('is_verified', true)->where('is_approved', true);
		$domain_list->where('domain_status', '!=', 'SOLD');
		$domain_list = $domain_list->join('users', 'domains.vendor_id', '=', 'users.id')->where('users.plan_expires', '>=', time())->get();
		$tlds = Domain::getAvailableExtensions();
		$domain_list->map(function ($d) {
			$d->domain_age = Domain::computeAge($d->reg_date, 0);
		});
		return view('domain-info')->with('domain', $domain)->with('no1', $no1)->with('no2', $no2)->with('total', $total)->with('categories', $categories)->with('tlds', $tlds)->with('domain_list', $domain_list);
	}

	public function update_domain_drop_price_value(Request $r) {
		if ($r->final_price > '0') {
			$domain_detail = DB::table('domains')->where('id', $r->domain)->first();
			$to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $domain_detail->start_datetime);
			$end = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $domain_detail->end_datetime);
			$carbon_date = \Carbon\Carbon::now();
			$diff_in_days = $end->diffInDays($carbon_date);
			$actual = $from->subDays($diff_in_days)->subHour(3);
			DB::table('domains')->where('id', $r->domain) // find your user by their email
			->update(array('discount' => $r->final_price, 'days_difference' => $diff_in_days)); // update the record in the DB.
			return response()->json(['success' => 'Ajax request submitted successfully', 'vaa' => $r->final_price, 'org' => $r->orignal_amount]);
		} else {
			DB::table('domains')->where('id', $r->domain)->delete();
			return response()->json(['success' => 'Delet Success']);
		}
	}

	public function all_domains(Request $request) {
		$domain_list = Domain::orderBy('domain', 'ASC')->where('is_verified', true);
		$domain_list = $domain_list->where('domain_status', '!=', 'SOLD');
		$domain_list = $domain_list->join('users', 'domains.vendor_id', '=', 'users.id')->where('is_approved', true)->where('users.plan_expires', '>=', time())->paginate(env('PAGINATION_PER_PAGE', 20));
		$domain_list->map(function ($d) {
			$d->domain_age = Domain::computeAge($d->reg_date, 0);
		});
		$tlds = Domain::getAvailableExtensions();
		$categories = Categories::orderBy('catname', 'ASC')->get();
		$autoKeyword = '';
		$autoSearch = '';
		if ($request->has('keyword')) {
			$autoKeyword = trim(strip_tags($request->input('keyword')));
			$autoSearch = "yes";
		}
		return view('all-domains')->with('domains', $domain_list)->with('tlds', $tlds)->with('categories', $categories)->with('autoSearch', $autoSearch)->with('autoKeyword', $autoKeyword);
	}

	public function premium_domains(Request $request) {
		$domain_list = Domain::orderBy('domain', 'ASC')->where('is_verified', true)->where('is_premium', true);
		$domain_list = $domain_list->join('users', 'domains.vendor_id', '=', 'users.id')->where('users.plan_expires', '>=', time())->paginate(env('PAGINATION_PER_PAGE', 20));
		$domain_list->map(function ($d) {
			$d->domain_age = Domain::computeAge($d->reg_date, 0);
		});
		$tlds = Domain::getAvailableExtensions();
		$categories = Categories::orderBy('catname', 'ASC')->get();
		$autoKeyword = '';
		$autoSearch = '';
		if ($request->has('keyword')) {
			$autoKeyword = trim(strip_tags($r->input('keyword')));
			$autoSearch = "yes";
		}
		return view('premium-domains')->with('domains', $domain_list)->with('tlds', $tlds)->with('categories', $categories)->with('autoSearch', $autoSearch)->with('autoKeyword', $autoKeyword);
	}

	public function price_drop_domains(Request $request) {
		$domain_list = Domain::orderBy('domain', 'ASC')->where('is_verified', true)->where('price_drop', true);
		$domain_list = $domain_list->join('users', 'domains.vendor_id', '=', 'users.id')->where('users.plan_expires', '>=', time())->paginate(env('PAGINATION_PER_PAGE', 20));
		$domain_list->map(function ($d) {
			$d->domain_age = Domain::computeAge($d->reg_date, 0);
		});
		$tlds = Domain::getAvailableExtensions();
		$categories = Categories::orderBy('catname', 'ASC')->get();
		$autoKeyword = '';
		$autoSearch = '';
		if ($request->has('keyword')) {
			$autoKeyword = trim(strip_tags($r->input('keyword')));
			$autoSearch = "yes";
		}
		$normal_domain = Domain::where('domain_status', 'AVAILABLE')->where('is_verified', true)->where('price_drop', true)->orderBy('end_datetime', 'ASC')->get();
		return view('price-drop-domains')->with('domains', $domain_list)->with('tlds', $tlds)->with('categories', $categories)->with('autoSearch', $autoSearch)->with('autoKeyword', $autoKeyword)->with('normal_domain', $normal_domain);
	}

	public function plansAndPricing() {
		return view('pricing');
	}

	public function contact() {
		$no1 = rand(1, 5);
		$no2 = rand(1, 5);
		$total = $no1 + $no2;
		return view('contact')->with('no1', $no1)->with('no2', $no2)->with('total', $total);;
	}

	public function process_contact(Request $r) {
		$validator = \Validator::make($r->all(), ['name' => 'required', 'subject' => 'required', 'email' => 'required|email', 'message' => 'required|min:10', 'offer-answer' => 'required|integer', ]);
		if ($validator->fails()) {
			return redirect('contact')->withErrors($validator)->withInput();
		}
		$from = $r->input('email');
		try {
			\Mail::send('emails.contact-notification', ['input' => $r->all() ], function ($m) use ($from) {
				$m->to(Options::get_option('admin_email'), 'Site Admin')->subject('New Contact Form Message')->from($from);
			});
			return redirect('contact')->with('message', 'Thank you for contacting us, we will get back to you soon!');
		} catch(\Exception $e) {
			return redirect('contact')->with('message', $e->getMessage());
		}
	}

	public static function lchecker($l, $domain) {
		$url = 'http://crivion.com/envato-licensing/index.php';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 2);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'license_code=' . $l . '&blogURL=' . $domain);
		curl_setopt($ch, CURLOPT_USERAGENT, 'crivion/envato-license-checker-v1.0');
		$result = curl_exec($ch);
		if ($result !== 'LICENSE_VALID_AUTOUPDATE_ENABLED') return false;
		curl_close($ch);
		return true;
	}

	public function category_domains(Request $request, $id) {
		$domain_list = Domain::where('category', $id)->where('is_verified', true);
		$domain_list = $domain_list->join('users', 'domains.vendor_id', '=', 'users.id')->where('users.plan_expires', '>=', time())->paginate(env('PAGINATION_PER_PAGE', 20));
		$domain_list->map(function ($d) {
			$d->domain_age = Domain::computeAge($d->reg_date, 0);
		});
		$tlds = Domain::getAvailableExtensions();
		$categories = Categories::orderBy('catname', 'ASC')->get();
		$autoKeyword = '';
		$autoSearch = '';
		if ($request->has('keyword')) {
			$autoKeyword = trim(strip_tags($r->input('keyword')));
			$autoSearch = "yes";
		}
		return view('category-domains')->with('domains', $domain_list)->with('tlds', $tlds)->with('categories', $categories)->with('autoSearch', $autoSearch)->with('autoKeyword', $autoKeyword);
	}

}

