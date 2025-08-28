<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Support\Facades\Log;

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
			        'video_url' => $request->video_url ?? '',
			        'video_id' => $request->video_url ? getYouTubeVideoId($request) : '',
			    ]
			);

			$notification=array(
                'messege'=>'Successfully updated',
                'alert-type'=>'success',
            );

            return redirect()->back()->with($notification);

    	} catch(Exception $e) {
            Log::error('Error in storing store video: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification = [
                'messege' => 'Something went wrong!!!',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification);
    	}
    }


}
