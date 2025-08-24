<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Blade;
use Session;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;
use App\Models\Image;
use Illuminate\Support\Facades\File;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        Schema::defaultStringLength(191);
        Blade::directive('toastr', function ($expression){
            return "<script>
                    toastr.{{ Session::get('alert-type') }}($expression)
                 </script>";
        });

//        $files = File::allFiles(public_path('uploads/gallery_images'));
//        $images = Image::pluck('image')->toArray();
//        //$cdFiles = array();
//        foreach ($files as $file) {
//            //echo $file->getFilename();
//            $imgPath = 'uploads/gallery_images/'.$file->getFilename();
//            if(!in_array($imgPath, $images))
//            {
//                //$cdFiles[] = $imgPath;
//                unlink(public_path($imgPath));
//            }
//        }
    }
}
