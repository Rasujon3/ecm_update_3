<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\AjaxController;

Route::group(['middleware' => 'prevent-back-history'],function(){
  	
  //services
	Route::resource('services', ServiceController::class);

  //packages
	Route::resource('packages', PackageController::class);

  //users
	Route::get('/users', [StateController::class, 'users']);

  //user controls

	Route::get('/user-products', [StateController::class, 'userProducts']);

	Route::get('/user-product/{id}', [StateController::class, 'userProduct']);

	Route::get('/delete-user/{id}', [StateController::class, 'deleteUser']);
   

});

