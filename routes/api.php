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
use App\Http\Controllers\Api\TrackingController;
use App\Http\Controllers\Api\VisitController;
use App\Http\Controllers\Api\VisitTaskController;
use App\Http\Controllers\Api\AditionalController;
use App\Http\Controllers\Api\ExhibitionController;
use App\Http\Controllers\Api\ReportRoutesController;
use App\Http\Controllers\Api\ReportComplianceController;
use App\Http\Controllers\Api\ReportProductsController;
use App\Http\Controllers\Api\ReportPhotosController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\SyncController;
use App\Http\Controllers\Api\ExportController;
use App\Http\Controllers\Api\ExportPhotosController;

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
        Route::get( 'tracking' , [ TrackingController::class, 'index' ]);
        Route::get( '{id}' , [ RouteController::class, 'show' ]);
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

      Route::prefix('reports')->group( function() {
        Route::get( 'visits' , [ ReportRoutesController::class, 'visits' ]);
        Route::prefix('compliance')->group( function() {
          Route::get( 'general' , [ ReportComplianceController::class, 'general' ]);
        });
        Route::prefix('products')->group( function() {
          Route::get( 'inventory' , [ ReportProductsController::class, 'getInventory' ]);
          Route::get( 'inventory-widgets' , [ ReportProductsController::class, 'getInventoryWidgets' ]);
          Route::get( 'range-prices' , [ ReportProductsController::class, 'getRangePrices' ]);
          Route::get( 'range-prices' , [ ReportProductsController::class, 'getRangePrices' ]);
          Route::get( 'market-average' , [ ReportProductsController::class, 'getMarketAverage' ]);
        });
        Route::prefix('photos')->group( function() {
          Route::get( 'aditionals' , [ ReportPhotosController::class, 'getAditionals' ]);
          Route::get( 'exhibitions' , [ ReportPhotosController::class, 'getExhibitions' ]);
        });
      });
      Route::prefix('exports')->group( function() {
        Route::get( 'visits' , [ ExportController::class, 'visits' ]);
        Route::get( 'inventory' , [ ExportController::class, 'inventory' ]);
        Route::get( 'range-prices' , [ ExportController::class, 'rangePrices' ]);
        Route::get( 'market-average' , [ ExportController::class, 'marketAverage' ]);
        Route::get( 'aditionals' , [ ExportPhotosController::class, 'aditionals' ]);
      });
      Route::prefix('dashboard')->group( function() {
        Route::get( '' , [ DashboardController::class, 'index' ]);
      });
    });
    Route::prefix('sync')->group( function() {
      Route::get( 'visit-expired' , [ SyncController::class, 'visitExpired' ]);
    });
  });
});
