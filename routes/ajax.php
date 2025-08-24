<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AjaxController;

Route::post('slider-status-update', [AjaxController::class, 'sliderStatusUpdate']);
Route::post('unit-status-update', [AjaxController::class, 'unitStatusUpdate']);
Route::post('product-status-update', [AjaxController::class, 'productStatusUpdate']);
Route::post('review-status-update', [AjaxController::class, 'reviewStatusUpdate']);
Route::post('order-status-update', [AjaxController::class, 'orderStatusUpdate']);
Route::post('find-courier-order', [AjaxController::class, 'findCourierOrder']);
Route::post('service-status-update', [AjaxController::class, 'serviceStatusUpdate']);
Route::post('package-status-update', [AjaxController::class, 'packageStatusUpdate']);
Route::post('ariadhaka-status-update', [AjaxController::class, 'ariadhakaStatusUpdate']);

Route::post('/delete-variant', [AjaxController::class, 'deleteVariant']);

Route::post('user-status-update', [AjaxController::class, 'userStatusUpdate']);
Route::post('order-details', [AjaxController::class, 'orderDetails']);
Route::post('order-custom-discount', [AjaxController::class, 'orderCustomDiscount']);

Route::post('/delete-full-variant', [AjaxController::class, 'deleteFullVariant']);


Route::post('store-unit', [AjaxController::class, 'storeUnit']);