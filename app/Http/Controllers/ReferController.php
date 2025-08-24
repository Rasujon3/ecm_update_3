<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Refer;

class ReferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }

    public function referSettings()
    {   
    	$refer = Refer::where('user_id',user()->id)->first();
    	return view('settings.refer_settings', compact('refer'));
    }

    public function settingsRefer(Request $request)
    {
    	try
    	{
    		Refer::updateOrCreate(
			    ['user_id' => user()->id], // Search condition
			    [
			        'user_id' => user()->id,
			        'per_refer_point' => $request->per_refer_point,
			        'total_required_point' => $request->total_required_point,
			        'website_quantity' => $request->website_quantity,
			    ]
			); 

			$notification=array(
                'messege'=>'Successfully updated',
                'alert-type'=>'success',
            );

            return redirect()->back()->with($notification); 
    	}catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        } 
    }
}
