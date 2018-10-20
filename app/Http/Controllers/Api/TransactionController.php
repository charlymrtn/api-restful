<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\Models\Transaction;

class TransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $transactions = Transaction::all();

      return $this->showAll($transactions);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(String $id)
    {
        $transaction = Transaction::findOrFail($id);

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
