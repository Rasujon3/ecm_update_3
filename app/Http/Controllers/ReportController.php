<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orderdetail;
use App\Models\Expense;
use DataTables;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }

    public function salesReport(Request $request)
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
                            return $row->status;
                        })


                       ->addColumn('courier_status', function($row) { 
                            $courier = courier($row->id);
                            if($courier)
                            {
                                $curl = curl_init();

                                curl_setopt_array($curl, array(
                                  CURLOPT_URL => 'https://portal.packzy.com/api/v1/status_by_cid/147862675',
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
                                    'Api-Key: 4n8bjp8rtwqkof6aykmzixtyit2ibske',
                                    'Secret-Key: j6sedrotktdkvbueiacuahyd'
                                  ),
                                ));

                                $response = curl_exec($curl);

                                $result = json_decode($response,true);

                                return $result['delivery_status'];
                            }else{
                                return "-";
                            }
                            

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

                                
                        })
                        ->rawColumns(['status','order_id','order_date','courier_status'])
                        ->make(true);
            }
            $totalSum = totalSum();
            return view('reports.sales_report',compact('totalSum'));
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        } 
    }

    public function financeReport(Request $request)
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

                        ->addColumn('total_expense', function($row) {
                            //return $row->created_at->format('d M Y h:i a');
                           return expenseTotal($row);
                        })

                        ->addColumn('total_revenue', function($row) {
                            return totalRevenue($row);
                        })

                       ->addColumn('status', function($row) {
                            return $row->status;
                        })->filter(function ($instance) use ($request) {

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

                                
                        })
                        ->rawColumns(['status','order_id','order_date','total_expense','total_revenue'])
                        ->make(true); 
            }
            return view('reports.finance_report'); 
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    } 
}
