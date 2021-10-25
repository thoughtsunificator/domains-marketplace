<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Domain extends Model {
	use HasFactory;

	public $timestamps = false;
	protected $fillable = ['domain', 'youtube_video_id', 'keywords', 'domain_history', 'is_premium', 'domain_status', 'pricing', 'registrar', 'reg_date', 'exp_date', 'domain_age', 'description', 'short_description', 'discount', 'category', 'vendor_id', 'is_verified', 'price_drop', 'price_drop_value', 'start_datetime', 'end_datetime', 'days_difference'];

	public static function getAvailableExtensions() {
		$all_domains = self::select('domain')->get();
		$extensions = [];
		foreach ($all_domains as $domain) {
			array_push($extensions, pathinfo($domain->domain, PATHINFO_EXTENSION));
		}
		return array_unique($extensions, SORT_STRING);
	}

	public static function getCharacterCount($domainName) {
		return strlen(strtok($domainName, '.'));
	}

	public static function computeAge($reg_date, $exp_date) {
		try {
			$reg_date = Carbon::parse($reg_date);
			$exp_date = Carbon::parse(date('Y-m-d'));
			return $exp_date->diffInYears($reg_date);
		} catch(\Exception $e) {
			return 'N/A: Incorrect date format! Must be day-month-year';
		}
	}

	public function getDomainAgeAttribute() {
		try {
			$reg_date = $this->reg_date;
			$reg_date = Carbon::parse($reg_date);
			$exp_date = Carbon::parse(date('Y-m-d'));
			return $exp_date->diffInYears($reg_date);
		} catch(\Exception $e) {
			return 'N/A: Incorrect date format! Must be day-month-year';
		}
	}

	public function getFinalPriceAttribute() {
		if ($this->discount > 0) return $this->discount;
		return $this->pricing;
	}

	public function getRouteKeyName() {
		return 'domain';
	}

	public function industry() {
		return $this->hasOne('App\Models\Categories', 'catID', 'category');
	}

	public function scopeOfUser() {
		return self::where('vendor_id', auth()->user()->id);
	}

	public function user() {
		return $this->belongsTo(User::class, 'vendor_id');
	}
}

