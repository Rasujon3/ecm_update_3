<?php

namespace App\Http\Controllers;

use App\Http\Requests\WhyChooseUsRequest;
use App\Models\WhyChooseUs;
use Exception;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WhyChooseUsController extends Controller
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

            $url = getVideoUrl('Why Choose Us');

            if($request->ajax()){

                $whyChooseUs = WhyChooseUs::where('user_id', Auth::user()->id)
                    ->where('domain_id', $domainId)
                    ->where('sub_domain_id', $subDomainId)
                    ->select('*')
                    ->latest();

                return Datatables::of($whyChooseUs)
                    ->addIndexColumn()

                    ->addColumn('description', function($row){
                        return $row->description;
                    })

                    ->addColumn('action', function($row){

                        $btn = "";
                        $btn .= '&nbsp;';
                        $btn .= ' <a href="'.route('why_choose_us.edit',$row->id).'" class="btn btn-primary btn-sm action-button edit-service" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                        $btn .= '&nbsp;';

                        $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-service action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';

                        return $btn;
                    })

                    ->rawColumns(['description', 'action'])
                    ->make(true);
            }
            return view('whyChooseUs.index', compact('url'));
        } catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function create()
    {
        $url = getVideoUrl('Why Choose Us');
        return view('whyChooseUs.create', compact('url'));
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

            WhyChooseUs::create([
                'user_id' => Auth::user()->id,
                'domain_id' => $domainId,
                'sub_domain_id' => $subDomainId,
                'description' => $request->description,
            ]);
            $notification = array(
                'messege' => 'Successfully a item has been added',
                'alert-type' => 'success',
            );

            return redirect()->route('why_choose_us.index')->with($notification);
        } catch(Exception $e) {
            // Log the error
            Log::error('Error in storing Why Choose Us: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification = [
                'messege' => 'Something went wrong!!!',
                'alert-type' => 'error'
            ];

            return redirect()->route('why_choose_us.index')->with($notification);
        }
    }

    public function edit($id)
    {
        $item = WhyChooseUs::findOrFail($id);
        return view('whyChooseUs.edit', compact('item'));
    }

    public function update(WhyChooseUsRequest $request, WhyChooseUs $whyChooseUs)
    {
        try
        {
            $whyChooseUs->description = $request->description;
            $whyChooseUs->save();

            $notification=array(
                'messege' => 'Successfully the item has been updated',
                'alert-type' => 'success',
            );

            return redirect()->route('why_choose_us.index')->with($notification);

        } catch(Exception $e) {
            // Log the error
            Log::error('Error in updating Why Choose Us: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification = [
                'messege' => 'Something went wrong!!!',
                'alert-type' => 'error'
            ];

            return redirect()->route('why_choose_us.index')->with($notification);
        }
    }

    public function destroy(WhyChooseUs $whyChooseUs)
    {
        try
        {
            $whyChooseUs->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully the data has been deleted']);
        } catch(Exception $e) {
            // Log the error
            Log::error('Error in deleting Why Choose Us: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
}

