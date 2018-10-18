<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
  use SoftDeletes;

  protected $table='transactions';

  protected $fillable = [
      'quantity', 'buyer_id', 'product_id'
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
      if(empty($model->getKeyName()))
      {
        $model->getKeyName() = Uuid::generate(4)->string;
      }
    });
  }
}
