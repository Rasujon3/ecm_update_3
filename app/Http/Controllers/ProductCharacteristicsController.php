<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use App\Models\BannerText;
use App\Models\LoginPageContent;
use App\Models\ProductCharacteristicsTitle;
use App\Models\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductCharacteristicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }
    public function index()
    {
        $title = ProductCharacteristicsTitle::where('user_id', user()->id)->first();
        return view('productCharacteristics.product_characteristics_title',compact('title'));
    }
    public function store(Request $request)
    {
        try
        {
            $data = ProductCharacteristicsTitle::where('user_id', user()->id)->first();

            $defaults = [
                'title' => $data ? $data->title : null,
            ];

            if ($data) {
                ProductCharacteristicsTitle::where('id', $data->id)->update(
                    [
                        'user_id' => user()->id,
			            'domain_id' => getDomain()->id,
                        'title' => $request->title ?? $defaults['title'],
                    ]
                );
            } else {
                ProductCharacteristicsTitle::create(
                    [
                        'user_id' => user()->id,
			            'domain_id' => getDomain()->id,
                        'title' => $request->title ?? $defaults['title'],
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
            Log::error('Error in updating ProductCharacteristicsTitle: ', [
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
        $filePath = 'uploads/logo'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path if needed
        $fileName = uniqid('logo_', true) . '.' . $file->getClientOriginalExtension();

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
        $filePath = 'uploads/logo'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path following storeFile function
        $fileName = uniqid('logo_', true) . '.' . $file->getClientOriginalExtension();

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
        if (!empty($data->company_logo)) { # ensure from database
            $oldFilePath = public_path($data->company_logo); // Use without prepending $filePath
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
