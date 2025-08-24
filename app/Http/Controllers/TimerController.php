<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimerRequest;
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
        $data = Timer::where('user_id', user()->id)->first();
        return view('timer.timer',compact('data'));
    }
    public function store(TimerRequest $request)
    {
        try
        {
            $data = Timer::where('user_id', user()->id)->first();

            $defaults = [
                'title' => $data ? $data->title : null,
                'time' => $data ? $data->time : null,
            ];

            if ($data) {
                Timer::where('id', $data->id)->update(
                    [
                        'user_id' => user()->id,
			            'domain_id' => getDomain()->id,
                        'title' => $request->title ?? $defaults['title'],
                        'time' => Carbon::parse($request->time)->timestamp ?? $defaults['time'],
                    ]
                );
            } else {
                Timer::create(
                    [
                        'user_id' => user()->id,
			            'domain_id' => getDomain()->id,
                        'title' => $request->title ?? $defaults['title'],
                        'time' => Carbon::parse($request->time)->timestamp ?? $defaults['time'],
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
