<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use DataTables;

class ReviewController extends Controller
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

               $reviews = Review::where('user_id',user()->id)->select('*')->latest();

                    return Datatables::of($reviews)
                        ->addIndexColumn()

                        ->addColumn('image', function($row){
                            return "<img style='width: 60px; height:60px;' class='img-fluid' src='".$row->image."'>";
                        })

                        ->addColumn('status', function($row){
                            return '<label class="switch"><input class="' . ($row->status == 'Active' ? 'active-review' : 'decline-review') . '" id="status-review-update"  type="checkbox" ' . ($row->status == 'Active' ? 'checked' : '') . ' data-id="'.$row->id.'"><span class="slider round"></span></label>';
                        })
                       
                        ->addColumn('action', function($row){
                                                        
                           $btn = "";
                           $btn .= '&nbsp;';
                           $btn .= ' <a href="'.route('reviews.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-review" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                            $btn .= '&nbsp;';


                            $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-review action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>'; 
        
                          
        
                            return $btn;
                        })
                        ->rawColumns(['action','status','image'])
                        ->make(true);
            }
            return view('reviews.index');
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
        return view('reviews.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReviewRequest $request)
    {
        try
        {
            if($request->file('image'))
            {   
                $file = $request->file('image');
                $name = time().user()->id.$file->getClientOriginalName();
                $file->move(public_path().'/uploads/reviews/', $name); 
                $path = 'uploads/reviews/'.$name;
            }
            $review = new Review();
            $review->user_id = user()->id;
            $review->domain_id = getDomain()->id;
            $review->title = $request->title;
            $review->status = $request->status;
            $review->image = $path;
            $review->save();
            $notification=array(
                'messege'=>'Successfully a review has been added',
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
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        return view('reviews.edit',compact('review'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        try
        {
            if($request->file('image'))
            {   
                $file = $request->file('image');
                $name = time().user()->id.$file->getClientOriginalName();
                $file->move(public_path().'/uploads/reviews/', $name);
                unlink(public_path($review->image));
                $path = 'uploads/reviews/'.$name;
            }else{
                $path = $review->image;
            }

            $review->title = $request->title;
            $review->status = $request->status;
            $review->image = $path;
            $review->update();
            $notification=array(
                'messege'=>'Successfully the review has been updated',
                'alert-type'=>'success',
            );

            return redirect('/reviews')->with($notification);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        try
        {   
            unlink(public_path($review->image));
            $review->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully the review has been deleted']);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
}
