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
    Route::apiResource('users', 'UserController');

    Route::apiResource('buyers', 'BuyerController')->only(['index','show']);
    Route::get('buyers/{buyer}/transactions','BuyerController@transactions')->name('buyers.transactions.index');
    Route::get('buyers/{buyer}/products','BuyerController@products')->name('buyers.products.index');
    Route::get('buyers/{buyer}/sellers','BuyerController@sellers')->name('buyers.sellers.index');
    Route::get('buyers/{buyer}/categories','BuyerController@categories')->name('buyers.categories.index');

    Route::apiResource('sellers', 'SellerController')->only(['index','show']);
    Route::apiResource('products', 'ProductController')->only(['index','show']);

    Route::apiResource('transactions', 'TransactionController')->only(['index','show']);
    Route::get('transactions/{transaction}/categories','TransactionController@categories')->name('transactions.categories.index');
    Route::get('transactions/{transaction}/sellers','TransactionController@sellers')->name('transactions.sellers.index');

    Route::apiResource('categories', 'CategoryController');
});
