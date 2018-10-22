<?php

namespace App\Http\Controllers\Api;

use App\Models\Seller;
use App\Models\Product;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        return $this->showAll($seller->products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $seller)
    {
        $rules =[
          'name' => 'required',
          'description' => 'required',
          'quantity' => 'required|integer|min:1',
          'image' => 'required|image'
        ];

        $this->validate($request,$rules);

        $data =$request->all();
        $data['status'] = Product::PRODUCTO_NO_DISPONIBLE;
        $data['image'] = 'product1.jpg';
        $data['seller_uuid'] = $seller->uuid;

        $product =Product::create($data);

        return $this->showOne($product,201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
      $rules =[
        'name' => 'string|min:10,|max:50',
        'description' => 'string|max:200',
        'quantity' => 'integer|min:1',
        'image' => 'image',
        'status' => 'string|in:'.Product::PRODUCTO_DISPONIBLE.','.Product::PRODUCTO_NO_DISPONIBLE;
      ];

      $this->validate($request,$rules);

      if ($seller->uuid != $product->seller_uuid)
          return $this->error('The seller is not the real owner of the product that you want to update',422);

      $product->fill($request->only([
        'name', 'description', 'quantity'
      ]));

      if ($request->has('status')) {
        $product->status = $request->status;

        if ($product->disponible() && $product->categories()->count() == 0)
            return $this->error('at least the product must have one category',409);

        if ($product->isClean()) {
          return $this->error('at least one attribute of the product must be different to update');
        }

        $product->save();

        return $this->showOne($product);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Product $product)
    {
        //
    }
}
