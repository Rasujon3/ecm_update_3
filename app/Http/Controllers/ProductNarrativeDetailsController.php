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

class ProductNarrativeDetailsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }
    public function index(Request $request)
    {
        try
        {
            if($request->ajax()){

                $productNarrativeDetails = ProductNarrativeDetails::where('user_id', Auth::user()->id,)->select('*')->latest();

                return Datatables::of($productNarrativeDetails)
                    ->addIndexColumn()

                    ->addColumn('description', function($row){
                        return $row->description;
                    })

                    ->addColumn('action', function($row){

                        $btn = "";
                        $btn .= '&nbsp;';
                        $btn .= ' <a href="'.route('product_narrative_details.edit',$row->id).'" class="btn btn-primary btn-sm action-button edit-service" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                        $btn .= '&nbsp;';

                        $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-details action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';

                        return $btn;
                    })

                    ->rawColumns(['description', 'action'])
                    ->make(true);
            }
            return view('productNarrative.index');
        } catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function create()
    {
        return view('productNarrative.create');
    }

    public function store(WhyChooseUsRequest $request)
    {
        try
        {
            ProductNarrativeDetails::create([
                'user_id' => Auth::user()->id,
                'domain_id' => getDomain()->id,
                'description' => $request->description,
            ]);
            $notification = array(
                'messege' => 'Successfully a item has been added',
                'alert-type' => 'success',
            );

            return redirect()->route('product_narrative_details.index')->with($notification);
        } catch(Exception $e) {
            // Log the error
            Log::error('Error in storing ProductNarrativeDetails: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification = [
                'messege' => 'Something went wrong!!!',
                'alert-type' => 'error'
            ];
            return redirect()->route('product_narrative_details.index')->with($notification);
        }
    }

    public function edit($id)
    {
        $item = ProductNarrativeDetails::findOrFail($id);
        return view('productNarrative.edit', compact('item'));
    }

    public function update(WhyChooseUsRequest $request, ProductNarrativeDetails $productNarrativeDetails)
    {
        try
        {
            $productNarrativeDetails->description = $request->description;
            $productNarrativeDetails->save();
            $notification=array(
                'messege' => 'Successfully the item has been updated',
                'alert-type' => 'success',
            );

            return redirect()->route('product_narrative_details.index')->with($notification);

        } catch(Exception $e) {
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function destroy(ProductNarrativeDetails $productNarrativeDetails)
    {
        try
        {
            $productNarrativeDetails->delete();
            $notification=array(
                'messege' => 'Successfully the item has been deleted.',
                'alert-type' => 'success',
            );

            return redirect()->route('product_narrative_details.index')->with($notification);

        } catch(Exception $e) {
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
}

