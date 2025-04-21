<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelRequestController;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['middleware' => 'admin'], function () {
        Route::put('/travel-requests/change-status/{travelRequest}', [TravelRequestController::class, 'changeStatus']);
    });
    Route::apiResource('travel-requests', TravelRequestController::class);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});