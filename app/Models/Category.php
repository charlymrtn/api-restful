<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Product;

class Category extends Model
{
    use SoftDeletes;

    protected $table='categories';

    protected $fillable = [
        'name', 'description'
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

    public function products()
    {
      return $this->belongsToMany(Product::class,'product_uuid','category_uuid')
    }
}