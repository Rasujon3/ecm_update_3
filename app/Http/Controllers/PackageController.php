<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use App\Http\Requests\StorePackageRequest;
use App\Http\Requests\UpdatePackageRequest;
use DataTables;
use DB;
use Illuminate\Support\Facades\Log;

class PackageController extends Controller
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

               $packages = Package::where('user_id',user()->id)->select('*')->latest();

                    return Datatables::of($packages)
                        ->addIndexColumn()


                        ->addColumn('status', function($row){
                            return '<label class="switch"><input class="' . ($row->status == 'Active' ? 'active-package' : 'decline-package') . '" id="status-package-update"  type="checkbox" ' . ($row->status == 'Active' ? 'checked' : '') . ' data-id="'.$row->id.'"><span class="slider round"></span></label>';
                        })

                        ->addColumn('action', function($row){

                           $btn = "";
                           $btn .= '&nbsp;';
                           $btn .= ' <a href="'.route('packages.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-package" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                            $btn .= '&nbsp;';


                            $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-package action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';



                            return $btn;
                        })
                        ->rawColumns(['action','status'])
                        ->make(true);
            }
            return view('packages.index');
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
        return view('packages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePackageRequest $request)
    {
        // Handle file upload
        $img_url = '';
        if ($request->hasFile('img')) {
            $filePath = $this->storeFile($request->file('img'));
            $img_url = $filePath;
        }
        DB::beginTransaction();
        try
        {
            $package = new Package();
            $package->user_id = user()->id;
            $package->package_name = $request->package_name;
            $package->short_description = $request->short_description;
            $package->price = $request->price;
            $package->max_product = $request->max_product;
            $package->sub_title = $request->sub_title;
            $package->demo_url = $request->demo_url;
            $package->img = $img_url;
            $package->status = $request->status;
            $package->save();
            $package->services()->attach($request->services);
            $notification=array(
                'messege'=>'Successfully a package has been added',
                'alert-type'=>'success',
            );
            DB::commit();
            return redirect()->back()->with($notification);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function show(Package $package)
    {
        return view('packages.edit',compact('package'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function edit(Package $package)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePackageRequest $request, Package $package)
    {
        // Handle file upload
        $img_url = $package->img;
        if ($request->hasFile('img')) {
            $filePath = $this->updateFile($request->file('img'), $package);
            $img_url = $filePath ?? '';
        }

        DB::beginTransaction();
        try
        {
            $package->package_name = $request->package_name;
            $package->short_description = $request->short_description;
            $package->price = $request->price;
            $package->max_product = $request->max_product;
            $package->sub_title = $request->sub_title;
            $package->demo_url = $request->demo_url;
            $package->img = $img_url;
            $package->status = $request->status;
            $package->update();
            $package->services()->sync($request->services);
            $notification=array(
                'messege'=>'Successfully a package has been added',
                'alert-type'=>'success',
            );
            DB::commit();
            return redirect('/packages')->with($notification);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function destroy(Package $package)
    {
        try
        {
            $package->services()->delete();
            $package->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully the package has been deleted']);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    private function storeFile($file)
    {
        // Define the directory path
        // TODO: Change path if needed
        $filePath = 'uploads/package'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path if needed
        $fileName = uniqid('package_', true) . '.' . $file->getClientOriginalExtension();

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
        $filePath = 'uploads/package'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path following storeFile function
        $fileName = uniqid('package_', true) . '.' . $file->getClientOriginalExtension();

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
