<?php

namespace App\Models;

use App\User;

use App\Transformers\SellerTransformer;

class Seller extends User
{
  public $transformer = SellerTransformer::class;

  public function products()
  {
    return $this->hasMany(Product::class,'seller_uuid','uuid');
  }
}
