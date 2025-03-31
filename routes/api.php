<?php

use App\Http\Controllers\DriverController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    // login
    Route::post('login', [LoginController::class, 'submit'])->name('login');
    Route::post('login/verify', [LoginController::class, 'verify'])->name('login.verify');
});

Route::middleware(['auth:sanctum'])->group(function () {
    // user
    Route::resource('users', UserController::class);

    // driver
    Route::resource('drivers', DriverController::class)->only(['show', 'update']);

    // trip
    Route::resource('trips', TripController::class)->only(['store', 'show']);
    Route::prefix('trips/{trip}')->name('trip.')->controller(TripController::class)->group(function () {
        Route::post('accept', 'accept')->name('accept');
        Route::post('start', 'start')->name('start');
        Route::post('end', 'end')->name('end');
        Route::post('location', 'location')->name('location');
    });
});
