<?php

namespace App\Http\Controllers;

use App\Http\Requests\WhyChooseUsRequest;
use App\Models\WhyChooseUs;
use App\Models\WhyChooseUsTitle;
use Exception;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WhyChooseUsTitleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }
    public function index()
    {
        $data = WhyChooseUsTitle::where('user_id', user()->id)->first();
        return view('whyChooseUsTitle.create',compact('data'));
    }
    public function store(Request $request)
    {
        try
        {
            $data = WhyChooseUsTitle::where('user_id', user()->id)->first();

            $defaults = [
                'title' => $data ? $data->title : null,
            ];

            if ($data) {
                WhyChooseUsTitle::where('id', $data->id)->update(
                    [
                        'user_id' => user()->id,
                        'domain_id' => getDomain()->id,
                        'title' => $request->title ?? $defaults['title'],
                    ]
                );
            } else {
                WhyChooseUsTitle::create(
                    [
                        'user_id' => user()->id,
                        'domain_id' => getDomain()->id,
                        'title' => $request->title ?? $defaults['title'],
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
            Log::error('Error in updating WhyChooseUsTitle: ', [
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

