<?php

namespace App\Http\Controllers;

use App\Models\Ariadhaka;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAreaRequest;
use App\Http\Requests\UpdateAreaRequest;
use DataTables;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AriadhakaController extends Controller
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
        $count = Ariadhaka::where('user_id',user()->id)->count();
        try
        {
            if($request->ajax()){

               $ariadhakas = Ariadhaka::where('user_id',user()->id)->select('*')->latest();

                    return Datatables::of($ariadhakas)
                        ->addIndexColumn()


                        ->addColumn('status', function($row){
                            return '<label class="switch"><input class="' . ($row->status == 'Active' ? 'active-aria' : 'decline-aria') . '" id="status-aria-update"  type="checkbox" ' . ($row->status == 'Active' ? 'checked' : '') . ' data-id="'.$row->id.'"><span class="slider round"></span></label>';
                        })

                        ->addColumn('action', function($row){

                           $btn = "";
                           $btn .= '&nbsp;';
                           $btn .= ' <a href="'.route('ariadhakas.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-aria" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                            $btn .= '&nbsp;';


                            $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-aria action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';



                            return $btn;
                        })
                        ->rawColumns(['action','status'])
                        ->make(true);
            }
            return view('areas.index', compact('count'));
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
     */
    public function create()
    {
        $divisions = $this->getDivision();
        return view('areas.create', compact('divisions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreAreaRequest $request)
    {
        $count = Ariadhaka::where('user_id',user()->id)->count();
        if ($count > 0) {
            $notification=array(
                'messege'=>'You can not add more than one area',
                'alert-type'=>'error'
            );
            return redirect()->route('ariadhakas.index')->with($notification);
        }
        try
        {
            $area = new Ariadhaka();
            $area->user_id = user()->id;
            $area->division = $request->division ?? '';
            $area->area_name = $request->area_name;
            # $area->area_type = $request->area_type;
            $area->status = $request->status;
            $area->inside_delivery_charges = $request->inside_delivery_charges ?? null;
            $area->outside_delivery_charges = $request->outside_delivery_charges ?? null;
            $area->save();

            $notification=array(
                'messege'=>'Successfully an area has been added',
                'alert-type'=>'success',
            );

            return redirect()->back()->with($notification);
        }catch(Exception $e){
            // Log the error
            Log::error('Error in storing area: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification=array(
                'messege'=>'Something went wrong!!!',
                'alert-type'=>'error'
            );
            return redirect()->back()->with($notification);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ariadhaka  $ariadhaka
     * @return \Illuminate\Http\Response
     */
    public function show(Ariadhaka $ariadhaka)
    {
        $divisions = $this->getDivision();
        return view('areas.edit', compact('ariadhaka', 'divisions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ariadhaka  $ariadhaka
     * @return \Illuminate\Http\Response
     */
    public function edit(Ariadhaka $ariadhaka)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ariadhaka  $ariadhaka
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAreaRequest $request, Ariadhaka $ariadhaka)
    {
        try
        {
            $ariadhaka->division = $request->division ?? '';
            $ariadhaka->area_name = $request->area_name;
            # $ariadhaka->area_type = $request->area_type;
            $ariadhaka->status = $request->status;
            $ariadhaka->inside_delivery_charges = $request->inside_delivery_charges ?? null;
            $ariadhaka->outside_delivery_charges = $request->outside_delivery_charges ?? null;
            $ariadhaka->update();
            $notification=array(
                'messege'=>'Successfully the area has been updated',
                'alert-type'=>'success',
            );

            return redirect()->route('ariadhakas.index')->with($notification);
        }catch(Exception $e){
            // Log the error
            Log::error('Error in storing area: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification=array(
                'messege'=>'Something went wrong!!!',
                'alert-type'=>'error'
            );
            return redirect()->route('ariadhakas.index')->with($notification);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ariadhaka  $ariadhaka
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ariadhaka $ariadhaka)
    {
        try
        {
            $ariadhaka->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully the aria has been deleted']);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    private function getDivision()
    {
        // API call
        $response = Http::get('https://bdapi.vercel.app/api/v.1/division');

        $divisions = [];
        if ($response->successful()) {
            $divisions = $response['data'];
        }
        return $divisions;
    }

    public function getDistricts($division_id)
    {
        if (!is_numeric($division_id)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid division ID'
            ], 422);
        }

        $response = Http::get("https://bdapi.vercel.app/api/v.1/district/{$division_id}");

        if ($response->successful()) {
            return response()->json([
                'status' => true,
                'districts' => $response['data']
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'API error'
            ], 500);
        }
    }
}
