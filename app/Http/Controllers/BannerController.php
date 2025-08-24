<?php

namespace App\Http\Controllers;

use App\Http\Requests\BannerRequest;
use App\Http\Requests\UpdateBannerRequest;
use App\Models\Banner;
use Illuminate\Http\Request;
use Exception;
use DataTables;
use Illuminate\Support\Facades\Log;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }
    public function index(Request $request)
    {
        try
        {
            $count = Banner::count();
            if($request->ajax()){

                $banner = Banner::select('*')->latest();

                return Datatables::of($banner)
                    ->addIndexColumn()

                    ->addColumn('title', function($row){
                        return $row->title;
                    })

                    ->addColumn('description', function($row){
                        return $row->description;
                    })

                    ->addColumn('img', function($row){
                        $url = asset('files/music/mp3/' . $row->img_url);
                        return '<img src="' . $url . '" alt="Banner Image" style="height:60px;">';
                    })

                    ->addColumn('action', function($row){

                        $btn = "";
                        $btn .= '&nbsp;';
                        $btn .= ' <a href="'.route('banner.edit',$row->id).'" class="btn btn-primary btn-sm action-button edit-service" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                        return $btn;
                    })

                    ->rawColumns(['title', 'description', 'img', 'action'])
                    ->make(true);
            }
            return view('banner.index', compact('count'));
        } catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function create()
    {
        return view('banner.create');
    }

    public function store(BannerRequest $request)
    {
        $count = Banner::count();
        if ($count > 0) {
            $notification = [
                'messege' => 'You can only one banner.',
                'alert-type' => 'error'
            ];
            return redirect()->route('banner.index')->with($notification);
        }


        // Handle file upload
        if ($request->hasFile('img')) {
            $filePath = $this->storeFile($request->file('img'));
            $img_url = $filePath ?? '';
        }

        try
        {
            Banner::create([
                'title' => $request->title,
                'description' => $request->description,
                'img_url' => $img_url,
            ]);

            $notification = array(
                'messege' => 'Successfully a item has been added',
                'alert-type' => 'success',
            );

            return redirect()->route('banner.index')->with($notification);
        } catch(Exception $e) {
            // Log the error
            Log::error('Error in storing Banner: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification = [
                'messege' => 'Something went wrong!!!',
                'alert-type' => 'error'
            ];
            return redirect()->route('banner.index')->with($notification);
        }
    }

    public function edit($id)
    {
        $item = Banner::findOrFail($id);
        return view('banner.edit', compact('item'));
    }

    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        // Handle file upload
        if ($request->hasFile('img')) {
            $filePath = $this->updateFile($request->file('img'), $banner);
            $img_url = $filePath ?? '';
        }
        try
        {
            $banner->title = $request->title;
            $banner->description = $request->description;
            $banner->img_url = $request->hasFile('img') ? $img_url : $banner->img_url;
            $banner->save();
            $notification=array(
                'messege' => 'Successfully the banner has been updated',
                'alert-type' => 'success',
            );

            return redirect()->route('banner.index')->with($notification);

        } catch(Exception $e) {
            // Log the error
            Log::error('Error in updating Banner: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification = [
                'messege' => 'Something went wrong!!!',
                'alert-type' => 'error'
            ];
            return redirect()->route('banner.index')->with($notification);
        }
    }

    public function destroy(WhyChooseUs $whyChooseUs)
    {
        try
        {
            $whyChooseUs->delete();
            $notification=array(
                'messege' => 'Successfully the item has been deleted.',
                'alert-type' => 'success',
            );

            return redirect()->route('why_choose_us.index')->with($notification);

        } catch(Exception $e) {
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    private function storeFile($file)
    {
        // Define the directory path
        $filePath = 'files/music/mp3';
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        $fileName = uniqid('music_', true) . '.' . $file->getClientOriginalExtension();

        // Move the file to the destination directory
        $file->move($directory, $fileName);

        // path & file name in the database
        # $path = $filePath . '/' . $fileName;
        $path = $fileName;
        return $path;
    }

    public function getMusic($filename)
    {
        try {
            $path = public_path('files/music/mp3/' . $filename);

            if (!file_exists($path)) {
                return $this->sendResponse(false, '404, File not found.', []);
            }

            return response()->file($path);
        } catch (Exception $e) {

            // Log the error
            Log::error('Error in get File: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->sendResponse(false, 'Something went wrong!!!', [], 500);
        }
    }

    private function updateFile($file, $data)
    {
        // Define the directory path
        $filePath = 'files/music/mp3';
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        $fileName = uniqid('music_', true) . '.' . $file->getClientOriginalExtension();

        // Delete the old file if it exists
        $this->deleteOldFile($data);

        // Move the new file to the destination directory
        $file->move($directory, $fileName);

        // Store path & file name in the database
        # $path = $filePath . '/' . $fileName;
        $path = $fileName;
        return $path;
    }

    private function deleteOldFile($data)
    {
        if (!empty($data->img_url)) {
            $filePath = 'files/music/mp3';
            $directory = $data->img_url;
            $path = $filePath . '/' . $directory;

            $oldFilePath = public_path($path); // Use without prepending $filePath
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
