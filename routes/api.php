<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\RouteController;
use App\Http\Controllers\Api\VisitController;
use App\Http\Controllers\Api\VisitTaskController;
use App\Http\Controllers\Api\AditionalController;
use App\Http\Controllers\Api\ExhibitionController;
use App\Http\Controllers\Api\ReportController;

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
      Route::prefix('products')->group( function() {
        Route::get( '' , [ ProductController::class, 'index' ]);
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
      Route::prefix('routes')->group( function() {
        Route::get( '' , [ RouteController::class, 'index' ]);
        Route::post( '' , [ RouteController::class, 'store' ]);
      });
      Route::prefix('visits')->group( function() {
        Route::get( '' , [ VisitController::class, 'index' ]);
        Route::get( '{id}' , [ VisitController::class, 'show' ]);
        Route::post( '{id}/start' , [ VisitController::class, 'start' ]);
        Route::post( '{id}/finish' , [ VisitController::class, 'finish' ]);
        Route::prefix('{id}/tasks')->group( function() {
          Route::get( '' , [ VisitTaskController::class, 'show' ]);

          Route::post( 'observations' , [ VisitTaskController::class, 'createObservations' ]);
          Route::get( 'observations' , [ VisitTaskController::class, 'getObservations' ]);

          Route::post( 'exhibitions' , [ VisitTaskController::class, 'createExhibitions' ]);
          Route::get( 'exhibitions' , [ VisitTaskController::class, 'getExhibitions' ]);

          Route::post( 'aditionals' , [ VisitTaskController::class, 'createAditionals' ]);
          Route::get( 'aditionals' , [ VisitTaskController::class, 'getAditionals' ]);

          Route::get( 'competence' , [ VisitTaskController::class, 'getCompetence' ]);
        });
      });

      Route::prefix('report')->group( function() {
        Route::get( 'visits' , [ ReportController::class, 'visits' ]);
      });
    });
  });
});
