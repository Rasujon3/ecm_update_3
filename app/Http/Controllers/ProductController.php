<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Product;
use App\Models\Image;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use DataTables;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Package;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth_check');
    }

    public function index(Request $request)
    {
        try
        {
            if($request->ajax()){

               $products = Product::where('user_id',user()->id)->select('*')->latest();

                    return Datatables::of($products)
                        ->addIndexColumn()

                        ->addColumn('unit', function($row){
                            return $row->unit->title;
                        })

                        // ->addColumn('status', function($row){
                        //     return '<label class="switch"><input class="' . ($row->status == 'Active' ? 'active-product' : 'decline-product') . '" id="status-product-update"  type="checkbox" ' . ($row->status == 'Active' ? 'checked' : '') . ' data-id="'.$row->id.'"><span class="slider round"></span></label>';
                        // })

                        ->addColumn('status', function($row){
                            $result = $row->status == 'Active'?"<span class='badge badge-success p-2 font-weight-bold'>ACTIVE</span>":"<span class='badge badge-danger p-2 font-weight-bold'>INACTIVE</span>";
                            return $result;
                        })

                        ->addColumn('action', function($row){

                           $btn = "";
                           $btn .= '&nbsp;';

                            $btn .= ' <a href="'.url('/add-variant/'.$row->id).'" class="btn btn-success btn-sm action-button edit-product"><i class="fa fa-plus"></i> Add Variant</a>';

                           $btn .= '&nbsp;';

                           $btn .= ' <a href="'.route('products.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-product" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                            $btn .= '&nbsp;';


                            $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-product action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';



                            return $btn;
                        })
                        ->rawColumns(['action','status','unit'])
                        ->make(true);
            }

            $userInfo = User::with('domain', 'package')
                ->where('id',Auth::user()->id)
                ->firstOrFail();
            return view('products.index', compact('userInfo'));
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        DB::beginTransaction();
        try
        {
            $user = User::where('id',user()->id)->first();
            $count = Product::where('user_id',user()->id)->count();
            if ($user->products_add_status === 1 || $count >= 5) {
                $notification = array(
                    'messege'=>'You can not add more than 5 products',
                    'alert-type'=>'error'
                );
                return redirect()->route('products.index')->with($notification);
            }
            $package = Package::where('id',getDomain()->package_id)->first();
            if($count > $package->max_product)
            {
                $notification=array(
                 'messege'=>'Product Upload Quota Exceeded',
                 'alert-type'=>'error',
                );
               return redirect()->back()->with($notification);
            }

            $product = new Product();
            $product->user_id = user()->id;
            $product->domain_id = getDomain()->id;
            $product->unit_id = $request->unit_id;
            $product->product_name = $request->product_name;
            $product->product_price = $request->product_price;
            $product->discount_price = $request->discount_price;
            $product->stock_qty = $request->stock_qty;
            $product->discount = $request->discount;
            $product->description = $request->description;
            $product->fake_stocks = $request->fake_stocks;
            //$product->status = auth()->user()->status ==='Inactive' ? "Active" : "Inactive";
            $product->status = 'Inactive';
            $product->save();

            if($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $image) {
                    $imageName = time().$product->id.'-' . $image->getClientOriginalName();
                    $image->move(public_path('uploads/gallery_images'), $imageName);

                    $imageModel = new Image(['image' => 'uploads/gallery_images/' . $imageName]);
                    $product->images()->save($imageModel);
                }
            }

            $notification=array(
                'messege'=>'Successfully a product has been added',
                'alert-type'=>'success',
            );
            DB::commit();

            $countAfterInsert = Product::where('user_id',user()->id)->count();
            if($countAfterInsert >= 5)
            {
                $user->products_add_status = 1;
                $user->save();
            }
            return redirect('/add-variant/'.$product->id)->with($notification);

        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        try
        {
            $product->unit_id = $request->unit_id;
            $product->product_name = $request->product_name;
            $product->product_price = $request->product_price;
            $product->discount_price = $request->discount_price;
            $product->stock_qty = $request->stock_qty;
            $product->discount = $request->discount;
            $product->description = $request->description;
            $product->fake_stocks = $request->fake_stocks;
            $product->save();
            $notification=array(
                'messege'=>'Successfully the product has been updated',
                'alert-type'=>'success',
            );

            return redirect('/products')->with($notification);

        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try
        {
            $product->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully the product has been deleted']);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function redirectDemoUrl(Product $product)
    {
        try
        {
            $demo_url = config('services.demo.url'); // ex: ?user_id=101
            $userId = Auth::user()->id;
            $domain = Domain::where('user_id', $userId)->first();
            $prepare_url = "$demo_url?user_id=$userId&domain=$domain->domain&package_id=$domain->package_id";

            return redirect()->away($prepare_url);
        } catch(Exception $e) {
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
}
