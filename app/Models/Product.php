<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Category;
use App\Models\Seller;
use App\Models\Transaction;

use App\Transformers\ProductTransformer;

class Product extends Model
{
  use SoftDeletes;

  protected $table = 'products';

  public $incrementing = false;
  protected $primaryKey = 'uuid';
  protected $keyType = 'uuid';

  const PRODUCTO_DISPONIBLE ='available';
  const PRODUCTO_NO_DISPONIBLE ='unavailable';}

  public $transformer = ProductTransformer::class;

  protected $fillable = [
      'name', 'description', 'quantity', 'status', 'image', 'seller_uuid'
  ];

  protected $dates = [
      'created_at', 'updated_at', 'deleted_at'
  ];

  protected $hidden = [
      'deleted_at', 'pivot'
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

    self::updated(function ($model){
      if ($model->quantity == 0 && $model->disponible) {
          $model->status = self::PRODUCTO_NO_DISPONIBLE;
          $model->save();
      }
    });
  }

  public function getDisponibleAttribute()
  {
    return $this->status == Product::PRODUCTO_DISPONIBLE;
  }

  public function seller()
  {
    return $this->belongsTo(Seller::class,'seller_uuid','uuid');
  }

  public function transactions()
  {
    return $this->hasMany(Transaction::class,'product_uuid','uuid');
  }

  public function categories()
  {
    return $this->belongsToMany(Category::class,'category_product','category_uuid','product_uuid');
  }
}
