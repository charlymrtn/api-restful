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

    Route::apiResource('users', 'UserController');

    Route::apiResource('buyers', 'BuyerController')->only(['index','show']);
    Route::get('buyers/{buyer}/transactions','BuyerController@transactions')->name('buyers.transactions');
    Route::get('buyers/{buyer}/products','BuyerController@products')->name('buyers.products');
    Route::get('buyers/{buyer}/sellers','BuyerController@sellers')->name('buyers.sellers');
    Route::get('buyers/{buyer}/categories','BuyerController@categories')->name('buyers.categories');

    Route::apiResource('sellers', 'SellerController')->only(['index','show']);
    Route::get('sellers/{seller}/transactions','SellerController@transactions')->name('sellers.transactions');
    Route::get('sellers/{seller}/categories','SellerController@categories')->name('sellers.categories');
    Route::get('sellers/{seller}/buyers','SellerController@buyers')->name('sellers.buyers');

    Route::apiResource('products', 'ProductController')->only(['index','show']);
    Route::get('products/{product}/transactions','ProductController@transactions')->name('products.transactions');
    Route::get('products/{product}/buyers','ProductController@buyers')->name('products.buyers');
    Route::post('products/{product}/transaction/{buyer}','ProductController@transaction')->name('products.transactions');

    Route::apiResource('transactions', 'TransactionController')->only(['index','show']);
    Route::get('transactions/{transaction}/categories','TransactionController@categories')->name('transactions.categories');
    Route::get('transactions/{transaction}/sellers','TransactionController@sellers')->name('transactions.sellers');

    Route::apiResource('categories', 'CategoryController');
    Route::get('categories/{category}/products','CategoryController@products')->name('categories.products');
    Route::get('categories/{category}/sellers','CategoryController@sellers')->name('categories.sellers');
    Route::get('categories/{category}/transactions','CategoryController@transactions')->name('categories.transactions');
    Route::get('categories/{category}/buyers','CategoryController@buyers')->name('categories.buyers');

    Route::namespace('Complex')->group(function () {
      Route::apiResource('sellers.products','SellerProductController');
      Route::apiResource('products.categories','ProductCategoryController')->only(['index','update','destroy']);
    });
});
