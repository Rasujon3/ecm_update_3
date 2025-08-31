<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use App\Models\BannerText;
use App\Models\LoginPageContent;
use App\Models\ModuleTutorial;
use App\Models\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BannerTextController extends Controller
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
            return redirect()->route('units.index')->with($notification);
        }

        $bannerText = BannerText::where('user_id', user()->id)
            ->where('domain_id', $domainId)
            ->where('sub_domain_id', $subDomainId)
            ->first();

        $moduleName = 'Banner Text';
        $url = null;
        $tutorial = null;
        if (!empty($moduleName)) {
            $tutorial = ModuleTutorial::where('module_title', trim($moduleName))->first();
        }
        if($tutorial && !empty($tutorial->video_url)) {
            $url = $this->getYoutubeEmbedUrl($tutorial->video_url);
        }

        return view('bannerText.bannerText',compact('bannerText', 'url'));
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

    public function store(Request $request)
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

            $data = BannerText::where('user_id', user()->id)
                ->where('domain_id', $domainId)
                ->where('sub_domain_id', $subDomainId)
                ->first();

            $defaults = [
                'banner_text' => $data ? $data->banner_text : null,
                'description' => $data ? $data->description : null,
                'contents' => $data ? $data->contents : null,
            ];

            if ($data) {
                BannerText::where('id', $data->id)->update(
                    [
                        'user_id' => user()->id,
			            'domain_id' => $domainId,
			            'sub_domain_id' => $subDomainId,
//                        'banner_text' => $request->banner_text ?? $defaults['banner_text'],
//                        'description' => $request->description ?? $defaults['description'],
//                        'contents' => $request->contents ?? $defaults['contents'],
                        'banner_text' => $request->banner_text,
                        'description' => $request->description,
                        'contents' => $request->contents,
                    ]
                );
            } else {
                BannerText::create(
                    [
                        'user_id' => user()->id,
                        'domain_id' => $domainId,
                        'sub_domain_id' => $subDomainId,
//                        'banner_text' => $request->banner_text ?? $defaults['banner_text'],
//                        'description' => $request->description ?? $defaults['description'],
//                        'contents' => $request->contents ?? $defaults['contents'],
                        'banner_text' => $request->banner_text,
                        'description' => $request->description,
                        'contents' => $request->contents,
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
            Log::error('Error in updating BannerText: ', [
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
