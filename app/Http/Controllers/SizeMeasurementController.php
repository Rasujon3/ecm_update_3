<?php

namespace App\Http\Controllers;

use App\Http\Requests\SizeMeasurementRequest;
use App\Models\AboutUs;
use App\Models\BannerText;
use App\Models\LoginPageContent;
use App\Models\Setting;
use App\Models\SizeMeasurement;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SizeMeasurementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }
    public function index()
    {
        $data = SizeMeasurement::where('user_id', user()->id)->first();
        return view('sizeMeasurement.create',compact('data'));
    }
    public function store(SizeMeasurementRequest $request)
    {
        try
        {
            $data = SizeMeasurement::where('user_id', user()->id)->first();

            $defaults = [
                'img' => $data ? $data->img : null,
            ];

            // Handle file upload
            $img_url = $data ? $data->img : '';
            if ($request->hasFile('img')) {
                $filePath = $this->storeFile($request->file('img'));
                $img_url = $filePath ?? '';
            }
            // Delete the old file if it exists
            $this->deleteOldFile($data);

            if ($data) {
                SizeMeasurement::where('id', $data->id)->update(
                    [
                        'user_id' => user()->id,
			            'domain_id' => getDomain()->id,
                        'img' => $img_url,
                    ]
                );
            } else {
                SizeMeasurement::create(
                    [
                        'user_id' => user()->id,
			            'domain_id' => getDomain()->id,
                        'img' => $img_url,
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
            Log::error('Error in updating SizeMeasurement: ', [
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
    private function storeFile($file)
    {
        // Define the directory path
        // TODO: Change path if needed
        $filePath = 'uploads/size_measurement'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path if needed
        $fileName = uniqid('size_measurement_', true) . '.' . $file->getClientOriginalExtension();

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
        $filePath = 'uploads/size_measurement'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path following storeFile function
        $fileName = uniqid('size_measurement_', true) . '.' . $file->getClientOriginalExtension();

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
