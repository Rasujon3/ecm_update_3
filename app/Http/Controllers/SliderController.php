<?php

namespace App\Http\Controllers;

use App\Models\Slider;
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
            if($request->ajax()){

               $sliders = Slider::where('user_id',user()->id)->select('*')->latest();

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
            return view('sliders.index');
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sliders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSliderRequest $request)
    {
        try
        {
            if($request->file('image'))
            {   
                $file = $request->file('image');
                $name = time().user()->id.$file->getClientOriginalName();
                $file->move(public_path().'/uploads/slider/', $name); 
                $path = 'uploads/slider/'.$name;
            }
            $slider = new Slider();
            $slider->user_id = user()->id;
            $slider->domain_id = getDomain()->id;
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
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
