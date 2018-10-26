<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\Models\Product;
use App\Models\Transaction;
use App\User;

use DB;
use App\Transformers\TransactionTransformer;

class ProductController extends ApiController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index','show']);
        $this->middleware('transform.input:'. TransactionTransformer::class)->only(['transaction']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('seller')->get()->each(function($product){
          $product->makeHidden(['seller_uuid']);
        });

        return $this->showAll($products);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(String $product)
    {
        $aproduct = Product::with('seller')->find($product);
        $aproduct->makeHidden(['seller_uuid']);
        return $this->showOne($aproduct);
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

    public function transaction(Request $request, Product $product, User $buyer)
    {
      $rules = [
        'quantity' =>'required|integer|min:1'
      ];

      $this->validate($request, $rules);

      if ($buyer->uuid == $product->seller->uuid) {
        return $this->error('the buyer and the seller must be diferent users',409);
      }

      if (!$buyer->verificado) {
        return $this->error('the buyer must be a verified user',409);
      }

      if (!$product->seller->verificado) {
        return $this->error('the seller must be a verified user',409);
      }

      if(!$product->disponible){
        return $this->error('this product is not available',409);
      }

      if($product->quantity < $request->quantity){
        return $this->error('this product has no enough units',409);
      }

      return DB::transaction(function() use ($request, $product, $buyer){
        $product->quantity -= $request->quantity;
        $product->save();

        $transaction = Transaction::create([
          'quantity' => $request->quantity,
          'buyer_uuid' => $buyer->uuid,
          'product_uuid' => $product->uuid
        ]);

        return $this->showOne($transaction,201);
      });

    }

}
