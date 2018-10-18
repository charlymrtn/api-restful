<?php

namespace App\Models;

use App\User;
use App\Models\Transaction;

class Buyer extends User
{    
    public function transactions()
    {
      return $this->hasMany(Transaction::class,'buyer_uuid','uuid');
    }
}
