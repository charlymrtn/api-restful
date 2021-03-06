<?php

namespace App\Http\Controllers\Api\Complex;

use App\Models\Seller;
use App\Models\Product;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Auth\Access\AuthorizationException;

use Storage;

use App\Transformers\ProductTransformer;

class SellerProductController extends ApiController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('transform.input:'. ProductTransformer::class)->only(['store','update']);
        $this->middleware('scope:manage-product')->except(['index','show']);
        $this->middleware('can:view,seller')->only(['index','show']);
        $this->middleware('can:sale,seller')->only(['store']);
        $this->middleware('can:edit-product,seller')->only(['update']);
        $this->middleware('can:delete-product,seller')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        if (request()->user()->tokenCan('read-general') || request()->user()->tokenCan('manage-product')) {
          return $this->showAll($seller->products);
        }

        throw new AuthorizationException;

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
          'name' => 'required|string|min:5,|max:50',
          'description' => 'required|string|min:5,max:200',
          'quantity' => 'required|integer|min:1',
          'image' => 'required|image'
        ];

        $this->validate($request,$rules);

        $data =$request->all();
        $data['status'] = Product::PRODUCTO_NO_DISPONIBLE;
        $data['image'] = $request->image->store('','images');
        $data['seller_uuid'] = $seller->uuid;

        $product =Product::create($data);

        return $this->showOne($product,201);
    }

    public function show(Seller $seller, Product $product)
    {
        $this->verifySeller($seller, $product);

        return $this->showOne($product);
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
        'name' => 'string|min:5,|max:50',
        'description' => 'string|min:5,max:200',
        'quantity' => 'integer|min:1',
        'image' => 'image',
        'status' => 'string|in:'.Product::PRODUCTO_DISPONIBLE.','.Product::PRODUCTO_NO_DISPONIBLE
      ];

      $this->validate($request,$rules);

      $this->verifySeller($seller, $product);

      $product->fill($request->only([
        'name', 'description', 'quantity'
      ]));

      if ($request->has('status')) {
        $product->status = $request->status;

        if ($product->disponible() && $product->categories()->count() == 0)
            return $this->error('at least the product must have one category',409);
      }

      if ($request->hasFile('image')) {
        Storage::disk('images')->delete($product->image);
        $product->image = $request->image->store('','images');
      }

      if ($product->isClean()) {
        return $this->error('at least one attribute of the product must be different to update');
      }

      $product->save();

      return $this->showOne($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Product $product)
    {
      $this->verifySeller($seller, $product);

      Storage::disk('images')->delete($product->image);

      $product->delete();

      return $this->showOne($product);
    }

    protected function verifySeller(Seller $seller, Product $product)
    {
      if ($seller->uuid != $product->seller_uuid)
          throw new HttpException(422,'This seller is not the real owner of the product that you want to edit/delete');
    }
}
