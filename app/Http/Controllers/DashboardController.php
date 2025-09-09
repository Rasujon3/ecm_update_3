<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Orderdetail;
use App\Models\Product;
use App\Models\Review;
use App\Models\Slider;
use App\Models\SubDomain;
use App\Models\Unit;
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

            $user_id = user()->id;
            $domain_id = getDomain()->id;

            $selection = getCurrentSelection();
            $domainId = $selection['domain_id'];
            $subDomainId = $selection['sub_domain_id'];

            // Sliders
            $totalSliders = Slider::where('user_id', $user_id)
                ->where('domain_id', $domainId)
                ->where('sub_domain_id', $subDomainId)
                ->count();

            // Products
            $totalProducts = Product::where('user_id', $user_id)
                ->where('domain_id', $domainId)
                ->where('sub_domain_id', $subDomainId)
                ->count();

            // Reviews
            $totalReviews = Review::where('user_id', $user_id)
                ->where('domain_id', $domainId)
                ->where('sub_domain_id', $subDomainId)
                ->count();

            // Units (sub_domain_id ছাড়া)
            $totalUnits = Unit::where('user_id', $user_id)
                ->where('domain_id', $domain_id)
                ->count();

            // Orders (total, today, monthly, yearly)
            $totalOrders = OrderDetail::where('domain_id', $domain_id)->count();

            $todayOrders = OrderDetail::where('domain_id', $domain_id)
                ->whereDate('created_at', now())
                ->count();

            $monthlyOrders = OrderDetail::where('domain_id', $domain_id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            $yearlyOrders = OrderDetail::where('domain_id', $domain_id)
                ->whereYear('created_at', now()->year)
                ->count();

            return view('layouts.app', compact(
                'totalSliders',
                'totalProducts',
                'totalReviews',
                'totalUnits',
                'totalOrders',
                'todayOrders',
                'monthlyOrders',
                'yearlyOrders'
            ));

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
                Session::put('full_domain_name', 'https://' . $domain->domain  . '.hosstify.com');
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
