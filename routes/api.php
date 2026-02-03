<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\VisitController;

Route::prefix('v1')->group( function() {
  Route::namespace('Api')->group( function () {
    Route::post( 'auth/login' , [ AuthController::class, 'doLogin' ]);

    Route::middleware(['auth.jwt'])->group( function() {
      Route::prefix('users')->group( function() {
        Route::put( '{id}' , [ UserController::class, 'update' ]);
        Route::put( '/change-password/{id}' , [ UserController::class, 'password' ]);
        Route::get( '' , [ UserController::class, 'index' ]);
        Route::post( '' , [ UserController::class, 'store' ]);
      });
      Route::prefix('locations')->group( function() {
        Route::get( '' , [ LocationController::class, 'index' ]);
        Route::post( '' , [ LocationController::class, 'store' ]);
        Route::put( '{id}' , [ LocationController::class, 'update' ]);
        Route::delete( '{id}' , [ LocationController::class, 'destroy' ]);
      });
      Route::prefix('provinces')->group( function() {
        Route::get( '' , [ ProvinceController::class, 'index' ]);
        Route::get( '{id}/cities' , [ CityController::class, 'index' ]);
      });
      Route::prefix('visits')->group( function() {
        Route::post( '{id}/start' , [ VisitController::class, 'start' ]);
      });
    });
  });
});
