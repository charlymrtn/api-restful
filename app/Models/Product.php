<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
  use SoftDeletes;

  protected $table='products';

  const PRODUCTO_DISPONIBLE ='disponible';
  const PRODUCTO_NO_DISPONIBLE ='no disponible';

  protected $fillable = [
      'name', 'description', 'quantity', 'status', 'image', 'seller_id'
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

  public function getDisponibleAttribute()
  {
    return $this->status == Product::PRODUCTO_DISPONIBLE;
  }
}
