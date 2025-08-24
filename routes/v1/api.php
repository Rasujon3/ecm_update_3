<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\ApiController;

Route::middleware(['custom.cors', 'throttle:60,1'])->group(function () {
    Route::post('save-domain', [ApiController::class, 'saveDomain']);
	Route::get('/packages', [ApiController::class, 'packages']);
	Route::get('/domain-lists', [ApiController::class, 'domainLists']);
	Route::post('domain-details', [ApiController::class, 'domainDetails']);
	Route::post('search-domain', [ApiController::class, 'searchDomain']);
	Route::post('add-temp-user', [ApiController::class, 'addTempUser']);


	Route::post('sliders', [ApiController::class, 'sliders']);

	Route::post('products', [ApiController::class, 'products']);

	Route::post('reviews', [ApiController::class, 'reviews']);

	Route::post('get-video', [ApiController::class, 'getVideo']);

	Route::post('save-order', [ApiController::class, 'saveOrder']);

	Route::post('accept-courier-order', [ApiController::class, 'acceptCourierOrder']);

	Route::post('search-domain', [ApiController::class, 'searchDomain']);

	Route::get('/packages', [ApiController::class, 'packages']);

	Route::post('privacy-policy', [ApiController::class, 'privacyPolicy']);

	Route::post('contact-us', [ApiController::class, 'contactUs']);

	Route::post('about-us', [ApiController::class, 'aboutUs']);

	Route::post('admin-info', [ApiController::class, 'adminInfo']);

	Route::put('update-user-theme', [ApiController::class, 'updateUserTheme']);

	Route::post('site-other-info', [ApiController::class, 'siteOtherInfo']);

	Route::get('insider-dhaka-area', [ApiController::class, 'insideDhakaArea']);

    Route::post('website-purchase', [ApiController::class, 'websitePurchase']);

    Route::get('/payment-info', [ApiController::class, 'getPaymentInfo']);

    Route::post('/payment-info', [ApiController::class, 'paymentInfo']);

    Route::get('/get-image/{fileName}', [ApiController::class, 'getMusic'])->name('payment-info.getImage');

    Route::get('/get-token', [ApiController::class, 'getToken'])->name('get-token');

    Route::post('payment-request', [ApiController::class, 'paymentRequest']);

    Route::post('user-payment-store', [ApiController::class, 'userPaymentStore']);

    Route::get('/why-choose-us', [ApiController::class, 'whyChooseUs'])->name('why-choose-us');

    Route::get('/banner', [ApiController::class, 'banner'])->name('banner');

    Route::get('/get-banner-image/{fileName}', [ApiController::class, 'getBannerImg'])->name('banner.getImage');

    Route::post('/get-delivery-charges', [ApiController::class, 'getDeliveryCharges']);

    Route::post('/find-delivery-charge', [ApiController::class, 'findDeliveryCharge']);

    Route::post('conversations', [ApiController::class, 'conversations']);

    Route::post('product-characteristics', [ApiController::class, 'productCharacteristics']);

    Route::post('product-narrative', [ApiController::class, 'productNarrative']);
});
