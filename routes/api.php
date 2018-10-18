<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::namespace('Api')->group(function () {
    // Controllers Within The "App\Http\Controllers\Admin" Namespace
    Route::apiResource('users', 'UserController')->parameters([
      'users' => 'id'
    ]);
    Route::apiResource('buyers', 'BuyerController')->only(['index','show'])->parameters([
      'buyers' => 'id'
    ]);
    Route::apiResource('sellers', 'SellerController')->only(['index','show'])->parameters([
      'sellers' => 'id'
    ]);;
    Route::apiResource('products', 'ProductController')->only(['index','show'])->parameters([
      'products' => 'id'
    ]);;
    Route::apiResource('transactions', 'TransactionController')->only(['index','show'])->parameters([
      'transactions' => 'id'
    ]);;
    Route::apiResource('categories', 'CategoryController')->parameters([
      'categories' => 'id'
    ]);;
});
