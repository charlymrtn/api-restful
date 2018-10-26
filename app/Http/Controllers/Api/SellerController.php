<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\Models\Seller;

use App\Http\Resources\User as SellerResource;

class SellerController extends ApiController
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('auth:api');
  }

  public function index()
  {
      $sellers = Seller::has('products')->get();

      return $this->showAll($sellers);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(String $seller)
  {
      $aSeller = Seller::has('products')->findOrFail($seller);

      return $this->showOne($aSeller);
  }

  public function transactions(Seller $seller)
  {
    $transactions = $seller->products()
                           ->whereHas('transactions')
                           ->with('transactions')
                           ->get()
                           ->pluck('transactions')
                           ->collapse();

    return $this->showAll($transactions);
  }

  public function categories(Seller $seller)
  {
    $categories = $seller->products()
                         ->with('categories')
                         ->get()
                         ->pluck('categories')
                         ->collapse()
                         ->unique('uuid')
                         ->values();

    return $this->showAll($categories);
  }

  public function buyers(Seller $seller)
  {
    $buyers = $seller->products()
                     ->whereHas('transactions')
                     ->with('transactions.buyer')
                     ->get()
                     ->pluck('transactions')
                     ->collapse()
                     ->pluck('buyer')
                     ->unique('uuid')
                     ->values();

    return $this->showAll($buyers);
  }
}
