<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\Models\Product;

class ProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();

        return $this->showAll($products);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $this->showOne($product);
    }

    public function transactions(Product $product)
    {
        return $this->showAll($product->transactions);
    }

    public function buyers(Product $product)
    {
      $buyers = $product->transactions()
                        ->with('buyer')
                        ->get()
                        ->pluck('buyer')
                        ->unique('uuid')
                        ->values();

      return $this->showAll($buyers);
    }

}
