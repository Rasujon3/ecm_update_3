<?php

namespace App\Http\Controllers;

use App\Http\Requests\SizeMeasurementRequest;
use App\Models\AboutUs;
use App\Models\BannerText;
use App\Models\LoginPageContent;
use App\Models\ModuleTutorial;
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
        $selection = getCurrentSelection();
        $domainId = $selection['domain_id'];
        $subDomainId = $selection['sub_domain_id'];

        if ((!$domainId && !$subDomainId)) {
            $notification=array(
                'messege' => 'Domain & Subdomain mismatch.',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        $moduleName = 'Size Measurement';
        $url = null;
        $tutorial = null;
        if (!empty($moduleName)) {
            $tutorial = ModuleTutorial::where('module_title', trim($moduleName))->first();
        }
        if($tutorial && !empty($tutorial->video_url)) {
            $url = $this->getYoutubeEmbedUrl($tutorial->video_url);
        }

        $data = SizeMeasurement::where('user_id', user()->id)
            ->where('domain_id', $domainId)
            ->where('sub_domain_id', $subDomainId)
            ->first();

        return view('sizeMeasurement.create',compact('data', 'url'));
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
    public function store(SizeMeasurementRequest $request)
    {
//        dd($request->prev_img,!($request->hasFile('img')),$request->all());
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
                return redirect()->back()->with($notification);
            }

            $data = SizeMeasurement::where('user_id', user()->id)
                ->where('domain_id', $domainId)
                ->where('sub_domain_id', $subDomainId)
                ->first();

            $defaults = [
                'img' => $data ? $data->img : null,
            ];

            // Handle file upload
//            $img_url = $data ? $data->img : '';
            $img_url = $request->prev_img;

            // Case 1: New file uploaded
            if ($request->hasFile('img')) {
                $filePath = $this->storeFile($request->file('img'));

                // Delete the old file if it exists
                $this->deleteOldFile($data);

                $img_url = $filePath ?? '';
            }

            // Case 2: User remove image
            if (!$request->hasFile('img') && empty($request->prev_img)) {
                $img_url = '';
                $this->deleteOldFile($data);
            }

            if ($data) {
                SizeMeasurement::where('id', $data->id)->update(
                    [
                        'user_id' => user()->id,
			            'domain_id' => $domainId,
			            'sub_domain_id' => $subDomainId,
                        'img' => $img_url,
                    ]
                );
            } else {
                SizeMeasurement::create(
                    [
                        'user_id' => user()->id,
                        'domain_id' => $domainId,
                        'sub_domain_id' => $subDomainId,
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
