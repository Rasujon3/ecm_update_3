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

        $url = getVideoUrl('Video');

        $video = Video::where('user_id',user()->id)
            ->where('domain_id', $domainId)
            ->where('sub_domain_id', $subDomainId)
            ->first();

    	return view('videos.add_video', compact('video', 'url'));
    }

    public function saveVideo(Request $request)
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


            $data = Video::where('user_id', user()->id)
                ->where('domain_id', $domainId)
                ->where('sub_domain_id', $subDomainId)
                ->first();

            if ($data) {
                Video::where('id', $data->id)->update(
                    [
                        'user_id' => user()->id,
                        'domain_id' => $domainId,
                        'sub_domain_id' => $subDomainId,
                        'video_type' => 'Youtube',
                        'video_url' => $request->video_url ?? '',
                        'video_id' => $request->video_url ? getYouTubeVideoId($request) : '',
                    ]
                );
            } else {
                Video::create(
                    [
                        'user_id' => user()->id,
                        'domain_id' => $domainId,
                        'sub_domain_id' => $subDomainId,
                        'video_type' => 'Youtube',
                        'video_url' => $request->video_url ?? '',
                        'video_id' => $request->video_url ? getYouTubeVideoId($request) : '',
                    ]
                );
            }

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
