<?php

namespace App\Http\Controllers;

use App\Http\Requests\WhyChooseUsRequest;
use App\Models\TakeALook;
use App\Models\WhyChooseUs;
use App\Models\WhyChooseUsTitle;
use Exception;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TakeALookTitleController extends Controller
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
            return redirect()->route('units.index')->with($notification);
        }

        $url = getVideoUrl('Take A Look');

        $data = TakeALook::where('user_id', user()->id)
            ->where('domain_id', $domainId)
            ->where('sub_domain_id', $subDomainId)
            ->first();

        return view('takeALookTitle.create',compact('data', 'url'));
    }
    public function store(Request $request)
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

            $data = TakeALook::where('user_id', user()->id)
                ->where('domain_id', $domainId)
                ->where('sub_domain_id', $subDomainId)
                ->first();

            $defaults = [
                'title' => $data ? $data->title : null,
            ];

            if ($data) {
                TakeALook::where('id', $data->id)->update(
                    [
                        'user_id' => user()->id,
                        'domain_id' => $domainId,
                        'sub_domain_id' => $subDomainId,
//                        'title' => $request->title ?? $defaults['title'],
                        'title' => $request->title ?? '',
                    ]
                );
            } else {
                TakeALook::create(
                    [
                        'user_id' => user()->id,
                        'domain_id' => $domainId,
                        'sub_domain_id' => $subDomainId,
//                        'title' => $request->title ?? $defaults['title'],
                        'title' => $request->title ?? '',
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
            Log::error('Error in updating TakeALook: ', [
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

