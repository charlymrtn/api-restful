<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\Models\Seller;

use App\Http\Resources\User as SellerResource;

class SellerController extends ApiController
{
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
  public function show(String $id)
  {
      $seller = Seller::has('products')->findOrFail($id);

      return $this->showOne($seller);
  }
}
