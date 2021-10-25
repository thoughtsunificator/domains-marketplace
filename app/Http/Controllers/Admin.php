<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Navi;
use App\Models\Options;
use App\Models\Domain;
use App\Models\Orders;
use App\Models\Categories;
use App\Models\User;
use App\Models\Subscriptions;
use Mail;
use Carbon\Carbon;
use DB;

class Admin extends Controller {

	public function login() {
		$message = '';
		if (\Request::isMethod('post')) {
			$credentials = ['email' => request('ausername'), 'password' => request('apassword') ];
			if (\Auth::attempt($credentials)) {
				$user = auth()->user();
				if ($user->isAdmin) {
					return redirect('admin');
				} else {
					$message = 'Invalid admin login.';
				}
			} else {
				$message = 'Invalid login.';
			}
		}
		return view('admin-login')->with('message', $message);
	}

	public function logout() {
		\Session::forget('admin');
		return redirect('/admin/login');
	}

	public function configLogins() {
		return view('admin.config-logins')->with('active', 'admin-login');
	}

	public function saveLogins(Request $r) {
		$this->validate($r, ['admin_user' => 'required|email', 'admin_pass' => 'required|confirmed']);
		$user = auth()->user()->id;
		$user = User::findOrFail($user);
		$user->email = $r->admin_user;
		$user->password = \Hash::make($r->admin_pass);
		$user->save();
		return back()->with('msg', 'Successfully updated admin user details.');
	}

	public function dashboard_load() {
		$allVendors = User::orderBy('id', 'DESC')->get();
		$payingVendors = $allVendors->filter(function ($v) {
			return $v->plan_expires >= time() && !in_array($v->plan_gateway, ['- Free Trial -', '- Manual by ADMIN -']);
		});
		$freeTrialVendors = $allVendors->filter(function ($v) {
			return in_array($v->plan_gateway, ['- Free Trial -']);
		});
		$startOfMonth = Carbon::now()->startOfMonth()->timestamp;
		$monthEarnings = Subscriptions::whereBetween('subscription_date', [$startOfMonth, time() ])->sum('subscription_price');
		$date = \Carbon\Carbon::parse('31 days ago');
		$days = Subscriptions::select(array(\DB::raw('DATE(FROM_UNIXTIME(`subscription_date`)) as `date`'), \DB::raw('SUM(`subscription_price`) as `total`')))->where('subscription_date', '>', $date)->groupBy('date')->orderBy('date', 'DESC')->pluck('total', 'date');
		return view('admin.dashboard-load')->with('active', 'dashboard')->with('totalVendors', $allVendors->count())->with('freeTrialVendors', $freeTrialVendors->count())->with('monthEarnings', $monthEarnings)->with('earnings_30_days', $days)->with('totalPaying', $payingVendors->count());
	}

	public function dashboard() {
		$allVendors = User::orderBy('id', 'DESC')->get();
		$payingVendors = $allVendors->filter(function ($v) {
			return $v->plan_expires >= time() && !in_array($v->plan_gateway, ['- Free Trial -', '- Manual by ADMIN -']);
		});
		$freeTrialVendors = $allVendors->filter(function ($v) {
			return in_array($v->plan_gateway, ['- Free Trial -']);
		});
		$startOfMonth = Carbon::now()->startOfMonth()->timestamp;
		$monthEarnings = Subscriptions::whereBetween('subscription_date', [$startOfMonth, time() ])->sum('subscription_price');
		$date = \Carbon\Carbon::parse('31 days ago');
		$days = Subscriptions::select(array(\DB::raw('DATE(FROM_UNIXTIME(`subscription_date`)) as `date`'), \DB::raw('SUM(`subscription_price`) as `total`')))->where('subscription_date', '>', $date)->groupBy('date')->orderBy('date', 'DESC')->pluck('total', 'date');
		return view('admin.dashboard')->with('active', 'dashboard')->with('totalVendors', $allVendors->count())->with('freeTrialVendors', $freeTrialVendors->count())->with('monthEarnings', $monthEarnings)->with('earnings_30_days', $days)->with('totalPaying', $payingVendors->count());
	}

	public function vendors() {
		if (request('remove-vendor')) {
			$vendor = User::findOrFail(request('remove-vendor'));
			$vendorDomains = Domain::where('vendor_id', $vendor->id)->delete();
			$vendor->delete();
			return redirect('admin/vendors')->with('msg', 'Successfully deleted vendor and all the data associated with it');
		}
		$vendors = User::where('id', '>', 1)->orderBy('id', 'DESC')->get();
		$active = 'vendors';
		return view('admin.vendors', compact('vendors', 'active'));
	}

	public function vendorsDomains($vendorId) {
		$user = User::findOrFail($vendorId);
		$domains = $user->domains()->get();
		$active = 'vendors';
		return view('admin.vendors-domains', compact('domains', 'active', 'user'));
	}

	public function loginAsVendor($vendorId) {
		$user = User::findOrFail($vendorId);
		\Auth::loginUsingId($user->id);
		return redirect('/dashboard');
	}

	public function addPlanManually($vendorId) {
		$user = User::findOrFail($vendorId);
		$active = 'vendors';
		return view('admin.add-plan-manually', compact('active', 'user'));
	}

	public function addPlanManuallyProcess($vendorId, Request $r) {
		$this->validate($r, ['mm' => 'required|numeric', 'dd' => 'required|numeric', 'yy' => 'required|numeric']);
		$planExpires = mktime(0, 0, 0, $r->mm, $r->dd, $r->yy);
		$user = User::findOrFail($vendorId);
		$user->plan = $r->Plan;
		$user->plan_expires = $planExpires;
		$user->plan_gateway = '- Manual by ADMIN -';
		$user->save();
		return redirect('admin/vendors')->with('msg', $user->name . ' plan successfully updated');
	}

	public function domains_overview() {
		if ($removeId = request('remove')) {
			$d = Domain::findOrFail($removeId);
			$d->delete();
			return back()->with('msg', 'Successfully removed domain "' . htmlspecialchars($d->domain) . '"');
		}
		if ($verifyId = request('verify')) {
			$d = Domain::findOrFail($verifyId);
			$d->is_verified = true;
			$d->save();
			return back()->with('msg', 'Domain "' . htmlspecialchars($d->domain) . '" marked as ownership verified');
		}
		$domains = Domain::with('user')->where('price_drop', false)->where('is_premium', false)->orderBy('id', 'DESC')->get();
		$domains->map(function ($d) {
			$d->domain_age = Domain::computeAge($d->reg_date, 0);
		});
		return view('admin.domains-overview')->with('active', 'domains')->with('domains', $domains);
	}

	public function premium_domain_overview() {
		if ($removeId = request('remove')) {
			$d = Domain::findOrFail($removeId);
			$d->delete();
			return back()->with('msg', 'Successfully removed domain "' . htmlspecialchars($d->domain) . '"');
		}
		if ($verifyId = request('verify')) {
			$d = Domain::findOrFail($verifyId);
			$d->is_verified = true;
			$d->save();
			return back()->with('msg', 'Domain "' . htmlspecialchars($d->domain) . '" marked as ownership verified');
		}
		$domains = Domain::with('user')->where('is_premium', true)->orderBy('id', 'DESC')->get();
		$domains->map(function ($d) {
			$d->domain_age = Domain::computeAge($d->reg_date, 0);
		});
		return view('admin.domains-premium-overview')->with('active', 'premiumdomains')->with('domains', $domains);
	}

	public function price_drop_domains() {
		if ($removeId = request('remove')) {
			$d = Domain::findOrFail($removeId);
			$d->delete();
			return back()->with('msg', 'Successfully removed domain "' . htmlspecialchars($d->domain) . '"');
		}
		if ($verifyId = request('verify')) {
			$d = Domain::findOrFail($verifyId);
			$d->is_verified = true;
			$d->save();
			return back()->with('msg', 'Domain "' . htmlspecialchars($d->domain) . '" marked as ownership verified');
		}
		$domains = Domain::with('user')->where('price_drop', false)->orderBy('id', 'DESC')->get();
		$domains->map(function ($d) {
			$d->domain_age = Domain::computeAge($d->reg_date, 0);
		});
		return view('admin.pricedrop-domains-overview')->with('active', 'price_drop')->with('domains', $domains);
	}

	public function domains_view_detail($domainId, Request $r) {
		$domain = Domain::findOrFail($domainId);
		return view('admin.domain-view-detail')->with('domain_detail', $domain);
	}

	public function price_drop_view_detail($domainId, Request $r) {
		$domain = Domain::findOrFail($domainId);
		return view('admin.price-drop-view-detail')->with('domain_detail', $domain);
	}

	public function edit_domain_detail($domainId) {
		$domain = Domain::findOrFail($domainId);
		if (auth()->user()->plan_expires <= time()) {
			return redirect('dashboard/subscribe?plan=' . auth()->user()->plan);
		}
		$categories = \App\Models\Categories::all()->toArray();
		return view('admin.edit-domain-detail')->with('d', $domain)->with('categories', $categories);
	}

	public function update_domain_detail($domainId, Request $r) {
		$this->validate($r, ['domain' => 'required|min:3', 'start_datetime' => 'date_format:d-m-Y', 'end_datetime' => 'date_format:d-m-Y', 'pricing' => 'required|integer']);
		$domain = Domain::findOrFail($domainId);
		$domain->domain = $r->domain;
		$domain->start_datetime = $r->start_datetime;
		$domain->end_datetime = $r->end_datetime;
		$domain->price_drop_value = $r->price_drop_value;
		$domain->domain_status = 'AVAILABLE';
		$domain->pricing = $r->pricing;
		$domain->price_drop = $r->price_drop;
		$domain->save();
		if ($r->hasFile('domain_logo')) {
			if (@getimagesize($r->file('domain_logo'))) {
				$file = $r->file('domain_logo');
				$filename = \Illuminate\Support\Str::slug($r->domain) . '.' . $file->getClientOriginalExtension();
				$file->move(storage_path('app/public'), $filename);
				$img = \Image::make(storage_path('app/public') . '/' . $filename);
				$img->resize(null, 98, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				});
				$img->save(storage_path('app/public') . '/thumbnail-' . $filename);
				$domain->domain_logo = $filename;
				$domain->save();
			} else {
				return back()->with('msg', 'DOMAIN DETAILS SUCCESSFULLY SAVED HOWEVER THE IMAGE FAILED VALIDATIONS!');
			}
		}
		$domain->update($r->except(['sb', '_token', '_wysihtml5_mode']));
		$domain->save();
		return back()->with('msg', 'Update SuccessFully');
	}

	public function edit_premium_domain_detail($domainId) {
		$domain = Domain::findOrFail($domainId);
		if (auth()->user()->plan_expires <= time()) {
			return redirect('dashboard/subscribe?plan=' . auth()->user()->plan);
		}
		$categories = \App\Models\Categories::all()->toArray();
		return view('admin.edit-premium-domain-detail')->with('d', $domain)->with('categories', $categories);
	}

	public function update_premium_domain_detail($domainId, Request $r) {
		$domain = Domain::findOrFail($domainId);
		$domain->domain = $r->domain;
		$domain->start_datetime = $r->start_datetime;
		$domain->end_datetime = $r->end_datetime;
		$domain->price_drop_value = $r->price_drop_value;
		$domain->domain_status = 'AVAILABLE';
		$domain->pricing = $r->pricing;
		$domain->is_approved = $r->is_approved;
		$domain->save();
		if ($r->hasFile('domain_logo')) {
			if (@getimagesize($r->file('domain_logo'))) {
				$file = $r->file('domain_logo');
				$filename = \Illuminate\Support\Str::slug($r->domain) . '.' . $file->getClientOriginalExtension();
				$file->move(storage_path('app/public'), $filename);
				$img = \Image::make(storage_path('app/public') . '/' . $filename);
				$img->resize(null, 98, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				});
				$img->save(storage_path('app/public') . '/thumbnail-' . $filename);
				$domain->domain_logo = $filename;
				$domain->save();
			} else {
				return back()->with('msg', 'DOMAIN DETAILS SUCCESSFULLY SAVED HOWEVER THE IMAGE FAILED VALIDATIONS!');
			}
		}
		$domain->update($r->except(['sb', '_token', '_wysihtml5_mode']));
		$domain->save();
		if ($r->is_verified) {
			$userEmail = DB::table('domains')->join('users', 'users.id', '=', 'domains.vendor_id')->Select('users.email', 'domains.domain')->where('domains.id', $r->id)->first();
			$data['message'] = _(sprintf('Hello,
				<br>We Recieved Your Request
				<br>Admin  Approved your request <br>' . $userEmail->domain, $userEmail->domain));
			$data['intromessage'] = _('Premium domain Notification!');
			$data['subject'] = _('Premium domain Notification!');
			$Email = $userEmail->email;
			\Mail::send('emails.general-notification', ['data' => $data], function ($m) use ($Email, $data) {
				$m->from(env('MAIL_FROM_ADDRESS'), Options::get_option('site_title'));
				$m->to($Email);
				$m->subject($data['subject']);
			});
		}
		return back()->with('msg', 'Update SuccessFully');
	}

	public function price_drop_edit_domain_detail(Request $r, $domainId) {
		$domain = Domain::findOrFail($domainId);
		if (auth()->user()->plan_expires <= time()) {
			return redirect('dashboard/subscribe?plan=' . auth()->user()->plan);
		}
		$categories = \App\Models\Categories::all()->toArray();
		return view('admin.price-drop-edit-domain-detail')->with('d', $domain)->with('categories', $categories);
	}

	public function update_price_drop_detail(Request $r, $domainId) {
		$domain = Domain::findOrFail($domainId);
		$domain->domain_status = $r->domain_status;
		$domain->youtube_video_id = $r->youtube_video_id;
		$domain->domain = $r->domain;
		$domain->is_premium = $r->is_premium;
		$domain->domain_status = $r->domain_status;
		$domain->pricing = $r->pricing;
		$domain->start_datetime = $r->start_datetime;
		$domain->end_datetime = $r->end_datetime;
		$domain->price_drop_value = $r->price_drop_value;
		$domain->registrar = $r->registrar;
		$domain->reg_date = $r->reg_date;
		$domain->category = $r->category;
		$domain->description = $r->description;
		$domain->is_verified = $r->is_verified;
		$domain->save();
		if ($r->hasFile('domain_logo')) {
			if (@getimagesize($r->file('domain_logo'))) {
				$file = $r->file('domain_logo');
				$filename = \Illuminate\Support\Str::slug($r->domain) . '.' . $file->getClientOriginalExtension();
				$file->move(storage_path('app/public'), $filename);
				$img = \Image::make(storage_path('app/public') . '/' . $filename);
				$img->resize(null, 98, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				});
				$img->save(storage_path('app/public') . '/thumbnail-' . $filename);
				$domain->domain_logo = $filename;
				$domain->save();
			} else {
				return back()->with('msg', 'DOMAIN DETAILS SUCCESSFULLY SAVED HOWEVER THE IMAGE FAILED VALIDATIONS!');
			}
		}
		$domain->update($r->except(['sb', '_token', '_wysihtml5_mode']));
		$domain->youtube_video_id = $r->youtube_video_id;
		$domain->save();
		return back()->with('msg', 'Update SuccessFully');
	}

	public function categories_overview(Request $request) {
		if ($removeId = $request->input('remove')) {
			$containsDomains = Domain::where('category', $removeId)->count();
			if ($containsDomains != 0) {
				return redirect('admin/categories')->with('msg', 'Sorry, this category contains domains. You can only remove categories that have 0 domains inside. Move the domains from this category to another then remove this one.');
			}
			$d = Categories::findOrFail($removeId);
			$d->delete();
			return redirect('admin/categories')->with('msg', 'Successfully removed category "' . e($d->catname) . '"');
		}
		$catname = '';
		$catID = '';
		if ($updateCat = $request->input('update')) {
			$c = Categories::findOrFail($updateCat);
			$catname = $c->catname;
			$catID = $c->catID;
		}
		$categories = Categories::orderBy('catname', 'ASC')->get();
		return view('admin.categories')->with('active', 'categories')->with('categories', $categories)->with('catname', $catname)->with('catID', $catID);
	}

	public function add_category(Request $r) {
		$this->validate($r, ['catname' => 'required']);
		$c = new Categories;
		$c->catname = $r->catname;
		$c->save();
		return redirect('admin/categories')->with('msg', 'Category successfully created.');
	}

	public function update_category(Request $r) {
		$this->validate($r, ['catname' => 'required']);
		$c = Categories::findOrFail($r->catID);
		$c->catname = $r->catname;
		$c->save();
		return redirect('admin/categories')->with('msg', 'Category successfully updated.');
	}

	public function pages() {
		$pages = Page::all();
		return view('admin.pages')->with('pages', $pages)->with('active', 'pages');
	}

	public function create_page(Request $r) {
		$this->validate($r, ['page_title' => 'unique:pages|required']);
		$page = new Page;
		$page->page_title = $r->page_title;
		$page->page_slug = \Illuminate\Support\Str::slug($r->page_title);
		$page->page_content = $r->page_content;
		$page->save();
		return redirect()->route('admin-cms')->with('msg', 'Page successfully created');
	}

	public function navigation() {
		$navi_order = Options::get_option('navi_order');
		if ($navi_order && !empty($navi_order)) {
			$navi = Navi::orderByRaw("FIELD(id, $navi_order)")->get();
		} else {
			$navi = Navi::all();
		}
		return view('admin.navigation')->with('navi', $navi)->with('active', 'navi');
	}

	public function navigation_save(Request $r) {
		Navi::create($r->except('sb_navi', '_token'));
		return redirect('admin/navigation')->with('msg', 'Item successfully added to navigation');
	}

	public function appearance() {
		return view('admin.appearance')->with('active', 'appearance');
	}

	public function paymentsSetup() {
		return view('admin.payments-setup')->with('active', 'payments');
	}

	public function paymentsSetupProcess() {
		$options = request()->except('_token', 'sb_settings');
		foreach ($options as $name => $value) {
			Options::update_option($name, $value);
		}
		return redirect('admin/payments-settings')->with('msg', 'Payments & Plans settings successfully saved!');
	}

	public function offer_list() {
		$alloffer = DB::table('offers')->join('domains', 'domains.id', '=', 'offers.domain_id')->join('users', 'users.id', '=', 'domains.vendor_id')->select('domains.domain', 'offers.*', 'users.name', 'users.email')->get();
		return view('admin.offer-list')->with('active', 'offers')->with('alloffer', $alloffer);
	}

	public function delete_offer_list(Request $r, $id) {
		DB::table('offers')->where('id', $id)->delete();
		$alloffer = DB::table('offers')->join('domains', 'domains.id', '=', 'offers.domain_id')->join('users', 'users.id', '=', 'domains.vendor_id')->select('domains.domain', 'offers.*', 'users.name', 'users.email')->get();
		return view('admin.offer_list')->with('active', 'offers')->with('alloffer', $alloffer);
	}

}

