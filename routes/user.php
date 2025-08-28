<?php

use App\Http\Controllers\AllProductContentController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BannerTextController;
use App\Http\Controllers\ConversionController;
use App\Http\Controllers\ModuleTutorialController;
use App\Http\Controllers\ProductCharacteristicsController;
use App\Http\Controllers\ProductCharacteristicsDetailsController;
use App\Http\Controllers\ProductNarrativeController;
use App\Http\Controllers\ProductNarrativeDetailsController;
use App\Http\Controllers\ReviewContentController;
use App\Http\Controllers\SizeMeasurementController;
use App\Http\Controllers\TakeALookImagesController;
use App\Http\Controllers\TakeALookTitleController;
use App\Http\Controllers\TimerController;
use App\Http\Controllers\WhyChooseUsController;
use App\Http\Controllers\WhyChooseUsTitleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReferController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AriadhakaController;
use App\Http\Controllers\VariantController;
use App\Http\Controllers\IndexController;

Route::get('/add-user-product', [IndexController::class, 'addUserProduct']);

Route::group(['middleware' => 'prevent-back-history'],function(){

	//sliders

    Route::resource('sliders', SliderController::class);

  //expenses

    Route::resource('expenses', ExpenseController::class);

  //units

    Route::resource('units', UnitController::class);

  //products

    Route::resource('products', ProductController::class);
    Route::get('/redirect-demo-url', [ProductController::class, 'redirectDemoUrl'])->name('redirect-demo-url');

  //variants

    Route::get('/add-variant/{id}', [VariantController::class, 'addVariant']);
    Route::post('store-variants', [VariantController::class, 'storeVariants']);

  //revieews

    Route::resource('reviews', ReviewController::class);

  //areas

    Route::resource('ariadhakas', AriadhakaController::class);


    Route::get('/get-districts/{division_id}', [AriadhakaController::class, 'getDistricts']);


    //video

    Route::get('/add-video', [VideoController::class, 'addVideo']);

    Route::post('save-video', [VideoController::class, 'saveVideo']);

  //report

    Route::get('/sales-report', [ReportController::class, 'salesReport']);
    Route::get('/finance-report', [ReportController::class, 'financeReport']);

  //orders

    Route::get('/orders', [OrderController::class, 'orders'])->name('my.orders');

    Route::delete('/delete-order/{id}', [OrderController::class, 'deleteOrder']);

    Route::get('/show-invoice/{id}', [OrderController::class, 'showInvoice']);

    Route::get('/print-invoice/{id}', [OrderController::class, 'printInvoice']);

    Route::get('/search-courier-order', [OrderController::class, 'searchCourierOrder']);


    Route::get('/order-print/{id}', [OrderController::class, 'orderPrint']);

    Route::post('see-order-status', [OrderController::class, 'seeOrderStatus']);

    Route::get('/show-products/{id}', [OrderController::class, 'showProducts']);


  //settings

    Route::get('/refer-settings', [ReferController::class, 'referSettings']);
    Route::post('settings-refer', [ReferController::class, 'settingsRefer']);
    Route::get('/info-settings', [InfoController::class, 'infoSettings']);
    Route::post('settings-info', [InfoController::class, 'settingsInfo']);
    Route::get('/meta-pixel-settings', [SettingController::class, 'metaPixelSettings']);
    Route::get('/set-delivery-charge', [SettingController::class, 'setDelveryCharge']);
    Route::get('/app-settings', [SettingController::class, 'appSettings']);
    Route::post('settings-app', [SettingController::class, 'settingApp']);
    Route::get('/password-change', [SettingController::class, 'passwordChange']);
    Route::post('change-password', [SettingController::class, 'changePassword']);
    Route::get('/social-media-settings', [SettingController::class, 'socialMediaSettings']);
    Route::get('/terms-conditions', [SettingController::class, 'termsCondition']);
    Route::get('/refund-policy', [SettingController::class, 'refundPolicy']);

    Route::get('/payment-info', [SettingController::class, 'paymentInfo'])->name('payment-info.index');
    Route::get('/create-payment-info', [SettingController::class, 'createPaymentInfo'])->name('payment-info.create');
    Route::post('/store-payment-info', [SettingController::class, 'StorePaymentInfo'])->name('payment-info.store');
    Route::get('/edit-payment-info/{id}', [SettingController::class, 'EditPaymentInfo'])->name('payment-info.edit');
    Route::post('/update-payment-info/{paymentInfo}', [SettingController::class, 'UpdatePaymentInfo'])->name('payment-info.update');
    Route::delete('/payment-info/{paymentInfo}', [SettingController::class, 'deletePaymentInfo']);

    Route::get('/purchase-history', [SettingController::class, 'purchaseHistory'])->name('purchase-history');
    Route::post('/purchase-status-update', [SettingController::class, 'userStatusUpdate']);
    Route::get('/view-purchase-history/{id}', [SettingController::class, 'viewPurchaseHistory'])->name('view-purchase-history');

    // Why Choose Us Content
    Route::get('/why-choose-us', [WhyChooseUsController::class, 'index'])->name('why_choose_us.index');
    Route::get('/why-choose-us/create', [WhyChooseUsController::class, 'create'])->name('why_choose_us.create');
    Route::post('/why-choose-us', [WhyChooseUsController::class, 'store'])->name('why_choose_us.store');
    Route::get('/why-choose-us/{id}/edit', [WhyChooseUsController::class, 'edit'])->name('why_choose_us.edit');
    Route::post('/why-choose-us/{whyChooseUs}', [WhyChooseUsController::class, 'update'])->name('why_choose_us.update');
    Route::post('/delete/why-choose-us/{whyChooseUs}', [WhyChooseUsController::class, 'destroy'])->name('why_choose_us.destroy');

    // Why Choose Us Title
    Route::get('/why-choose-us-title', [WhyChooseUsTitleController::class, 'index'])->name('why-choose-us-title');
    Route::post('why-choose-us-title', [WhyChooseUsTitleController::class, 'store'])->name('why-choose-us-title.store');

    // All Product Content
    Route::get('/all-product-content', [AllProductContentController::class, 'index'])->name('all-product-content');
    Route::post('all-product-content', [AllProductContentController::class, 'store'])->name('all-product-content.store');

    // Take a Look
    Route::get('/take-a-look-title', [TakeALookTitleController::class, 'index'])->name('take-a-look-title');
    Route::post('take-a-look-title', [TakeALookTitleController::class, 'store'])->name('take-a-look-title.store');

    // Take a Look Images
    Route::get('/take-a-look-images', [TakeALookImagesController::class, 'index'])->name('take-a-look-images.index');
    Route::get('/take-a-look-images/create', [TakeALookImagesController::class, 'create'])->name('take-a-look-images.create');
    Route::post('/take-a-look-images', [TakeALookImagesController::class, 'store'])->name('take-a-look-images.store');
    Route::get('/take-a-look-images/{id}/edit', [TakeALookImagesController::class, 'edit'])->name('take-a-look-images.edit');
    Route::post('/take-a-look-images/{takeALookImage}', [TakeALookImagesController::class, 'update'])->name('take-a-look-images.update');
    Route::post('/delete/take-a-look-images/{takeALookImage}', [TakeALookImagesController::class, 'destroy'])->name('take-a-look-images.destroy');

    // Banner
    Route::get('/banner', [BannerController::class, 'index'])->name('banner.index');
    Route::get('/banner/create', [BannerController::class, 'create'])->name('banner.create');
    Route::post('/banner', [BannerController::class, 'store'])->name('banner.store');
    Route::get('/banner/{id}/edit', [BannerController::class, 'edit'])->name('banner.edit');
    Route::post('/banner/{banner}', [BannerController::class, 'update'])->name('banner.update');
    Route::post('/delete/banner/{banner}', [BannerController::class, 'destroy'])->name('banner.destroy');

    // Banner Text
    Route::get('/banner-text', [BannerTextController::class, 'index'])->name('banner-text');
    Route::post('banner-text', [BannerTextController::class, 'store'])->name('banner-text.store');

    // Size Measurement
    Route::get('/size-measurement', [SizeMeasurementController::class, 'index'])->name('size-measurement');
    Route::post('size-measurement', [SizeMeasurementController::class, 'store'])->name('size-measurement.store');

    // Timer
    Route::get('/timer', [TimerController::class, 'index'])->name('timer');
    Route::post('timer', [TimerController::class, 'store'])->name('timer.store');

    // Conversion
    Route::get('/conversions', [ConversionController::class, 'index'])->name('conversions');
    Route::post('conversions', [ConversionController::class, 'store'])->name('conversions.store');

    // Product characteristics title
    Route::get('product-characteristics-title', [ProductCharacteristicsController::class, 'index'])->name('product-characteristics-title');
    Route::post('product-characteristics-title', [ProductCharacteristicsController::class, 'store'])->name('product-characteristics-title.store');

    // Product characteristics details
    Route::get('/product-characteristics-details', [ProductCharacteristicsDetailsController::class, 'index'])->name('product_characteristics_details.index');
    Route::get('/product-characteristics-details/create', [ProductCharacteristicsDetailsController::class, 'create'])->name('product_characteristics_details.create');
    Route::post('/product-characteristics-details', [ProductCharacteristicsDetailsController::class, 'store'])->name('product_characteristics_details.store');
    Route::get('/product-characteristics-details/{id}/edit', [ProductCharacteristicsDetailsController::class, 'edit'])->name('product_characteristics_details.edit');
    Route::post('/product-characteristics-details/{productCharacteristicsDetails}', [ProductCharacteristicsDetailsController::class, 'update'])->name('product_characteristics_details.update');
    Route::post('/delete/product-characteristics-details/{productCharacteristicsDetails}', [ProductCharacteristicsDetailsController::class, 'destroy'])->name('product_characteristics_details.destroy');

    // Product narrative title
    Route::get('product-narrative-title', [ProductNarrativeController::class, 'index'])->name('product-narrative-title');
    Route::post('product-narrative-title', [ProductNarrativeController::class, 'store'])->name('product-narrative-title.store');

    // Product narrative details
    Route::get('/product-narrative-details', [ProductNarrativeDetailsController::class, 'index'])->name('product_narrative_details.index');
    Route::get('/product-narrative-details/create', [ProductNarrativeDetailsController::class, 'create'])->name('product_narrative_details.create');
    Route::post('/product-narrative-details', [ProductNarrativeDetailsController::class, 'store'])->name('product_narrative_details.store');
    Route::get('/product-narrative-details/{id}/edit', [ProductNarrativeDetailsController::class, 'edit'])->name('product_narrative_details.edit');
    Route::post('/product-narrative-details/{productNarrativeDetails}', [ProductNarrativeDetailsController::class, 'update'])->name('product_narrative_details.update');
    Route::post('/delete/product-narrative-details/{productNarrativeDetails}', [ProductNarrativeDetailsController::class, 'destroy'])->name('product_narrative_details.destroy');

    // Review Content
    Route::get('/review-content', [ReviewContentController::class, 'index'])->name('review-content');
    Route::post('review-content', [ReviewContentController::class, 'store'])->name('review-content.store');

    // Module Tutorials
    Route::resource('module-tutorials', ModuleTutorialController::class);
    Route::get('/module-video/{moduleName}', [ModuleTutorialController::class, 'moduleVideo'])->name('module-video');
});
