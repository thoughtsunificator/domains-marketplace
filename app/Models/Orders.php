<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model {

	public $timestamps = false;
	protected $fillable = [];

	public function scopeOfUser($query) {
		$regexp = '"vendor_id":' . auth()->user()->id . ',';
		return $query->where('order_contents', 'regexp', $regexp);
	}

}

