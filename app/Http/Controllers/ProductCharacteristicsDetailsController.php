<?php

namespace App\Http\Controllers;

use App\Http\Requests\WhyChooseUsRequest;
use App\Models\ProductCharacteristicsDetails;
use App\Models\ProductNarrativeDetails;
use App\Models\WhyChooseUs;
use Exception;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductCharacteristicsDetailsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }
    public function index(Request $request)
    {
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

            $url = getVideoUrl('Product');
            if($request->ajax()){

                $productCharacteristicsDetails = ProductCharacteristicsDetails::where('user_id', Auth::user()->id,)
                    ->where('domain_id', $domainId)
                    ->where('sub_domain_id', $subDomainId)
                    ->select('*')
                    ->latest();

                return Datatables::of($productCharacteristicsDetails)
                    ->addIndexColumn()

                    ->addColumn('description', function($row){
                        return $row->description;
                    })

                    ->addColumn('action', function($row){

                        $btn = "";
                        $btn .= '&nbsp;';
                        $btn .= ' <a href="'.route('product_characteristics_details.edit',$row->id).'" class="btn btn-primary btn-sm action-button edit-service" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                        $btn .= '&nbsp;';

                        $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-details action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';

                        return $btn;
                    })

                    ->rawColumns(['description', 'action'])
                    ->make(true);
            }
            return view('productCharacteristics.index', compact('url'));
        } catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function create()
    {
        $url = getVideoUrl('Product');
        return view('productCharacteristics.create', compact('url'));
    }

    public function store(WhyChooseUsRequest $request)
    {
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

            ProductCharacteristicsDetails::create([
                'user_id' => Auth::user()->id,
                'domain_id' => $domainId,
                'sub_domain_id' => $subDomainId,
                'description' => $request->description,
            ]);
            $notification = array(
                'messege' => 'Successfully a item has been added',
                'alert-type' => 'success',
            );

            return redirect()->route('product_characteristics_details.index')->with($notification);
        } catch(Exception $e) {
            // Log the error
            Log::error('Error in storing ProductCharacteristicsDetails: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification = [
                'messege' => 'Something went wrong!!!',
                'alert-type' => 'error'
            ];
            return redirect()->route('product_characteristics_details.index')->with($notification);
        }
    }

    public function edit($id)
    {
        $item = ProductCharacteristicsDetails::findOrFail($id);
        return view('productCharacteristics.edit', compact('item'));
    }

    public function update(WhyChooseUsRequest $request, ProductCharacteristicsDetails $productCharacteristicsDetails)
    {
        try
        {
            $productCharacteristicsDetails->description = $request->description;
            $productCharacteristicsDetails->save();
            $notification=array(
                'messege' => 'Successfully the item has been updated',
                'alert-type' => 'success',
            );

            return redirect()->route('product_characteristics_details.index')->with($notification);

        } catch(Exception $e) {
            // Log the error
            Log::error('Error in updating ProductCharacteristicsDetails: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification = [
                'messege' => 'Something went wrong!!!',
                'alert-type' => 'error'
            ];
            return redirect()->route('product_characteristics_details.index')->with($notification);
        }
    }

    public function destroy(ProductCharacteristicsDetails $productCharacteristicsDetails)
    {
        try
        {
            $productCharacteristicsDetails->delete();
            $notification=array(
                'messege' => 'Successfully the item has been deleted.',
                'alert-type' => 'success',
            );

            return redirect()->route('product_characteristics_details.index')->with($notification);

        } catch(Exception $e) {
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
}

