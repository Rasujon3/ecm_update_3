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

            self::setSession();

            if(user()->role_id == 1)
            {
                return view('layouts.admin_app');
            }
    		return view('layouts.app');
    	} catch(Exception $e){

                $message = $e->getMessage();

                $code = $e->getCode();

                $string = $e->__toString();
                return response()->json(['message'=>$message, 'execption_code'=>$code, 'execption_string'=>$string]);
                exit;
        }
    }
    public static function setSession()
    {
        $domains = Domain::where('user_id', user()->id)->get();
        $domain = Domain::where('user_id', user()->id)->first();
        $subDomains = [];

        if ($domain) {
            $subDomains = SubDomain::where('domain_id', $domain->id)->get();
        }


        if (!Session::has('domain_id') && !Session::has('sub_domain_id')) {
            Session::put('domains', $domains);

            // Only set domain-related session data if domain exists
            if ($domain) {
                Session::put('domain', $domain);
                Session::put('subDomains', $subDomains);
                Session::put('full_domain_name', $domain->domain);
                Session::put('domain_id', $domain->id);
            } else {
                Session::put('domain', null);
                Session::put('subDomains', []);
                Session::put('full_domain_name', 'No Domain');
                Session::put('domain_id', null);
            }

            Session::put('sub_domain_id', null);
        }
    }
}
