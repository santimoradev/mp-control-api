<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

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
    });
  });
});
