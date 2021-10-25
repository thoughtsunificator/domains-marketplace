<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToolsController extends Controller {

	public function checkAuth(Request $request) {
		if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password') ])) {
			$user = Auth::User();
			if ($user->plan === "Unlimited" || $user->plan === "pro" || $user->isAdmin) {
				return response()->json(['status' => 'authorized']);
			}
		}
		return response()->json(['error' => 'Unauthorized'], 401);
	}

}

