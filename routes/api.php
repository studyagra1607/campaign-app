<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\EListController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\TemplateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/check-auth', function () {
    return response()->json([
        'authenticated' => Auth::check()
    ]);
});

Route::middleware('auth')->group(function () {
    Route::resource('list', EListController::class);
    Route::resource('campaign', CampaignController::class);
    Route::resource('templates', TemplateController::class);
    Route::resource('email', EmailController::class);
});
