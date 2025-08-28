<?php

namespace App\Http\Controllers;

use App\Http\Requests\WhyChooseUsRequest;
use App\Models\AllProductContent;
use App\Models\WhyChooseUs;
use App\Models\WhyChooseUsTitle;
use Exception;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AllProductContentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }
    public function index()
    {
        $data = AllProductContent::where('user_id', user()->id)->first();
        return view('allProductContent.create',compact('data'));
    }
    public function store(Request $request)
    {
        try
        {
            $data = AllProductContent::where('user_id', user()->id)->first();

            $defaults = [
                'title' => $data ? $data->title : null,
                'description' => $data ? $data->description : null,
            ];

            if ($data) {
                AllProductContent::where('id', $data->id)->update(
                    [
                        'user_id' => user()->id,
                        'domain_id' => getDomain()->id,
//                        'title' => $request->title ?? $defaults['title'],
//                        'description' => $request->description ?? $defaults['description'],
                        'title' => $request->title ?? '',
                        'description' => $request->description ?? '',
                    ]
                );
            } else {
                AllProductContent::create(
                    [
                        'user_id' => user()->id,
                        'domain_id' => getDomain()->id,
//                        'title' => $request->title ?? $defaults['title'],
//                        'description' => $request->description ?? $defaults['description'],
                        'title' => $request->title ?? '',
                        'description' => $request->description ?? '',
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
            Log::error('Error in updating AllProductContent: ', [
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

