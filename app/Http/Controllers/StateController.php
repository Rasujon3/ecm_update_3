<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use DataTables;

class StateController extends Controller
{

	public function __construct()
    {
        $this->middleware('auth_check');
    }

    public function userProducts(Request $request)
    {
    	try
        {
            if($request->ajax()){
                $products = Product::select('*')->latest();
                if ($request->has('user_id') && $request->user_id) {
                    $products->where('user_id', $request->user_id);
                }
                    return Datatables::of($products)
                        ->addIndexColumn()


                        ->addColumn('status', function($row){
                            return '<label class="switch"><input class="' . ($row->status == 'Active' ? 'active-product' : 'decline-product') . '" id="status-product-update"  type="checkbox" ' . ($row->status == 'Active' ? 'checked' : '') . ' data-id="'.$row->id.'"><span class="slider round"></span></label>';
                        })

                        ->addColumn('unit', function($row){
                            return $row->unit->title;
                        })

                        ->addColumn('action', function($row){

                           $btn = "";
                           $btn .= '&nbsp;';
                           $btn .= ' <a href="'.url('/user-product/'.$row->id).'" class="btn btn-primary btn-sm action-button view-product" data-id="'.$row->id.'"><i class="fa fa-eye"></i></a>';

                            $btn .= '&nbsp;';


                            $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-product action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';



                            return $btn;

                        })->filter(function ($instance) use ($request) {


                            if ($request->get('status') != "") {
                                 $instance->where(function($w) use($request){
                                    $status = $request->get('status');
                                    $w->orWhere('products.status', $status);
                                });
                            }


                        })
                        ->rawColumns(['action','status','unit'])
                        ->make(true);
            }
            return view('states.products');
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }


    public function users(Request $request)
    {
        try
        {
            if($request->ajax()){

               $users = User::where('role_id',2)->select('*')->latest();

                    return Datatables::of($users)
                        ->addIndexColumn()

                        ->addColumn('domain', function($row){
                            return $row->domain?$row->domain->domain:"-";
                        })


                        ->addColumn('total_products', function($row){
                            return count($row->products);
                        })

                        ->addColumn('status', function($row){
                            return '<label class="switch"><input class="' . ($row->status == 'Active' ? 'active-user' : 'decline-user') . '" id="status-user-update"  type="checkbox" ' . ($row->status == 'Active' ? 'checked' : '') . ' data-id="'.$row->id.'"><span class="slider round"></span></label>';
                        })

                        ->addColumn('action', function($row){

                           $btn = "";

                            $btn .= '&nbsp;';


                            $btn .= ' <a href="'.url('/user-products?user_id='.$row->id).'" class="btn btn-primary btn-sm action-button view-product" target="_blank"><i class="fa fa-eye"></i></a>';
                            $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-user action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';



                            return $btn;

                        })
                        ->rawColumns(['action','status','domain','total_products'])
                        ->make(true);
            }
            return view('states.users');
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function userProduct($id)
    {
        $product = Product::with('unit','images')->findorfail($id);
        //return $product;
        return view('states.user_product', compact('product'));
    }

    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);

            // Loop through products
            foreach ($user->products as $product) {
                foreach ($product->orders as $order) {
                    // Delete related order details
                    $order->orderdetail()->delete();

                    // Then delete the order
                    $order->delete();
                }

                // Optionally delete the product itself
                $product->delete();
            }

            // Finally, delete the user
            $user->delete();

            return response()->json(['status' => true, 'message' => 'Successfully the user has been deleted']);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
