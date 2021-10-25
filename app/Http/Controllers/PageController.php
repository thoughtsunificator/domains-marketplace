<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Page;

class PageController extends Controller {

	public function page($slug) {
		$page = Page::where('page_slug', $slug)->firstOrFail();
		return view('single')->with('page', $page);
	}

}

