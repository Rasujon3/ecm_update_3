<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\ModuleTutorial;
use App\Models\Package;
use App\Models\Slider;
use App\Models\SubDomain;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSliderRequest;
use App\Http\Requests\UpdateSliderRequest;
use DataTables;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth_check');
    }

    public function index(Request $request)
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

            $packageId = null;

            if ($domainId) {
                $packageId = Domain::where('id',$domainId)->first();
            }

            if (!$domainId && $subDomainId) {
                $packageId = SubDomain::where('id', $subDomainId)->first();
            }

            if (!$packageId || empty($packageId->package_id)) {
                $notification=array(
                    'messege' => 'Package not found.',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }

            $canSliderAdd = $this->canSliderAdd($packageId->package_id);

            $moduleName = 'Slider';
            $url = null;
            $tutorial = null;
            if (!empty($moduleName)) {
                $tutorial = ModuleTutorial::where('module_title', trim($moduleName))->first();
            }
            if($tutorial && !empty($tutorial->video_url)) {
                $url = $this->getYoutubeEmbedUrl($tutorial->video_url);
            }

            if($request->ajax()){

               $sliders = Slider::where('user_id',user()->id)
                   ->where('domain_id', $domainId)
                   ->where('sub_domain_id', $subDomainId)
                   ->select('*')
                   ->latest();

                    return Datatables::of($sliders)
                        ->addIndexColumn()

                        ->addColumn('image', function($row){
                            return "<img style='width: 60px; height:60px;' class='img-fluid' src='".$row->image."'>";
                        })

                        ->addColumn('status', function($row){
                            return '<label class="switch"><input class="' . ($row->status == 'Active' ? 'active-slider' : 'decline-slider') . '" id="status-slider-update"  type="checkbox" ' . ($row->status == 'Active' ? 'checked' : '') . ' data-id="'.$row->id.'"><span class="slider round"></span></label>';
                        })

                        ->addColumn('action', function($row){

                           $btn = "";
                           $btn .= '&nbsp;';
                           $btn .= ' <a href="'.route('sliders.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-slider" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                            $btn .= '&nbsp;';


                            $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-slider action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';



                            return $btn;
                        })
                        ->rawColumns(['image','action','status'])
                        ->make(true);
            }
            return view('sliders.index', compact('canSliderAdd', 'url'));
        } catch(Exception $e) {
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
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

    private function canSliderAdd($packageId)
    {
        $canSliderAdd = false;

        $package = Package::where('id',$packageId)->first();
        if ($package && !empty($package->is_slider)) {
            if ($package->is_slider === 'Yes') {
                $canSliderAdd = true;
            }
        }
        return $canSliderAdd;
    }

    public function create()
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

        $packageId = null;

        if ($domainId) {
            $packageId = Domain::where('id',$domainId)->first();
        }

        if (!$domainId && $subDomainId) {
            $packageId = SubDomain::where('id', $subDomainId)->first();
        }

        if (!$packageId || empty($packageId->package_id)) {
            $notification=array(
                'messege' => 'Package not found.',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        $canSliderAdd = $this->canSliderAdd($packageId->package_id);

        if (!$canSliderAdd) {
            $notification=array(
                'messege' => 'This package is not allow slider add.',
                'alert-type' => 'error',
            );

            return redirect()->route('sliders.index')->with($notification);
        }

        $moduleName = 'Slider';
        $url = null;
        $tutorial = null;
        if (!empty($moduleName)) {
            $tutorial = ModuleTutorial::where('module_title', trim($moduleName))->first();
        }
        if($tutorial && !empty($tutorial->video_url)) {
            $url = $this->getYoutubeEmbedUrl($tutorial->video_url);
        }

        return view('sliders.create', compact('url'));
    }
    public function store(StoreSliderRequest $request)
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

            $packageId = null;

            if ($domainId) {
                $packageId = Domain::where('id',$domainId)->first();
            }

            if (!$domainId && $subDomainId) {
                $packageId = SubDomain::where('id', $subDomainId)->first();
            }

            if (!$packageId || empty($packageId->package_id)) {
                $notification=array(
                    'messege' => 'Package not found.',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }

            $canSliderAdd = $this->canSliderAdd($packageId->package_id);

            if (!$canSliderAdd) {
                $notification=array(
                    'messege' => 'This package is not allow slider add.',
                    'alert-type' => 'error',
                );

                return redirect()->route('sliders.index')->with($notification);
            }

            if($request->file('image'))
            {
                $file = $request->file('image');
                $name = time().user()->id.$file->getClientOriginalName();
                $file->move(public_path().'/uploads/slider/', $name);
                $path = 'uploads/slider/'.$name;
            }
            $slider = new Slider();
            $slider->user_id = user()->id;
            $slider->domain_id = $domainId;
            $slider->sub_domain_id = $subDomainId;
            $slider->title = $request->title;
            $slider->sub_title = $request->sub_title;
            $slider->status = $request->status;
            $slider->image = $path;
            $slider->save();
            $notification=array(
                'messege'=>'Successfully a slider has been added',
                'alert-type'=>'success',
            );

            return redirect()->back()->with($notification);

        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function show(Slider $slider)
    {
        return view('sliders.edit',compact('slider'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function edit(Slider $slider)
    {
        //
    }

    public function update(UpdateSliderRequest $request, Slider $slider)
    {
        try
        {
            if($request->file('image'))
            {
                $file = $request->file('image');
                $name = time().user()->id.$file->getClientOriginalName();
                $file->move(public_path().'/uploads/slider/', $name);
                unlink(public_path($slider->image));
                $path = 'uploads/slider/'.$name;
            }else{
                $path = $slider->image;
            }

            $slider->title = $request->title;
            $slider->sub_title = $request->sub_title;
            $slider->status = $request->status;
            $slider->image = $path;
            $slider->save();
            $notification=array(
                'messege'=>'Successfully the slider has been updated',
                'alert-type'=>'success',
            );

            return redirect('/sliders')->with($notification);

        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slider $slider)
    {
        try
        {
            unlink(public_path($slider->image));
            $slider->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully the slider has been deleted']);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
}
