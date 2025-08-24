<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Http\Requests\ExpenseRequest;
use DataTables;

class ExpenseController extends Controller
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

               $expenses = Expense::where('user_id',user()->id)->select('*')->latest();

                    //date("d-m-Y", strtotime($originalDate));

                    return Datatables::of($expenses)
                        ->addIndexColumn()

                        ->addColumn('date', function($row){
                            
                            return date("d F Y", strtotime($row->date));                            
                            
                        })
                       
                        ->addColumn('action', function($row){
                                                        
                           $btn = "";
                           $btn .= '&nbsp;';
                           $btn .= ' <a href="'.route('expenses.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-expense" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                            $btn .= '&nbsp;';


                            $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-expense action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>'; 
        
                          
        
                            return $btn;
                        })->filter(function ($instance) use ($request) {

                            if ($request->get('search') != "") {
                                $instance->where(function($w) use($request){
                                    $search = $request->get('search');
                                    $w->orWhere('expenses.title', 'LIKE', "%$search%");
                                });
                            } 

                            if ($request->get('from_date') != "") {
                                 $instance->where(function($w) use($request){
                                    $w->orWhere('expenses.date', '>=', $request->from_date);
                                });
                            } 

                            if ($request->get('to_date') != "") {
                                 $instance->where(function($w) use($request){
                                    $w->orWhere('expenses.date', '<=', $request->to_date);
                                });
                            }

                                
                        })
                        ->rawColumns(['action','date'])
                        ->make(true);
            }
            return view('expenses.index');
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
        return view('expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpenseRequest $request)
    {
        try
        {
            $expense = new Expense();
            $expense->user_id = user()->id;
            $expense->title = $request->title;
            $expense->date = date('Y-m-d');
            $expense->amount = $request->amount;
            $expense->remarks = $request->remarks;
            $expense->save();
            $notification=array(
                'messege'=>'Successfully an expense has been added',
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
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense) 
    {
        return view('expenses.edit', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(ExpenseRequest $request, Expense $expense)
    {
        try 
        {   
            $expense->title = $request->title;
            $expense->amount = $request->amount;
            $expense->remarks = $request->remarks;
            $expense->update();
            $notification=array(
                'messege'=>'Successfully the expense has been updated',
                'alert-type'=>'success',
            );

            return redirect()->route('expenses.index')->with($notification);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        try
        {
            $expense->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully the expense has been deleted']);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
}
