<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }
    public function addVideo(Request $request)
    {
    	$video = Video::where('user_id',user()->id)->first();
    	return view('videos.add_video', compact('video'));
    }

    public function saveVideo(Request $request)
    {
    	try
    	{
    		Video::updateOrCreate(
			    ['user_id' => user()->id], // Search condition
			    [
			        'user_id' => user()->id,
			        'domain_id' => getDomain()->id,
			        'video_type' => 'Youtube',
			        'video_url' => $request->video_url,
			        'video_id' => getYouTubeVideoId($request),
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
