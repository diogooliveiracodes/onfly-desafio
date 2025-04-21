<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelRequestController;
use App\Http\Controllers\TravelRequestNotificationController;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['middleware' => 'admin'], function () {
        Route::put('/travel-requests/change-status/{travelRequest}', [TravelRequestController::class, 'changeStatus']);
    });
    Route::post('/travel-requests/search', [TravelRequestController::class, 'search']);
    Route::apiResource('travel-requests', TravelRequestController::class);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', [TravelRequestNotificationController::class, 'index']);
        Route::post('/read/{travelRequestNotification}', [TravelRequestNotificationController::class, 'markAsRead']);
    });
});