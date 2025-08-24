<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimerRequest;
use App\Models\AboutUs;
use App\Models\BannerText;
use App\Models\LoginPageContent;
use App\Models\Setting;
use App\Models\Timer;
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
        $data = Timer::where('user_id', user()->id)->first();
        return view('timer.timer',compact('data'));
    }
    public function store(TimerRequest $request)
    {
        try
        {
            $data = Timer::where('user_id', user()->id)->first();

            $defaults = [
                'days' => $data ? $data->days : null,
                'hours' => $data ? $data->hours : null,
                'minutes' => $data ? $data->minutes : null,
            ];

            if ($data) {
                Timer::where('id', $data->id)->update(
                    [
                        'user_id' => user()->id,
			            'domain_id' => getDomain()->id,
                        'days' => $request->days ?? $defaults['days'],
                        'hours' => $request->hours ?? $defaults['hours'],
                        'minutes' => $request->minutes ?? $defaults['minutes'],
                    ]
                );
            } else {
                Timer::create(
                    [
                        'user_id' => user()->id,
			            'domain_id' => getDomain()->id,
                        'days' => $request->days ?? $defaults['days'],
                        'hours' => $request->hours ?? $defaults['hours'],
                        'minutes' => $request->minutes ?? $defaults['minutes'],
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
