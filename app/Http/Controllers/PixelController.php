<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePixelRequest;
use App\Models\Domain;
use App\Models\Pixel;
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

class PixelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }

    public function index(Request $request)
    {
        try
        {
            $url = getVideoUrl('Pixel');

            $selection = getCurrentSelection();
            $domainId = $selection['domain_id'];
            $subDomainId = $selection['sub_domain_id'];

            if ((!$domainId && !$subDomainId)) {
                $notification=array(
                    'messege' => 'Domain & Subdomain mismatch.',
                    'alert-type' => 'error'
                );
                return redirect()->route('units.index')->with($notification);
            }
            if($request->ajax()){

               $pixels = Pixel::where('user_id',user()->id)
                   ->where('domain_id', $domainId)
                   ->where('sub_domain_id', $subDomainId)
                   ->select('*')
                   ->latest();

                    return Datatables::of($pixels)
                        ->addIndexColumn()

                        ->addColumn('pixel_id', function($row){
                            return $row->pixel_id;
                        })

                        ->addColumn('status', function($row){
                            return '<label class="switch"><input class="' . ($row->status == 'Active' ? 'active-aria' : 'decline-aria') . '" id="status-update"  type="checkbox" ' . ($row->status == 'Active' ? 'checked' : '') . ' data-id="'.$row->id.'"><span class="slider round"></span></label>';
                        })

                        ->addColumn('action', function($row){

                           $btn = "";
                           $btn .= '&nbsp;';

                           $btn .= ' <a href="'.route('pixels.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-product" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                           $btn .= '&nbsp;';

                           $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-data action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';

                            return $btn;
                        })
                        ->rawColumns(['action','status','pixel_id'])
                        ->make(true);
            }

            $count = Pixel::where('user_id',user()->id)
                ->where('domain_id', $domainId)
                ->where('sub_domain_id', $subDomainId)
                ->count();

            return view('pixels.index', compact( 'url', 'count'));
        } catch(Exception $e) {
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    private function isCreated()
    {
        $selection = getCurrentSelection();
        $domainId = $selection['domain_id'];
        $subDomainId = $selection['sub_domain_id'];

        if ((!$domainId && !$subDomainId)) {
            $notification=array(
                'messege' => 'Domain & Subdomain mismatch.',
                'alert-type' => 'error'
            );
            return redirect()->route('units.index')->with($notification);
        }

        $isCreated = false;

        $count = Pixel::where('user_id',user()->id)
            ->where('domain_id', $domainId)
            ->where('sub_domain_id', $subDomainId)
            ->count();

        $packageId = getPackage($domainId, $subDomainId);
        if (!$packageId || empty($packageId->package_id)) {
            $notification=array(
                'messege' => 'Package not found.',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        $package = Package::where('id',$packageId->package_id)->first();
        if(!$package)
        {
            $notification=array(
                'messege' => 'Package not found.',
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        }

        if($count < $package->max_product)
        {
            $isCreated = true;
        }

        return $isCreated;
    }

    public function create()
    {
        return view('pixels.create');
    }
    public function store(StorePixelRequest $request)
    {
        DB::beginTransaction();
        try
        {
            $selection = getCurrentSelection();
            $domainId = $selection['domain_id'];
            $subDomainId = $selection['sub_domain_id'];

            if ((!$domainId && !$subDomainId)) {
                $notification=array(
                    'messege' => 'Domain & Subdomain mismatch.',
                    'alert-type' => 'error'
                );
                return redirect()->route('units.index')->with($notification);
            }

            $count = Pixel::where('user_id',user()->id)
                ->where('domain_id', $domainId)
                ->where('sub_domain_id', $subDomainId)
                ->count();

            if ($count >= 1) {
                $notification = array(
                    'messege'=>'You can not add more than 1 pixel ID',
                    'alert-type'=>'error'
                );
                return redirect()->route('pixels.index')->with($notification);
            }

            $pixel = new Pixel();
            $pixel->user_id = user()->id;
            $pixel->domain_id = $domainId;
            $pixel->sub_domain_id = $subDomainId;
            $pixel->pixel_id = $request->pixel_id;
            $pixel->status = 'Active';
            $pixel->save();

            DB::commit();

            $notification=array(
                'messege'=>'Successfully a pixel has been added',
                'alert-type'=>'success',
            );

            return redirect()->route('pixels.index')->with($notification);

        } catch(Exception $e) {
            DB::rollback();
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    public function show(Pixel $pixel)
    {
        return view('pixels.edit', compact('pixel'));
    }
    public function edit(Pixel $pixel)
    {
        //
    }
    public function update(StorePixelRequest $request, Pixel $pixel)
    {
        try
        {
            $pixel->pixel_id = $request->pixel_id;
            $pixel->save();

            $notification=array(
                'messege'=>'Successfully the pixel has been updated',
                'alert-type'=>'success',
            );

            return redirect('/pixels')->with($notification);

        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    public function destroy(Pixel $pixel)
    {
        try
        {
            $pixel->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully the pixel has been deleted']);
        } catch(Exception $e) {
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    public function pixelStatusUpdate(Request $request)
    {
        try {
            $pixel = Pixel::findorfail($request->product_id);
            $pixel->status = $request->status;
            $pixel->update();
            return response()->json([
                'status'=>true,
                'message'=>"Successfully the pixel's status has been updated"
            ]);
        } catch(Exception $e) {
            return response()->json([
                'status'=>false,
                'code'=>$e->getCode(),
                'message'=>$e->getMessage()
            ],500);
        }
    }
}
