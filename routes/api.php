<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PhysicalProgressController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);
Route::post('logout',[AuthController::class,'logout'])
  ->middleware('auth:sanctum');

  Route::middleware('auth:sanctum')->group(function () {
    Route::post('/progress', [PhysicalProgressController::class, 'store']);
    Route::get('/progress', [PhysicalProgressController::class, 'getProgresshistory']);
    Route::put('/progress/{id}', [PhysicalProgressController::class, 'update']);
    Route::patch('/progress/{id}/status', [PhysicalProgressController::class, 'updateStatus']);
    Route::delete('/progress/{id}', [PhysicalProgressController::class, 'destroy']);
});
