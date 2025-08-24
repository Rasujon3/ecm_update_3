<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Domain;
use App\Models\User;
use Auth;

class IndexController extends Controller
{   

	public function addUserProduct(Request $request)
	{   
		if($request->has('domain'))
		{
			$domain = Domain::where('domain',$request->domain)->first(); 
			$user = User::findorfail($domain->user_id);
			//return $user;
			if($user->status == 'Inactive')
			{
				Auth::login($user);
				//return redirect('/products');
				return redirect('/dashboard');
			} 
		}
		
	}
    public function loginPage()
    {   
    	$routeName = Route::currentRouteName();
    	$title = $routeName == 'admin'?'Admin Panel':"User Panel";
    	return view('admin_login', compact('title','routeName'));
    }
}
