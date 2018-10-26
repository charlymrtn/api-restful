<?php

namespace App\Http\Controllers\Api\Complex;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductCategoryController extends ApiController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        return $this->showAll($product->categories);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product, Category $category)
    {
      //sync sustituye la lista
      //attach agrega a la lista pero acepta duplicados
      $product->categories()->syncWithoutDetaching([$category->uuid]);

      return $this->showAll($product->categories);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, Category $category)
    {
        if ($product->categories()->find($category->uuid)) {
          $product->categories()->detach([$category->uuid]);
          return $this->showAll($product->categories);
        }else{
          return $this->error("this category is not inside the product's categories",404);
        }
    }
}
