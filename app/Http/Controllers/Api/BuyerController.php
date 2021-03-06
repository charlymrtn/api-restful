<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\Models\Buyer;

class BuyerController extends ApiController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //parent::__construct();
        $this->middleware('auth:api');
        $this->middleware('scope:read-general')->only(['transactions','products','show','categories']);
        $this->middleware('can:view,buyer')->only(['categories','show','products','transactions']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->allowAdmin();

        $buyers = Buyer::has('transactions')->get();

        return $this->showAll($buyers);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(String $buyer)
    {
        $aBuyer = Buyer::has('transactions')->findOrFail($buyer);

        return $this->showOne($aBuyer);
    }

    public function transactions(Buyer $buyer)
    {
        return $this->showAll($buyer->transactions);
    }

    public function products(Buyer $buyer)
    {
        $products = $buyer->transactions()->with('product')->get()->pluck('product');

        return $this->showAll($products);
    }

    public function sellers(Buyer $buyer)
    {
        $this->allowAdmin();
        $sellers = $buyer->transactions()->with('product.seller')
                                        ->get()
                                        ->pluck('product.seller')
                                        ->unique('uuid')
                                        ->values();

        return $this->showAll($sellers);
    }

    public function categories(Buyer $buyer)
    {
        $categories = $buyer->transactions()->with('product.categories')
                                        ->get()
                                        ->pluck('product.categories')
                                        ->collapse()
                                        ->unique('uuid')
                                        ->values();

        return $this->showAll($categories);
    }

}
