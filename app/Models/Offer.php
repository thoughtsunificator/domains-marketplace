<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model {

	public $timestamps = false;
	protected $fillable = ['guest_user_id', 'domain_id', 'user_name', 'email', 'phone_no', 'remarks', 'offer_price', 'is_read_admin', 'is_read_seller', 'datetime', 'date'];

}

