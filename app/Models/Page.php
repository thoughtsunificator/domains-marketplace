<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model {

	protected $fillable = ['page_title', 'page_content', 'page_slug'];

	public static function slug(Page $p) {
		return '/p-' . $p->page_slug;
	}

}

