<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\Models\Transaction;

class TransactionController extends ApiController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['categories']);
        $this->middleware('auth:api')->except(['categories']);
        $this->middleware('scope:read-general')->only(['sellers','show']);
        $this->middleware('can:view,transaction')->only(['show','sellers']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $this->allowAdmin();
      $transactions = Transaction::all();

      return $this->showAll($transactions);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        return $this->showOne($transaction);
    }

    public function categories(Transaction $transaction)
    {
        $categories = $transaction->product->categories;

        return $this->showAll($categories);
    }

    public function sellers(Transaction $transaction)
    {
        $seller = $transaction->product->seller;
        return $this->showOne($seller);
    }
}
