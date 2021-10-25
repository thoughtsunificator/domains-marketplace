<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categories extends Model {

	use HasFactory;

	protected $fillable = ['catID', 'catname'];
	public $timestamps = false;
	public $primaryKey = 'catID';

	public function domain() {
		return $this->hasMany('App\Models\Domain', 'category');
	}

}

