<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimerRequest;
use App\Models\ModuleTutorial;
use App\Models\Timer;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TimerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }
    public function index()
    {
        $selection = getCurrentSelection();
        $domainId = $selection['domain_id'];
        $subDomainId = $selection['sub_domain_id'];

        if ((!$domainId && !$subDomainId)) {
            $notification=array(
                'messege' => 'Domain & Subdomain mismatch.',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        $moduleName = 'Timer';
        $url = null;
        $tutorial = null;
        if (!empty($moduleName)) {
            $tutorial = ModuleTutorial::where('module_title', trim($moduleName))->first();
        }
        if($tutorial && !empty($tutorial->video_url)) {
            $url = $this->getYoutubeEmbedUrl($tutorial->video_url);
        }

        $data = Timer::where('user_id', user()->id)
            ->where('domain_id', $domainId)
            ->where('sub_domain_id', $subDomainId)
            ->first();
        return view('timer.timer',compact('data', 'url'));
    }
    function getYoutubeEmbedUrl($url)
    {
        $pattern_long = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=))([^"&?\/ ]{11})/';
        $pattern_short = '/youtu\.be\/([^"&?\/ ]{11})/';

        if (preg_match($pattern_long, $url, $matches)) {
            $videoId = $matches[1];
        } elseif (preg_match($pattern_short, $url, $matches)) {
            $videoId = $matches[1];
        } else {
            return null; // Not a valid YouTube URL
        }

        return 'https://www.youtube.com/embed/' . $videoId;
    }
    public function store(TimerRequest $request)
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
                return redirect()->back()->with($notification);
            }

            $data = Timer::where('user_id', user()->id)->first();

            $defaults = [
                'title' => $data ? $data->title : null,
                'time' => $data ? $data->time : null,
            ];

            if ($data) {
                Timer::where('id', $data->id)->update(
                    [
                        'user_id' => user()->id,
			            'domain_id' => $domainId,
			            'sub_domain_id' => $subDomainId,
//                        'title' => $request->title ?? $defaults['title'],
//                        'time' => Carbon::parse($request->time)->timestamp ?? $defaults['time'],
                        'title' => $request->title ?? '',
                        'time' => $request->time ? Carbon::parse($request->time)->timestamp : null,
                    ]
                );
            } else {
                Timer::create(
                    [
                        'user_id' => user()->id,
                        'domain_id' => $domainId,
                        'sub_domain_id' => $subDomainId,
//                        'title' => $request->title ?? $defaults['title'],
//                        'time' => Carbon::parse($request->time)->timestamp ?? $defaults['time'],
                        'title' => $request->title ?? '',
                        'time' => $request->time ? Carbon::parse($request->time)->timestamp : null,
                    ]
                );
            }

            $notification = [
                'messege'    => 'Successfully updated',
                'alert-type' => 'success',
            ];

            return redirect()->back()->with($notification);

        } catch (Exception $e) {
            // Log the error
            Log::error('Error in updating Timer: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification=array(
                'messege' => 'Something went wrong!!!',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }
}
