<?php

namespace App\Http\Controllers;

use App\Http\Requests\TakeALookImgRequest;
use App\Models\ProductCharacteristicsTitle;
use App\Models\ProductNarrativeTitle;
use App\Models\Setting;
use App\Models\TakeALookImg;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TakeALookImagesController extends Controller
{
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

            $url = getVideoUrl('Take A Look');

            $count = TakeALookImg::where('user_id', Auth::user()->id)
                ->where('domain_id', $domainId)
                ->where('sub_domain_id', $subDomainId)
                ->count();

            if($request->ajax()){

                $data = TakeALookImg::where('user_id', Auth::user()->id)
                    ->where('domain_id', $domainId)
                    ->where('sub_domain_id', $subDomainId)
                    ->select('*')
                    ->latest();

                return Datatables::of($data)
                    ->addIndexColumn()

                    ->addColumn('img', function($row){
                        return "<img style='width: 60px; height:60px;' class='img-fluid' src='".$row->img."'>";
                    })

                    ->addColumn('action', function($row){

                        $btn = "";
                        $btn .= '&nbsp;';
                        $btn .= ' <a href="'.route('take-a-look-images.edit',$row->id).'" class="btn btn-primary btn-sm action-button edit-service" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                        $btn .= '&nbsp;';

                        $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-data action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';

                        return $btn;
                    })

                    ->rawColumns(['img', 'action'])
                    ->make(true);
            }
            return view('takeALookImg.index', compact('count', 'url'));
        } catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function create()
    {
        $url = getVideoUrl('Take A Look');
        return view('takeALookImg.create', compact('url'));
    }

    public function store(TakeALookImgRequest $request)
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

            // Handle file upload
            $img_url = '';
            if ($request->hasFile('img')) {
                $filePath = $this->storeFile($request->file('img'));
                $img_url = $filePath ?? '';
            }
            TakeALookImg::create([
                'user_id' => Auth::user()->id,
                'domain_id' => $domainId,
                'sub_domain_id' => $subDomainId,
                'img' => $img_url,
            ]);
            $notification = array(
                'messege' => 'Successfully a item has been added',
                'alert-type' => 'success',
            );

            return redirect()->route('take-a-look-images.index')->with($notification);
        } catch(Exception $e) {
            // Log the error
            Log::error('Error in storing ProductNarrativeDetails: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification = [
                'messege' => 'Something went wrong!!!',
                'alert-type' => 'error'
            ];
            return redirect()->route('take-a-look-images.index')->with($notification);
        }
    }

    public function edit($id)
    {
        $item = TakeALookImg::findOrFail($id);
        return view('takeALookImg.edit', compact('item'));
    }

    public function update(TakeALookImgRequest $request, TakeALookImg $takeALookImage)
    {
        try
        {
            // Handle file upload
            $img_url = $takeALookImage->img;
            if ($request->hasFile('img')) {
                $filePath = $this->updateFile($request->file('img'), $takeALookImage);
                $img_url = $filePath ?? '';
            }

            $takeALookImage->img = $img_url;
            $takeALookImage->save();

            $notification=array(
                'messege' => 'Successfully the item has been updated',
                'alert-type' => 'success',
            );

            return redirect()->route('take-a-look-images.index')->with($notification);

        } catch(Exception $e) {
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function destroy(TakeALookImg $takeALookImage)
    {
        try
        {
            $this->deleteOldFile($takeALookImage);
            $takeALookImage->delete();

            return response()->json([
                'status' => true,
                'message' => 'Successfully the item has been deleted.'
            ]);

        } catch(Exception $e) {
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    private function storeFile($file)
    {
        // Define the directory path
        // TODO: Change path if needed
        $filePath = 'uploads/look'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path if needed
        $fileName = uniqid('look_', true) . '.' . $file->getClientOriginalExtension();

        // Move the file to the destination directory
        $file->move($directory, $fileName);

        // path & file name in the database
        $path = $filePath . '/' . $fileName;
        return $path;
    }
    private function updateFile($file, $data)
    {
        // Define the directory path
        // TODO: Change path if needed
        $filePath = 'uploads/look'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path following storeFile function
        $fileName = uniqid('look_', true) . '.' . $file->getClientOriginalExtension();

        // Delete the old file if it exists
        $this->deleteOldFile($data);

        // Move the new file to the destination directory
        $file->move($directory, $fileName);

        // Store path & file name in the database
        $path = $filePath . '/' . $fileName;
        return $path;
    }
    private function deleteOldFile($data)
    {
        // TODO: ensure from database
        if (!empty($data->img)) { # ensure from database
            $oldFilePath = public_path($data->img); // Use without prepending $filePath
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath); // Delete the old file
                return true;
            } else {
                Log::warning('Old file not found for deletion', ['path' => $oldFilePath]);
                return false;
            }
        }
    }
}
