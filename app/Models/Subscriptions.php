<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Subscriptions extends Model {

	public $timestamps = false;

	public function user() {
		return $this->belongsTo(User::class);
	}

}

