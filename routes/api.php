<?php

use App\Http\Controllers\Api\v1\TransactionsController;
use App\Http\Controllers\Api\v1\UsersController;
use Illuminate\Support\Facades\Route;

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

Route::controller(UsersController::class)->group(function(){
   Route::post('/v1/users', 'store');
});

Route::controller(TransactionsController::class)->group(function(){
    Route::post('/v1/transactions', 'store');
});
