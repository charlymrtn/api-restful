<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Buyer;
use App\Models\Product;

class Transaction extends Model
{
  use SoftDeletes;

  protected $table = 'transactions';

  public $incrementing = false;
  protected $primaryKey = 'uuid';
  protected $keyType = 'uuid';

  protected $fillable = [
    'quantity', 'buyer_uuid', 'product_uuid'
  ];

  protected $dates = [
      'created_at', 'updated_at', 'deleted_at'
  ];

  protected $hidden = [
      'deleted_at'
  ];

  public static function boot()
  {
    parent::boot();
    self::creating(function ($model){
      if(empty($model->uuid))
      {
        $model->uuid = Uuid::generate(4)->string;
      }
    });
  }

  public function buyer()
  {
    return $this->belongsTo(Buyer::class,'buyer_uuid','uuid');
  }

  public function product()
  {
    return $this->belongsTo(Product::class,'product_uuid','uuid');
  }
}
