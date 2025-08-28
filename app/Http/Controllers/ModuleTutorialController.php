<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModuleTutorialRequest;
use App\Models\Module;
use App\Models\ModuleTutorial;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ModuleTutorialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }
    public function index(Request $request)
    {
        try
        {
            if($request->ajax()){

                $data = ModuleTutorial::select('*')->latest();

                return Datatables::of($data)
                    ->addIndexColumn()

                    ->addColumn('module_title', function($row){
                        return $row->module_title;
                    })

                    ->addColumn('video_url', function($row){
                        return $row->video_url;
                    })

                    ->addColumn('action', function($row){

                        $btn = "";
                        $btn .= '&nbsp;';
                        $btn .= ' <a href="'.route('module-tutorials.edit',$row->id).'" class="btn btn-primary btn-sm action-button edit-service" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                        $btn .= '&nbsp;';

                        $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-data action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';

                        return $btn;
                    })

                    ->rawColumns(['description', 'action'])
                    ->make(true);
            }
            return view('moduleTutorials.index');
        } catch(Exception $e) {
            return response()->json([
                'status'=>false,
                'code'=>$e->getCode(),
                'message'=>$e->getMessage()
            ],500);
        }
    }
    public function create()
    {
        $addedModuleIds = ModuleTutorial::get('module_id');

        $modules = Module::where('status', 'Active')
            ->whereNotIn('id', $addedModuleIds)
            ->latest()
            ->get();

        return view('moduleTutorials.create', compact('modules'));
    }
    public function store(ModuleTutorialRequest $request)
    {
        try
        {
            $module = Module::where('id', $request->module_id)->first();
            if (empty($module->title)) {
                $notification = [
                    'messege' => 'Module Title not found.',
                    'alert-type' => 'error'
                ];
                return redirect()->route('module-tutorials.index')->with($notification);
            }

            ModuleTutorial::create([
                'module_id' => $request->module_id,
                'module_title' => $module->title,
                'video_type' => 'Youtube',
                'video_url' => $request->video_url ?? '',
                'video_id' => $request->video_url ? getYouTubeVideoId($request) : '',
            ]);

            $notification = array(
                'messege' => 'Successfully a item has been added',
                'alert-type' => 'success',
            );

            return redirect()->route('module-tutorials.index')->with($notification);
        } catch(Exception $e) {
            // Log the error
            Log::error('Error in storing ModuleTutorial: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification = [
                'messege' => 'Something went wrong!!!',
                'alert-type' => 'error'
            ];

            return redirect()->route('module-tutorials.index')->with($notification);
        }
    }
    public function edit(ModuleTutorial $moduleTutorial)
    {
        $modules = Module::where('status', 'Active')->latest()->get();
        return view('moduleTutorials.edit', compact('modules','moduleTutorial'));
    }
    public function update(ModuleTutorialRequest $request, ModuleTutorial $moduleTutorial)
    {
        try
        {
            $module = Module::where('id', $request->module_id)->first();

            if (empty($module->title)) {
                $notification = [
                    'messege' => 'Module Title not found.',
                    'alert-type' => 'error'
                ];
                return redirect()->route('module-tutorials.index')->with($notification);
            }

            $moduleTutorial->module_id = $request->module_id;
            $moduleTutorial->module_title = $module->title;
            $moduleTutorial->video_type = 'Youtube';
            $moduleTutorial->video_url = $request->video_url ?? '';
            $moduleTutorial->video_id = $request->video_url ? getYouTubeVideoId($request) : '';
            $moduleTutorial->save();

            $notification=array(
                'messege' => 'Successfully the item has been updated',
                'alert-type' => 'success',
            );

            return redirect()->route('module-tutorials.index')->with($notification);

        } catch(Exception $e) {
            // Log the error
            Log::error('Error in updating ModuleTutorial: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification = [
                'messege' => 'Something went wrong!!!',
                'alert-type' => 'error'
            ];

            return redirect()->back()->with($notification);
        }
    }
    public function destroy(ModuleTutorial $moduleTutorial)
    {
        try
        {
            $moduleTutorial->delete();
            return response()->json([
                'status' => true,
                'message' => 'Successfully the data has been deleted'
            ]);
        } catch(Exception $e) {
            // Log the error
            Log::error('Error in deleting ModuleTutorial: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!!!'
            ],500);
        }
    }
    public function moduleVideo($moduleName)
    {
        $url = null;
        $tutorial = null;
        if (!empty($moduleName)) {
            $tutorial = ModuleTutorial::where('module_title', trim($moduleName))->first();
        }
        if($tutorial && !empty($tutorial->video_url)) {
            $url = $tutorial->video_url;
        }

        if ($url) {
            return redirect()->away($url);
        }
//        dd($moduleName, $tutorial, $url);
        $notification = [
            'messege' => 'Tutorial not found for this module.',
            'alert-type' => 'error'
        ];

        return redirect()->back()->with($notification);
    }
}
