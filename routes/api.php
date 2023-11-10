<?php

use App\Http\Controllers\Api\TourController;
use App\Http\Controllers\Api\TravelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Models\Image;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('travels', [TravelController::class, 'index']);
Route::get('travel/{travel:slug}/tours', [TourController::class, 'index']);

Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum']], function () {

    Route::group(['middleware' => ['role:admin']], function () {
        Route::post('travels', [Admin\TravelController::class, 'store']);
        Route::post('travels/{travel:slug}/tours', [Admin\TourController::class, 'store']);
    });

    Route::group(['middleware' => ['role:admin|editor']], function () {
        Route::put('travels/{travel:slug}', [Admin\TravelController::class, 'update']);
    });


    Route::post('delete-image', function (Request $request) {
        $image = Image::where('filename', $request->filename)->first();
        $image->delete();
        return response()->json(['message' => 'Image deleted successfully']);
    });

    Route::post('logout', [LoginController::class, 'logout']);
});

Route::post('login', [LoginController::class, 'login']);
