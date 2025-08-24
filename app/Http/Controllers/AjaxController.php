<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Unit;
use App\Models\Product;
use App\Models\Review;
use App\Models\Orderdetail;
use App\Models\Courier;
use App\Models\Package;
use App\Models\Service;
use App\Models\User;
use App\Models\Ariadhaka;
use App\Models\Variant;
use DB;

class AjaxController extends Controller
{
    public function sliderStatusUpdate(Request $request)
    {
    	try
    	{
    		$slider = Slider::findorfail($request->slider_id);
    		$slider->status = $request->status;
    		$slider->update();
    		return response()->json(['status'=>true, 'message'=>"Successfully the slider's status has been updated"]);
    	}catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function unitStatusUpdate(Request $request)
    {
        try
        {
            $unit = Unit::findorfail($request->unit_id);
            $unit->status = $request->status;
            $unit->update();
            return response()->json(['status'=>true, 'message'=>"Successfully the unit's status has been updated"]);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function productStatusUpdate(Request $request)
    {
        try
        {
            $product = Product::findorfail($request->product_id);
            $product->status = $request->status;
            $product->update();
            return response()->json(['status'=>true, 'message'=>"Successfully the product's status has been updated"]);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function reviewStatusUpdate(Request $request)
    {
        try
        {
            $review = Review::findorfail($request->review_id);
            $review->status = $request->status;
            $review->update();
            return response()->json(['status'=>true, 'message'=>"Successfully the review's has been updated"]);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function orderStatusUpdate(Request $request)
    {   
        DB::beginTransaction();
        try
        {   

            $order = Orderdetail::findOrFail($request->order_id);

            $apiKey = setting()->courier_api_key;
            $secretKey = setting()->courier_secret;

            $payload = [
                'invoice'            => generateInvoice(),
                'recipient_name'     => $order->customer_name,
                'recipient_phone'    => $order->customer_phone,
                'cod_amount'         => $order->total,
                'recipient_address'  => $order->customer_address,
                'note'               => setting()->order_note,
            ];

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://portal.packzy.com/api/v1/create_order',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($payload), // <-- Encode as JSON
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    "Api-Key: {$apiKey}",
                    "Secret-Key: {$secretKey}",
                ],
            ]);

            $response = curl_exec($curl);
            curl_close($curl);

            $result = json_decode($response, true);

            //return response()->json($result);

            Courier::updateOrCreate(
                ['orderdetail_id' => $order->id], // Search condition
                [
                    'orderdetail_id' => $order->id,
                    'invoice_no' => $result['consignment']['invoice'],
                    'consignment_id' => $result['consignment']['consignment_id'],
                    'tracking_code' => $result['consignment']['tracking_code'],
                ]
            );

            // $courier = new Courier();
            // $courier->orderdetail_id = $order->id;
            // $courier->invoice_no = $result['consignment']['invoice'];
            // $courier->consignment_id = $result['consignment']['consignment_id'];
            // $courier->tracking_code = $result['consignment']['tracking_code'];
            // $courier->save(); 
            
            $order->status = $request->status;
            $order->update();
            if($request->status == 'Accept')
            {   
                //ekhane kisu kaj korta hobe
                foreach($order->orders as $row)
                {
                    $product = Product::where('id',$row->product_id)->first();
                    $variant = Variant::where('id',$row->variant_id)->first();
                    if($product)
                    {
                        $product->stock_qty-=$row->qty;
                        $product->update();
                    }
                    
                    // if($variant)
                    // {
                    //     $variant->stock_qty-=$row->qty;
                    //     $variant->update();
                    // }
                    
                }
            }
            DB::commit();
            return response()->json(['status'=>true, 'message'=>"Successfully the order's status has been changed"]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function findCourierOrder(Request $request)
    {
        try
        {
            $courier = Courier::with('orderdetail')->where('consignment_id',$request->search)->first();
            if(!$courier)
            {
                return response()->json(['status'=>false, 'message'=>'No record found', 'data'=>new \stdClass()]);
            }
            return response()->json(['status'=>true, 'data'=>$courier]);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function serviceStatusUpdate(Request $request)
    {
        try
        {
            $service = Service::findorfail($request->service_id);
            $service->status = $request->status;
            $service->update();
            return response()->json(['status'=>true, 'message'=>"Successfully the service's status has been deleted"]);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function packageStatusUpdate(Request $request)
    {
        try
        {
            $package = Package::findorfail($request->package_id);
            $package->status = $request->status;
            $package->update();
            return response()->json(['status'=>true, 'message'=>"Successfully the package's status has been updated"]);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function userStatusUpdate(Request $request)
    {
        try
        {
            $user = User::findorfail($request->user_id);
            $user->status = $request->status;
            $user->update();
            return response()->json(['status'=>true, 'message'=>"Successfully the user's status has been updated"]);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function orderDetails(Request $request)
    {
        try
        {
            $order = Orderdetail::findorfail($request->order_id);
            return response()->json($order);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function orderCustomDiscount(Request $request)
    {
        try
        {   
            $order = Orderdetail::findorfail($request->order_id);
            if($order->discount != NULL)
            {
                return response()->json(['status'=>false, 'message'=>'Already discount exist for this']);
            }
            $order->discount = $request->discount;
            $order->total = $request->discount_price;
            $order->update();
            return response()->json(['status'=>true, 'message'=>'Successfully the discount has been taken']);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function ariadhakaStatusUpdate(Request $request)
    {
        try
        {   
            $area = Ariadhaka::findorfail($request->area_id);
            $area->status = $request->status;
            $area->update();
            return response()->json(['status'=>true, 'message'=>"Successfully the area's status has been updated"]);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function deleteVariant(Request $request)
    {
        try
        {
            $variant = Variant::findorfail($request->variant_id);
            $variant->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully the variant has been deleted']);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    
    public function deleteFullVariant(Request $request)
    {
        try
        {   
            $variant = Variant::findorfail($request->variant_id);
            Variant::where('variant_name',$variant->variant_name)->where('product_id',$request->product_id)->delete();
            return response()->json(['status' => true, 'message'=>'Successfully the variant has been deleted']);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    
    public function storeUnit(Request $request)
    {
        try
        {
            $unit = new Unit();
            $unit->user_id = $request->user_id; 
            $unit->domain_id = $request->domain_id;
            $unit->title = $request->title;
            $unit->status = $request->status;
            $unit->save();
            return response()->json(['status'=>true, 'unit_id'=>intval($unit->id), 'unit_title'=>$unit->title, 'units'=>units(), 'message'=>'Successfully a unit has been added']);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        } 
    }
}
