<?php

use Illuminate\Support\Facades\Route;
use Square1\LaravelIdempotency\Http\Middleware\IdempotencyMiddleware;

Route::middleware('api')->group(function () {

    Route::group(['namespace' => 'App\Http\Controllers', 'prefix' => 'slots'], function () {
        Route::get('/availability', 'AvailabilityController@availability')->name('slots-availability');
        Route::post('/{id?}/hold', 'AvailabilityController@hold')->name('slots-hold')->middleware(IdempotencyMiddleware::class);
    });

    Route::group(['namespace' => 'App\Http\Controllers', 'prefix' => 'holds'], function () {
        Route::post('/{id?}/confirm', 'HoldController@confirm')->name('holds-confirm')->middleware(IdempotencyMiddleware::class);
        Route::delete('/{id?}', 'HoldController@delete')->name('holds-delete')->middleware(IdempotencyMiddleware::class);
    });
});
