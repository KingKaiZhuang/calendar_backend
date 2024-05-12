<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
// Google 登入和回調路由
Route::get('/login/google', [AuthController::class, 'callbackFromGoogle']);
Route::post('/login/google/callback', [AuthController::class, 'callbackFromGoogle']);

// Route::get('/test', function () {
//     return response()->json(['message' => 'Hello World!'], 200);
// });
