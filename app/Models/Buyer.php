<?php

namespace App\Models;

use App\User;
use App\Models\Transaction;

use App\Transformers\BuyerTransformer;

class Buyer extends User
{

    public $transformer = BuyerTransformer::class;

    public function transactions()
    {
      return $this->hasMany(Transaction::class,'buyer_uuid','uuid');
    }
}
