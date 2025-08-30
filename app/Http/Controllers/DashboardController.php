<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\SubDomain;
use Exception;
use Illuminate\Http\Request;
use Auth;
use Session;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }
    public function Dashboard()
    {
    	try
    	{
            Session::forget('redirectRoute');

            $domain = Domain::where('user_id', user()->id)->first();
            $subDomains = [];
            if ($domain) {
                $subDomains = SubDomain::where('domain_id', $domain->id)->get();
            }

            if(user()->role_id == 1)
            {
                return view('layouts.admin_app');
            }
    		return view('layouts.app', compact('domain', 'subDomains'));
    	} catch(Exception $e){

                $message = $e->getMessage();

                $code = $e->getCode();

                $string = $e->__toString();
                return response()->json(['message'=>$message, 'execption_code'=>$code, 'execption_string'=>$string]);
                exit;
        }
    }
}
