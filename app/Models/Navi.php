<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Navi extends Model {

	protected $fillable = ['title', 'url', 'target'];
	public $timestamps = false;

}

