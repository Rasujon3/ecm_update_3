<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Setting;
use Exception;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Orderdetail;
use App\Models\Courier;
use DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth_check');
    }

    public function orders(Request $request)
    {
        try
        {
            if($request->ajax()){

                $orders = Orderdetail::leftJoin('couriers','orderdetails.id','couriers.orderdetail_id')->select('orderdetails.*','couriers.invoice_no','couriers.consignment_id','couriers.tracking_code')->where('orderdetails.domain_id',getDomain()->id)->latest();

                return Datatables::of($orders)
                    ->addIndexColumn()

                    ->addColumn('order_id', function($row){
                        $courier = courier($row->id);
                        $result = $courier?$courier->consignment_id:"Order-00".$row->id;
                        return $result;
                    })

                    ->addColumn('order_date', function($row) {
                        return $row->created_at->format('d M Y h:i a');
                    })

                    ->addColumn('status', function($row) {
                        $statuses = ['Pending', 'Accept', 'Cancel'];

                        // Find the current status index
                        $currentIndex = array_search($row->status, $statuses);

                        $html = "<select class='form-control change-status' data-id='".$row->id."'>";

                        foreach ($statuses as $index => $status) {
                            // Disable previous statuses (index < currentIndex)
                            $disabled = ($index < $currentIndex && $row->status != 'Cancel') ? "disabled" : "";

                            // Mark current status as selected
                            $selected = ($row->status == $status) ? "selected" : "";

                            $html .= "<option value='{$status}' {$selected} {$disabled}>{$status}</option>";
                        }

                        $html .= "</select>";

                        return $html;
                    })


                    ->addColumn('courier_status', function($row) {

                        // $courier = courier($row->id);
                        // $apiKey = setting()->courier_api_key;
                        // $apiSecret = setting()->courier_secret;
                        // if($courier)
                        // {
                        //     $curl = curl_init();

                        //     curl_setopt_array($curl, array(
                        //         CURLOPT_URL => "https://portal.packzy.com/api/v1/status_by_cid/{$courier->consignment_id}",
                        //         CURLOPT_RETURNTRANSFER => true,
                        //         CURLOPT_ENCODING => '',
                        //         CURLOPT_MAXREDIRS => 10,
                        //         CURLOPT_TIMEOUT => 0,
                        //         CURLOPT_FOLLOWLOCATION => true,
                        //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        //         CURLOPT_CUSTOMREQUEST => 'GET',
                        //         CURLOPT_HTTPHEADER => array(
                        //             'Accept: application/json',
                        //             'Content-Type: application/json',
                        //             "Api-Key: $apiKey",
                        //             "Secret-Key: $apiSecret"
                        //         ),
                        //     ));

                        //     $response = curl_exec($curl);

                        //     $result = json_decode($response,true);

                        //     return $result['delivery_status'];

                        // }else{
                        //     return "-";
                        // }

                        return "<button type='button' class='btn btn-success see-order-status order_status_btn_".$row->id."' data-id='".$row->id."'>See Status</button>";
                    })

                    ->addColumn('payment_status', function($row) {
                        return !empty($row->transaction_hash)
                            ? "Paid ($row->payment_number, $row->transaction_hash)"
                            : "Due ($row->payment_method)";
                    })

                    ->addColumn('product_details', function($row) {
                        return '<a href="'.url('/show-products/'.$row->id).'">
                                <button type="button" class="btn btn-success btn-sm action-button edit-order" data-id="'.$row->id.'">View</button>
                                </a>';
                    })

                    ->addColumn('action', function($row){

                        $btn = "";
                        $btn .= '&nbsp;';
                        $btn .= ' <button type="button" class="btn btn-success btn-sm action-button edit-order customer-discount" data-id="'.$row->id.'"><i class="fa fa-percent"></i></button>';

                        $btn .= '&nbsp;';



                        $btn .= ' <a href="'.url('/show-invoice/'.$row->id).'" class="btn btn-primary btn-sm action-button edit-order" data-id="'.$row->id.'"><i class="fa fa-eye"></i></a>';

                        $btn .= '&nbsp;';


                        $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-order action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';

                        $btn .= '&nbsp;';


                        $btn .= ' <a href="'.url('/order-print/'.$row->id).'" target="_blank" class="btn btn-info btn-sm action-button" data-id="'.$row->id.'"><i class="fa fa-print"></i></a>';



                        return $btn;
                    })->filter(function ($instance) use ($request) {

                        if ($request->get('search') != "") {
                            $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('orderdetails.customer_name', 'LIKE', "%$search%")->orWhere('orderdetails.customer_phone', 'LIKE', "%$search%")->orWhere('couriers.consignment_id','LIKE',"%$search%");
                            });
                        }

                        if ($request->get('from_date') != "") {
                            $instance->where(function($w) use($request){
                                $w->orWhereDate('orderdetails.created_at', '>=', $request->from_date);
                            });
                        }

                        if ($request->get('to_date') != "") {
                            $instance->where(function($w) use($request){
                                $w->orWhereDate('orderdetails.created_at', '<=', $request->to_date);
                            });
                        }

                        if ($request->get('status') != "") {
                            $instance->where(function($w) use($request){
                                $status = $request->get('status');
                                $w->orWhere('orderdetails.status', $status);
                            });
                        }


                    })->setRowID('id')
                    ->rawColumns(['action','status','order_id','order_date','courier_status', 'product_details'])
                    ->make(true);
            }
            return view('orders.my_order');
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    
    
    public function seeOrderStatus(Request $request)
    {
        try
        {
            $row = Orderdetail::findorfail($request->order_id);
            $courier = courier($row->id);
            $apiKey = setting()->courier_api_key;
            $apiSecret = setting()->courier_secret;
            if($courier)
            {
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://portal.packzy.com/api/v1/status_by_cid/{$courier->consignment_id}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Accept: application/json',
                        'Content-Type: application/json',
                        "Api-Key: $apiKey",
                        "Secret-Key: $apiSecret"
                    ),
                ));

                $response = curl_exec($curl);

                $result = json_decode($response,true);

                return response()->json($result['delivery_status']);

            }else{
                return response()->json("Pending");
            }
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function deleteOrder($id)
    {
        try
        {
            DB::beginTransaction();
            $orderDetail = Orderdetail::findOrFail($id);

            foreach ($orderDetail->orders as $order) {
                $order->variantIds()->delete();
                $order->delete();
            }

            if ($orderDetail->courier) {
                $orderDetail->courier->delete();
            }

            $orderDetail->delete();
            DB::commit();
            return response()->json(['status'=>true, 'message'=>'Successfully the order has been deleted']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function showInvoice($id)
    {
        try
        {
            $order = Orderdetail::with([
                'orders.product',
                'orders.variantIds.variant'
            ])->findOrFail($id);
            return view('orders.invoice',compact('order'));
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function printInvoice($id)
    {
        try
        {
            $order = Orderdetail::with('orders.variant')->findorfail($id);
            return view('orders.print_invoice',compact('order'));
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function searchCourierOrder()
    {
        return view('orders.search');
    }

    public function orderPrint($id)
    {
        try
        {
            $order = Orderdetail::with('orders')->findorfail($id);
            $courier = courier($order->id);
            $OrderID = $courier ? $courier->consignment_id : "Order-00".$order->id;

            if (!$order->domain_id) {
                $notification = [
                    'messege' => 'Domain Not Found',
                    'alert-type' => 'error'
                ];
                return redirect()->back()->with($notification);
            }
            $domain = Domain::where('id',$order->domain_id)->first('user_id');
            if (!$domain->user_id) {
                $notification = [
                    'messege' => 'User Not Found',
                    'alert-type' => 'error'
                ];
                return redirect()->back()->with($notification);
            }
            $settings = Setting::where('user_id',$domain->user_id)->first('merchant_id');
            return view('orders.print_order',compact('order', 'OrderID', 'settings'));
        } catch(Exception $e) {
            // Log the error
            Log::error('Error in storing Banner: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification = [
                'messege' => 'Something went wrong!!!',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification);
        }
    }
    
    public function showProducts($id)
    {
        try
        {
            $order = Orderdetail::with([
                'orders.product',
                'orders.variantIds.variant'
            ])->findOrFail($id);
            return view('orders.products',compact('order'));
        }catch(Exception $e){
            // Log the error
            Log::error('Error in showing ordered products: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification = [
                'messege' => 'Something went wrong!!!',
                'alert-type' => 'error'
            ];
            return redirect()->route('my.orders')->with($notification);
        }
    }
}
