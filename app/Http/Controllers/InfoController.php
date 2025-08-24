<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class InfoController extends Controller
{   

	public function __construct()
    {
        $this->middleware('auth_check');
    }

    public function infoSettings()
    {
    	$info = setting();
    	return view('settings.info_settings',compact('info'));
    }

    public function settingsInfo(Request $request)
    {
    	try
    	{
    		Setting::updateOrCreate(
			    ['user_id' => user()->id], // Search condition
			    [
			        'user_id' => user()->id,
			        'privacy_policy' => $request->privacy_policy,
			        'contact_name' => $request->contact_name,
			        'contact_phone' => $request->contact_phone,
                    'contact_email' => $request->contact_email,
                    'contact_description' => $request->contact_description,
                    'contact_address' => $request->contact_address,
                    'about_us' => $request->about_us,
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
