<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CategoryController;
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

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('category')->name('category.')->group(function(){
        Route::get('/all', [CategoryController::class, 'getAllCategories'])->name('all');
    });
    Route::resource('category', CategoryController::class);
    
    Route::prefix('emails')->name('emails.')->group(function(){
        Route::post('/upload-email-csv', [EmailController::class, 'uploadEmailCsv'])->name('upload-email-csv');
    });
    Route::resource('emails', EmailController::class);

    Route::prefix('template')->name('template.')->group(function(){
        Route::get('/all', [TemplateController::class, 'getAllTemplates'])->name('all');
    });
    Route::resource('template', TemplateController::class);

    Route::prefix('campaign')->name('campaign.')->group(function(){
        // Route::get('/all', [CampaignController::class, 'getAllCampaign'])->name('all');
    });
    Route::resource('campaign', CampaignController::class);

});
