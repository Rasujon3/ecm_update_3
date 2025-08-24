<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\BannerText;
use App\Models\Conversation;
use App\Models\OrderVariantId;
use App\Models\PaymentInfo;
use App\Models\ProductCharacteristicsDetails;
use App\Models\ProductCharacteristicsTitle;
use App\Models\ProductNarrativeDetails;
use App\Models\ProductNarrativeTitle;
use App\Models\WebsitePurchase;
use Exception;
use Illuminate\Http\Request;
use App\Models\Domain;
use App\Models\User;
use Validator;
use DB;
use Illuminate\Support\Facades\File;
use App\Models\Image;
use App\Models\Package;
use App\Models\Product;
use App\Models\Review;
use App\Models\Video;
use App\Models\Order;
use App\Models\Orderdetail;
use App\Models\Slider;
use SteadFast\SteadFastCourierLaravelPackage\Facades\SteadfastCourier;
use App\Models\Referlog;
use App\Models\Setting;
use App\Models\Ariadhaka;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\Banner;
use App\Models\WhyChooseUs;

class ApiController extends Controller
{

    public function saveDomain(Request $request)
    {
    	DB::beginTransaction();
    	try
    	{
    		$validator = Validator::make($request->all(), [
    			'name' => 'required|string|max:50',
    			'email' => 'nullable|email|unique:users',
    			'phone' => 'required|string|min:11|unique:users',
	            'package_id' => 'required|integer|exists:packages,id',
	            'shop_name' => 'required|string',
	            'domain' => 'required|string|unique:domains',
	            'logo' => 'nullable',
	            'image' => 'nullable',
	            'address' => 'nullable',
	            //'max_product' => 'required|integer|min:1',
	        ]);

	        if ($validator->fails()) {
	        	DB::commit();
	            return response()->json([
	                'status' => false,
	                'message' => 'The given data was invalid',
	                'data' => $validator->errors()
	            ], 422);
	        }

	        //$user = User::where('phone',$request->phone)->first();

	        $checkDomain = Domain::where('domain',$request->domain)->first();

	        if($checkDomain)
	        {
	        	DB::commit();
	        	return response()->json(['status'=>false, 'domain_id'=>0, 'message'=>'The domain is not available'],400);
	        }

	        $shop_slug = str_replace(" ", "_", $request->shop_name);

	        if($request->file('image'))
	        {
	            $file = $request->file('image');
	            $name = time().$shop_slug.$file->getClientOriginalName();
	            $file->move(public_path().'/uploads/users/', $name);
	            $pathImg = 'uploads/users/'.$name;
	        }else{
	        	$pathImg = "defaults/profile.png";
	        }

	        if($request->file('logo'))
	        {
	            $file = $request->file('logo');
	            $name = time().$shop_slug.$file->getClientOriginalName();
	            $file->move(public_path().'/uploads/shops/', $name);
	            $pathLogo = 'uploads/shops/'.$name;
	        }else{
	        	$pathLogo = NULL;
	        }


	        $user = new User();
	        $user->name = $request->name;
	        $user->role_id = 2;
	        $user->email = $request->email;
	        $user->phone = $request->phone;
	        $user->password = bcrypt('123456');
	        $user->image = $pathImg;
	        $user->status = 'Inactive';
	        $user->refer_code = referCode();
	        $user->save();

	        $domain = new Domain();
	        $domain->user_id = $user->id;
	        $domain->theme_id = $request->package_id;
	        $domain->package_id = $request->package_id;
	        $domain->shop_name = $request->shop_name;
	        $domain->domain = $request->domain;
	        $domain->address = $request->address;
	        //$domain->max_product = $request->max_product;
	        $domain->logo = $pathLogo;
	        $domain->status = 'Inactive';
	        $domain->save();

	        DB::commit();
	        return response()->json(['status'=>true, 'domain_id'=>intval($domain->id), 'user_id'=>intval($user->id), 'message'=>'Successfully a shop has been added']);

    	}catch(Exception $e){
    		DB::rollback();
    		return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
    	}
    }

    public function domainLists(Request $request)
    {
    	try
    	{
    		$domains = Domain::latest()->where('status','Active')->get();
    		return response()->json(['status'=>count($domains)>0, 'data'=>$domains]);
    	}catch(Exception $e){
    		return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
    	}
    }

    public function domainDetails(Request $request)
    {
    	try
    	{
    		$validator = Validator::make($request->all(), [
    			'domain' => 'required|string',
    			'user_id' => 'nullable|integer',
	        ]);

	        if ($validator->fails()) {
	            return response()->json([
	                'status' => false,
	                'message' => 'The given data was invalid',
	                'data' => $validator->errors()
	            ], 422);
	        }


	        $domain = Domain::with('theme', 'package')->where('domain',$request->domain)->first();

	        if($request->domain == 'dummy')
	        {
	            $user = User::findorfail($request->user_id);
	            $infoData = Setting::where('user_id',$request->user_id)->first();
	            $domain = Domain::with('theme', 'package')->where('user_id',$request->user_id)->first();
	            return response()->json(['status'=>true, 'is_dummy'=>true, 'inside_dhaka'=>$infoData?$infoData->inside_delivery_charge:NULL, 'outside_dhaka'=>$infoData?$infoData->outside_delivery_charge:NULL, 'my_theme'=>$user->theme, 'domain'=>$domain]);
	        }

	        $infoData = Setting::where('user_id',$domain->user_id)->first();

	        $user = User::findorfail($domain->user_id);

	        return response()->json(['status'=>true, 'is_dummy'=>false, 'inside_dhaka'=>$infoData?$infoData->inside_delivery_charge:NULL, 'outside_dhaka'=>$infoData?$infoData->outside_delivery_charge:NULL, 'my_theme'=>$user->theme, 'domain'=>$domain]);

    	}catch(Exception $e){
    		return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
    	}
    }

    public function sliders(Request $request)
    {
    	try
    	{
    		$validator = Validator::make($request->all(), [
    			'domain' => 'required|string',
    			'user_id' => 'nullable|integer',
	        ]);

	        if ($validator->fails()) {
	            return response()->json([
	                'status' => false,
	                'message' => 'The given data was invalid',
	                'data' => $validator->errors()
	            ], 422);
	        }


	        $domain = domainDetails($request);

	        if($request->has('user_id'))
	        {
	            $sliders = Slider::where('user_id',$request->user_id)->where('status','Active')->get();
	        }else{
	            $sliders = Slider::where('domain_id',$domain->id)->where('status','Active')->get();
	        }

            if ($domain) {
                $bannerText = BannerText::where('domain_id',$domain?->id)->first();
            }

	        return response()->json([
                'status'=>count($sliders)>0,
                'total'=>count($sliders),
                'bannerText' => !empty($bannerText && $bannerText->banner_text) ? $bannerText->banner_text : '',
                'data'=>$sliders
            ]);

    	}catch(Exception $e){
    		return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
    	}
    }

    public function products(Request $request)
    {
    	try
    	{
    		$validator = Validator::make($request->all(), [
    			'domain' => 'required|string',
                'user_id' => 'nullable|integer|exists:users,id',
	        ]);

	        if ($validator->fails()) {
	            return response()->json([
	                'status' => false,
	                'message' => 'The given data was invalid',
	                'data' => $validator->errors()
	            ], 422);
	        }


	        $domain = domainDetails($request);
            if($request->has('user_id') && $request->domain == 'dummy')
            {
                $products = Product::with('images','variants')->where('user_id',$request->user_id)->get();
            }else{
                $products = Product::with('images','variants')->where('domain_id',$domain->id)->where('status','Active')->get();
            }



	        return response()->json(['status'=>count($products)>0, 'total'=>count($products), 'data'=>$products]);

    	}catch(Exception $e){
    		return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
    	}
    }

    public function reviews(Request $request)
    {
    	try
    	{
    		$validator = Validator::make($request->all(), [
    			'domain' => 'required|string',
    			'user_id' => 'nullable|integer',
	        ]);

	        if ($validator->fails()) {
	            return response()->json([
	                'status' => false,
	                'message' => 'The given data was invalid',
	                'data' => $validator->errors()
	            ], 422);
	        }

	        $domain = domainDetails($request);

	        if($request->has('user_id'))
	        {
	            $reviews = Review::where('user_id',$request->user_id)->where('status','Active')->latest()->get();
	        }else{
	           $reviews = Review::where('domain_id',$domain->id)->where('status','Active')->latest()->get();
	        }



	        return response()->json(['status'=>count($reviews)>0, 'total'=>count($reviews), 'data'=>$reviews]);

    	}catch(Exception $e){
    		return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
    	}
    }

    public function getVideo(Request $request)
    {
    	try
    	{
    		$validator = Validator::make($request->all(), [
    			'domain' => 'required|string',
    			'user_id' => 'nullable|integer',
	        ]);

	        if ($validator->fails()) {
	            return response()->json([
	                'status' => false,
	                'message' => 'The given data was invalid',
	                'data' => $validator->errors()
	            ], 422);
	        }

	        $domain = domainDetails($request);

	        if($request->has('user_id'))
	        {
	            $video = Video::where('user_id',$request->user_id)->first();
	        }else{
	            $video = Video::where('domain_id',$domain->id)->first();
	        }



	       // return $video;

	        return response()->json(['status'=>$video?true:false, 'data'=>$video?$video:new \stdClass()]);

    	}catch(Exception $e){
    		return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
    	}
    }

    public function saveOrder(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'domain' => 'required|string|exists:domains,domain',
                'customer_name' => 'required|string',
                'customer_phone' => 'required|string',
                'customer_address' => 'required',
                'district' => 'nullable',
                'payment_method' => 'required|string',
                'sub_total' => 'required|numeric',
                'total' => 'required|numeric',
                'refer_code' => 'nullable|string|exists:users,refer_code',
                'delivery_charge' => 'nullable|numeric',
                'transaction_hash' => 'nullable|string',
                'payment_number' => 'nullable|string',
                'order_note' => 'nullable|string',
                'orders' => 'required|array|min:1',
                'orders.*.product_id' => 'required|exists:products,id',
                'orders.*.product_price' => 'required',
                'orders.*.variant_id' => 'nullable|array',
                'orders.*.variant_id.*' => 'nullable|exists:variants,id',
                'orders.*.qty' => 'required',
                'orders.*.unit_total' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'The given data was invalid',
                    'data' => $validator->errors()
                ], 422);
            }

            foreach ($request->orders as $item) {
                $product = Product::where('id', $item['product_id'])->first();
                if((int)$product->stock_qty < (int)$item['qty']) {
                    return response()->json([
                        'status' => false,
                        'data' => [],
                        'message' => "Stock not available for product: {$product->product_name}"
                    ], 422);
                }
            }

            DB::beginTransaction();

            $domain = domainDetails($request);

            $orderDetail = new Orderdetail();
            $orderDetail->domain_id = $domain->id;
            $orderDetail->customer_name = $request->customer_name;
            $orderDetail->customer_phone = $request->customer_phone;
            $orderDetail->customer_address = $request->customer_address;
            $orderDetail->district = $request->district;
            $orderDetail->payment_method = $request->payment_method;
            $orderDetail->sub_total = $request->sub_total;
            $orderDetail->delivery_charge = $request->delivery_charge;
            $orderDetail->total = $request->total;
            $orderDetail->transaction_hash = $request->transaction_hash;
            $orderDetail->payment_number = $request->payment_number;
            $orderDetail->order_note = $request->order_note;
            $orderDetail->save();

            foreach ($request->orders as $item) {
                $order = new Order();
                $order->orderdetail_id = $orderDetail->id;
                $order->product_id = $item['product_id'];
                # $order->variant_id = $item['variant_id'] ?? null;
                $order->product_price = $item['product_price'];
                $order->qty = $item['qty'];
                $order->unit_total = $item['unit_total'];
                $order->save();

                $variantIds = $item['variant_id'] ?? [];
                if (is_array($variantIds) && count($variantIds) > 0) {
                    foreach ($variantIds as $vid) {
                        OrderVariantId::create([
                            'order_id' => $order->id,
                            'product_id' => $item['product_id'],
                            'variant_id' => $vid
                        ]);
                    }
                }
            }

            if($request->has('refer_code'))
            {
                $log = new Referlog();
                $log->user_id = $domain->user_id;
                $log->refer_code = $request->refer_code;
                $log->date = date('Y-m-d');
                $log->time = date('h:i A');
                $log->save();
            }

            DB::commit();
            return response()->json([
                'status' => true,
                'order_id' => intval($orderDetail->id),
                'message' => 'Successfully your order has been processed. We will contact you soon.'
            ]);

        } catch(Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

	public function acceptCourierOrder(Request $request)
	{
		try
		{
			$validator = Validator::make($request->all(), [
	            'order_id' => 'required|string|exists:orderdetails,id',
	        ]);

	        if ($validator->fails()) {
	            return response()->json([
	                'status' => false,
	                'message' => 'The given data was invalid',
	                'data' => $validator->errors()
	            ], 422);
	        }

	        $order = Orderdetail::findorfail($request->order_id);
	        $orderData = [
			    'invoice' => '123456',
			    'recipient_name' => 'John Doe',
			    'recipient_phone' => '01234567890',
			    'recipient_address' => 'Fla# A1,House# 17/1, Road# 3/A, Dhanmondi,Dhaka-1209',
			    'cod_amount' => 1000,
			    'note' => 'Handle with care'
			];

			$response = SteadfastCourier::placeOrder($orderData);

			return $response;

		}catch(Exception $e) {
	        return response()->json([
	            'status' => false,
	            'code' => $e->getCode(),
	            'message' => $e->getMessage()
	        ], 500);
	    }
	}

	public function addTempUser(Request $request)
	{
		try
		{
			$validator = Validator::make($request->all(), [
    			'name' => 'required|string|max:50',
    			'email' => 'nullable|email|unique:users',
    			'phone' => 'required|string|min:11|unique:users',
    			'package_id' => 'required|integer|exists:packages,id',
	        ]);

	        if ($validator->fails()) {
	            return response()->json([
	                'status' => false,
	                'message' => 'The given data was invalid',
	                'data' => $validator->errors()
	            ], 422);
	        }

	        $user = new User();
	        $user->role_id = 2;
	        $user->package_id = $request->package_id;
	        $user->name = $request->name;
	        $user->email = $request->email;
	        $user->password = bcrypt('123456');
	        $user->status = 'Active';
	        $user->save();
	        return response()->json(['status'=>true, 'user_id'=>intval($user->id), 'message'=>'Successfully an user has been added']);
		}catch(Exception $e) {
	        return response()->json([
	            'status' => false,
	            'code' => $e->getCode(),
	            'message' => $e->getMessage()
	        ], 500);
	    }
	}

	public function searchDomain(Request $request)
	{
		try
		{
			$validator = Validator::make($request->all(), [
	            'domain' => 'required|string',
	        ]);

	        if ($validator->fails()) {
	            return response()->json([
	                'status' => false,
	                'message' => 'The given data was invalid',
	                'data' => $validator->errors()
	            ], 422);
	        }

	        $search = $request->domain;
	        $data = Domain::where('domains.domain', 'LIKE', "%$search%")->latest()->get();
	        if(count($data) > 0)
	        {
	        	return response()->json(['status'=>false, 'message'=>'Sorry this domain is not available'],400);
	        }
	        return response()->json(['status'=>true, 'message'=>'The domain is available']);
		}catch(Exception $e) {
	        return response()->json([
	            'status' => false,
	            'code' => $e->getCode(),
	            'message' => $e->getMessage()
	        ], 500);
	    }
	}

	public function packages()
	{
	    try
	    {
	       $packages = Package::with(['services' => function ($query) {
                $query->where('status', 'Active');
            }])
            ->where('status', 'Active')
            ->whereHas('services', function ($query) {
                $query->where('status', 'Active');
            })
            ->latest()
            ->get();
	        return response()->json(['status'=>count($packages) > 0, 'data'=>$packages]);
	    }catch(Exception $e) {
	        return response()->json([
	            'status' => false,
	            'code' => $e->getCode(),
	            'message' => $e->getMessage()
	        ], 500);
	    }
	}

	public function privacyPolicy(Request $request)
	{
		try
		{
			$validator = Validator::make($request->all(), [
	            'domain' => 'required|string',
	        ]);

	        if ($validator->fails()) {
	            return response()->json([
	                'status' => false,
	                'message' => 'The given data was invalid',
	                'data' => $validator->errors()
	            ], 422);
	        }

	        $domain = Domain::where('domain',$request->domain)->first();
	        $user = User::findorfail($domain->user_id);
	        $data = Setting::select('privacy_policy')->where('user_id',$user->id)->first();
	        return response()->json(['status'=>true, 'data'=>$data]);
		}catch(Exception $e) {
	        return response()->json([
	            'status' => false,
	            'code' => $e->getCode(),
	            'message' => $e->getMessage()
	        ], 500);
	    }
	}

	public function contactUs(Request $request)
	{
		try
		{
			$validator = Validator::make($request->all(), [
	            'domain' => 'required|string',
	        ]);

	        if ($validator->fails()) {
	            return response()->json([
	                'status' => false,
	                'message' => 'The given data was invalid',
	                'data' => $validator->errors()
	            ], 422);
	        }

	        $domain = Domain::where('domain',$request->domain)->first();
	        $user = User::findorfail($domain->user_id);
	        $data = Setting::select('contact_name','contact_email','contact_phone','contact_address','contact_description')->where('user_id',$user->id)->first();
	        return response()->json(['status'=>true, 'data'=>$data]);
		}catch(Exception $e) {
	        return response()->json([
	            'status' => false,
	            'code' => $e->getCode(),
	            'message' => $e->getMessage()
	        ], 500);
	    }
	}

	public function aboutUs(Request $request)
	{
	    try
		{
			$validator = Validator::make($request->all(), [
	            'domain' => 'required|string',
	        ]);

	        if ($validator->fails()) {
	            return response()->json([
	                'status' => false,
	                'message' => 'The given data was invalid',
	                'data' => $validator->errors()
	            ], 422);
	        }

	        $domain = Domain::where('domain',$request->domain)->first();
	        $user = User::findorfail($domain->user_id);
	        $data = Setting::select('about_us')->where('user_id',$user->id)->first();
	        return response()->json(['status'=>true, 'data'=>$data]);
		}catch(Exception $e) {
	        return response()->json([
	            'status' => false,
	            'code' => $e->getCode(),
	            'message' => $e->getMessage()
	        ], 500);
	    }
	}


	public function adminInfo(Request $request)
	{
	    try
	    {
	        $validator = Validator::make($request->all(), [
	            'role_id' => 'required|integer',
	        ]);

	        if ($validator->fails()) {
	            return response()->json([
	                'status' => false,
	                'message' => 'The given data was invalid',
	                'data' => $validator->errors()
	            ], 422);
	        }

	        $user = User::where('role_id',$request->role_id)->first();
	        $data = Setting::select('privacy_policy','contact_name','contact_phone','contact_email','contact_description','contact_address','about_us')->where('user_id',$user->id)->first();
	        return response()->json(['status'=>true, 'data'=>$data]);
	    }catch(Exception $e) {
	        return response()->json([
	            'status' => false,
	            'code' => $e->getCode(),
	            'message' => $e->getMessage()
	        ], 500);
	    }
	}


	public function updateUserTheme(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'domain' => 'required|string|exists:domains,domain',
                'theme' => 'nullable|string|max:191',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'The given data was invalid',
                    'data' => $validator->errors()
                ], 422);
            }

            $domain = $request->get('domain');
            $theme = $request->get('theme');

            if (empty($theme)) {
                return response()->json([
                    'status' => true,
                    'message' => 'No theme provided. Nothing to update.'
                ]);
            }

            $domain = Domain::where('domain', $domain)->first(['user_id']);

            $updatedCount = User::where('id', $domain->user_id)
                ->update(['theme' => $theme]);

            if ($updatedCount > 0) {
                return response()->json([
                    'status' => true,
                    'data' => $domain->user_id,
                    'message' => 'Theme updated successfully.'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'User not found or theme was not changed.'
            ], 404);

        } catch(Exception $e) {

            // Log the error
            Log::error('Error in updating user theme: ' , [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!!!'
            ], 500);
        }
    }

    public function siteOtherInfo(Request $request)
    {
    	try
    	{
    		$validator = Validator::make($request->all(), [
	            'domain' => 'required|string',
	        ]);

	        if ($validator->fails()) {
	            return response()->json([
	                'status' => false,
	                'message' => 'The given data was invalid',
	                'data' => $validator->errors()
	            ], 422);
	        }

	        $domain = Domain::where('domain',$request->domain)->first();
	        $user = User::findorfail($domain->user_id);
	        $data = Setting::select('facebook_url','twitter_url','instagram_url','youtube_url','terms_conditions','refund_policy')->where('user_id',$user->id)->first();
	        return response()->json(['status'=>true, 'data'=>$data]);
    	}catch(Exception $e) {
	        return response()->json([
	            'status' => false,
	            'code' => $e->getCode(),
	            'message' => $e->getMessage()
	        ], 500);
	    }
    }

    public function insideDhakaArea()
    {
    	try
    	{
    		$data = Ariadhaka::where('status','Active')->get();
    		return response()->json(['status'=>count($data)>0, 'data'=>$data]);
    	}catch(Exception $e) {
	        return response()->json([
	            'status' => false,
	            'code' => $e->getCode(),
	            'message' => $e->getMessage()
	        ], 500);
	    }
    }

    public function websitePurchase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'domain_name'      => 'required|exists:domains,domain',
            'user_id'          => 'required|exists:users,id',
            'package_id'       => 'required|exists:packages,id',
            'theme'            => 'required|string|max:191',
            'payment_method'   => 'required|string|max:191',
            'transaction_hash' => 'required|string|unique:website_purchases,transaction_hash',
            # 'status'           => 'nullable|string|max:191',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $domain = Domain::where('domain', $request->domain_name)->first();

            $purchase = WebsitePurchase::create([
                'domain_id'        => $domain->id,
                'user_id'          => $request->user_id,
                'package_id'       => $request->package_id,
                'theme'            => $request->theme,
                'payment_method'   => $request->payment_method,
                'transaction_hash' => $request->transaction_hash,
                'status'           => "pending",
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Website purchase created successfully.',
                'data'    => $purchase,
            ], 201);
        } catch(Exception $e) {
            // Log the error
            Log::error('Error in creating websitePurchase: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => false,
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getPaymentInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'  => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 422);
        }
        try {
            $paymentInfo = PaymentInfo::where('user_id', $request->user_id)->latest()->get();

            return response()->json([
                'status'  => true,
                'message' => 'Payment info retrieved successfully.',
                'data'    => $paymentInfo
            ], 200);
        } catch(Exception $e) {
            // Log the error
            Log::error('Error in creating paymentInfo: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => false,
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function paymentInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_method'  => 'required|in:bKash,rocket,nogod',
            'account_number'  => 'required|string|max:191',
            'payment_type'    => 'required|string|max:191',
            'instructions'    => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Check uniqueness of payment_method + account_number combo
        $exists = PaymentInfo::where('payment_method', $request->payment_method)
            ->where('account_number', $request->account_number)
            ->exists();

        if ($exists) {
            return response()->json([
                'status'  => false,
                'message' => 'This account number already exists for the selected payment method.'
            ], 409);
        }

        try {
            $paymentInfo = PaymentInfo::create([
                'payment_method' => $request->payment_method,
                'account_number' => $request->account_number,
                'payment_type'   => $request->payment_type,
                'instructions'   => $request->instructions,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Payment info created successfully.',
                'data'    => $paymentInfo
            ], 201);
        } catch(Exception $e) {
            // Log the error
            Log::error('Error in creating paymentInfo: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => false,
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getMusic($filename)
    {
        try {
            $path = public_path('payment_icon/' . $filename);

            if (!file_exists($path)) {
                return $this->sendResponse(false, '404, File not found.', []);
            }

            return response()->file($path);
        } catch (Exception $e) {

            // Log the error
            Log::error('Error in get File: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->sendResponse(false, 'Something went wrong!!!', [], 500);
        }
    }

    public function getToken()
    {
        $credentials = [
            'username' => config('services.surjopay.username'),
            'password' => config('services.surjopay.password'),
        ];

        if (!$credentials['username'] || !$credentials['password']) {
            return response()->json([
                'success' => false,
                'status_code' => 500,
                'message' => 'ShurjoPay credentials are not configured properly.'
            ], 500);
        }

        try {
            $response = Http::post(config('services.surjopay.token_url'), $credentials);

            if ($response->successful()) {
                $data = $response->json();

                // Check if token and other expected fields exist
                if (isset($data['token']) && isset($data['execute_url'])) {
                    return response()->json([
                        'success' => true,
                        'statusCode' => 200,
                        'data' => $data
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'statusCode' => 422,
                        'message' => 'Token not received properly.'
                    ], 422);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'statusCode' => $response->status(),
                    'message' => 'Failed to get token',
                    'error' => $response->json()
                ], $response->status());
            }
        } catch (\Exception $e) {
            // Log the error
            Log::error('ShurjoPay token error: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'statusCode' => 500,
                'message' => 'Something went wrong while requesting token.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function userPaymentStore(Request $request)
    {
        DB::beginTransaction();
        try
        {
             $validator = Validator::make($request->all(), [
                'gateway_order_id' => 'required|string|unique:website_purchases',
                'user_id' => 'required|integer|exists:users,id',
                'token' => 'required|string',

            ]);

            if ($validator->fails()) {
                DB::commit();
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation error',
                    'errors'  => $validator->errors()
                ], 422);
            }

            $user = User::findorfail($request->user_id);

            $domain = Domain::where('user_id',$user->id)->first();

            if(!$domain)
            {
                return response()->json(['status'=>false, 'message'=>'No domain found'],404);
            }

            //$domain = Domain::where('domain', $request->domain_name)->first();


            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://engine.shurjopayment.com/api/verification',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>'{
               "order_id":"'.$request->gateway_order_id.'"
            }',
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Authorization: Bearer {$request->token}"
              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $result = json_decode($response,true);

            //return $result;

            $purchase = WebsitePurchase::create([
                'domain_id'        => $domain->id,
                'user_id'          => $user->id,
                'package_id'       => $domain->package_id,
                'theme'            => $user->theme,
                'payment_method'   => "shurjopay",
                'transaction_hash' => $result[0]['bank_trx_id'],
                'status'           => $result[0]['transaction_status'] == 'Incomplete'?"pending":"approved",
            ]);

            if($purchase->transaction_hash != NULL)
            {
                Product::where('user_id',$request->user_id)->update(['status'=>'Active']);
                Domain::where('user_id',$request->user_id)->update(['status'=>'Active']);
                User::where('id',$request->user_id)->update(['status'=>'Active']);
            }


            DB::commit();

            return response()->json([
                'status'  => $result[0]['transaction_status'] == 'Incomplete'?false:true,
                'message' => $result[0]['transaction_status'] == 'Incomplete'?"Failed to purchase":"Website purchase created successfully.",
                'data'    => $purchase,
            ], 200);



        }catch (\Exception $e) {
            // Log the error
            Log::error('ShurjoPay token error: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while requesting token.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function whyChooseUs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'  => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 422);
        }
        try {
            $whyChooseUs = WhyChooseUs::where('user_id',$request->user_id)->latest()->get();

            return response()->json([
                'status'  => true,
                'message' => 'Why choose us retrieved successfully.',
                'data'    => $whyChooseUs
            ], 200);
        } catch(Exception $e) {
            // Log the error
            Log::error('Error in creating WhyChooseUs: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => false,
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function banner()
    {
        try {
            $banner = Banner::latest()->get();

            return response()->json([
                'status'  => true,
                'message' => 'Banner retrieved successfully.',
                'data'    => $banner
            ], 200);
        } catch(Exception $e) {
            // Log the error
            Log::error('Error in creating WhyChooseUs: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => false,
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getBannerImg($filename)
    {
        try {
            $path = public_path('files/music/mp3/' . $filename);

            if (!file_exists($path)) {
                return $this->sendResponse(false, '404, File not found.', []);
            }

            return response()->file($path);
        } catch (Exception $e) {

            // Log the error
            Log::error('Error in get File: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->sendResponse(false, 'Something went wrong!!!', [], 500);
        }
    }


    public function getDeliveryCharges(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'domain'  => 'required|exists:domains,domain',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $domain = Domain::where('domain', $request->domain)->first('user_id');
            $data = Ariadhaka::where('user_id',$domain->user_id)->latest()->get();
            // Map and set delivery_charges to 0 if null
            $data = $data->map(function ($item) {
                $item->inside_delivery_charges = $item->inside_delivery_charges ?? '0';
                $item->outside_delivery_charges = $item->outside_delivery_charges ?? '0';
                return $item;
            });

            return response()->json([
                'status'  => true,
                'message' => 'Delivery Charges data fetched successfully.',
                'data'    => $data,
                'user_id'    => $domain->user_id
            ], 200);
        } catch(Exception $e) {
            // Log the error
            Log::error('Error in creating getDeliveryCharges: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => false,
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function findDeliveryCharge(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
             'user_id'  => 'required|exists:users,id',
             'district' => 'required|string',
           ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation error',
                    'errors'  => $validator->errors()
                ], 422);
            }

            //$data = DB::table('ariadhakas')->where('area_name',$request->district)->where('user_id',$request->user_id)->first();
            $getCharge = DB::table('ariadhakas')->where('user_id',$request->user_id)->first();

            if(!$getCharge)
            {
                return response()->json(['status'=>true, 'data'=>array('delivery_charge'=>"0")]);
            }

            if($request->district == $getCharge->area_name)
            {
                $delivery_charge = $getCharge->inside_delivery_charges;
            }else{
                $delivery_charge = $getCharge->outside_delivery_charges;
            }
            return response()->json(['status'=>true, 'data'=>array('delivery_charge'=>$delivery_charge)]);
        }catch(Exception $e) {

            return response()->json([
                'status' => false,
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function conversations(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'domain' => 'required|string|exists:domains,domain',
                'user_id' => 'nullable|integer|exists:users,user_id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'The given data was invalid',
                    'data' => $validator->errors()
                ], 422);
            }


            $domain = domainDetails($request);

            $data = Conversation::where('domain_id',$domain?->id)->first();


            return response()->json([
                'status' => !empty($data),
                'data' => $data
            ]);

        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function productCharacteristics(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'domain' => 'required|string|exists:domains,domain',
                'user_id' => 'nullable|integer|exists:users,user_id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'The given data was invalid',
                    'data' => $validator->errors()
                ], 422);
            }


            $domain = domainDetails($request);

            $title = ProductCharacteristicsTitle::where('domain_id',$domain?->id)->first();
            $data = ProductCharacteristicsDetails::where('domain_id',$domain?->id)->get();


            return response()->json([
                'status' => !empty($data),
                'title' => ($title && $title->title) ? $title->title : '',
                'data' => $data
            ]);

        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ],500);
        }
    }

    public function productNarrative(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'domain' => 'required|string|exists:domains,domain',
                'user_id' => 'nullable|integer|exists:users,user_id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'The given data was invalid',
                    'data' => $validator->errors()
                ], 422);
            }


            $domain = domainDetails($request);

            $title = ProductNarrativeTitle::where('domain_id',$domain?->id)->first();
            $data = ProductNarrativeDetails::where('domain_id',$domain?->id)->get();


            return response()->json([
                'status' => !empty($data),
                'title' => ($title && $title->title) ? $title->title : '',
                'data' => $data
            ]);

        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ],500);
        }
    }
}
