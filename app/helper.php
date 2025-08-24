<?php
 use App\Models\User;
 use App\Models\Domain;
 use App\Models\Unit;
 use App\Models\Setting;
 use App\Models\Orderdetail;
 use App\Models\Order;
 use App\Models\Courier;
 use App\Models\Service;
 use App\Models\Expense;
 use App\Models\Refer;
 use App\Models\Referlog;

 function user()
 {
 	$user = auth()->user();
 	return $user;
 }


 function getDomain()
 {
 	$domain = Domain::where('user_id',user()->id)->first();
 	return $domain;
 }

 function domainDetails($request)
 {
 	$domain = Domain::where('domain',$request->domain)->first();
 	return $domain;
 }

 function units()
 {
 	$units = Unit::where('status','Active')->latest()->get();
 	return $units;
 }

 function getYouTubeVideoId($request) {
    preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|v\/|shorts\/))([^\?&\/]+)/', $request->video_url, $matches);
    return $matches[1] ?? null;
 }

 function setting()
 {
 	$setting = Setting::where('user_id',user()->id)->first();
 	return $setting;
 }

 function generateInvoice()
 {
 	$count = Orderdetail::count();
 	$count+=1;
 	$invoiceID = time().$count;
 	return strval($invoiceID);
 }

 function courier($order_id)
 {
 	$courier = Courier::where('orderdetail_id',$order_id)->first();
 	return $courier;
 }

 function services()
 {
 	$services = Service::where('status','Active')->latest()->get();
 	return $services;
 }

 function checkService($package,$service_id)
 {
 	$services = $package->services->pluck('id')->toArray();
 	if(in_array($service_id,$services))
 	{
 		return "checked";
 	}
 }

 function totalSum()
 {
 	$total = Orderdetail::leftJoin('couriers','orderdetails.id','couriers.orderdetail_id')->select('orderdetails.*','couriers.invoice_no','couriers.consignment_id','couriers.tracking_code')->where('orderdetails.domain_id',getDomain()->id)->where('orderdetails.status','Delivered')->sum('total');
 	return $total;
 }

 function expenseTotal($row)
 {
 	$orderDate = $row->created_at->format('Y-m-d');
    $totalExpense = Expense::where('date',$orderDate)->sum('amount'); 
    return $totalExpense;
 }

 function totalRevenue($row)
 {
 	$orderDate = $row->created_at->format('Y-m-d');
 	$orderTotal = Orderdetail::whereDate('created_at',$orderDate)->where('status','!=','Pending')->sum('total');
    $totalExpense = Expense::where('date',$orderDate)->sum('amount'); 
    $total = $orderTotal - $totalExpense;
    return $total;
 }

 function referCode()
 {
 	$count = User::count();
 	$count+=1;
 	$data = "Refer_00".$count;
 	return $data;
 }

 function userPoint($user_id)
 {
 	$user = User::findorfail($user_id);
 	if($user)
 	{
 		$count = Referlog::where('refer_code',$user->refer_code)->count();
 		$refer = Refer::find(1);
 	    $total = $refer->per_refer_point * $count;
 	    return $total;
 	}
 }