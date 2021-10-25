<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Domain;
use App\Models\Orders;
use App\Models\User;
use App\Models\Options;
use DB;
use Carbon\Carbon;

class HomeController extends Controller {

	public function __construct() {
		$this->middleware('auth');
	}

	public function index() {
		if (auth()->user()->plan_expires <= time()) {
			return redirect('dashboard/subscribe?plan=' . auth()->user()->plan);
		}
		$regexp = '"vendor_id":' . auth()->user()->id . ',';
		$mtd_count = Orders::where('order_date', '>=', \Carbon\Carbon::now()->startOfMonth())->where('order_status', '=', 'Paid')->where('order_contents', 'regexp', $regexp)->count();
		$earnings_mtd = Orders::where('order_date', '>=', \Carbon\Carbon::now()->startOfMonth())->where('order_status', '=', 'Paid')->where('order_contents', 'regexp', $regexp)->sum('total');
		$all_time_earnings = Orders::where('order_status', '=', 'Paid')->where('order_contents', 'regexp', $regexp)->sum('total');
		$all_time_sales = Orders::where('order_status', '=', 'Paid')->where('order_contents', 'regexp', $regexp)->count();
		$date = \Carbon\Carbon::parse('31 days ago');
		$days = Orders::select(array(\DB::raw('DATE(`order_date`) as `date`'), \DB::raw('SUM(`total`) as `total`')))->where('order_date', '>', $date)->where('order_contents', 'regexp', $regexp)->groupBy('date')->orderBy('date', 'DESC')->pluck('total', 'date');
		return view('dashboard')->with('active', 'dashboard')->with('mtd_count', $mtd_count)->with('earnings_mtd', $earnings_mtd)->with('all_time_sales', $all_time_sales)->with('all_time_earnings', $all_time_earnings)->with('earnings_30_days', $days);
	}

	public function orders(Request $request) {
		if (auth()->user()->plan_expires <= time()) {
			return redirect('dashboard/subscribe?plan=' . auth()->user()->plan);
		}
		if ($removeId = $request->input('remove')) {
			$d = Orders::findOrFail($removeId);
			$order_contents = $d->order_contents;
			$regexp = '"vendor_id":' . auth()->user()->id . ',';
			if (!preg_match("/" . $regexp . "/i", $order_contents)) return redirect('/dashboard/orders')->with('msg', 'Do not remove orders that are not yours.');
			$d->delete();
			return redirect('/dashboard/orders')->with('msg', 'Successfully removed order "#' . $d->id . '"');
		}
		$regexp = '"vendor_id":' . auth()->user()->id . ',';
		$orders = Orders::where('order_contents', 'regexp', $regexp)->orderBy('id', 'desc')->paginate(10);
		return view('dashboard/orders-overview')->with('orders', $orders);
	}

	public function addDomain() {
		if (auth()->user()->plan_expires <= time()) {
			return redirect('dashboard/subscribe?plan=' . auth()->user()->plan);
		}
		if (auth()->user()->planLimit && !auth()->user()->isAdmin) {
			return view('dashboard/limit-reached');
		}
		$categories = Categories::orderBy('catname', 'ASC')->get();
		return view('dashboard/add-domain', compact('categories'));
	}

	public function viewOrder(Orders $order) {
		if (auth()->user()->plan_expires <= time()) {
			return redirect('dashboard/subscribe?plan=' . auth()->user()->plan);
		}
		$order_contents = $order->order_contents;
		$regexp = '"vendor_id":' . auth()->user()->id . ',';
		if (!preg_match("/" . $regexp . "/i", $order_contents)) return redirect('/dashboard')->with('msg', 'This order is not yours, so you cannot view it.');
		return view('dashboard/view-order', compact('order'));
	}

	public function addDomainProcess(Request $r) {
		if (auth()->user()->planLimit && !auth()->user()->isAdmin) {
			return view('dashboard/limit-reached');
		}
		$this->validate($r, ['domain' => 'required', 'pricing' => 'required|numeric', 'domain_logo' => 'image', 'reg_date' => 'date_format:d-m-Y']);
		$newDomain = $r->except(['sb', '_token', '_wysihtml5_mode']);
		$newDomain['domain'] = str_ireplace(['http://', 'https://'], ['', ''], $newDomain['domain']);
		$newDomain['vendor_id'] = auth()->user()->id;
		$d = Domain::create($newDomain);
		if ($r->hasFile('domain_logo')) {
			if (@getimagesize($r->file('domain_logo'))) {
				$file = $r->file('domain_logo');
				$filename = \Illuminate\Support\Str::slug($r->domain) . '.' . $file->getClientOriginalExtension();
				$file->move(storage_path('app/public') . '/', $filename);
				$img = \Image::make(storage_path('app/public') . '/' . $filename);
				$img->resize(null, 98, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				});
				$img->save(storage_path('app/public') . '/' . 'thumbnail-' . $filename);
				$d->is_premium = false;
				$d->price_drop = false;
				$d->domain_logo = $filename;
				$d->youtube_video_id = $r->youtube_video_id;
				$d->save();
			} else {
				$d->is_premium = false;
				$d->price_drop = false;
				$d->domain_logo = 'default-logo.webp';
				$d->youtube_video_id = $r->youtube_video_id;
				$d->save();
				// redirect with message
				return redirect('/dashboard/manage-domain/' . $d->domain)->with('msg', 'Domain successfully created but the image failed validations!');
			}
		} else {
			$d->is_premium = false;
			$d->price_drop = false;
			$d->youtube_video_id = $r->youtube_video_id;
			$d->domain_logo = 'default-logo.webp';
			$d->save();
		}
		$validateMsg = '';
		if (auth()->user()->isAdmin) {
			$d->is_verified = true;
			$d->is_approved = true;
			$d->save();
			$validateMsg = ' Also, because you are logged in as admin, ownership was set to Verified and Approved!';
		}
		if ($d->is_premium) {
			return redirect('/dashboard/manage-domain/' . $d->domain)->with('msg', 'Thanks for your submission . Quick Note (we review Premium submissions Within 24 Hours ) Thank you' . $validateMsg);
		} else {
			return redirect('/dashboard/manage-domain/' . $d->domain)->with('msg', 'Thanks for your submission' . $validateMsg);
		}
	}

	public function add_price_drop() {
		if (auth()->user()->plan_expires <= time()) {
			return redirect('dashboard/subscribe?plan=' . auth()->user()->plan);
		}
		if (auth()->user()->planLimit && !auth()->user()->isAdmin) {
			return view('dashboard/limit-reached');
		}
		$categories = Categories::orderBy('catname', 'ASC')->get();
		return view('dashboard/add-price-drop', compact('categories'));
	}

	public function addPriceDropDomainProcess(Request $r) {
		if (auth()->user()->planLimit && !auth()->user()->isAdmin) {
			return view('dashboard/limit-reached');
		}
		$this->validate($r, ['domain' => 'required', 'pricing' => 'required|numeric', 'domain_logo' => 'image', 'reg_date' => 'date_format:d-m-Y']);
		$newDomain = $r->except(['sb', '_token', '_wysihtml5_mode']);
		$newDomain['domain'] = str_ireplace(['http://', 'https://'], ['', ''], $newDomain['domain']);
		$newDomain['vendor_id'] = auth()->user()->id;
		$d = Domain::create($newDomain);
		if ($r->hasFile('domain_logo')) {
			if (@getimagesize($r->file('domain_logo'))) {
				$file = $r->file('domain_logo');
				$filename = \Illuminate\Support\Str::slug($r->domain) . '.' . $file->getClientOriginalExtension();
				$file->move(storage_path('app/public') . '/', $filename);
				$img = \Image::make(storage_path('app/public') . '/' . $filename);
				$img->resize(null, 98, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				});
				$img->save(storage_path('app/public') . '/' . 'thumbnail-' . $filename);
				$d->price_drop_value = $r->price_drop_value;
				$d->end_datetime = $r->end_datetime;
				$d->price_drop = false;
				$d->is_premium = false;
				$d->domain_logo = $filename;
				$d->youtube_video_id = $r->youtube_video_id;
				$d->save();
			}
			else {
				$d->domain_logo = 'default-logo.webp';
				$d->price_drop = false;
				$d->is_premium = false;
				$d->price_drop_value = $r->price_drop_value;
				$d->end_datetime = $r->end_datetime;
				$d->youtube_video_id = $r->youtube_video_id;
				$d->save();
				return redirect('/dashboard/manage-pricedrop-domain/' . $d->domain)->with('msg', 'Domain successfully created but the image failed validations!');
			}
		} else {
			$d->price_drop = false;
			$d->is_premium = false;
			$d->price_drop_value = $r->price_drop_value;
			$d->end_datetime = $r->end_datetime;
			$d->youtube_video_id = $r->youtube_video_id;
			$d->domain_logo = 'default-logo.webp';
			$d->save();
		}
		$validateMsg = '';
		if (auth()->user()->isAdmin) {
			$d->is_verified = true;
			$d->is_approved = true;
			$d->save();
			$validateMsg = ' Also, because you are logged in as admin, ownership was set to Verified and Approved!';
		}
		if ($d->is_premium) {
			return redirect('/dashboard/manage-pricedrop-domain/' . $d->domain)->with('msg', 'Thanks for your submission . Quick Note (we review Premium submissions Within 24 Hours ) Thank you' . $validateMsg);
		} else {
			return redirect('/dashboard/manage-pricedrop-domain/' . $d->domain)->with('msg', 'Thanks for your submission' . $validateMsg);
		}
	}

	public function manage_domain(Domain $domain) {
		$this->_isDomainOwnedByThisUser($domain);
		// check if plan is needed
		if (auth()->user()->plan_expires <= time()) {
			return redirect('dashboard/subscribe?plan=' . auth()->user()->plan);
		}
		$categories = \App\Models\Categories::all()->toArray();
		return view('dashboard/manage-domain')->with('d', $domain)->with('categories', $categories);
	}

	public function manage_domain_update(\App\Models\Domain $domain, Request $r) {
		$this->validate($r, ['domain' => 'required', 'pricing' => 'required', 'reg_date' => 'date_format:d-m-Y']);
		$this->_isDomainOwnedByThisUser($domain);
		$domain->domain_status = $r->domain_status;
		$domain->youtube_video_id = $r->youtube_video_id;
		$domain->save();
		if ($r->hasFile('domain_logo')) {
			if (@getimagesize($r->file('domain_logo'))) {
				$file = $r->file('domain_logo');
				$filename = \Illuminate\Support\Str::slug($r->domain) . '.' . $file->getClientOriginalExtension();
				$file->move(storage_path('app/public') . '/', $filename);
				$img = \Image::make(storage_path('app/public') . '/' . $filename);
				$img->resize(null, 98, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				});
				$img->save(storage_path('app/public') . '/' . 'thumbnail-' . $filename);
				$domain->domain_logo = $filename;
				$domain->save();
			} else {
				return back()->with('msg', 'Domain details successfully saved however the image failed validations!');
			}
		}
		$domain->update($r->except(['sb', '_token', '_wysihtml5_mode']));
		$domain->youtube_video_id = $r->youtube_video_id;
		$domain->save();
		if ($domain->is_premium) {
			return back()->with('msg', 'Thanks for your submission . Quick Note (we review Premium submissions Within 24 Hours ) Thank you');
		} else {
			return back()->with('msg', 'Thanks for your submission.');
		}
	}

	public function premium_manage_domain(Domain $domain) {
		$this->_isDomainOwnedByThisUser($domain);
		if (auth()->user()->plan_expires <= time()) {
			return redirect('dashboard/subscribe?plan=' . auth()->user()->plan);
		}
		$categories = \App\Models\Categories::all()->toArray();
		return view('dashboard/premium-manage-domain')->with('d', $domain)->with('categories', $categories);
	}

	public function premium_manage_domain_update(\App\Models\Domain $domain, Request $r) {
		$this->validate($r, ['domain' => 'required', 'pricing' => 'required', 'reg_date' => 'date_format:d-m-Y']);
		$this->_isDomainOwnedByThisUser($domain);
		$domain->domain_status = $r->domain_status;
		$domain->youtube_video_id = $r->youtube_video_id;
		$domain->save();
		if ($r->hasFile('domain_logo')) {
			if (@getimagesize($r->file('domain_logo'))) {
				$file = $r->file('domain_logo');
				$filename = \Illuminate\Support\Str::slug($r->domain) . '.' . $file->getClientOriginalExtension();
				$file->move(storage_path('app/public') . '/', $filename);
				$img = \Image::make(storage_path('app/public') . '/' . $filename);
				$img->resize(null, 98, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				});
				$img->save(storage_path('app/public') . '/' . 'thumbnail-' . $filename);
				$domain->domain_logo = $filename;
				$domain->save();
			} else {
				return back()->with('msg', 'Domain details successfully saved however the image failed validations!');
			}
		}
		$domain->update($r->except(['sb', '_token', '_wysihtml5_mode']));
		$domain->youtube_video_id = $r->youtube_video_id;
		$domain->save();
		if ($domain->is_premium) {
			return back()->with('msg', 'Thank you for your submission . Please allow us 24 hours to review');
		} else {
			return back()->with('msg', 'Thank you for your submission . Please allow us 24 hours to review');
		}
	}

	public function manage_pricedrop_domain(Domain $domain) {
		$this->_isDomainOwnedByThisUser($domain);
		if (auth()->user()->plan_expires <= time()) {
			return redirect('dashboard/subscribe?plan=' . auth()->user()->plan);
		}
		$categories = \App\Models\Categories::all()->toArray();
		return view('dashboard/manage-pricedrop-domain')->with('d', $domain)->with('categories', $categories);
	}

	public function manage_pricedrop_update(\App\Models\Domain $domain, Request $r) {
		$this->validate($r, ['domain' => 'required', 'pricing' => 'required', 'reg_date' => 'date_format:d-m-Y', 'start_datetime' => 'required', 'price_drop_value' => 'required']);
		$this->_isDomainOwnedByThisUser($domain);
		$domain->domain_status = $r->domain_status;
		$domain->youtube_video_id = $r->youtube_video_id;
		$domain->save();
		if ($r->hasFile('domain_logo')) {
			if (@getimagesize($r->file('domain_logo'))) {
				$file = $r->file('domain_logo');
				$filename = \Illuminate\Support\Str::slug($r->domain) . '.' . $file->getClientOriginalExtension();
				$file->move(storage_path('app/public') . '/', $filename);
				$img = \Image::make(storage_path('app/public') . '/' . $filename);
				$img->resize(null, 98, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				});
				$img->save(storage_path('app/public') . '/' . 'thumbnail-' . $filename);
				$domain->domain_logo = $filename;
				$domain->save();
			} else {
				return back()->with('msg', 'Domain details successfully saved however the image failed validations!');
			}
		}
		$domain->update($r->except(['sb', '_token', '_wysihtml5_mode']));
		$domain->price_drop = false;
		$domain->is_premium = false;
		$domain->is_verified = false;
		$domain->price_drop_value = $r->price_drop_value;
		$domain->start_datetime = $r->start_datetime;
		$domain->end_datetime = Carbon::parse($r->start_datetime)->addDays(7);
		$domain->discount = 0;
		$domain->youtube_video_id = $r->youtube_video_id;
		$domain->save();
		if ($domain->is_premium) {
			return back()->with('msg', 'Thanks for your submission . Quick Note (we review Premium submissions Within 24 Hours ) Thank you');
		} else {
			return back()->with('msg', 'Thanks for your submission.');
		}
	}

	public function postinsert(Request $r) {
		$to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $r->start_datetime);
		$from_date = Carbon::parse($r->start_datetime)->addDays(7);
		$total_days = $to->diffInDays($from_date);
		DB::table('domains')->where('id', $r->domain_id)->update(['start_datetime' => $r->start_datetime, 'end_datetime' => Carbon::parse($r->start_datetime)->addDays(7), 'price_drop_value' => $r->price_drop_value, 'days_difference' => $total_days, 'pricing' => $r->pricing, 'price_drop' => true, 'discount' => 0, 'is_premium' => false, 'is_verified' => false]);
		return response()->json(['success' => 'Product saved successfully.']);
	}

	public function update_premium_data(Request $r) {
		DB::table('domains')->where('id', $r->domain_id)->update(['is_premium' => $r->is_premium, 'price_drop' => false, 'discount' => 0, 'is_verified' => false]);
		$userEmail = DB::table('domains')->join('users', 'users.id', '=', 'domains.vendor_id')->Select('users.email', 'domains.domain')->where('domains.id', $r->domain_id)->first();
		$data['message'] = _(sprintf('Hello,
				<br>We Recieved Your Request
				<br>Admin Will Approve your request then you will received notification in email<br>' . $userEmail->domain, $userEmail->domain));
		$data['intromessage'] = _('Premium domain Notification!');
		$data['subject'] = _('Premium domain Notification!');
		$Email = $userEmail->email;
		\Mail::send('emails.general-notification', ['data' => $data], function ($m) use ($Email, $data) {
			$m->from(env('MAIL_FROM_ADDRESS'), Options::get_option('site_title'));
			$m->to($Email);
			$m->subject($data['subject']);
		});
		$data1['message'] = _(sprintf('Hello,
				<br>You have just got a new Premium domain Notification
				<br>Go to admin panel for details<br>' . $userEmail->domain, $userEmail->domain));
		$data1['intromessage'] = _('New Premium domain!');
		$data1['subject'] = _('New Premium domain!');
		$adminEmail = Options::get_option('admin_email');
		\Mail::send('emails.general-notification', ['data' => $data1], function ($m) use ($adminEmail, $data1) {
			$m->from(env('MAIL_FROM_ADDRESS'), Options::get_option('site_title'));
			$m->to($adminEmail);
			$m->subject($data1['subject']);
		});
		return response()->json(['success' => 'Premium saved successfully.']);
	}

	public function domainsOverview(Request $request) {
		if (auth()->user()->plan_expires <= time()) {
			return redirect('dashboard/subscribe?plan=' . auth()->user()->plan);
		}
		if ($removeId = $request->input('remove')) {
			$d = Domain::findOrFail($removeId);
			$this->_isDomainOwnedByThisUser($d);
			$d->delete();
			return redirect('/dashboard/domains-overview')->with('msg', 'Successfully removed domain "' . $d->domain . '"');
		}
		$domains = Domain::where('vendor_id', auth()->user()->id)->where('price_drop', false)->where('is_premium', false)->where('domain_status', '!=', 'SOLD')->orderBy('domain', 'ASC')->paginate(25);
		$domains->map(function ($d) {
			$d->domain_age = Domain::computeAge($d->reg_date, 0);
		});
		return view('dashboard/domains-overview')->with('active', 'domains')->with('domains', $domains);
	}

	public function premiumdomainOverview(Request $request) {
		if (auth()->user()->plan_expires <= time()) {
			return redirect('dashboard/subscribe?plan=' . auth()->user()->plan);
		}
		if ($removeId = $request->input('remove')) {
			$d = Domain::findOrFail($removeId);
			$this->_isDomainOwnedByThisUser($d);
			$d->delete();
			return redirect('/dashboard/premium-overview')->with('msg', 'Successfully removed domain "' . $d->domain . '"');
		}
		$domains = Domain::where('vendor_id', auth()->user()->id)->where('is_premium', true)->where('domain_status', 'AVAILABLE')->orderBy('domain', 'ASC')->paginate(25);
		$domains->map(function ($d) {
			$d->domain_age = Domain::computeAge($d->reg_date, 0);
		});
		return view('dashboard/premium-domain-list')->with('active', 'domains')->with('domains', $domains);
	}

	public function approved_premium_domain(Request $request) {
		if (auth()->user()->plan_expires <= time()) {
			return redirect('dashboard/subscribe?plan=' . auth()->user()->plan);
		}
		if ($removeId = $request->input('remove')) {
			$d = Domain::findOrFail($removeId);
			$this->_isDomainOwnedByThisUser($d);
			$d->delete();
			return redirect('/dashboard/premium-overview')->with('msg', 'Successfully removed domain "' . $d->domain . '"');
		}
		$domains = Domain::where('vendor_id', auth()->user()->id)->where('domain_status', 'AVAILABLE')->where('is_premium', true)->where('is_approved', true)->orderBy('domain', 'ASC')->paginate(25);
		$domains->map(function ($d) {
			$d->domain_age = Domain::computeAge($d->reg_date, 0);
		});
		return view('dashboard/approved-premium')->with('active', 'domains')->with('domains', $domains);
	}

	public function all_domain(Request $request) {
		if (auth()->user()->plan_expires <= time()) {
			return redirect('dashboard/subscribe?plan=' . auth()->user()->plan);
		}
		if ($removeId = $request->input('remove')) {
			$d = Domain::findOrFail($removeId);
			$this->_isDomainOwnedByThisUser($d);
			$d->delete();
			return redirect('/dashboard/domains-overview')->with('msg', 'Successfully removed domain "' . $d->domain . '"');
		}
		$domains = Domain::where('vendor_id', auth()->user()->id)->where('price_drop', false)->orderBy('domain', 'ASC')->paginate(25);
		$domains->map(function ($d) {
			$d->domain_age = Domain::computeAge($d->reg_date, 0);
		});
		return view('dashboard/all_domains')->with('active', 'domains')->with('domains', $domains);
	}

	public function pricedrop(Request $request) {
		if (auth()->user()->plan_expires <= time()) {
			return redirect('dashboard/subscribe?plan=' . auth()->user()->plan);
		}
		if ($removeId = $request->input('remove')) {
			$d = Domain::findOrFail($removeId);
			$this->_isDomainOwnedByThisUser($d);
			$d->delete();
			return redirect('/dashboard/price-drop')->with('msg', 'Successfully removed domain "' . $d->domain . '"');
		}
		$domains = Domain::where('vendor_id', auth()->user()->id)->where('price_drop', false)->where('domain_status', 'AVAILABLE')->orderBy('domain', 'ASC')->paginate(25);
		$domains->map(function ($d) {
			$d->domain_age = Domain::computeAge($d->reg_date, 0);
		});
		return view('dashboard/price-drop')->with('active', 'domains')->with('domains', $domains);
	}

	public function pricedropOverview(Request $request) {
		if (auth()->user()->plan_expires <= time()) {
			return redirect('dashboard/subscribe?plan=' . auth()->user()->plan);
		}
		if ($removeId = $request->input('remove')) {
			$d = Domain::findOrFail($removeId);
			$this->_isDomainOwnedByThisUser($d);
			$d->delete();
			return redirect('/dashboard/price-drop-overview')->with('msg', 'Successfully removed domain "' . $d->domain . '"');
		}
		$domains = Domain::where('vendor_id', auth()->user()->id)->where('price_drop', true)->where('domain_status', 'AVAILABLE')->orderBy('domain', 'ASC')->paginate(25);
		$domains->map(function ($d) {
			$d->domain_age = Domain::computeAge($d->reg_date, 0);
		});
		return view('dashboard/price-drop-overview')->with('active', 'domains')->with('domains', $domains);
	}

	public function verifyDomainOwnership(Domain $domain) {
		if ($domain->vendor_id != auth()->user()->id) die('It looks like this listing is owned by another vendor.');
		return view('dashboard/verify-domain-ownership', compact('domain'));
	}

	public function verifyDNS(Domain $domain) {
		if ($domain->vendor_id != auth()->user()->id) die('It looks like this listing is owned by another vendor.');
		$code = md5($domain->id . $domain->domain);
		$dns = dns_get_record(strtolower($domain->domain), DNS_TXT);
		$dnsEntries = [];
		$dnsEntries[] = 'Starting DNS_TXT Check for ' . $domain->domain;
		foreach ($dns as $entry) {
			$dnsEntries[] = $entry['txt'] . ' ' . $entry['class'] . ' ' . reset($entry['entries']);
			$txt = $entry['txt'];
			if ($txt == $code or $txt == 'owner-of') {
				$domain->is_verified = true;
				$domain->save();
				return redirect('dashboard/domains-overview')->with('msg', 'Congratulations, you have successfully validated your domain ' . $domain->domain . ' ownership.');
			}
		}
		return back()->with('dnsEntries', $dnsEntries);
	}

	public function verifyFile(Domain $domain) {
		if ($domain->vendor_id != auth()->user()->id) die('It looks like this listing is owned by another vendor.');
		$url = 'http://' . $domain->domain . '/' . md5($domain->id . $domain->domain) . '.html';
		if (!$this->_urlExists($url)) return back()->with('msg', 'Could not access url ' . $url . ' - maybe your host is blocking this checker. Use DNS verification instead or check your host if they block a HEAD request to this url.');
		$domain->is_verified = true;
		$domain->save();
		return redirect('dashboard/domains-overview')->with('msg', 'Congratulations, you have successfully validated your domain ' . $domain->domain . ' ownership.');
	}
	private function _urlExists($url) {
		$result = false;
		$url = filter_var($url, FILTER_VALIDATE_URL);
		$handle = curl_init($url);
		curl_setopt_array($handle, array(CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_NOBODY => true,
		CURLOPT_HEADER => false,
		CURLOPT_RETURNTRANSFER => false,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_SSL_VERIFYPEER => false
		));
		$response = curl_exec($handle);
		$httpCode = curl_getinfo($handle, CURLINFO_EFFECTIVE_URL);
		$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		if ($httpCode == 200) {
			$result = true;
		}
		return $result;
		curl_close($handle);
	}

	private function _isDomainOwnedByThisUser($d) {
		if ($d->vendor_id != auth()->user()->id) die('It looks like this listing is owned by another vendor.');
	}

	public function paymentGateways() {
		if (auth()->user()->plan_expires <= time()) {
			return redirect('dashboard/subscribe?plan=' . auth()->user()->plan);
		}
		return view('dashboard/payment-gateways')->with('active', 'payment-gateways');
	}

	public function processPaymentGateways(Request $r) {
		$this->validate($r, ['payment_gateways' => 'required|array', 'financingEnabled' => 'required|in:1,0']);
		$user_meta = $r->payment_gateways;
		$user = auth()->user();
		$user->user_meta = $user_meta;
		$user->financingEnabled = $r->financingEnabled;
		$user->save();
		return back()->with('msg', 'Payment settings successfully saved.');
	}

	public function myProfile() {
		if (auth()->user()->plan_expires <= time()) {
			return redirect('dashboard/subscribe?plan=' . auth()->user()->plan);
		}
		return view('dashboard/my-profile')->with('active', 'profile');
	}

	public function processMyProfile(Request $r) {
		$this->validate($r, ['email' => 'required|email', 'name' => 'required', 'profilePic' => 'image|mimes:jpeg,png,jpg,gif', ]);
		$user = auth()->user();
		$user->name = $r->name;
		$user->headline = $r->headline;
		$user->save();
		$email = $r->email;
		if (auth()->user()->email != $email) {
			$exists = User::where('email', $email)->where('id', '!=', auth()->user()->id)->first();
			if ($exists) {
				return back()->with('msg', 'This email already exists. Please enter another one.');
			}
			else {
				$user->email = $email;
				$user->save();
			}
		}
		if ($r->file('profilePic')) {
			$profilePic = $r->file('profilePic');
			$imageName = uniqid() . '.' . $profilePic->getClientOriginalExtension();
			$img = \Image::make($profilePic->getPathName());
			$img->resize(140, 140);
			$img->save(storage_path('app/public') . '/' . $imageName);
			$user->profilePic = $imageName;
			$user->save();
		}
		return back()->with('msg', 'Successfully updated profile details.');
	}

	public function updateUserPassword(Request $r) {
		$this->validate($r, ['password' => 'required|min:4|confirmed']);
		$user = auth()->user();
		$user->password = \Hash::make($r->password);
		$user->save();
		return back()->with('msg', 'Successfully updated account password.');
	}

	public function mySubscription() {
		$hasActiveSubscriptions = \App\Models\Subscriptions::where('user_id', auth()->user()->id)->where('gateway', 'Credit Card')->where('subscription_status', 'Active')->orderBy('id', 'DESC')->first();
		return view('dashboard/my-subscription')->with('active', 'subscription')->with('hasActiveSubscriptions', $hasActiveSubscriptions);
	}

	public function addDomainBulk() {
		if (auth()->user()->plan_expires <= time()) {
			return redirect('dashboard/subscribe?plan=' . auth()->user()->plan);
		}
		if (auth()->user()->planLimit && !auth()->user()->isAdmin) {
			return view('dashboard/limit-reached');
		}
		return view('dashboard/bulk-add');
	}

	public function addDomainBulkProcess(Request $r) {
		if (auth()->user()->planLimit && !auth()->user()->isAdmin) {
			return view('dashboard/limit-reached');
		}
		$this->validate($r, ['csv' => 'required|file']);
		$csv_file = $r->file('csv')->getPathname();
		$handle = fopen($csv_file, 'r');
		$row = 1;
		$insert = [];
		while (($data = fgetcsv($handle, 0, ",")) !== false) {
			if (auth()->user()->planLimit && !auth()->user()->isAdmin) {
				return view('dashboard/limit-reached');
				exit;
			}
			$num = count($data);
			$row++;
			$category = trim(strip_tags($data[4]));
			$categoryDB = Categories::where('catname', $category)->first();
			if (!$categoryDB) {
				$categoryDB = new Categories;
				$categoryDB->catname = $category;
				$categoryDB->save();
			}
			$categoryFromDb = $categoryDB->catID;
			$i['domain'] = $data[0];
			$i['pricing'] = intval($data[1]);
			$i['registrar'] = $data[2];
			$i['reg_date'] = $data[3];
			$i['category'] = $categoryFromDb;
			$i['description'] = $data[5];
			$i['domain_status'] = 'Available';
			$i['domain_logo'] = 'default-logo.webp';
			$i['vendor_id'] = auth()->user()->id;
			if (auth()->user()->isAdmin) {
				$i['is_verified'] = true;
				$i['is_approved'] = true;
			}
			if (isset($data[6])) {
				$filename = \Illuminate\Support\Str::slug($i['domain']);
				$path = storage_path('app/public') . '/' . $filename;
				$thumbnail_path = storage_path('app/public') . '/' . 'thumbnail-' . $filename;
				$extension = pathinfo($data[6], PATHINFO_EXTENSION);
				$saveto = $path . '.' . $extension;
				$ch = curl_init($data[6]);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
				$raw = curl_exec($ch);
				curl_close($ch);
				if (file_exists($saveto)) {
					unlink($saveto);
				}
				$fp = fopen($saveto, 'x');
				fwrite($fp, $raw);
				fclose($fp);
				$img = \Image::make($saveto);
				$img->resize(null, 98, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				});
				$img->save($thumbnail_path . '.' . $extension);
				$i['domain_logo'] = basename($saveto);
			}
			$insert[] = $i;
			Domain::insert($i);
		}
		fclose($handle);
		return redirect('/dashboard/domains/bulk-add')->with('msg', 'Found and inserted ' . $row . ' total domains');
	}

	public function offerlist() {
		$alloffer = DB::table('offers')->join('domains', 'domains.id', '=', 'offers.domain_id')->join('users', 'users.id', '=', 'domains.vendor_id')->select('domains.domain', 'offers.*')->get();
		return view('dashboard/offer_list')->with('alloffer', $alloffer);
	}

	public function offer_list_delete(Request $r, $id) {
		DB::table('offers')->where('id', $id)->delete();
		$alloffer = DB::table('offers')->join('domains', 'domains.id', '=', 'offers.domain_id')->join('users', 'users.id', '=', 'domains.vendor_id')->select('domains.domain', 'offers.*')->get();
		return view('dashboard/offer_list')->with('alloffer', $alloffer);
	}

	public function marketingTools() {
		if (auth()->user()->plan !== "pro" && auth()->user()->plan !== "Unlimited") {
			return redirect('dashboard/subscribe?plan=pro');
		}
		if (auth()->user()->plan_expires <= time()) {
			return redirect('dashboard/subscribe?plan=' . auth()->user()->plan);
		}
		$regexp = '"vendor_id":' . auth()->user()->id . ',';
		$mtd_count = Orders::where('order_date', '>=', \Carbon\Carbon::now()->startOfMonth())->where('order_status', '=', 'Paid')->where('order_contents', 'regexp', $regexp)->count();
		$earnings_mtd = Orders::where('order_date', '>=', \Carbon\Carbon::now()->startOfMonth())->where('order_status', '=', 'Paid')->where('order_contents', 'regexp', $regexp)->sum('total');
		$all_time_earnings = Orders::where('order_status', '=', 'Paid')->where('order_contents', 'regexp', $regexp)->sum('total');
		$all_time_sales = Orders::where('order_status', '=', 'Paid')->where('order_contents', 'regexp', $regexp)->count();
		$date = \Carbon\Carbon::parse('31 days ago');
		$days = Orders::select(array(\DB::raw('DATE(`order_date`) as `date`'), \DB::raw('SUM(`total`) as `total`')))->where('order_date', '>', $date)->where('order_contents', 'regexp', $regexp)->groupBy('date')->orderBy('date', 'DESC')->pluck('total', 'date');
		return view('marketing-tools')->with('active', 'dashboard');
	}

}

