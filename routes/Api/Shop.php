<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Shop\ShopController;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/shops', [ShopController::class, 'index']);
    Route::post('/shops', [ShopController::class, 'store']);
});

Route::get('/hello', [ShopController::class, 'hello']);
